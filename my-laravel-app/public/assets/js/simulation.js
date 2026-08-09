// ============================================
// SIMULATION DATA
// ============================================

const simulations = [
    {
        id: 1,
        townId: 1,
        name: 'Laoag City',
        description: 'Capital City • Sunshine City',
        image: 'assets/images/laoag.jpg',
        status: 'completed',
        unlocked: true,
        completed: true,
        videoUrl: 'https://www.youtube.com/embed/VIDEO_ID_LAOAG',
        keywords: ['Laoag', 'Ilocos Norte', 'capital', 'Sinking Bell Tower', 'St. William', 'Cathedral', 'Aurora Park', 'Museo Ilocos Norte', 'Sand Dunes', 'Marcos Bridge', 'Ermita Hill', 'Tobacco Monopoly', 'Sunshine City', 'light', 'brightness']
    },
    {
        id: 2,
        townId: 2,
        name: 'Paoay',
        description: 'UNESCO World Heritage Site',
        image: 'assets/images/paoay.jpg',
        status: 'available',
        unlocked: true,
        completed: false,
        videoUrl: 'https://www.youtube.com/embed/VIDEO_ID_PAOAy',
        keywords: ['Paoay', 'Ilocos Norte', 'UNESCO', 'Paoay Church', 'Saint Augustine', 'buttresses', 'Earthquake Baroque', 'Malacañang of the North', 'Paoay Lake', 'Sand Dunes', '1701', 'makapaway', 'coral stones', 'bell tower']
    },
    {
        id: 3,
        townId: 3,
        name: 'Pagudpud',
        description: 'Northernmost tip • Boracay of the North',
        image: 'assets/images/pagudpud.jpg',
        status: 'locked',
        unlocked: false,
        completed: false,
        videoUrl: 'https://www.youtube.com/embed/VIDEO_ID_PAGUDPUD',
        keywords: []
    }
];

// Town-specific questions for simulation
const townQuestions = {
    laoag: [
        { question: 'What is the meaning of "Laoag"?', keywords: ['light', 'brightness', 'sunshine'], guest: 'Female Adult' },
        { question: 'What is the Sinking Bell Tower famous for?', keywords: ['sinking', 'inch', 'year', 'earthquake', 'baroque'], guest: 'Male Adult' }
    ],
    paoay: [
        { question: 'What makes Paoay Church unique?', keywords: ['buttresses', 'earthquake baroque', 'coral stones', 'UNESCO', '24 buttresses'], guest: 'Male Adult' },
        { question: 'What does "Paoay" mean and where does the name come from?', keywords: ['makapaway', 'live alone', 'tirongs', 'independent'], guest: 'Female Teen' }
    ],
    pagudpud: [
        { question: 'What is Pagudpud famous for?', keywords: ['northernmost', 'beach', 'white sand', 'Saud Beach', 'Blue Lagoon'], guest: 'Female Adult' },
        { question: 'Where does the name "Pagudpud" come from?', keywords: ['pagud', 'tired', 'pudpod', 'worn out', 'Batangas'], guest: 'Male Adult' }
    ]
};

// ============================================
// VOICE SETTINGS (from Welcome Page)
// ============================================

let selectedVoice = null;
let speechSynthesis = window.speechSynthesis;
let availableVoices = [];

// Load voice preference from welcome page
function loadVoicePreference() {
    const savedAvatar = localStorage.getItem('selectedAvatar');
    const customVoice = localStorage.getItem('customVoice');
    
    if (availableVoices.length === 0) {
        availableVoices = speechSynthesis.getVoices();
    }
    
    if (customVoice && availableVoices.length > 0) {
        selectedVoice = availableVoices.find(v => v.name === customVoice);
    }
    
    if (!selectedVoice && savedAvatar) {
        setVoiceByGender(savedAvatar);
    }
    
    console.log('Voice loaded:', selectedVoice?.name || 'Default voice');
    
    // Update the voice indicator in navbar
    updateVoiceIndicator();
}

// Set voice based on gender preference
function setVoiceByGender(gender) {
    if (availableVoices.length === 0) {
        availableVoices = speechSynthesis.getVoices();
    }
    
    let preferredVoice = null;
    
    if (gender === 'male') {
        preferredVoice = availableVoices.find(voice => 
            voice.name.toLowerCase().includes('male') ||
            voice.name.toLowerCase().includes('daniel') ||
            voice.name.toLowerCase().includes('david') ||
            voice.name.toLowerCase().includes('google uk english male') ||
            voice.name.toLowerCase().includes('microsoft david')
        );
    } else {
        preferredVoice = availableVoices.find(voice => 
            voice.name.toLowerCase().includes('female') ||
            voice.name.toLowerCase().includes('samantha') ||
            voice.name.toLowerCase().includes('victoria') ||
            voice.name.toLowerCase().includes('google uk english female') ||
            voice.name.toLowerCase().includes('microsoft zira')
        );
    }
    
    if (!preferredVoice) {
        preferredVoice = availableVoices.find(voice => voice.lang.startsWith('en'));
    }
    
    selectedVoice = preferredVoice;
}

