<template>
    <StudentLayout>
        <div class="simulation-container py-4">
            <div class="container">
                <!-- Page Header matching overall design -->
                <div class="welcome-banner mb-4">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <h2 class="display-6 fw-bold mb-3">
                                <i class="fas fa-mountain me-2"></i>
                                Adventure Awaits
                            </h2>
                            <p class="mb-0 opacity-90 fs-5">Live Virtual Tour Guiding Simulation Engine</p>
                        </div>
                        <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                            <div class="d-flex justify-content-lg-end">
                                <div class="text-center">
                                    <div class="overall-progress-circle mb-2 mx-auto" :style="progressCircleStyle">
                                        <span class="progress-percentage fs-4">{{ satisfactionScore }}%</span>
                                    </div>
                                    <span class="small fw-bold text-white opacity-75">Tourist Satisfaction</span>
                                </div>
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
                                    <span class="fw-bold"><i class="fas fa-map-marker-alt me-2 text-warning"></i>{{ currentStepData.title }}</span>
                                    <div class="d-flex align-items-center gap-3">
                                        <span class="badge bg-danger text-white fs-6 border border-light" :class="{ 'animate-pulse': timeRemaining <= 10 }">
                                            <i class="fas fa-clock me-1"></i> {{ formatTime(timeRemaining) }}
                                        </span>
                                        <span class="badge bg-warning text-dark">Step {{ currentStepIndex + 1 }} of {{ steps.length }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body p-4">
                                <!-- Scenario Prompt -->
                                <div class="tourist-question-card mb-4 shadow-sm border-0">
                                    <h5 class="fw-bold text-dark mb-2"><i class="fas fa-bullhorn me-2 text-primary"></i>Your Turn to Guide</h5>
                                    <p class="mb-0 text-muted fs-6">You are now at <strong>{{ currentStepData.title }}</strong>. Begin your commentary and make sure to mention the required keywords before the timer runs out!</p>
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
                                        <button @click="toggleSpeechRecognition" class="btn rounded-pill px-4 fw-bold shadow-sm" :class="isListening ? 'btn-danger' : 'btn-success'" :disabled="stepAnswered">
                                            <i class="fas" :class="isListening ? 'fa-stop-circle me-1' : 'fa-microphone me-1'"></i>
                                            {{ isListening ? 'Stop Speech Commentary' : 'Start Spoken Commentary' }}
                                        </button>
                                        <button v-if="spokenTranscript && !stepAnswered" @click="resetTranscript" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                                            Clear
                                        </button>
                                        <button v-if="spokenTranscript && !stepAnswered" @click="validateSpeechWithServer" class="btn btn-primary rounded-pill px-4 fw-bold ms-auto">
                                            Submit Answer <i class="fas fa-check ms-1"></i>
                                        </button>
                                    </div>

                                    <!-- Live Speech Transcript Display Box -->
                                    <div class="transcript-box p-3 bg-white rounded-3 border mb-3" style="min-height: 80px; max-height: 140px; overflow-y: auto;">
                                        <span v-if="spokenTranscript" class="text-dark fw-medium">{{ spokenTranscript }}</span>
                                        <span v-else class="text-muted italic small"><i class="fas fa-comment-dots me-1"></i>Click 'Start Spoken Commentary' and speak your response...</span>
                                    </div>

                                    <!-- Typing Fallback -->
                                    <h6 class="fw-bold text-dark mb-2 fs-6"><i class="fas fa-keyboard me-2 text-success"></i>Or Type Your Commentary:</h6>
                                    <textarea 
                                        v-model="spokenTranscript" 
                                        class="form-control rounded-3" 
                                        rows="2" 
                                        placeholder="Type your commentary here if you cannot use the microphone..." 
                                        :disabled="stepAnswered || isListening" 
                                        @input="parseKeywordsFromTranscript(spokenTranscript)"
                                    ></textarea>
                                </div>

                                <!-- Validation Keyword Cloud -->
                                <div>
                                    <h6 class="fw-bold text-dark mb-2"><i class="fas fa-tags me-2 text-primary"></i>Required Validation Keywords:</h6>
                                    <div class="keyword-cloud">
                                        <span
                                            v-for="(kw, idx) in currentStepData.keywords"
                                            :key="'kw-'+idx"
                                            class="keyword-tag"
                                            :class="isKeywordMatched(kw) ? 'covered bg-success text-white border-success' : ''"
                                        >
                                            <i class="fas fa-check me-1" v-if="isKeywordMatched(kw)"></i>{{ kw.word }} ({{ kw.points }} pts)
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

                            <!-- Live Points Counter -->
                            <div class="p-3 bg-success bg-opacity-10 rounded-3 text-start border border-success">
                                <small class="text-success d-block mb-1 fw-bold">POINTS EARNED:</small>
                                <h4 class="fw-bold text-success mb-0">
                                    {{ currentStepData.keywords.filter(kw => isKeywordMatched(kw)).reduce((sum, kw) => sum + (Number(kw.points) || 0), 0) }} / 
                                    {{ currentStepData.keywords.reduce((sum, kw) => sum + (Number(kw.points) || 0), 0) }}
                                </h4>
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
                        <i :class="isPassed ? 'fas fa-trophy text-warning' : 'fas fa-times-circle text-danger'" class="display-3 mb-3"></i>
                        <h3 class="fw-bold mb-2" :class="isPassed ? 'text-success' : 'text-danger'">
                            {{ isPassed ? 'Simulation Complete!' : 'Simulation Failed' }}
                        </h3>
                        <p class="text-muted mb-4">
                            {{ isPassed ? 'You successfully completed the Tour Guiding Simulation.' : 'You did not meet the required satisfaction score to pass this simulation.' }}
                        </p>

                        <div class="bg-light p-3 rounded-4 mb-4 border">
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted d-block">Satisfaction Score</small>
                                    <h3 class="fw-bold mb-0" :class="isPassed ? 'text-success' : 'text-danger'">{{ Math.round(satisfactionScore) }}%</h3>
                                    <small class="text-muted">Required: {{ props.simulation.passing_score || 80 }}%</small>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">XP Points Earned</small>
                                    <h3 class="fw-bold text-warning mb-0">+{{ isPassed ? totalXpEarned : 0 }} XP</h3>
                                </div>
                            </div>
                        </div>

                        <div v-if="isPassed">
                            <Link :href="route('achievements.index')" class="btn btn-warning rounded-pill px-4 fw-bold me-2 mb-2">
                                <i class="fas fa-certificate me-1"></i> View Certificate
                            </Link>
                            <Link :href="route('dashboard')" class="btn btn-success rounded-pill px-4 fw-bold mb-2">
                                Return to Dashboard
                            </Link>
                        </div>
                        <div v-else>
                            <button @click="retrySimulation" class="btn btn-primary rounded-pill px-4 fw-bold me-2 mb-2">
                                <i class="fas fa-redo me-1"></i> Retry Simulation
                            </button>
                            <Link :href="route('towns.show', props.simulation.town?.slug || '')" class="btn btn-light border rounded-pill px-4 fw-bold mb-2">
                                Back to Town
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </StudentLayout>
</template>

<script setup>
import StudentLayout from '@/Layouts/StudentLayout.vue';
import { ref, computed, onMounted, onUnmounted, nextTick } from 'vue';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';
import { ImageSegmenter, FilesetResolver } from '@mediapipe/tasks-vision';

const props = defineProps({
    simulation: Object
});

const currentStepIndex = ref(0);
const satisfactionScore = ref(0);
const totalXpEarned = ref(0);
const stepAnswered = ref(false);
const selectedOptionIndex = ref(null);
const feedbackText = ref('');
const feedbackIsGood = ref(true);
const showCompleteModal = ref(false);
const isPassed = ref(false);

const retrySimulation = () => {
    window.location.reload();
};

const progressCircleStyle = computed(() => {
    const degrees = (satisfactionScore.value / 100) * 360;
    return {
        background: `conic-gradient(#ffc107 0deg ${degrees}deg, rgba(255,255,255,0.2) ${degrees}deg 360deg)`
    };
});

// Webcam & Virtual Canvas States
const isWebcamActive = ref(false);
const webcamVideo = ref(null);
const bgCanvas = ref(null);
let mediaStream = null;
let animFrameId = null;

// Web Speech API States
const isListening = ref(false);
const spokenTranscript = ref('');
const finalTranscriptBuffer = ref('');
const matchedKeywordsList = ref([]);
const speechSupported = ref(false);
let recognition = null;

const scenarioData = props.simulation ? props.simulation.scenarios : [];

const steps = computed(() => {
    return scenarioData.map((s) => ({ title: s.title }));
});

const currentStepData = computed(() => scenarioData[Math.min(currentStepIndex.value, scenarioData.length - 1)]);

// Timer States
const timeRemaining = ref(0);
let timerInterval = null;

const formatTime = (seconds) => {
    const m = Math.floor(seconds / 60);
    const s = seconds % 60;
    return `${m}:${s.toString().padStart(2, '0')}`;
};

const startTimer = () => {
    if (timerInterval) clearInterval(timerInterval);
    timeRemaining.value = currentStepData.value.time_limit || 60;
    timerInterval = setInterval(() => {
        if (timeRemaining.value > 0) {
            timeRemaining.value--;
        } else {
            clearInterval(timerInterval);
            if (isListening.value) {
                toggleSpeechRecognition();
            } else {
                validateSpeechWithServer();
            }
        }
    }, 1000);
};

// Virtual Background Segmenter State
let imageSegmenter = null;
const isSegmenterReady = ref(false);

const initSegmenter = async () => {
    try {
        const vision = await FilesetResolver.forVisionTasks(
            "https://cdn.jsdelivr.net/npm/@mediapipe/tasks-vision@latest/wasm"
        );
        imageSegmenter = await ImageSegmenter.createFromOptions(vision, {
            baseOptions: {
                modelAssetPath: "/assets/models/selfie_segmenter.tflite",
                delegate: "GPU"
            },
            runningMode: "VIDEO",
            outputCategoryMask: true,
            outputConfidenceMasks: false
        });
        isSegmenterReady.value = true;
    } catch (e) {
        console.error("Failed to initialize MediaPipe Segmenter:", e);
    }
};

// Web Speech API Initialization
onMounted(() => {
    initSegmenter();
    
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    if (SpeechRecognition) {
        speechSupported.value = true;
        recognition = new SpeechRecognition();
        recognition.continuous = true;
        recognition.interimResults = true;
        recognition.lang = 'en-US';

        recognition.onstart = () => {
            isListening.value = true;
        };

        recognition.onresult = (event) => {
            let current = '';
            for (let i = 0; i < event.results.length; i++) {
                current += event.results[i][0].transcript;
            }
            console.log("Speech Result Received:", current);
            spokenTranscript.value = (finalTranscriptBuffer.value + ' ' + current).trim();
            parseKeywordsFromTranscript(spokenTranscript.value);
        };

        recognition.onerror = (event) => {
            console.error('Speech Recognition Error:', event.error);
            if (event.error === 'not-allowed') {
                alert('Microphone access denied. Please allow microphone permissions in your browser.');
            }
            isListening.value = false;
        };

        recognition.onend = () => {
            isListening.value = false;
            finalTranscriptBuffer.value = spokenTranscript.value;
        };
    }
    
    startTimer();
});

onUnmounted(() => {
    if (timerInterval) clearInterval(timerInterval);
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
                await nextTick(); // Wait for canvas to be visible and get real dimensions
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

    let lastVideoTime = -1;

    // Mask processing canvases
    const maskCanvas = document.createElement('canvas');
    const maskCtx = maskCanvas.getContext('2d');
    const personCanvas = document.createElement('canvas');
    const personCtx = personCanvas.getContext('2d');

    const draw = () => {
        if (!isWebcamActive.value) return;
        ctx.clearRect(0, 0, width, height);

        // Draw destination background image
        if (bgImg.complete) {
            ctx.drawImage(bgImg, 0, 0, width, height);
        }

        if (webcamVideo.value.readyState === webcamVideo.value.HAVE_ENOUGH_DATA) {
            const videoWidth = webcamVideo.value.videoWidth;
            const videoHeight = webcamVideo.value.videoHeight;
            const insetWidth = width * 0.45; // Increased slightly for better presence
            const insetHeight = (insetWidth / videoWidth) * videoHeight;
            const x = width - insetWidth - 15;
            const y = height - insetHeight - 55;

            if (isSegmenterReady.value && imageSegmenter) {
                if (webcamVideo.value.currentTime !== lastVideoTime) {
                    lastVideoTime = webcamVideo.value.currentTime;
                    const startTimeMs = performance.now();
                    const segmentation = imageSegmenter.segmentForVideo(webcamVideo.value, startTimeMs);
                    
                    if (segmentation && segmentation.categoryMask) {
                        const mask = segmentation.categoryMask;
                        maskCanvas.width = mask.width;
                        maskCanvas.height = mask.height;
                        
                        personCanvas.width = mask.width;
                        personCanvas.height = mask.height;

                        const maskArray = mask.getAsUint8Array();
                        const imageData = maskCtx.createImageData(mask.width, mask.height);
                        
                        for (let i = 0; i < maskArray.length; i++) {
                            const isPerson = maskArray[i] === 0; // 0 is person in this model, 255 is background
                            const offset = i * 4;
                            imageData.data[offset] = 0;
                            imageData.data[offset + 1] = 0;
                            imageData.data[offset + 2] = 0;
                            imageData.data[offset + 3] = isPerson ? 255 : 0;
                        }
                        maskCtx.putImageData(imageData, 0, 0);

                        personCtx.clearRect(0, 0, personCanvas.width, personCanvas.height);
                        personCtx.drawImage(maskCanvas, 0, 0);
                        personCtx.globalCompositeOperation = 'source-in';
                        personCtx.drawImage(webcamVideo.value, 0, 0, personCanvas.width, personCanvas.height);
                        personCtx.globalCompositeOperation = 'source-over';
                    }
                }

                // Draw the segmented person onto main canvas
                if (personCanvas.width > 0) {
                    ctx.drawImage(personCanvas, x, y, insetWidth, insetHeight);
                }
            } else {
                // Fallback while segmenter is loading
                ctx.save();
                ctx.strokeStyle = '#ffd700';
                ctx.lineWidth = 3;
                ctx.strokeRect(x, y, insetWidth, insetHeight);
                ctx.drawImage(webcamVideo.value, x, y, insetWidth, insetHeight);
                ctx.restore();
            }
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
    } else {
        try {
            recognition.start();
            isListening.value = true;
        } catch (e) {
            console.error("Speech Recognition Start Error:", e);
            if (e.name === 'InvalidStateError') {
                // Already started, just set state to true
                isListening.value = true;
            }
        }
    }
};

const resetTranscript = () => {
    spokenTranscript.value = '';
    finalTranscriptBuffer.value = '';
    matchedKeywordsList.value = [];
};

const parseKeywordsFromTranscript = (text) => {
    if (!text) return;
    const cleanText = text.toLowerCase().replace(/[.,/#!$%^&*;:{}=\-_`~()]/g,"");
    const matches = [];
    currentStepData.value.keywords.forEach(kw => {
        if (kw && kw.word) {
            const cleanKw = kw.word.toLowerCase().trim().replace(/[.,/#!$%^&*;:{}=\-_`~()]/g,"");
            if (cleanText.includes(cleanKw)) {
                matches.push(kw.word);
            }
        }
    });
    matchedKeywordsList.value = matches;
};

const isKeywordMatched = (kw) => {
    return matchedKeywordsList.value.includes(kw.word) || stepAnswered.value;
};

const stepScores = ref([]);

const validateSpeechWithServer = async () => {
    if (!spokenTranscript.value) return;
    if (timerInterval) clearInterval(timerInterval); // stop timer on submission
    try {
        const response = await axios.post(route('simulation.validate'), {
            transcript: spokenTranscript.value,
            required_keywords: currentStepData.value.keywords,
        });

        if (response.data.success) {
            matchedKeywordsList.value = response.data.matched_keywords;
            stepAnswered.value = true;
            
            const stepScore = response.data.score_percent;
            const totalKw = currentStepData.value.keywords.length;
            
            stepScores.value[currentStepIndex.value] = stepScore;
            
            const totalScores = stepScores.value.reduce((a,b) => a+b, 0);
            satisfactionScore.value = totalScores / (currentStepIndex.value + 1);

            totalXpEarned.value += response.data.xp_earned;
            feedbackText.value = `Speech processed! Score: ${Math.round(stepScore)}% (${response.data.match_count} keywords matched)`;
            feedbackIsGood.value = response.data.match_count > 0 || totalKw === 0;
        }
    } catch (e) {
        // Fallback to local keyword validation
        stepAnswered.value = true;
        
        let earnedPoints = 0;
        let totalPoints = 0;
        
        currentStepData.value.keywords.forEach(kw => {
            const pts = Number(kw.points) || 0;
            totalPoints += pts;
            if (matchedKeywordsList.value.includes(kw.word)) {
                earnedPoints += pts;
            }
        });
        
        let stepScore = 100;
        if (totalPoints > 0) {
            stepScore = (earnedPoints / totalPoints) * 100;
        }
        stepScores.value[currentStepIndex.value] = stepScore;
        const totalScores = stepScores.value.reduce((a,b) => a+b, 0);
        satisfactionScore.value = totalScores / (currentStepIndex.value + 1);
        
        feedbackText.value = `Commentary recorded. Score: ${Math.round(stepScore)}% (${earnedPoints} / ${totalPoints} points)`;
        feedbackIsGood.value = earnedPoints > 0 || totalPoints === 0;
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
        startTimer();
    } else {
        const isPassed = satisfactionScore.value >= (props.simulation.passing_score || 80);
        showCompleteModal.value = true;
        axios.post(route('simulation.complete', props.simulation.id), { passed: isPassed }).catch(err => console.error(err));
    }
};
</script>

<style scoped>
.simulation-container {
    min-height: 100vh;
}

.welcome-banner {
    background: linear-gradient(135deg, #0a472e 0%, #1a5f7a 100%);
    border-radius: 30px;
    padding: 40px;
    color: white;
    margin-bottom: 30px;
    position: relative;
    overflow: hidden;
}

.overall-progress-circle {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: conic-gradient(#ffc107 0deg 0deg, rgba(255,255,255,0.2) 0deg 360deg);
    position: relative;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.overall-progress-circle::before {
    content: '';
    position: absolute;
    width: 80px;
    height: 80px;
    background-color: #1a5f7a; /* matching banner */
    border-radius: 50%;
}

.progress-percentage {
    position: relative;
    font-weight: 800;
    color: white;
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
    background: #f8f9fa;
    border-left: 4px solid #007bff;
    border-radius: 12px;
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
