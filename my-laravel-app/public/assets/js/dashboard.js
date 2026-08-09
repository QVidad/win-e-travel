// User data management
let userProgress = {
    foundation: {
        completed: 0,
        total: 4,
        modules: [false, false, false, false]
    },
    discover: {
        completed: 0,
        total: 21,
        towns: []
    },
    adventure: {
        completed: 0,
        total: 22, // 21 towns + 1 final tour
        unlocked: 0
    }
};

// Initialize dashboard
document.addEventListener('DOMContentLoaded', function() {
    // Set current year
    document.querySelector('#copyright-year').textContent = new Date().getFullYear();
    
    // Load user data
    loadUserData();
    
    // Load progress from localStorage
    loadProgress();
    
    // Update UI
    updateDashboard();
    
    // Check stage unlocks
    checkStageUnlocks();
});

// Load user data
function loadUserData() {
    const userName = localStorage.getItem('userName') || 'Trainee';
    document.getElementById('dashboardUserName').textContent = userName;
    document.getElementById('welcomeName').textContent = userName;
    
    // Set motivation message based on time of day
    const hour = new Date().getHours();
    const messages = [
        "Your next simulation awaits. Let's practice to perfection!",
        "Every great tour guide started exactly where you are now.",
        "Ready to explore Ilocos Norte? Your journey continues!",
        "Practice makes perfect. Your next chapter is waiting."
    ];
    document.getElementById('motivationMessage').textContent = 
        messages[Math.floor(Math.random() * messages.length)];
}

// Load progress from localStorage
function loadProgress() {
    const savedProgress = localStorage.getItem('userProgress');
    if (savedProgress) {
        userProgress = JSON.parse(savedProgress);
    } else {
        // Initialize discover towns array (21 towns, all false)
        for (let i = 0; i < 21; i++) {
            userProgress.discover.towns.push(false);
        }
        saveProgress();
    }
}

// Save progress to localStorage
function saveProgress() {
    localStorage.setItem('userProgress', JSON.stringify(userProgress));
}

// Update dashboard UI
function updateDashboard() {
    // Calculate overall progress
    const foundationWeight = 4;
    const discoverWeight = 21;
    const adventureWeight = 22;
    const totalWeight = foundationWeight + discoverWeight + adventureWeight;
    
    const foundationScore = (userProgress.foundation.completed / 4) * foundationWeight;
    const discoverScore = (userProgress.discover.completed / 21) * discoverWeight;
    const adventureScore = (userProgress.adventure.completed / 22) * adventureWeight;
    
    const overallPercent = Math.round(((foundationScore + discoverScore + adventureScore) / totalWeight) * 100);
    
    // Update overall progress
    document.getElementById('overallProgressPercent').textContent = overallPercent + '%';
    document.getElementById('progressPercent').textContent = overallPercent + '%';
    document.getElementById('mainProgressBar').style.width = overallPercent + '%';
    
    // Update overall progress circle
    const circle = document.querySelector('.overall-progress-circle');
    const degrees = (overallPercent / 100) * 360;
    circle.style.background = `conic-gradient(#0a472e 0deg ${degrees}deg, #e9ecef ${degrees}deg 360deg)`;
    
    // Update completed chapters count
    const totalCompleted = userProgress.foundation.completed + 
                          userProgress.discover.completed + 
                          userProgress.adventure.completed;
    const totalChapters = 4 + 21 + 22;
    document.getElementById('completedChapters').textContent = totalCompleted;
    document.getElementById('totalChapters').textContent = totalChapters;
    
    // Update Foundation stage
    document.getElementById('foundationCompleted').textContent = userProgress.foundation.completed;
    document.getElementById('foundationPercent').textContent = 
        Math.round((userProgress.foundation.completed / 4) * 100) + '%';
    document.getElementById('foundationProgress').style.width = 
        (userProgress.foundation.completed / 4) * 100 + '%';
    
    // Update foundation dots
    for (let i = 0; i < 4; i++) {
        const dot = document.getElementById(`foundationDot${i + 1}`);
        if (userProgress.foundation.modules[i]) {
            dot.classList.add('completed');
        }
    }
    
    // Update Discover stage
    document.getElementById('discoverCompleted').textContent = userProgress.discover.completed;
    document.getElementById('discoverPercent').textContent = 
        Math.round((userProgress.discover.completed / 21) * 100) + '%';
    document.getElementById('discoverProgress').style.width = 
        (userProgress.discover.completed / 21) * 100 + '%';
    
    // Update Adventure stage
    document.getElementById('adventureCompleted').textContent = userProgress.adventure.completed;
    document.getElementById('adventurePercent').textContent = 
        Math.round((userProgress.adventure.completed / 22) * 100) + '%';
    document.getElementById('adventureProgress').style.width = 
        (userProgress.adventure.completed / 22) * 100 + '%';
}