// Update voice indicator in navbar
function updateVoiceIndicator() {
    const indicator = document.getElementById('voiceName');
    if (indicator) {
        const avatar = localStorage.getItem('selectedAvatar');
        let voiceType = 'Default Voice';
        if (avatar === 'male') {
            voiceType = 'Male Facilitator';
        } else if (avatar === 'female') {
            voiceType = 'Female Facilitator';
        }
        indicator.textContent = voiceType;
    }
}

// Speak text with selected voice
function speakText(text, onEndCallback = null) {
    if (speechSynthesis.speaking) {
        speechSynthesis.cancel();
    }
    
    const utterance = new SpeechSynthesisUtterance(text);
    
    if (selectedVoice) {
        utterance.voice = selectedVoice;
    }
    
    utterance.rate = 0.9;
    utterance.pitch = 1;
    utterance.volume = 1;
    
    if (onEndCallback) {
        utterance.onend = onEndCallback;
    }
    
    speechSynthesis.speak(utterance);
}

// Speak tourist question
function speakTouristQuestion(question, guest, callback) {
    const fullText = `${guest} asks: ${question}`;
    speakText(fullText, callback);
}

// Replay the current question
function replayQuestion() {
    if (window.currentQuestionText) {
        speakText(window.currentQuestionText);
    }
}

// ============================================
// SIMULATION STATE
// ============================================

let currentSimulation = null;
let currentStep = 1;
let videoWatched = false;
let guideTimerInterval = null;
let currentQuestionIndex = 0;
let questions = [];
let coveredKeywords = [];
let missedKeywords = [];
let guideModeScore = 0;

// ============================================
// INITIALIZATION
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    loadNavUserName();
    loadSimulationProgress();
    renderSimulationGrid();
    renderFinalTourCard();
    updateOverallProgress();
    
    // Initialize voices
    if (speechSynthesis.onvoiceschanged !== undefined) {
        speechSynthesis.onvoiceschanged = function() {
            availableVoices = speechSynthesis.getVoices();
            loadVoicePreference();
        };
    }
    loadVoicePreference();
    
    // Check URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const townParam = urlParams.get('town');
    if (townParam) {
        const sim = simulations.find(s => s.name.toLowerCase().includes(townParam));
        if (sim && sim.unlocked && !sim.completed) {
            startSimulation(sim.id);
        }
    }
});

// ============================================
// MAIN VIEW FUNCTIONS
// ============================================

function showMainView() {
    if (guideTimerInterval) clearInterval(guideTimerInterval);
    if (speechSynthesis.speaking) speechSynthesis.cancel();
    document.getElementById('mainView').style.display = 'block';
    document.getElementById('simulationView').style.display = 'none';
    renderSimulationGrid();
}

function renderSimulationGrid() {
    const grid = document.getElementById('simulationGrid');
    
    grid.innerHTML = simulations.map(sim => {
        const statusClass = sim.completed ? 'completed' : (sim.unlocked ? '' : 'locked');
        const imageUrl = sim.image || `https://placehold.co/600x400/1a5f7a/ffffff?text=${encodeURIComponent(sim.name)}`;
        
        return `
            <div class="simulation-card ${statusClass}" onclick="openSimulation(${sim.id})">
                <div class="card-image" style="background-image: url('${imageUrl}')">
                    <span class="card-badge ${sim.completed ? 'bg-success' : (sim.unlocked ? 'bg-primary' : 'bg-secondary')} text-white">
                        ${sim.completed ? 'Completed' : (sim.unlocked ? 'Available' : 'Locked')}
                        ${sim.completed ? ' <i class="fas fa-check-circle ms-1"></i>' : ''}
                        ${!sim.unlocked ? ' <i class="fas fa-lock ms-1"></i>' : ''}
                    </span>
                </div>
                <div class="card-content">
                    <h5 class="fw-bold mb-1">${sim.name}</h5>
                    <p class="text-muted small mb-3">${sim.description}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="small text-muted">
                            <i class="fas fa-key me-1"></i>${sim.keywords.length} keywords
                        </span>
                        ${sim.completed ? 
                            '<i class="fas fa-check-circle text-success"></i>' : 
                            (sim.unlocked ? '<i class="fas fa-play-circle text-primary"></i>' : '<i class="fas fa-lock text-secondary"></i>')}
                    </div>
                </div>
            </div>
        `;
    }).join('');
}

