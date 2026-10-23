<template>
    <Teleport to="body">
        <Transition name="tutorial-fade">
            <div 
                v-if="show" 
                class="tutorial-modal-overlay d-flex align-items-center justify-content-center p-3"
                @click.self="handleClose"
                role="dialog"
                aria-modal="true"
                tabindex="-1"
                @keydown.esc="handleClose"
                @keydown.left="prevStep"
                @keydown.right="nextStep"
            >
                <div class="tutorial-card shadow-2xl rounded-4 overflow-hidden position-relative bg-white text-dark">
                    <!-- Top Gradient Header Bar with Stage Identity -->
                    <div class="tutorial-header px-4 py-3 text-white position-relative overflow-hidden" :class="currentStepData.headerClass">
                        <!-- Background Watermark -->
                        <div class="tutorial-header-watermark" aria-hidden="true">
                            <i :class="currentStepData.icon"></i>
                        </div>

                        <div class="d-flex align-items-center justify-content-between position-relative z-1 mb-2">
                            <div class="d-inline-flex align-items-center gap-2 stage-tag-pill">
                                <i :class="currentStepData.pillIcon" class="text-warning"></i>
                                <span>{{ currentStepData.tag }}</span>
                            </div>
                            
                            <button 
                                type="button" 
                                class="btn-close-tutorial" 
                                @click="handleClose"
                                aria-label="Close Tutorial"
                                title="Close Tutorial"
                            >
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <div class="position-relative z-1">
                            <h2 class="h4 fw-bold mb-1 text-white">{{ currentStepData.title }}</h2>
                            <p class="small mb-0 text-white-50">{{ currentStepData.subtitle }}</p>
                        </div>

                        <!-- Mini Step Tracker -->
                        <div class="tutorial-step-bar mt-3 position-relative z-1">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="tutorial-step-label">Step {{ currentStep }} of {{ steps.length }}</span>
                                <span class="tutorial-step-title">{{ currentStepData.stepName }}</span>
                            </div>
                            <div class="progress tutorial-progress-track">
                                <div 
                                    class="progress-bar bg-warning transition-all" 
                                    role="progressbar" 
                                    :style="{ width: `${(currentStep / steps.length) * 100}%` }"
                                    aria-valuemin="0" 
                                    aria-valuemax="100"
                                ></div>
                            </div>
                        </div>
                    </div>

                    <!-- Step Body with Animated Slide Transitions -->
                    <div class="tutorial-body p-4">
                        <Transition name="slide-step" mode="out-in">
                            <div :key="currentStep" class="tutorial-step-content">
                                <!-- Step 1: Welcome & Mission -->
                                <div v-if="currentStep === 1">
                                    <div class="d-flex align-items-center gap-3 mb-3 p-3 rounded-3 bg-light border border-light-subtle">
                                        <div class="tutorial-icon-box bg-success bg-opacity-10 text-success flex-shrink-0">
                                            <i class="fas fa-compass fa-2x"></i>
                                        </div>
                                        <div>
                                            <h3 class="h6 fw-bold mb-1 text-dark">Mariano Marcos State University</h3>
                                            <p class="small text-muted mb-0">Tourism & Hospitality Management Tour Guiding Training System</p>
                                        </div>
                                    </div>
                                    <p class="text-secondary small mb-3">
                                        Welcome, Trainee Guide! Your mission is to master the culture, history, and storytelling of Ilocos Norte and earn your official <strong>Certificate of Completion</strong> through 3 guided stages:
                                    </p>
                                    <div class="row g-2 mb-2">
                                        <div class="col-12 col-md-4">
                                            <div class="p-2 rounded-3 border h-100 text-center bg-purple-subtle-box">
                                                <i class="fas fa-book-open text-purple mb-1 fs-5"></i>
                                                <div class="fw-bold small text-dark">Stage 1</div>
                                                <div class="text-muted" style="font-size: 0.72rem;">Go Beyond Books</div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="p-2 rounded-3 border h-100 text-center bg-pink-subtle-box">
                                                <i class="fas fa-compass text-pink mb-1 fs-5"></i>
                                                <div class="fw-bold small text-dark">Stage 2</div>
                                                <div class="text-muted" style="font-size: 0.72rem;">Dare to Discover</div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="p-2 rounded-3 border h-100 text-center bg-blue-subtle-box">
                                                <i class="fas fa-mountain text-primary mb-1 fs-5"></i>
                                                <div class="fw-bold small text-dark">Stage 3</div>
                                                <div class="text-muted" style="font-size: 0.72rem;">Adventure Awaits</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 2: Gamification & XP System -->
                                <div v-else-if="currentStep === 2">
                                    <div class="d-flex align-items-center gap-3 mb-3 p-3 rounded-3 bg-warning bg-opacity-10 border border-warning-subtle">
                                        <div class="tutorial-icon-box bg-warning text-dark flex-shrink-0 shadow-sm">
                                            <i class="fas fa-star fa-2x"></i>
                                        </div>
                                        <div>
                                            <h3 class="h6 fw-bold mb-1 text-dark">Gamified Progression</h3>
                                            <p class="small text-muted mb-0">Every learning activity rewards you with XP and badges</p>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column gap-2 text-secondary small mb-2">
                                        <div class="d-flex align-items-start gap-2">
                                            <i class="fas fa-bolt text-warning mt-1"></i>
                                            <div><strong>Experience Points (XP):</strong> Earn XP by completing lessons, passing quizzes, and finishing speech simulations.</div>
                                        </div>
                                        <div class="d-flex align-items-start gap-2">
                                            <i class="fas fa-trophy text-warning mt-1"></i>
                                            <div><strong>Achievements & Badges:</strong> Collect prestigious badges like <em>Foundation Scholar</em>, <em>Ilocos Explorer</em>, and <em>Master Guide</em>.</div>
                                        </div>
                                        <div class="d-flex align-items-start gap-2">
                                            <i class="fas fa-chart-line text-success mt-1"></i>
                                            <div><strong>Live Progress Bar:</strong> Monitor your target XP and overall completion from the Trainee Dashboard.</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 3: Stage 1 (Go Beyond Books) -->
                                <div v-else-if="currentStep === 3">
                                    <div class="d-flex align-items-center gap-3 mb-3 p-3 rounded-3 bg-purple-subtle-box border border-purple-subtle">
                                        <div class="tutorial-icon-box bg-purple text-white flex-shrink-0 shadow-sm">
                                            <i class="fas fa-book-open fa-2x"></i>
                                        </div>
                                        <div>
                                            <h3 class="h6 fw-bold mb-1 text-dark">Stage 1 &bull; Go Beyond Books</h3>
                                            <p class="small text-muted mb-0">Core principles, ethics & tour guiding preparation</p>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column gap-2 text-secondary small mb-2">
                                        <div class="d-flex align-items-start gap-2">
                                            <i class="fas fa-check-circle text-purple mt-1"></i>
                                            <div><strong>Interactive Lessons:</strong> Master preparation checklists, storytelling techniques, and tourist safety protocols.</div>
                                        </div>
                                        <div class="d-flex align-items-start gap-2">
                                            <i class="fas fa-pencil-alt text-purple mt-1"></i>
                                            <div><strong>Knowledge Checks:</strong> Quick assessments at the end of each topic test your understanding.</div>
                                        </div>
                                        <div class="d-flex align-items-start gap-2">
                                            <i class="fas fa-key text-warning mt-1"></i>
                                            <div><strong>Unlock Condition:</strong> Score at least <strong>80%</strong> on the End-of-Module Evaluation to unlock Stage 2!</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 4: Stage 2 (Dare to Discover) -->
                                <div v-else-if="currentStep === 4">
                                    <div class="d-flex align-items-center gap-3 mb-3 p-3 rounded-3 bg-pink-subtle-box border border-pink-subtle">
                                        <div class="tutorial-icon-box bg-pink text-white flex-shrink-0 shadow-sm">
                                            <i class="fas fa-compass fa-2x"></i>
                                        </div>
                                        <div>
                                            <h3 class="h6 fw-bold mb-1 text-dark">Stage 2 &bull; Dare to Discover</h3>
                                            <p class="small text-muted mb-0">Explore municipalities across Ilocos Norte</p>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column gap-2 text-secondary small mb-2">
                                        <div class="d-flex align-items-start gap-2">
                                            <i class="fas fa-map-marked-alt text-pink mt-1"></i>
                                            <div><strong>Interactive Map:</strong> Discover towns including Laoag City, Paoay, Pagudpud, Batac, and more.</div>
                                        </div>
                                        <div class="d-flex align-items-start gap-2">
                                            <i class="fas fa-landmark text-pink mt-1"></i>
                                            <div><strong>Attractions & Heritage:</strong> Study UNESCO heritage sites, sand dunes, wind farms, and local delicacies.</div>
                                        </div>
                                        <div class="d-flex align-items-start gap-2">
                                            <i class="fas fa-key text-warning mt-1"></i>
                                            <div><strong>Unlock Condition:</strong> Complete all municipality chapters to unlock live simulation practice in Stage 3!</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 5: Stage 3 (Adventure Awaits & Certification) -->
                                <div v-else-if="currentStep === 5">
                                    <div class="d-flex align-items-center gap-3 mb-3 p-3 rounded-3 bg-blue-subtle-box border border-primary-subtle">
                                        <div class="tutorial-icon-box bg-primary text-white flex-shrink-0 shadow-sm">
                                            <i class="fas fa-mountain fa-2x"></i>
                                        </div>
                                        <div>
                                            <h3 class="h6 fw-bold mb-1 text-dark">Stage 3 &bull; Adventure Awaits</h3>
                                            <p class="small text-muted mb-0">Live speech simulation & final certification</p>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column gap-2 text-secondary small mb-2">
                                        <div class="d-flex align-items-start gap-2">
                                            <i class="fas fa-microphone-alt text-primary mt-1"></i>
                                            <div><strong>Virtual Speech Guiding:</strong> Deliver tour spiels into your microphone with real-time speech evaluation.</div>
                                        </div>
                                        <div class="d-flex align-items-start gap-2">
                                            <i class="fas fa-smile text-success mt-1"></i>
                                            <div><strong>Tourist Satisfaction:</strong> Receive dynamic satisfaction scores based on pronunciation and delivery.</div>
                                        </div>
                                        <div class="d-flex align-items-start gap-2">
                                            <i class="fas fa-certificate text-warning mt-1"></i>
                                            <div><strong>Official Certificate:</strong> Reach 100% completion to unlock and download your authenticated MMSU Certificate!</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </Transition>

                        <!-- Step Dots Navigation -->
                        <div class="d-flex justify-content-center align-items-center gap-2 mt-4 pt-2 border-top">
                            <button 
                                v-for="(s, idx) in steps" 
                                :key="idx" 
                                type="button" 
                                class="tutorial-dot"
                                :class="{ 'is-active': currentStep === idx + 1 }"
                                :title="`Go to Step ${idx + 1}: ${s.stepName}`"
                                @click="currentStep = idx + 1"
                            ></button>
                        </div>
                    </div>

                    <!-- Footer Controls -->
                    <div class="tutorial-footer px-4 py-3 bg-light border-top d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <button 
                            type="button" 
                            class="btn btn-sm btn-link text-muted text-decoration-none p-0 fw-semibold"
                            @click="handleClose"
                        >
                            Skip Briefing
                        </button>

                        <div class="d-flex align-items-center gap-2 ms-auto">
                            <button 
                                v-if="currentStep > 1" 
                                type="button" 
                                class="btn btn-sm btn-outline-secondary px-3 rounded-pill fw-semibold"
                                @click="prevStep"
                            >
                                <i class="fas fa-chevron-left me-1"></i> Back
                            </button>

                            <button 
                                v-if="currentStep < steps.length" 
                                type="button" 
                                class="btn btn-sm btn-success px-4 rounded-pill fw-bold text-white shadow-sm"
                                @click="nextStep"
                            >
                                Next <i class="fas fa-chevron-right ms-1"></i>
                            </button>

                            <button 
                                v-else 
                                type="button" 
                                class="btn btn-sm btn-success px-4 rounded-pill fw-bold text-white shadow-sm animate-pulse"
                                @click="handleFinish"
                            >
                                Start My Journey! <i class="fas fa-rocket ms-1"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { ref, computed, watch } from 'vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    initialStep: {
        type: Number,
        default: 1
    }
});