// Check which stages should be unlocked
function checkStageUnlocks() {
    const discoverStage = document.getElementById('discoverStage');
    const adventureStage = document.getElementById('adventureStage');
    const discoverBtn = document.getElementById('discoverBtn');
    const adventureBtn = document.getElementById('adventureBtn');
    const discoverLock = document.getElementById('discoverLock');
    const adventureLock = document.getElementById('adventureLock');
    
    // Unlock Dare to Discover if all foundation modules completed
    if (userProgress.foundation.completed >= 4) {
        discoverStage.classList.remove('locked');
        discoverLock.style.display = 'none';
        discoverBtn.disabled = false;
        discoverBtn.className = 'btn btn-journey w-100';
        discoverBtn.style.backgroundColor = '#f5576c';
        discoverBtn.style.color = 'white';
        discoverBtn.innerHTML = '<i class="fas fa-arrow-right me-2"></i>Explore Towns';
        discoverBtn.onclick = () => window.location.href = 'towns.html';
    } else {
        discoverStage.classList.add('locked');
        discoverLock.style.display = 'block';
        discoverBtn.disabled = true;
        discoverBtn.className = 'btn btn-outline-secondary w-100 btn-journey';
        discoverBtn.innerHTML = '<i class="fas fa-lock me-2"></i>Locked';
    }
    
    // Unlock Adventure Awaits if all discover towns completed
    if (userProgress.discover.completed >= 21) {
        adventureStage.classList.remove('locked');
        adventureLock.style.display = 'none';
        adventureBtn.disabled = false;
        adventureBtn.className = 'btn btn-journey w-100';
        adventureBtn.style.backgroundColor = '#00f2fe';
        adventureBtn.style.color = 'white';
        adventureBtn.innerHTML = '<i class="fas fa-arrow-right me-2"></i>Start Simulation';
        adventureBtn.onclick = () => window.location.href = 'simulation.html';
    } else {
        adventureStage.classList.add('locked');
        adventureLock.style.display = 'block';
        adventureBtn.disabled = true;
        adventureBtn.className = 'btn btn-outline-secondary w-100 btn-journey';
        adventureBtn.innerHTML = '<i class="fas fa-lock me-2"></i>Locked';
    }
}

// Update next steps based on progress
function updateNextSteps() {
    const nextStepsContainer = document.getElementById('nextSteps');
    let stepsHtml = '<div class="list-group list-group-flush">';
    
    if (userProgress.foundation.completed < 4) {
        const nextModule = userProgress.foundation.completed + 1;
        const moduleNames = ['Tour Preparation', 'Tour Briefings', 'Tour Information Delivery', 'Conclude the Tour'];
        stepsHtml += `
            <div class="list-group-item px-0 d-flex align-items-center">
                <i class="fas fa-circle text-success me-3 small"></i>
                <span>Complete Module ${nextModule}: ${moduleNames[nextModule - 1]}</span>
            </div>
            <div class="list-group-item px-0 d-flex align-items-center">
                <i class="fas fa-circle text-secondary me-3 small"></i>
                <span>Pass Module ${nextModule} Quiz (90% required)</span>
            </div>
        `;
    } else if (userProgress.discover.completed < 21) {
        stepsHtml += `
            <div class="list-group-item px-0 d-flex align-items-center">
                <i class="fas fa-circle text-success me-3 small"></i>
                <span>Start exploring towns in Dare to Discover</span>
            </div>
            <div class="list-group-item px-0 d-flex align-items-center">
                <i class="fas fa-circle text-secondary me-3 small"></i>
                <span>Complete town quizzes to unlock simulations</span>
            </div>
        `;
    } else {
        stepsHtml += `
            <div class="list-group-item px-0 d-flex align-items-center">
                <i class="fas fa-circle text-success me-3 small"></i>
                <span>Practice town simulations in Adventure Awaits</span>
            </div>
            <div class="list-group-item px-0 d-flex align-items-center">
                <i class="fas fa-circle text-secondary me-3 small"></i>
                <span>Complete all 21 town simulations</span>
            </div>
            <div class="list-group-item px-0 d-flex align-items-center">
                <i class="fas fa-circle text-secondary me-3 small"></i>
                <span>Unlock the Final Ilocos Norte Virtual Tour</span>
            </div>
        `;
    }
    
    stepsHtml += '</div>';
    nextStepsContainer.innerHTML = stepsHtml;
}

// Show notification
function showNotification(message, type = 'info') {
    let toastContainer = document.querySelector('.toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
        toastContainer.style.zIndex = '9999';
        document.body.appendChild(toastContainer);
    }
    
    const toastId = 'toast-' + Date.now();
    const bgClass = type === 'success' ? 'bg-success' : 'bg-info';
    
    const toastHtml = `
        <div id="${toastId}" class="toast align-items-center text-white ${bgClass} border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-info-circle me-2"></i>${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;
    
    toastContainer.insertAdjacentHTML('beforeend', toastHtml);
    const toastElement = document.getElementById(toastId);
    const toast = new bootstrap.Toast(toastElement, { animation: true, autohide: true, delay: 3000 });
    toast.show();
    
    toastElement.addEventListener('hidden.bs.toast', function() {
        this.remove();
    });
}