function renderFinalTourCard() {
    const container = document.getElementById('finalTourContainer');
    const allCompleted = simulations.every(s => s.completed);
    const finalCompleted = localStorage.getItem('finalTourCompleted') === 'true';
    
    container.innerHTML = `
        <div class="final-tour-card ${!allCompleted || finalCompleted ? 'locked' : ''}" 
             onclick="${allCompleted && !finalCompleted ? 'startFinalTour()' : ''}">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h4 class="fw-bold mb-3">
                        <i class="fas fa-crown me-2" style="color: #ffd700;"></i>
                        Final Virtual Tour: Ilocos Norte
                    </h4>
                    <p class="mb-2 opacity-90">Complete all 21 town simulations to unlock the ultimate challenge!</p>
                    <p class="mb-0 small opacity-75">
                        <i class="fas fa-check-circle me-1"></i>
                        ${simulations.filter(s => s.completed).length} of 3 towns completed (demo)
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                    ${finalCompleted ? 
                        '<span class="badge bg-success fs-6 py-2 px-4"><i class="fas fa-trophy me-2"></i>Completed</span>' :
                        (!allCompleted ? 
                            '<span class="badge bg-secondary fs-6 py-2 px-4"><i class="fas fa-lock me-2"></i>Locked</span>' :
                            '<span class="badge bg-warning fs-6 py-2 px-4"><i class="fas fa-star me-2"></i>Ready to Start</span>')}
                </div>
            </div>
        </div>
    `;
}

function openSimulation(simId) {
    const sim = simulations.find(s => s.id === simId);
    
    if (!sim.unlocked) {
        alert(`${sim.name} is locked. Complete previous simulations first!`);
        return;
    }
    
    if (sim.completed) {
        const confirmRetake = confirm(`${sim.name} is already completed. Do you want to retake the simulation?`);
        if (confirmRetake) {
            sim.completed = false;
            sim.status = 'available';
            
            const nextSim = simulations.find(s => s.id === sim.id + 1);
            if (nextSim) {
                nextSim.unlocked = false;
                nextSim.status = 'locked';
            }
            
            saveSimulationProgress();
            renderSimulationGrid();
            renderFinalTourCard();
            startSimulation(simId);
        }
        return;
    }
    
    startSimulation(simId);
}

// ============================================
// SIMULATION FLOW
// ============================================

function startSimulation(simId) {
    currentSimulation = simulations.find(s => s.id === simId);
    currentStep = 1;
    videoWatched = false;
    currentQuestionIndex = 0;
    coveredKeywords = [];
    missedKeywords = [];
    guideModeScore = 0;
    
    // Load voice preference
    loadVoicePreference();
    
    // Load town-specific questions
    const townKey = currentSimulation.name.toLowerCase().split(' ')[0];
    questions = townQuestions[townKey] || townQuestions.laoag;
    
    document.getElementById('simulationBreadcrumb').textContent = `${currentSimulation.name} Simulation`;
    document.getElementById('mainView').style.display = 'none';
    document.getElementById('simulationView').style.display = 'block';
    
    updateStepIndicators();
    showStepContent();
}

function updateStepIndicators() {
    for (let i = 1; i <= 4; i++) {
        const step = document.getElementById(`step${i}`);
        step.classList.remove('active', 'completed');
        if (i < currentStep) {
            step.classList.add('completed');
        } else if (i === currentStep) {
            step.classList.add('active');
        }
    }
}