const emit = defineEmits(['close', 'finish']);

const currentStep = ref(props.initialStep || 1);

watch(() => props.initialStep, (newStep) => {
    if (newStep >= 1 && newStep <= steps.length) {
        currentStep.value = newStep;
    }
});

watch(() => props.show, (newVal) => {
    if (newVal && props.initialStep) {
        currentStep.value = props.initialStep;
    }
});

const steps = [
    {
        stepName: 'Mission Briefing',
        tag: 'TRAINEE ORIENTATION',
        pillIcon: 'fas fa-graduation-cap',
        title: 'Welcome to WIN e-Travel Academy',
        subtitle: 'Your 3-stage journey to becoming an MMSU-certified tour guide.',
        icon: 'fas fa-compass',
        headerClass: 'header-stage-dashboard'
    },
    {
        stepName: 'Gamification & XP',
        tag: 'GAME SYSTEM',
        pillIcon: 'fas fa-star',
        title: 'Level Up & Earn Badges',
        subtitle: 'Earn Experience Points and showcase achievements on your profile.',
        icon: 'fas fa-trophy',
        headerClass: 'header-stage-xp'
    },
    {
        stepName: 'Stage 1: Books',
        tag: 'STAGE 1 &bull; FOUNDATION',
        pillIcon: 'fas fa-book-open',
        title: 'Stage 1: Go Beyond Books',
        subtitle: 'Master fundamental guiding ethics, preparation, and assessments.',
        icon: 'fas fa-book-open',
        headerClass: 'header-stage-foundation'
    },
    {
        stepName: 'Stage 2: Discover',
        tag: 'STAGE 2 &bull; EXPLORATION',
        pillIcon: 'fas fa-compass',
        title: 'Stage 2: Dare to Discover',
        subtitle: 'Explore the municipalities and cultural landmarks of Ilocos Norte.',
        icon: 'fas fa-compass',
        headerClass: 'header-stage-discover'
    },
    {
        stepName: 'Stage 3: Adventure',
        tag: 'STAGE 3 &bull; ADVENTURE',
        pillIcon: 'fas fa-mountain',
        title: 'Stage 3: Adventure Awaits',
        subtitle: 'Practice live virtual tour speeches and download your certificate.',
        icon: 'fas fa-certificate',
        headerClass: 'header-stage-adventure'
    }
];

