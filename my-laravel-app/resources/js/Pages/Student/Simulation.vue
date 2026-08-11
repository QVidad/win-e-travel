<template>
    <StudentLayout>
        <div class="simulation-container py-4">
            <div class="container">
                <!-- Page Header matching simulation.html -->
                <div class="page-header">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <h2 class="fw-bold mb-2">
                                <i class="fas fa-mountain me-2"></i>
                                Stage 3: Adventure Awaits Simulation Engine
                            </h2>
                            <p class="mb-0 opacity-90">Live Webcam Virtual Background & Real-Time Speech Recognition Tour Guiding Simulation</p>
                        </div>
                        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                            <div class="d-inline-block bg-white bg-opacity-25 rounded-4 px-4 py-2 fw-bold">
                                <i class="fas fa-smile text-warning me-1"></i> Satisfaction: {{ satisfactionScore }}%
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Simulation Steps Breadcrumb -->
                <div class="simulation-steps">
                    <div v-for="(step, idx) in steps" :key="idx" class="step-item" :class="{ completed: idx < currentStepIndex, active: idx === currentStepIndex }">
                        <div class="step-number">
                            <i v-if="idx < currentStepIndex" class="fas fa-check"></i>
                            <span v-else>{{ idx + 1 }}</span>
                        </div>
                        <small class="fw-bold d-block text-dark">{{ step.title }}</small>
                    </div>
                </div>

                <!-- Active Simulation Interface -->
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
                            
                            <!-- WebRTC Live Webcam & Virtual Background Canvas Container -->
                            <div class="position-relative bg-dark" style="height: 380px;">
                                <!-- Background Image (Virtual Canvas Container) -->
                                <img
                                    v-if="!isWebcamActive"
                                    :src="currentStepData.image"
                                    :alt="currentStepData.title"
                                    class="w-100 h-100"
                                    style="object-fit: cover;"
                                />

                                <!-- WebRTC Video & Blended Canvas Overlay -->
                                <div v-show="isWebcamActive" class="w-100 h-100 position-relative">
                                    <video ref="webcamVideo" class="d-none" autoplay playsinline muted></video>
                                    <canvas ref="bgCanvas" class="w-100 h-100" style="object-fit: cover;"></canvas>

                                    <!-- Webcam Virtual Overlay Label -->
                                    <div class="position-absolute top-0 start-0 m-3 badge bg-success bg-opacity-90 px-3 py-2 rounded-pill shadow-sm">
                                        <i class="fas fa-video me-1"></i> Virtual Background Overlay Active
                                    </div>
                                </div>

                                <!-- Camera Toggle Controls -->
                                <div class="position-absolute top-0 end-0 m-3 z-3">
                                    <button @click="toggleWebcam" class="btn btn-sm rounded-pill px-3 shadow" :class="isWebcamActive ? 'btn-danger' : 'btn-light text-dark'">
                                        <i class="fas" :class="isWebcamActive ? 'fa-video-slash me-1' : 'fa-camera me-1'"></i>
                                        {{ isWebcamActive ? 'Disable Camera' : 'Enable Live Webcam' }}
                                    </button>
                                </div>

                                <!-- Location Header Overlay -->
                                <div class="position-absolute bottom-0 start-0 end-0 p-3 bg-dark bg-opacity-75 text-white d-flex justify-content-between align-items-center">
                                    <span class="fw-bold"><i class="fas fa-map-marker-alt me-2 text-warning"></i>{{ currentStepData.location }}</span>
                                    <span class="badge bg-warning text-dark">Step {{ currentStepIndex + 1 }} of {{ steps.length }}</span>
                                </div>
                            </div>

                            <div class="card-body p-4">
                                <!-- Scenario Prompt -->
                                <div class="tourist-question-card mb-4">
                                    <h5 class="fw-bold text-white mb-2"><i class="fas fa-user-circle me-2"></i>Tourist Scenario Prompt</h5>
                                    <p class="mb-0 opacity-90 fs-6">{{ currentStepData.prompt }}</p>
                                </div>

                                <!-- Speech Recognition Pipeline Section -->
                                <div class="speech-pipeline-card p-3 rounded-4 bg-light border mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="fw-bold text-dark mb-0">
                                            <i class="fas fa-microphone text-danger me-2"></i>
                                            Real-Time Speech Recognition Commentary
                                        </h6>
                                        <span class="badge" :class="isListening ? 'bg-danger animate-pulse' : 'bg-secondary'">
                                            <i class="fas fa-circle me-1" style="font-size: 0.6rem;"></i>
                                            {{ isListening ? 'Listening Live...' : 'Microphone Idle' }}
                                        </span>
                                    </div>
                                    
                                    <p class="small text-muted mb-3">Speak your commentary response clearly into your microphone to earn XP points and unlock destination badges.</p>

                                    <!-- Action Buttons -->
                                    <div class="d-flex gap-2 mb-3">
                                        <button @click="toggleSpeechRecognition" class="btn rounded-pill px-4 fw-bold shadow-sm" :class="isListening ? 'btn-danger' : 'btn-success'">
                                            <i class="fas" :class="isListening ? 'fa-stop-circle me-1' : 'fa-microphone me-1'"></i>
                                            {{ isListening ? 'Stop Speech Commentary' : 'Start Spoken Commentary' }}
                                        </button>
                                        <button v-if="spokenTranscript" @click="resetTranscript" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                                            Clear Speech
                                        </button>
                                    </div>

                                    <!-- Live Speech Transcript Display Box -->
                                    <div class="transcript-box p-3 bg-white rounded-3 border" style="min-height: 80px; max-height: 140px; overflow-y: auto;">
                                        <span v-if="spokenTranscript" class="text-dark fw-medium">{{ spokenTranscript }}</span>
                                        <span v-else class="text-muted italic small"><i class="fas fa-comment-dots me-1"></i>Click 'Start Spoken Commentary' and speak your response...</span>
                                    </div>
                                </div>

                                <!-- Multiple Choice Fallback Options -->
                                <h6 class="fw-bold text-dark mb-3"><i class="fas fa-comments me-2 text-success"></i>Or Select Prepared Commentary Text:</h6>
                                <div class="d-grid gap-3 mb-4">
                                    <button
                                        v-for="(option, idx) in currentStepData.options"
                                        :key="idx"
                                        type="button"
                                        class="btn btn-outline-secondary text-start p-3 rounded-3 option-btn"
                                        :class="{ selected: selectedOptionIndex === idx }"
                                        @click="chooseOption(option, idx)"
                                        :disabled="stepAnswered"
                                    >
                                        <strong class="text-success me-2">{{ String.fromCharCode(65 + idx) }}.</strong> {{ option.text }}
                                    </button>
                                </div>

                                <!-- Validation Keyword Cloud -->
                                <div>
                                    <h6 class="fw-bold text-dark mb-2"><i class="fas fa-tags me-2 text-primary"></i>Required Validation Keywords:</h6>
                                    <div class="keyword-cloud">
                                        <span
                                            v-for="kw in currentStepData.keywords"
                                            :key="kw"
                                            class="keyword-tag"
                                            :class="isKeywordMatched(kw) ? 'covered bg-success text-white border-success' : ''"
                                        >
                                            <i class="fas fa-check me-1" v-if="isKeywordMatched(kw)"></i>{{ kw }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Feedback Section -->
                                <div v-if="feedbackText" class="mt-4 p-3 rounded-3 border" :class="feedbackIsGood ? 'bg-success bg-opacity-10 border-success' : 'bg-warning bg-opacity-10 border-warning'">
                                    <h6 class="fw-bold mb-1" :class="feedbackIsGood ? 'text-success' : 'text-dark'">
                                        <i :class="feedbackIsGood ? 'fas fa-check-circle text-success' : 'fas fa-exclamation-triangle text-warning'" class="me-2"></i>Evaluation Result
                                    </h6>
                                    <p class="mb-3 small text-dark">{{ feedbackText }}</p>
                                    <button @click="proceedNextStep" class="btn btn-success rounded-pill fw-bold px-4">
                                        {{ currentStepIndex + 1 < scenarioData.length ? 'Next Destination Step' : 'Complete Simulation' }} <i class="fas fa-arrow-right ms-1"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Side Facilitator Panel -->
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 p-4 text-center bg-white sticky-top" style="top: 20px;">
                            <img src="/assets/images/facilitator-female.jpg" alt="Guide Maria" class="rounded-circle mx-auto mb-3 shadow border border-3 border-warning" style="width: 90px; height: 90px; object-fit: cover;">
                            <h5 class="fw-bold text-dark mb-1">Guide Maria</h5>
                            <small class="text-success fw-bold d-block mb-3">AI Tour Facilitator</small>

                            <!-- Speech Recognition Status Indicator -->
                            <div class="p-3 bg-light rounded-3 text-start mb-3 border">
                                <small class="text-muted d-block mb-1 fw-bold">SPEECH ENGINE STATUS:</small>
                                <small class="fw-semibold" :class="speechSupported ? 'text-success' : 'text-warning'">
                                    <i class="fas" :class="speechSupported ? 'fa-check-circle me-1' : 'fa-exclamation-triangle me-1'"></i>
                                    {{ speechSupported ? 'Web Speech API Ready' : 'Speech API fallback active' }}
                                </small>
                            </div>

                            <div class="p-3 bg-light rounded-3 text-start mb-3 border">
                                <small class="text-muted d-block mb-1 fw-bold">FACILITATOR TIP:</small>
                                <small class="text-secondary">Speak key terms like <strong>Earthquake Baroque</strong>, <strong>UNESCO</strong>, and historical dates out loud to maximize score accuracy.</small>
                            </div>

                            <!-- Live Keyword Score Counter -->
                            <div class="p-3 bg-success bg-opacity-10 rounded-3 text-start border border-success">
                                <small class="text-success d-block mb-1 fw-bold">KEYWORDS MATCHED:</small>
                                <h4 class="fw-bold text-success mb-0">{{ matchedKeywordsList.length }} / {{ currentStepData.keywords.length }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Simulation Complete Modal -->
        <div v-if="showCompleteModal" class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.75);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 shadow-lg border-0">
                    <div class="modal-body text-center p-4">
                        <i class="fas fa-trophy text-warning display-3 mb-3"></i>
                        <h3 class="fw-bold text-success mb-2">Simulation Complete!</h3>
                        <p class="text-muted mb-4">You successfully completed the Ilocos Norte Tour Guiding Simulation.</p>

                        <div class="bg-light p-3 rounded-4 mb-4 border">
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted d-block">Satisfaction Score</small>
                                    <h3 class="fw-bold text-success mb-0">{{ satisfactionScore }}%</h3>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">XP Points Earned</small>
                                    <h3 class="fw-bold text-warning mb-0">+{{ totalXpEarned }} XP</h3>
                                </div>
                            </div>
                        </div>

                        <Link :href="route('achievements.index')" class="btn btn-warning rounded-pill px-4 fw-bold me-2 mb-2">
                            <i class="fas fa-certificate me-1"></i> View Certificate
                        </Link>
                        <Link :href="route('dashboard')" class="btn btn-success rounded-pill px-4 fw-bold mb-2">
                            Return to Dashboard
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </StudentLayout>
</template>

<script setup>
import StudentLayout from '@/Layouts/StudentLayout.vue';
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';

const currentStepIndex = ref(0);
const satisfactionScore = ref(90);
const totalXpEarned = ref(150);
const stepAnswered = ref(false);
const selectedOptionIndex = ref(null);
const feedbackText = ref('');
const feedbackIsGood = ref(true);
const showCompleteModal = ref(false);

// Webcam & Virtual Canvas States
const isWebcamActive = ref(false);
const webcamVideo = ref(null);
const bgCanvas = ref(null);
let mediaStream = null;
let animFrameId = null;

// Web Speech API States
const isListening = ref(false);
const spokenTranscript = ref('');
const matchedKeywordsList = ref([]);
const speechSupported = ref(false);
let recognition = null;

const steps = [
    { title: 'Preparation' },
    { title: 'Briefing' },
    { title: 'Guiding' },
    { title: 'Conclusion' },
];

const scenarioData = [
    {
        location: 'St. William Cathedral & Sinking Bell Tower',
        title: 'Arrival in Laoag City Center',
        prompt: 'Your tour group steps out in front of the Sinking Bell Tower in Laoag City. A tourist asks: "Why is this bell tower located so far away from the main church structure?" How do you respond?',
        image: '/assets/images/Laoag.jpg',
        keywords: ['Earthquake Baroque', 'Augustinian', 'Sandy', '85 Meters'],
        options: [
            {
                text: 'Explain that Augustinian friars built it 85 meters away due to earthquake precautions and sandy ground foundation conditions.',
                score: 10,
                feedback: 'Excellent response! Accurate historical context regarding earthquake baroque design.',
                isGood: true,
            },
            {
                text: 'Tell them it was accidentally built in the wrong location by Spanish architects.',
                score: -10,
                feedback: 'Incorrect. The placement was deliberate due to soil and structural stability considerations.',
                isGood: false,
            },
        ],
    },
    {
        location: 'Paoay UNESCO World Heritage Church',
        title: 'Guiding at Paoay Church',
        prompt: 'As you approach Paoay Church, guests are amazed by the thick buttresses. What key feature should you highlight in your spoken commentary?',
        image: '/assets/images/Paoay.jpg',
        keywords: ['24 Buttresses', 'UNESCO', 'Coral Stone', 'Sugar Cane'],
        options: [
            {
                text: 'Highlight the 24 massive coral stone buttresses built to withstand severe seismic activity, inscribing it into UNESCO World Heritage list.',
                score: 10,
                feedback: 'Outstanding commentary! Guests are impressed by your heritage knowledge.',
                isGood: true,
            },
            {
                text: 'Focus only on taking photos and skip explaining the architectural history.',
                score: -5,
                feedback: 'Tourists appreciate photo opportunities, but expected historical commentary.',
                isGood: false,
            },
        ],
    },
];

const currentStepData = computed(() => scenarioData[Math.min(currentStepIndex.value, scenarioData.length - 1)]);

// Web Speech API Initialization
onMounted(() => {
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    if (SpeechRecognition) {
        speechSupported.value = true;
        recognition = new SpeechRecognition();
        recognition.continuous = true;
        recognition.interimResults = true;
        recognition.lang = 'en-US';

        recognition.onresult = (event) => {
            let current = '';
            for (let i = event.resultIndex; i < event.results.length; i++) {
                current += event.results[i][0].transcript;
            }
            spokenTranscript.value = current;
            parseKeywordsFromTranscript(current);
        };

        recognition.onerror = () => {
            isListening.value = false;
        };

        recognition.onend = () => {
            isListening.value = false;
        };
    }
});

onUnmounted(() => {
    stopWebcam();
    if (recognition && isListening.value) {
        recognition.stop();
    }
});

// Webcam Controls
const toggleWebcam = async () => {
    if (isWebcamActive.value) {
        stopWebcam();
    } else {
        try {
            mediaStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
            if (webcamVideo.value) {
                webcamVideo.value.srcObject = mediaStream;
                isWebcamActive.value = true;
                renderCanvasOverlay();
            }
        } catch (e) {
            alert('Unable to access webcam. Please check browser camera permissions.');
        }
    }
};

const stopWebcam = () => {
    if (mediaStream) {
        mediaStream.getTracks().forEach(track => track.stop());
        mediaStream = null;
    }
    if (animFrameId) {
        cancelAnimationFrame(animFrameId);
        animFrameId = null;
    }
    isWebcamActive.value = false;
};

const renderCanvasOverlay = () => {
    if (!isWebcamActive.value || !bgCanvas.value || !webcamVideo.value) return;
    const ctx = bgCanvas.value.getContext('2d');
    const width = bgCanvas.value.width = bgCanvas.value.clientWidth;
    const height = bgCanvas.value.height = bgCanvas.value.clientHeight;

    const bgImg = new Image();
    bgImg.src = currentStepData.value.image;

    const draw = () => {
        if (!isWebcamActive.value) return;
        ctx.clearRect(0, 0, width, height);

        // Draw destination background image
        if (bgImg.complete) {
            ctx.drawImage(bgImg, 0, 0, width, height);
        }

        // Draw webcam feed in lower right corner inset
        if (webcamVideo.value.readyState === webcamVideo.value.HAVE_ENOUGH_DATA) {
            const insetWidth = width * 0.35;
            const insetHeight = height * 0.45;
            const x = width - insetWidth - 15;
            const y = height - insetHeight - 55;

            ctx.save();
            ctx.strokeStyle = '#ffd700';
            ctx.lineWidth = 3;
            ctx.strokeRect(x, y, insetWidth, insetHeight);
            ctx.drawImage(webcamVideo.value, x, y, insetWidth, insetHeight);
            ctx.restore();
        }

        animFrameId = requestAnimationFrame(draw);
    };

    draw();
};

// Speech Recognition Controls
const toggleSpeechRecognition = () => {
    if (!speechSupported.value) {
        alert('Web Speech API is not supported in this browser. Please use Chrome/Edge.');
        return;
    }

    if (isListening.value) {
        recognition.stop();
        isListening.value = false;
        validateSpeechWithServer();
    } else {
        spokenTranscript.value = '';
        recognition.start();
        isListening.value = true;
    }
};

const resetTranscript = () => {
    spokenTranscript.value = '';
    matchedKeywordsList.value = [];
};

const parseKeywordsFromTranscript = (text) => {
    const lower = text.toLowerCase();
    const matches = [];
    currentStepData.value.keywords.forEach(kw => {
        if (lower.includes(kw.toLowerCase())) {
            matches.push(kw);
        }
    });
    matchedKeywordsList.value = matches;
};

const isKeywordMatched = (kw) => {
    return matchedKeywordsList.value.includes(kw) || stepAnswered.value;
};

const validateSpeechWithServer = async () => {
    if (!spokenTranscript.value) return;
    try {
        const response = await axios.post(route('simulation.validate'), {
            transcript: spokenTranscript.value,
            required_keywords: currentStepData.value.keywords,
        });

        if (response.data.success) {
            matchedKeywordsList.value = response.data.matched_keywords;
            stepAnswered.value = true;
            satisfactionScore.value = Math.min(100, satisfactionScore.value + (response.data.match_count * 5));
            totalXpEarned.value += response.data.xp_earned;
            feedbackText.value = `Speech processed! Matched ${response.data.match_count} of ${currentStepData.value.keywords.length} keywords. Earned +${response.data.xp_earned} XP!`;
            feedbackIsGood.value = response.data.match_count > 0;
        }
    } catch (e) {
        // Fallback to local keyword validation
        stepAnswered.value = true;
        feedbackText.value = `Commentary speech recorded. Matched ${matchedKeywordsList.value.length} keywords.`;
        feedbackIsGood.value = true;
    }
};

const chooseOption = (option, idx) => {
    selectedOptionIndex.value = idx;
    stepAnswered.value = true;
    satisfactionScore.value = Math.min(100, Math.max(0, satisfactionScore.value + option.score));
    feedbackText.value = option.feedback;
    feedbackIsGood.value = option.isGood;
    matchedKeywordsList.value = [...currentStepData.value.keywords];
};

const proceedNextStep = () => {
    if (currentStepIndex.value + 1 < scenarioData.length) {
        currentStepIndex.value++;
        stepAnswered.value = false;
        selectedOptionIndex.value = null;
        feedbackText.value = '';
        spokenTranscript.value = '';
        matchedKeywordsList.value = [];
        if (isWebcamActive.value) {
            renderCanvasOverlay();
        }
    } else {
        showCompleteModal.value = true;
    }
};
</script>

<style scoped>
.simulation-container {
    background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
    min-height: 100vh;
}

.page-header {
    background: linear-gradient(135deg, #0a472e 0%, #1a5f7a 100%);
    border-radius: 20px;
    padding: 30px;
    color: white;
    margin: 30px 0;
}

.simulation-steps {
    display: flex;
    justify-content: space-between;
    margin: 25px 0 35px;
}

.step-item {
    text-align: center;
    flex: 1;
    position: relative;
}

.step-item:not(:last-child)::after {
    content: '';
    position: absolute;
    top: 25px;
    right: -50%;
    width: 100%;
    height: 3px;
    background: #dee2e6;
    z-index: 1;
}

.step-item.completed:not(:last-child)::after {
    background: #28a745;
}

.step-number {
    width: 50px;
    height: 50px;
    background: white;
    border: 3px solid #dee2e6;
    color: #6c757d;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.2rem;
    margin: 0 auto 10px;
    position: relative;
    z-index: 2;
}

.step-item.active .step-number {
    border-color: #0a472e;
    background: #0a472e;
    color: white;
}

.step-item.completed .step-number {
    border-color: #28a745;
    background: #28a745;
    color: white;
}

.tourist-question-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px;
    padding: 20px 25px;
}

.option-btn {
    transition: all 0.3s ease;
}

.option-btn:hover:not(:disabled), .option-btn.selected {
    background-color: rgba(10, 71, 46, 0.08);
    border-color: #0a472e;
    color: #0a472e;
}

.keyword-cloud {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.keyword-tag {
    background: #f8f9fa;
    border-radius: 30px;
    padding: 6px 14px;
    font-size: 0.8rem;
    color: #495057;
    border: 1px solid #dee2e6;
}

.keyword-tag.covered {
    background: #28a745;
    color: white;
    border-color: #28a745;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.animate-pulse {
    animation: pulse 1.5s infinite;
}
</style>