function showStepContent() {
    const content = document.getElementById('simulationContent');
    
    if (currentStep === 1) {
        content.innerHTML = `
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-4">
                        <i class="fas fa-play-circle text-primary me-2"></i>
                        Step 1: Watch & Learn
                    </h4>
                    <p class="text-muted mb-3">Watch the training video for ${currentSimulation.name}.</p>
                    
                    <div class="video-container mb-4">
                        <iframe width="100%" height="400" 
                                src="${currentSimulation.videoUrl}" 
                                frameborder="0" allowfullscreen 
                                style="border-radius: 15px;">
                        </iframe>
                    </div>
                    
                    <button class="btn btn-mmsu btn-lg" onclick="markVideoWatched()">
                        <i class="fas fa-check me-2"></i>I've Watched the Full Video
                    </button>
                </div>
            </div>
        `;
    } else if (currentStep === 2) {
        content.innerHTML = `
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-4">
                        <i class="fas fa-microphone-alt text-success me-2"></i>
                        Step 2: Guide Mode
                    </h4>
                    <p class="text-muted mb-3">Deliver your tour guide spiel about ${currentSimulation.name}.</p>
                    
                    <div class="text-center mb-4">
                        <div class="timer-display mb-3" id="guideTimer">0:05</div>
                        <button class="btn guide-mode-btn" id="guideModeBtn" onclick="startGuideMode()">
                            <i class="fas fa-microphone me-2"></i>Guide Mode: ON!
                        </button>
                    </div>
                    
                    <div id="recordingStatus" class="text-center" style="display: none;">
                        <div class="mb-3">
                            <span class="recording-indicator"></span>
                            <span class="text-danger fw-bold">Recording in progress...</span>
                        </div>
                    </div>
                </div>
            </div>
        `;
    } else if (currentStep === 3) {
        content.innerHTML = `
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-4">
                        <i class="fas fa-question-circle text-warning me-2"></i>
                        Step 3: Tourist Questions
                    </h4>
                    <div id="questionsContainer"></div>
                </div>
            </div>
        `;
        showTouristQuestion(0);
    } else if (currentStep === 4) {
        const totalKeywords = currentSimulation.keywords.length;
        const coveredCount = coveredKeywords.length;
        const score = Math.round((coveredCount / totalKeywords) * 100);
        const passed = score >= 80;
        
        content.innerHTML = `
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        ${passed ? '<i class="fas fa-trophy text-warning fa-4x"></i>' : '<i class="fas fa-times-circle text-danger fa-4x"></i>'}
                    </div>
                    <h4 class="fw-bold text-center mb-3">${passed ? 'Congratulations!' : 'Almost There!'}</h4>
                    
                    <div class="text-center mb-4">
                        <div class="display-1 fw-bold" style="color: ${passed ? '#28a745' : '#dc3545'}">${score}%</div>
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">Keywords Covered (${coveredCount}/${totalKeywords}):</h6>
                        <div class="keyword-cloud">
                            ${currentSimulation.keywords.map(kw => {
                                const covered = coveredKeywords.includes(kw.toLowerCase());
                                return `<span class="keyword-tag ${covered ? 'covered' : 'missed'}">${kw}</span>`;
                            }).join('')}
                        </div>
                    </div>
                    
                    <div class="text-center">
                        ${passed ? `
                            <button class="btn btn-success btn-lg px-5" onclick="completeSimulation()">
                                <i class="fas fa-check-circle me-2"></i>Complete & Continue
                            </button>
                        ` : `
                            <button class="btn btn-warning btn-lg px-5 me-3" onclick="retrySimulation()">
                                <i class="fas fa-redo me-2"></i>Try Again
                            </button>
                            <button class="btn btn-outline-secondary btn-lg px-5" onclick="showMainView()">Exit</button>
                        `}
                    </div>
                </div>
            </div>
        `;
    }
}

function markVideoWatched() {
    videoWatched = true;
    currentStep = 2;
    updateStepIndicators();
    showStepContent();
}

function startGuideMode() {
    const btn = document.getElementById('guideModeBtn');
    const status = document.getElementById('recordingStatus');
    const timerDisplay = document.getElementById('guideTimer');
    
    btn.disabled = true;
    status.style.display = 'block';
    
    let timeLeft = 5;
    
    guideTimerInterval = setInterval(() => {
        timeLeft--;
        timerDisplay.textContent = `0:0${timeLeft}`;
        
        if (timeLeft <= 0) {
            clearInterval(guideTimerInterval);
            status.style.display = 'none';
            
            const allKeywords = currentSimulation.keywords;
            const coveragePercent = Math.random() * 0.3 + 0.6;
            const numToCover = Math.floor(allKeywords.length * coveragePercent);
            
            coveredKeywords = [];
            const shuffled = [...allKeywords].sort(() => Math.random() - 0.5);
            coveredKeywords = shuffled.slice(0, numToCover).map(k => k.toLowerCase());
            
            currentStep = 3;
            updateStepIndicators();
            showStepContent();
        }
    }, 1000);
}