const currentStepData = computed(() => steps[currentStep.value - 1]);

const nextStep = () => {
    if (currentStep.value < steps.length) {
        currentStep.value++;
    }
};

const prevStep = () => {
    if (currentStep.value > 1) {
        currentStep.value--;
    }
};

const handleClose = () => {
    emit('close');
};

const handleFinish = () => {
    emit('finish');
    emit('close');
};
</script>

<style scoped>
.tutorial-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(10, 25, 18, 0.75);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    z-index: 1060;
}

.tutorial-card {
    width: 100%;
    max-width: 620px;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);
    animation: zoomIn 0.25s ease-out;
}

.tutorial-header {
    flex-shrink: 0;
}

.tutorial-body {
    flex: 1 1 auto;
    overflow-y: auto;
}

.tutorial-footer {
    flex-shrink: 0;
}

@keyframes zoomIn {
    from {
        opacity: 0;
        transform: scale(0.96) translateY(10px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

/* Gradient Header Themes */
.header-stage-dashboard {
    background: linear-gradient(135deg, #0a472e 0%, #1a5f7a 100%);
}
.header-stage-xp {
    background: linear-gradient(135deg, #b45309 0%, #d97706 100%);
}
.header-stage-foundation {
    background: linear-gradient(135deg, #4a148c 0%, #7b1fa2 100%);
}
.header-stage-discover {
    background: linear-gradient(135deg, #880e4f 0%, #c2185b 100%);
}
.header-stage-adventure {
    background: linear-gradient(135deg, #01579b 0%, #0277bd 100%);
}

.tutorial-header-watermark {
    position: absolute;
    right: -15px;
    bottom: -25px;
    font-size: 130px;
    color: rgba(255, 255, 255, 0.08);
    transform: rotate(-12deg);
    pointer-events: none;
    z-index: 0;
}

.btn-close-tutorial {
    background: rgba(255, 255, 255, 0.15);
    border: none;
    color: white;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
}
.btn-close-tutorial:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: rotate(90deg);
}

.tutorial-step-bar {
    background: rgba(0, 0, 0, 0.15);
    backdrop-filter: blur(4px);
    border-radius: 10px;
    padding: 8px 12px;
}
.tutorial-step-label {
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: rgba(255, 255, 255, 0.75);
}
.tutorial-step-title {
    font-size: 0.78rem;
    font-weight: 700;
    color: #ffc107;
}
.tutorial-progress-track {
    height: 5px;
    background-color: rgba(255, 255, 255, 0.2);
    border-radius: 5px;
}

.tutorial-icon-box {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Stage color helper classes */
.text-purple { color: #7c3aed !important; }
.bg-purple { background-color: #7c3aed !important; }
.bg-purple-subtle-box { background-color: #f5f3ff !important; }
.border-purple-subtle { border-color: #ddd6fe !important; }

.text-pink { color: #db2777 !important; }
.bg-pink { background-color: #db2777 !important; }
.bg-pink-subtle-box { background-color: #fdf2f8 !important; }
.border-pink-subtle { border-color: #fbcfe8 !important; }

.bg-blue-subtle-box { background-color: #f0f9ff !important; }

/* Step Dots */
.tutorial-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background-color: #cbd5e1;
    border: none;
    padding: 0;
    cursor: pointer;
    transition: all 0.25s ease;
}
.tutorial-dot.is-active {
    background-color: #198754;
    width: 24px;
    border-radius: 10px;
}

/* Transitions */
.tutorial-fade-enter-active,
.tutorial-fade-leave-active {
    transition: opacity 0.25s ease;
}
.tutorial-fade-enter-from,
.tutorial-fade-leave-to {
    opacity: 0;
}

.slide-step-enter-active,
.slide-step-leave-active {
    transition: all 0.2s ease;
}
.slide-step-enter-from {
    opacity: 0;
    transform: translateX(15px);
}
.slide-step-leave-to {
    opacity: 0;
    transform: translateX(-15px);
}

@keyframes pulseBtn {
    0% { transform: scale(1); }
    50% { transform: scale(1.03); }
    100% { transform: scale(1); }
}
.animate-pulse {
    animation: pulseBtn 1.8s infinite ease-in-out;
}

@media (max-width: 576px) {
    .tutorial-card {
        max-width: 95vw;
    }
    .tutorial-header {
        padding: 16px 18px !important;
    }
    .tutorial-body {
        padding: 18px 16px !important;
    }
}
</style>
