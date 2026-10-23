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
                    <!-- Top Gradient Header Bar with Educator CMS Theme -->
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
                                <!-- Step 1: Faculty CMS Overview -->
                                <div v-if="currentStep === 1">
                                    <div class="d-flex align-items-center gap-3 mb-3 p-3 rounded-3 bg-light border border-light-subtle">
                                        <div class="tutorial-icon-box bg-success bg-opacity-10 text-success flex-shrink-0">
                                            <i class="fas fa-chalkboard-teacher fa-2x"></i>
                                        </div>
                                        <div>
                                            <h3 class="h6 fw-bold mb-1 text-dark">Faculty Curriculum Management System</h3>
                                            <p class="small text-muted mb-0">Mariano Marcos State University Tour Guiding Academy</p>
                                        </div>
                                    </div>
                                    <p class="text-secondary small mb-3">
                                        Welcome, Faculty Educator! As an administrator and instructor, you hold full control over student learning materials, quizzes, and certificate issuance across 4 key management areas:
                                    </p>
                                    <div class="row g-2 mb-2">
                                        <div class="col-6 col-md-3">
                                            <div class="p-2 rounded-3 border h-100 text-center bg-light">
                                                <i class="fas fa-book-open text-success mb-1 fs-5"></i>
                                                <div class="fw-bold small text-dark">Modules</div>
                                                <div class="text-muted" style="font-size: 0.72rem;">Content & Texts</div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <div class="p-2 rounded-3 border h-100 text-center bg-light">
                                                <i class="fas fa-tasks text-warning mb-1 fs-5"></i>
                                                <div class="fw-bold small text-dark">Quizzes</div>
                                                <div class="text-muted" style="font-size: 0.72rem;">Question Banks</div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <div class="p-2 rounded-3 border h-100 text-center bg-light">
                                                <i class="fas fa-user-graduate text-primary mb-1 fs-5"></i>
                                                <div class="fw-bold small text-dark">Analytics</div>
                                                <div class="text-muted" style="font-size: 0.72rem;">Student Tracking</div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <div class="p-2 rounded-3 border h-100 text-center bg-light">
                                                <i class="fas fa-certificate text-danger mb-1 fs-5"></i>
                                                <div class="fw-bold small text-dark">Certificate</div>
                                                <div class="text-muted" style="font-size: 0.72rem;">Signers & Logos</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 2: Creating & Managing Modules -->
                                <div v-else-if="currentStep === 2">
                                    <div class="d-flex align-items-center gap-3 mb-3 p-3 rounded-3 bg-success bg-opacity-10 border border-success-subtle">
                                        <div class="tutorial-icon-box text-white flex-shrink-0 shadow-sm" style="background-color: #0d4b38;">
                                            <i class="fas fa-layer-group fa-2x"></i>
                                        </div>
                                        <div>
                                            <h3 class="h6 fw-bold mb-1 text-dark">How to Add & Organize Modules</h3>
                                            <p class="small text-muted mb-0">Create new course chapters and control visibility</p>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column gap-2 text-secondary small mb-2">
                                        <div class="d-flex align-items-start gap-2">
                                            <i class="fas fa-plus-circle text-success mt-1"></i>
                                            <div><strong>Create Module:</strong> Click the yellow <em>Create Module</em> button in the Course Modules panel to add a title, description, and module category.</div>
                                        </div>
                                        <div class="d-flex align-items-start gap-2">
                                            <i class="fas fa-toggle-on text-success mt-1"></i>
                                            <div><strong>Draft vs. Published:</strong> Keep newly drafted modules in <em>Draft</em> mode while preparing content. Switch to <em>Published</em> to make them accessible to students.</div>
                                        </div>
                                        <div class="d-flex align-items-start gap-2">
                                            <i class="fas fa-arrows-alt-h text-primary mt-1"></i>
                                            <div><strong>Sequence Order:</strong> Use the left and right arrows on any module card to quickly change the sequential order students take.</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 3: Authoring Lessons & Rich Content -->
                                <div v-else-if="currentStep === 3">
                                    <div class="d-flex align-items-center gap-3 mb-3 p-3 rounded-3 bg-primary bg-opacity-10 border border-primary-subtle">
                                        <div class="tutorial-icon-box bg-primary text-white flex-shrink-0 shadow-sm">
                                            <i class="fas fa-edit fa-2x"></i>
                                        </div>
                                        <div>
                                            <h3 class="h6 fw-bold mb-1 text-dark">Editing Lessons & Learning Materials</h3>
                                            <p class="small text-muted mb-0">Rich-text authoring, images, checklists, and guidelines</p>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column gap-2 text-secondary small mb-2">
                                        <div class="d-flex align-items-start gap-2">
                                            <i class="fas fa-pen-fancy text-primary mt-1"></i>
                                            <div><strong>Rich Text Editor:</strong> Inside any module, open the editor to format text, format headings, add bullet points, and highlight key terms.</div>
                                        </div>
                                        <div class="d-flex align-items-start gap-2">
                                            <i class="fas fa-list-ol text-primary mt-1"></i>
                                            <div><strong>Structured Lessons:</strong> Break large modules down into bite-sized lessons so students can study topic-by-topic.</div>
                                        </div>
                                        <div class="d-flex align-items-start gap-2">
                                            <i class="fas fa-save text-success mt-1"></i>
                                            <div><strong>Instant Audit Trail:</strong> The system logs who last edited each module and timestamps changes for faculty collaboration.</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 4: Quiz Banks & Passing Thresholds -->
                                <div v-else-if="currentStep === 4">
                                    <div class="d-flex align-items-center gap-3 mb-3 p-3 rounded-3 bg-warning bg-opacity-10 border border-warning-subtle">
                                        <div class="tutorial-icon-box bg-warning text-dark flex-shrink-0 shadow-sm">
                                            <i class="fas fa-tasks fa-2x"></i>
                                        </div>
                                        <div>
                                            <h3 class="h6 fw-bold mb-1 text-dark">Managing Quiz Banks & Evaluations</h3>
                                            <p class="small text-muted mb-0">Create question items and configure passing rules</p>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column gap-2 text-secondary small mb-2">
                                        <div class="d-flex align-items-start gap-2">
                                            <i class="fas fa-clipboard-check text-warning mt-1"></i>
                                            <div><strong>Assessment Types:</strong> Create formative <em>Lesson Quick Checks</em> (short topic quizzes) and formal <em>End-of-Module Evaluations</em>.</div>
                                        </div>
                                        <div class="d-flex align-items-start gap-2">
                                            <i class="fas fa-key text-success mt-1"></i>
                                            <div><strong>Passing Threshold (80%):</strong> Students must achieve at least <strong>80%</strong> on module evaluations to unlock the next chapter and town destinations.</div>
                                        </div>
                                        <div class="d-flex align-items-start gap-2">
                                            <i class="fas fa-search text-info mt-1"></i>
                                            <div><strong>Question Bank Filter:</strong> Filter questions by module, lesson, or evaluation scope to easily audit and update answer keys.</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 5: Student Tracking & Certificate Settings -->
                                <div v-else-if="currentStep === 5">
                                    <div class="d-flex align-items-center gap-3 mb-3 p-3 rounded-3 bg-info bg-opacity-10 border border-info-subtle">
                                        <div class="tutorial-icon-box bg-info text-white flex-shrink-0 shadow-sm">
                                            <i class="fas fa-certificate fa-2x"></i>
                                        </div>
                                        <div>
                                            <h3 class="h6 fw-bold mb-1 text-dark">Tracking Students & Certificate Setup</h3>
                                            <p class="small text-muted mb-0">Real-time mastery analytics and certificate personalization</p>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column gap-2 text-secondary small mb-2">
                                        <div class="d-flex align-items-start gap-2">
                                            <i class="fas fa-chart-line text-info mt-1"></i>
                                            <div><strong>Class Performance:</strong> Track student rosters, quiz attempt histories, average scores, and completion timelines.</div>
                                        </div>
                                        <div class="d-flex align-items-start gap-2">
                                            <i class="fas fa-award text-success mt-1"></i>
                                            <div><strong>Automated Eligibility:</strong> Students who complete 100% of the 3-stage curriculum automatically become eligible for certification.</div>
                                        </div>
                                        <div class="d-flex align-items-start gap-2">
                                            <i class="fas fa-file-signature text-danger mt-1"></i>
                                            <div><strong>Certificate Editor:</strong> Upload official university/college logos, customize college names, and update signatory titles.</div>
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
                                style="background-color: #0d4b38 !important; border-color: #0d4b38 !important;"
                                @click="nextStep"
                            >
                                Next <i class="fas fa-chevron-right ms-1"></i>
                            </button>

                            <button 
                                v-else 
                                type="button" 
                                class="btn btn-sm btn-success px-4 rounded-pill fw-bold text-white shadow-sm animate-pulse"
                                style="background-color: #0d4b38 !important; border-color: #0d4b38 !important;"
                                @click="handleFinish"
                            >
                                Got It, Go to CMS! <i class="fas fa-check ms-1"></i>
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
        stepName: 'CMS Overview',
        tag: 'FACULTY CMS ORIENTATION',
        pillIcon: 'fas fa-chalkboard-teacher',
        title: 'Welcome to Faculty CMS',
        subtitle: 'Manage curriculum, question banks, student progress, and certificates.',
        icon: 'fas fa-desktop',
        headerClass: 'header-cms-overview'
    },
    {
        stepName: 'Manage Modules',
        tag: 'CURRICULUM ARCHITECTURE',
        pillIcon: 'fas fa-layer-group',
        title: 'How to Add & Organize Modules',
        subtitle: 'Create course chapters, toggle Draft/Published, and set sequence orders.',
        icon: 'fas fa-book-open',
        headerClass: 'header-cms-modules'
    },
    {
        stepName: 'Author Lessons',
        tag: 'LESSON CONTENT',
        pillIcon: 'fas fa-edit',
        title: 'Editing Lessons & Topics',
        subtitle: 'Rich text authoring, lesson structuring, and faculty audit trails.',
        icon: 'fas fa-pen-fancy',
        headerClass: 'header-cms-lessons'
    },
    {
        stepName: 'Quiz Banks',
        tag: 'ASSESSMENT ENGINE',
        pillIcon: 'fas fa-tasks',
        title: 'Quiz Banks & 80% Passing Rule',
        subtitle: 'Formative Quick Checks and formal Evaluations with threshold rules.',
        icon: 'fas fa-tasks',
        headerClass: 'header-cms-quizzes'
    },
    {
        stepName: 'Analytics & Certs',
        tag: 'TRACKING & CREDENTIALS',
        pillIcon: 'fas fa-certificate',
        title: 'Student Analytics & Certificates',
        subtitle: 'Monitor student completion and configure official MMSU certificates.',
        icon: 'fas fa-award',
        headerClass: 'header-cms-certificates'
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
.header-cms-overview {
    background: linear-gradient(135deg, #0a472e 0%, #1a5f7a 100%);
}
.header-cms-modules {
    background: linear-gradient(135deg, #0d4b38 0%, #1e6b52 100%);
}
.header-cms-lessons {
    background: linear-gradient(135deg, #155e75 0%, #0369a1 100%);
}
.header-cms-quizzes {
    background: linear-gradient(135deg, #854d0e 0%, #b45309 100%);
}
.header-cms-certificates {
    background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 100%);
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
    background-color: #0d4b38;
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