function showTouristQuestion(index) {
    const container = document.getElementById('questionsContainer');
    const question = questions[index];
    
    container.innerHTML = `
        <div class="tourist-question-card">
            <div class="d-flex align-items-center mb-3">
                <i class="fas fa-user-circle fa-3x me-3"></i>
                <div>
                    <h6 class="mb-1">${question.guest}</h6>
                    <small class="opacity-75">
                        <i class="fas fa-volume-up me-1"></i>Speaking...
                    </small>
                </div>
                <div class="ms-auto">
                    <span class="badge bg-light text-dark px-3 py-2" id="questionTimer">30s</span>
                </div>
            </div>
            <p class="lead mb-0">"${question.question}"</p>
            <button class="btn btn-sm btn-outline-light mt-3" onclick="replayQuestion()">
                <i class="fas fa-redo me-1"></i>Replay Question
            </button>
        </div>
        
        <div class="mb-3">
            <label class="form-label fw-bold">Your Answer:</label>
            <textarea class="form-control" id="touristAnswer" rows="3" placeholder="Type your answer here..."></textarea>
        </div>
        
        <button class="btn btn-mmsu" onclick="submitTouristAnswer(${index})">
            Submit Answer
        </button>
    `;
    
    window.currentQuestionText = `${question.guest} asks: ${question.question}`;
    speakTouristQuestion(question.question, question.guest);
    
    let timeLeft = 30;
    window.questionTimer = setInterval(() => {
        timeLeft--;
        const timerEl = document.getElementById('questionTimer');
        if (timerEl) {
            timerEl.textContent = `${timeLeft}s`;
            if (timeLeft <= 10) {
                timerEl.classList.remove('bg-light', 'text-dark');
                timerEl.classList.add('bg-danger', 'text-white');
            }
        }
        if (timeLeft <= 0) {
            clearInterval(window.questionTimer);
            submitTouristAnswer(index);
        }
    }, 1000);
}

function submitTouristAnswer(index) {
    if (window.questionTimer) clearInterval(window.questionTimer);
    if (speechSynthesis.speaking) speechSynthesis.cancel();
    
    const answer = document.getElementById('touristAnswer')?.value || '';
    const question = questions[index];
    
    const matchedKeywords = question.keywords.filter(kw => 
        answer.toLowerCase().includes(kw.toLowerCase())
    );
    
    matchedKeywords.forEach(kw => {
        if (!coveredKeywords.includes(kw.toLowerCase())) {
            coveredKeywords.push(kw.toLowerCase());
        }
    });
    
    currentQuestionIndex++;
    
    if (currentQuestionIndex < questions.length) {
        showTouristQuestion(currentQuestionIndex);
    } else {
        currentStep = 4;
        updateStepIndicators();
        showStepContent();
    }
}

function completeSimulation() {
    currentSimulation.completed = true;
    currentSimulation.status = 'completed';
    
    const nextSim = simulations.find(s => s.id === currentSimulation.id + 1);
    if (nextSim) {
        nextSim.unlocked = true;
        nextSim.status = 'available';
    }
    
    saveSimulationProgress();
    renderSimulationGrid();
    renderFinalTourCard();
    updateOverallProgress();
    
    showMainView();
    setTimeout(() => alert(`🎉 Congratulations! You've completed ${currentSimulation.name} simulation!`), 200);
}

function retrySimulation() {
    currentStep = 1;
    currentQuestionIndex = 0;
    coveredKeywords = [];
    updateStepIndicators();
    showStepContent();
}

function startFinalTour() {
    alert('Final Virtual Tour: Full Ilocos Norte simulation would start here!');
}

// ============================================
// HELPER FUNCTIONS
// ============================================

function updateOverallProgress() {
    const completed = simulations.filter(s => s.completed).length;
    document.getElementById('simulationsCompleted').textContent = completed;
}

function saveSimulationProgress() {
    const progress = simulations.map(s => ({
        id: s.id, completed: s.completed, unlocked: s.unlocked
    }));
    localStorage.setItem('simulationProgress', JSON.stringify(progress));
}

function loadSimulationProgress() {
    const saved = localStorage.getItem('simulationProgress');
    if (saved) {
        const progress = JSON.parse(saved);
        progress.forEach(p => {
            const sim = simulations.find(s => s.id === p.id);
            if (sim) {
                sim.completed = p.completed;
                sim.unlocked = p.unlocked;
                sim.status = sim.completed ? 'completed' : (sim.unlocked ? 'available' : 'locked');
            }
        });
    }
}

function loadNavUserName() {
    const userName = localStorage.getItem('userName') || 'Trainee';
    document.getElementById('navUserName').textContent = userName;
}

window.addEventListener('beforeunload', function() {
    if (guideTimerInterval) clearInterval(guideTimerInterval);
    if (window.questionTimer) clearInterval(window.questionTimer);
    if (speechSynthesis.speaking) speechSynthesis.cancel();
});