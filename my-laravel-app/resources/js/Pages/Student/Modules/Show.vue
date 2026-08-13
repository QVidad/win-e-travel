<template>
    <StudentLayout>
        <div>
            <!-- Breadcrumb Navigation -->
            <nav class="mb-4" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 align-items-center">
                    <li class="breadcrumb-item">
                        <Link :href="route('foundation.index')" class="text-decoration-none text-secondary fw-semibold hover-text-primary">
                            <i class="fas fa-book-open me-1"></i> Go Beyond Books
                        </Link>
                    </li>
                    <li class="breadcrumb-item" :class="{ 'active text-dark fw-bold': activeView === 'module' }">
                        <button 
                            v-if="activeView !== 'module'" 
                            @click="activeView = 'module'" 
                            class="btn btn-link p-0 text-decoration-none text-secondary fw-semibold hover-text-primary border-0 bg-transparent align-baseline"
                        >
                            Module {{ module.id }}: {{ module.title }}
                        </button>
                        <span v-else>Module {{ module.id }}: {{ module.title }}</span>
                    </li>
                    <li v-if="activeView === 'lesson_info'" class="breadcrumb-item active text-dark fw-bold" aria-current="page">
                        Lesson {{ activeLessonIndex + 1 }}: {{ currentLessonTopic.title }}
                    </li>
                    <li v-else-if="activeView === 'quiz'" class="breadcrumb-item active text-dark fw-bold" aria-current="page">
                        {{ activeAssessmentType === 'lesson' ? `Lesson ${activeLessonIndex + 1} Quick Check` : 'End-of-Module Evaluation' }}
                    </li>
                </ol>
            </nav>

            <div v-if="activeView === 'module'">
                <!-- Hero Banner -->
                <div class="welcome-banner text-white mb-4 shadow-sm position-relative overflow-hidden" style="background: linear-gradient(135deg, #0a472e 0%, #1a5f7a 100%); border-radius: 30px; padding: 35px 40px;">
                    <div class="row align-items-center position-relative z-1">
                        <div class="col-md-10">
                            <h2 class="fw-bold mb-2 display-6">Module {{ module.id }}: {{ module.title }}</h2>
                            <p class="fs-5 opacity-75 mb-0">Complete all {{ lessons.length }} lessons to unlock the End-of-Module Evaluation</p>
                        </div>
                    </div>
                </div>

                <!-- Overview / Description -->
                <div class="card border-0 shadow-sm rounded-4 bg-white mb-5 position-relative overflow-hidden">
                    <!-- Green left border accent -->
                    <div class="position-absolute top-0 start-0 h-100 bg-success" style="width: 5px;"></div>
                    
                    <div class="card-body p-4 p-md-5 ms-2">
                        <div class="text-dark fs-6 lh-lg opacity-90" style="white-space: pre-line;">
                            {{ module.description }}
                            
                            <span v-if="module.key_spots"><br><br><strong>Key Areas:</strong><br>{{ module.key_spots }}</span>
                        </div>
                    </div>
                </div>

                <!-- Lessons List -->
                <h4 class="fw-bold text-dark mb-3">Lessons</h4>
                <div class="d-flex flex-column gap-3 mb-5">
                    <div 
                        v-for="(lesson, index) in lessons" 
                        :key="index"
                        class="card border-0 shadow-sm rounded-4 transition-all"
                        :class="index + 1 <= unlockedLessonLevel ? 'cursor-pointer hover-lift' : 'opacity-75 bg-light-subtle'"
                        :style="index + 1 > unlockedLessonLevel ? 'cursor: not-allowed;' : ''"
                        @click="openLessonTopic(index)"
                    >
                        <div class="card-body p-4 d-flex align-items-center justify-content-between">
                            <div>
                                <h5 class="fw-bold text-dark mb-1">Lesson {{ index + 1 }}: {{ lesson.title }}</h5>
                                <p class="mb-0 text-secondary small d-flex align-items-center gap-2 flex-wrap">
                                    <span>Quick Check • {{ lesson.questions || 5 }} questions • 90% to pass</span>
                                    <span 
                                        v-if="getAssessmentDetails(index)" 
                                        class="badge rounded-pill px-2.5 py-1 fs-8 fw-bold"
                                        :class="getAssessmentDetails(index).passed ? 'bg-success text-white' : 'bg-danger text-white'"
                                    >
                                        Score: {{ getAssessmentDetails(index).correctCount }}/{{ getAssessmentDetails(index).totalQuestions }} ({{ getAssessmentDetails(index).passed ? 'PASSED' : 'FAILED' }})
                                    </span>
                                </p>
                            </div>
                            <div class="text-secondary fs-5">
                                <i v-if="getAssessmentDetails(index)?.passed" class="fas fa-check-circle text-success fs-4"></i>
                                <i v-else-if="index + 1 <= unlockedLessonLevel" class="fas fa-chevron-right text-dark fs-5"></i>
                                <i v-else class="fas fa-lock fs-5"></i>
                            </div>
                        </div>
                    </div>

                    <!-- End-of-Module Evaluation -->
                    <div 
                        class="card border-0 shadow-sm rounded-4 transition-all mt-2"
                        :class="isEvaluationUnlocked ? 'cursor-pointer hover-lift' : 'opacity-75'"
                        :style="isEvaluationUnlocked ? 'background: linear-gradient(90deg, #8b93d6 0%, #9b88c4 100%); cursor: pointer;' : 'background-color: #8fa0aa; cursor: not-allowed;'"
                        @click="startFinalEvaluation"
                    >
                        <div class="card-body p-4 d-flex align-items-center justify-content-between text-white">
                            <div>
                                <h5 class="fw-bold mb-1 d-flex align-items-center gap-2">
                                    <i class="fas fa-trophy" :class="isEvaluationUnlocked ? 'text-warning' : 'text-white'"></i> 
                                    End-of-Module Evaluation
                                </h5>
                                <p class="mb-0 opacity-90 small">25 questions • 90% required to pass and unlock next module</p>
                            </div>
                            <div class="fs-4">
                                <i v-if="isEvaluationUnlocked" class="fas fa-play-circle"></i>
                                <i v-else class="fas fa-lock"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lesson Topic View -->
            <div v-else-if="activeView === 'lesson_info'">
                <!-- Top Hero Banner for Lesson matching system UI design -->
                <div class="welcome-banner text-white mb-4 shadow-sm position-relative overflow-hidden" style="background: linear-gradient(135deg, #0a472e 0%, #1a5f7a 100%); border-radius: 30px; padding: 35px 40px;">
                    <div class="row align-items-center position-relative z-1">
                        <div class="col-md-9 col-lg-10">
                            <h2 class="fw-bold mb-2 display-6 text-white">{{ currentLessonTopic.title }}</h2>
                            <p class="fs-5 opacity-75 mb-0">Module {{ module.id }}: {{ module.title }} • Lesson {{ activeLessonIndex + 1 }} of {{ lessons.length }}</p>
                        </div>
                        <div class="col-md-3 col-lg-2 text-md-end mt-3 mt-md-0">
                            <!-- Bigger Circular Score Donut Meter without outer box or text label -->
                            <div v-if="getAssessmentDetails(activeLessonIndex)" class="d-inline-flex flex-column align-items-center">
                                <!-- Circular Donut Progress Ring -->
                                <div 
                                    class="position-relative d-flex align-items-center justify-content-center rounded-circle shadow"
                                    :style="{
                                        width: '96px',
                                        height: '96px',
                                        background: `conic-gradient(${getAssessmentDetails(activeLessonIndex).passed ? '#10b981' : '#ef4444'} 0% ${getAssessmentDetails(activeLessonIndex).score}%, rgba(255, 255, 255, 0.25) ${getAssessmentDetails(activeLessonIndex).score}% 100%)`
                                    }"
                                >
                                    <!-- Inner White Circle -->
                                    <div 
                                        class="rounded-circle bg-white d-flex align-items-center justify-content-center shadow-sm"
                                        style="width: 72px; height: 72px;"
                                    >
                                        <span class="fw-bold fs-5 text-dark">
                                            {{ getAssessmentDetails(activeLessonIndex).correctCount }}/{{ getAssessmentDetails(activeLessonIndex).totalQuestions }}
                                        </span>
                                    </div>
                                </div>
                                <!-- Badge below it -->
                                <span 
                                    class="badge rounded-pill px-3 py-1.5 fw-bold fs-7 mt-2 shadow-sm"
                                    :class="getAssessmentDetails(activeLessonIndex).passed ? 'bg-success text-white' : 'bg-danger text-white'"
                                >
                                    {{ getAssessmentDetails(activeLessonIndex).passed ? 'PASSED' : 'FAILED' }}
                                </span>
                            </div>
                            <!-- Status when not taken yet -->
                            <div v-else class="d-inline-flex flex-column align-items-md-end bg-white bg-opacity-10 rounded-4 p-3 px-4 border border-white border-opacity-20 text-white">
                                <span class="fs-8 text-uppercase tracking-wider opacity-75 fw-bold mb-1 text-white">
                                    <i class="fas fa-clipboard-check text-warning me-1"></i> Status
                                </span>
                                <span class="fs-6 fw-bold text-white opacity-90">Not Taken Yet (90% required)</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lesson Content Card -->
                <div class="card border-0 shadow-sm rounded-4 bg-white p-4 p-md-5 mb-4 position-relative">
                    <!-- Intro description paragraph -->
                    <p class="text-secondary fs-6 lh-base mb-4" style="white-space: pre-line;">
                        {{ currentLessonTopic.description }}
                    </p>

                    <!-- Key Topics / Guidelines with Icons -->
                    <div class="d-flex flex-column gap-4 my-4">
                        <div 
                            v-for="(point, pIdx) in currentLessonTopic.points" 
                            :key="pIdx"
                            class="d-flex align-items-start gap-3"
                        >
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center flex-shrink-0 mt-1" style="width: 38px; height: 38px;">
                                <i :class="point.icon || 'fas fa-check'" class="text-success fs-6"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-1">{{ point.title }}</h6>
                                <p class="text-secondary small mb-0 lh-base">{{ point.description }}</p>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4 opacity-10">

                    <!-- Footer Row with Quick Check Action -->
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div>
                            <h6 class="fw-bold text-dark mb-1">{{ currentLessonTopic.quiz_question_count > 0 ? 'Quick Check' : 'Lesson Completion' }}</h6>
                            <p v-if="currentLessonTopic.quiz_question_count > 0" class="text-secondary small mb-0">{{ currentLessonTopic.quiz_question_count }} questions • 90% required to pass</p>
                            <p v-else class="text-secondary small mb-0">No quiz for this lesson. Simply mark as complete to proceed.</p>
                        </div>
                        <button 
                            v-if="currentLessonTopic.quiz_question_count > 0"
                            @click="startLessonQuizFromTopic" 
                            class="btn text-white rounded-pill px-4 py-2-5 fw-bold shadow-sm d-flex align-items-center gap-2 transition-all hover-lift"
                            :style="getAssessmentDetails(activeLessonIndex)?.passed ? 'background-color: #10b981; opacity: 0.9; cursor: not-allowed;' : 'background-color: #0a472e;'"
                            :disabled="getAssessmentDetails(activeLessonIndex)?.passed"
                        >
                            <i v-if="getAssessmentDetails(activeLessonIndex)?.passed" class="fas fa-check-circle"></i>
                            <i v-else class="fas fa-play"></i>
                            <span>
                                {{ getAssessmentDetails(activeLessonIndex)?.passed ? 'Quick Check Passed' : (getAssessmentDetails(activeLessonIndex) ? 'Retake Quick Check' : 'Take Quick Check') }}
                            </span>
                        </button>
                        <button 
                            v-else
                            @click="markLessonCompleteWithoutQuiz" 
                            class="btn text-white rounded-pill px-4 py-2-5 fw-bold shadow-sm d-flex align-items-center gap-2 transition-all hover-lift"
                            :style="getAssessmentDetails(activeLessonIndex)?.passed ? 'background-color: #10b981; opacity: 0.9; cursor: not-allowed;' : 'background-color: #0a472e;'"
                            :disabled="getAssessmentDetails(activeLessonIndex)?.passed"
                        >
                            <i v-if="getAssessmentDetails(activeLessonIndex)?.passed" class="fas fa-check-circle"></i>
                            <i v-else class="fas fa-check"></i>
                            <span>
                                {{ getAssessmentDetails(activeLessonIndex)?.passed ? 'Completed' : 'Mark as Complete' }}
                            </span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Quiz / Assessment View -->
            <div v-else>
                <!-- Assessment Top Banner (Matching System Hero Banner Design) -->
                <div class="welcome-banner text-white mb-4 shadow-sm position-relative overflow-hidden" style="background: linear-gradient(135deg, #0a472e 0%, #1a5f7a 100%); border-radius: 30px; padding: 35px 40px;">
                    <div class="row align-items-center position-relative z-1">
                        <div class="col-md-8">
                            <h2 class="fw-bold mb-2 display-6 text-white">
                                {{ activeAssessmentType === 'lesson' ? currentLessonTopic.title : `Module ${module.id} Evaluation` }}
                            </h2>
                            <p class="fs-5 opacity-75 mb-0 text-white">
                                {{ activeAssessmentType === 'lesson' ? `Lesson ${activeLessonIndex + 1} Quick Check` : 'End-of-Module Evaluation' }} • 90% Passing Score Required
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <button 
                                v-if="!quizFinished" 
                                @click="attemptExitQuiz" 
                                class="btn btn-outline-light rounded-pill px-4 py-2-5 fw-bold shadow-sm d-inline-flex align-items-center gap-2"
                            >
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Exit Assessment</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">

                <!-- Active Question Screen -->
                <div v-if="!quizFinished" class="card-body p-4 p-md-5">
                    <!-- Question Progress Bar -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle px-3 py-2 rounded-pill fw-bold fs-8">
                            Question {{ currentQuestionIndex + 1 }} of {{ currentQuestions.length }}
                        </span>
                        <small class="text-muted fw-bold">
                            Progress: {{ Math.round(((currentQuestionIndex + 1) / currentQuestions.length) * 100) }}%
                        </small>
                    </div>
                    <div class="progress mb-4 rounded-pill" style="height: 8px; background-color: #e2e8f0;">
                        <div 
                            class="progress-bar rounded-pill transition-all" 
                            role="progressbar" 
                            :style="{ width: ((currentQuestionIndex + 1) / currentQuestions.length * 100) + '%', backgroundColor: '#0a472e' }"
                        ></div>
                    </div>

                    <!-- Question Text -->
                    <h4 class="fw-bold text-dark mb-4 lh-base">
                        {{ currentQuestions[currentQuestionIndex]?.text }}
                    </h4>

                    <!-- Options List -->
                    <div class="d-flex flex-column gap-3 mb-5">
                        <div
                            v-for="(opt, idx) in currentQuestions[currentQuestionIndex]?.options"
                            :key="idx"
                            class="p-3.5 p-md-4 border rounded-4 cursor-pointer transition-all option-card d-flex align-items-center justify-content-between"
                            :class="userAnswers[currentQuestionIndex] === idx ? 'border-success bg-success bg-opacity-10 shadow-sm' : 'bg-white'"
                            @click="selectQuizOption(idx)"
                        >
                            <div class="d-flex align-items-center gap-3">
                                <div 
                                    class="rounded-circle d-flex align-items-center justify-content-center fw-bold fs-7" 
                                    style="width: 34px; height: 34px;"
                                    :class="userAnswers[currentQuestionIndex] === idx ? 'bg-success text-white' : 'bg-light text-secondary border'"
                                >
                                    {{ String.fromCharCode(65 + idx) }}
                                </div>
                                <span class="fs-6" :class="userAnswers[currentQuestionIndex] === idx ? 'text-dark fw-bold' : 'text-dark'">
                                    {{ opt }}
                                </span>
                            </div>
                            <i v-if="userAnswers[currentQuestionIndex] === idx" class="fas fa-check-circle text-success fs-5"></i>
                        </div>
                    </div>

                    <!-- Navigation Footer -->
                    <div class="d-flex justify-content-between align-items-center border-top pt-4">
                        <button 
                            @click="goToPrevQuestion" 
                            class="btn btn-outline-secondary rounded-pill px-4 fw-bold"
                            :disabled="currentQuestionIndex === 0"
                        >
                            <i class="fas fa-arrow-left me-2"></i> Previous
                        </button>

                        <button 
                            v-if="currentQuestionIndex < currentQuestions.length - 1" 
                            @click="goToNextQuestion" 
                            class="btn text-white rounded-pill px-4 fw-bold shadow-sm"
                            style="background-color: #0a472e;"
                            :disabled="userAnswers[currentQuestionIndex] === undefined"
                        >
                            Next Question <i class="fas fa-arrow-right ms-2"></i>
                        </button>

                        <button 
                            v-else 
                            @click="calculateAndSubmitScore" 
                            class="btn btn-success rounded-pill px-5 py-2-5 fw-bold shadow-sm"
                            :disabled="userAnswers[currentQuestionIndex] === undefined"
                        >
                            <i class="fas fa-paper-plane me-2"></i> Submit Assessment
                        </button>
                    </div>
                </div>

                <!-- Finished Quiz Result Screen with Circular Score Donut Meter -->
                <div v-else class="card-body p-5 text-center">
                    <div v-if="latestAttemptResult" class="d-flex flex-column align-items-center my-3">
                        <!-- Circular Donut Progress Ring -->
                        <div 
                            class="position-relative d-flex align-items-center justify-content-center rounded-circle shadow-sm mb-2"
                            :style="{
                                width: '100px',
                                height: '100px',
                                background: `conic-gradient(${latestAttemptResult.passed ? '#10b981' : '#ef4444'} 0% ${latestAttemptResult.score}%, #e2e8f0 ${latestAttemptResult.score}% 100%)`
                            }"
                        >
                            <div 
                                class="rounded-circle bg-white d-flex align-items-center justify-content-center"
                                style="width: 76px; height: 76px;"
                            >
                                <span class="fs-4 fw-bold text-dark">
                                    {{ latestAttemptResult.correctCount }}/{{ latestAttemptResult.totalQuestions }}
                                </span>
                            </div>
                        </div>

                        <!-- Badge below it -->
                        <span 
                            class="badge rounded-pill px-4 py-2 fw-bold fs-7 shadow-sm mb-3"
                            :class="latestAttemptResult.passed ? 'bg-success text-white' : 'bg-danger text-white'"
                        >
                            {{ latestAttemptResult.passed ? 'PASSED' : 'FAILED' }}
                        </span>

                        <h3 class="fw-bold text-dark mb-1">
                            {{ latestAttemptResult.passed ? 'Assessment Passed!' : 'Assessment Retake Needed' }}
                        </h3>
                        <p class="text-secondary fs-6 mb-4">
                            You scored <strong>{{ latestAttemptResult.correctCount }} out of {{ latestAttemptResult.totalQuestions }}</strong> ({{ latestAttemptResult.score }}%).
                            <span v-if="!latestAttemptResult.passed" class="d-block mt-1 text-danger small">
                                A 90% passing score is required to unlock the next level. You can retake the quiz as many times as needed to pass.
                            </span>
                        </p>

                        <!-- Action Buttons -->
                        <div class="d-flex align-items-center justify-content-center gap-3 flex-wrap pt-2">
                            <button 
                                v-if="!latestAttemptResult.passed"
                                @click="startLessonQuizFromTopic" 
                                class="btn btn-warning text-dark rounded-pill px-5 py-3 fw-bold shadow-sm fs-5 hover-lift"
                            >
                                <i class="fas fa-redo me-2"></i> Retake Assessment
                            </button>
                            <button 
                                @click="returnToModule" 
                                class="btn text-white rounded-pill px-5 py-3 fw-bold shadow-sm fs-5 hover-lift" 
                                style="background-color: #0a472e;"
                            >
                                Return to Module <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <!-- Exit Confirmation Modal (System UI Aligned) -->
        <div v-if="showExitConfirmModal" class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.6); z-index: 1065;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 border-0 shadow-lg p-3">
                    <div class="modal-body text-center p-4">
                        <div class="rounded-circle bg-warning bg-opacity-10 text-warning d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                            <i class="fas fa-exclamation-triangle display-5"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-3">Exit Assessment?</h4>
                        <p class="text-secondary small lh-base mb-4">
                            Are you sure you want to exit? Exiting now will submit your current answers as your final attempt for this round and your score will be calculated. You can retake the assessment anytime to reach the 90% requirement.
                        </p>
                        <div class="d-flex flex-column gap-2">
                            <button 
                                @click="showExitConfirmModal = false" 
                                class="btn text-white rounded-pill py-2-5 fw-bold shadow-sm w-100"
                                style="background-color: #0a472e;"
                            >
                                Resume Assessment
                            </button>
                            <button 
                                @click="confirmExitQuiz" 
                                class="btn btn-outline-danger rounded-pill py-2-5 fw-bold w-100"
                            >
                                Submit & Exit Assessment
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </StudentLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import StudentLayout from '@/Layouts/StudentLayout.vue';

const props = defineProps({
    module: {
        type: Object,
        required: true,
    },
    userProgress: {
        type: Object,
        default: null,
    },
});

const activeView = ref('module'); // 'module' | 'lesson_info' | 'quiz'
const activeAssessmentType = ref('lesson'); // 'lesson' | 'final'
const activeLessonIndex = ref(0);
const unlockedLessonLevel = ref(1);
const selectedOption = ref(null);
const quizFinished = ref(false);

// Quiz Engine States
const currentQuestionIndex = ref(0);
const userAnswers = ref({});
const showExitConfirmModal = ref(false);
const completedAttempts = ref({}); // { 'lesson_0': { score: 100, passed: true }, 'final': { score: 90, passed: true } }
const latestAttemptResult = ref(null);
const currentQuestions = ref([]);

onMounted(() => {
    if (props.userProgress && props.userProgress.lesson_data) {
        const data = props.userProgress.lesson_data;
        if (data.completedAttempts) completedAttempts.value = data.completedAttempts;
        if (data.lessonScores) lessonScores.value = data.lessonScores;
        if (data.unlockedLessonLevel) unlockedLessonLevel.value = data.unlockedLessonLevel;
    }
});

const saveProgressToServer = () => {
    const payload = {
        lesson_data: {
            completedAttempts: completedAttempts.value,
            lessonScores: lessonScores.value,
            unlockedLessonLevel: unlockedLessonLevel.value
        },
        score_percentage: completedAttempts.value['final'] ? completedAttempts.value['final'].score : 0,
        passed: completedAttempts.value['final'] ? completedAttempts.value['final'].passed : false
    };
    
    router.post(route('student.modules.progress', props.module.id), payload, {
        preserveScroll: true,
        preserveState: true,
    });
};

// Dynamic lessons from database
const lessons = computed(() => {
    if (!props.module.lessons) return [];
    return props.module.lessons.map(lesson => ({
        id: lesson.id,
        title: lesson.title,
        content: lesson.content,
        key_points: lesson.key_points,
        questions: lesson.questions ? lesson.questions.length : 0
    }));
});

const isEvaluationUnlocked = computed(() => {
    return lessons.value.length > 0 && unlockedLessonLevel.value > lessons.value.length;
});

const currentLessonTopic = computed(() => {
    const lesson = lessons.value[activeLessonIndex.value];
    const lessonTitle = lesson ? lesson.title : `Lesson ${activeLessonIndex.value + 1}`;

    // 1. Check if educator has created content or key points for this lesson
    if (lesson) {
        let educatorPoints = [];
        if (Array.isArray(lesson.key_points)) {
            educatorPoints = lesson.key_points;
        } else if (typeof lesson.key_points === 'string') {
            try { educatorPoints = JSON.parse(lesson.key_points); } catch(e) {}
        }

        if (lesson.content || educatorPoints.length > 0) {
            return {
                title: lesson.title,
                description: lesson.content || `Master the essential concepts for ${lesson.title}.`,
                quiz_question_count: lesson.quiz_question_count ?? 5,
                points: educatorPoints.length > 0 ? educatorPoints : [
                    {
                        icon: 'fas fa-compass',
                        title: 'Know the Core Principles',
                        description: 'Understand the fundamental guidelines, safety standards, and historical background.'
                    }
                ]
            };
        }
    }

    const topicsMap = {
        'General Preparation Before the Tour': {
            title: 'General Preparation Before the Tour',
            description: "Even if you've guided the same tour many times, no two are ever the same. Weather, traffic, guest expectations, and site conditions can change overnight. Being prepared means you're ready for anything — from sudden route changes to unexpected guest needs.",
            points: [
                {
                    icon: 'fas fa-walking',
                    title: 'Know the tour style',
                    description: 'Walking tours require stamina and weather planning. Coach tours need precise timing to avoid delays. Museum or cultural tours demand accurate knowledge and respectful conduct.'
                },
                {
                    icon: 'fas fa-map-marked-alt',
                    title: 'Review the itinerary',
                    description: 'Things change. Check each stop, travel time, and sequence. Identify areas that may require flexibility.'
                },
                {
                    icon: 'fas fa-briefcase',
                    title: "Understand employer's standards",
                    description: 'Dress codes, documentation, and specific service expectations vary by operator. Always represent your company professionally.'
                }
            ]
        },
        'Researching Your Incoming Group': {
            title: 'Researching Your Incoming Group',
            description: 'Understanding your guests before they arrive allows you to tailor your commentary, pacing, and interaction style to their cultural background, interests, and age demographics.',
            points: [
                {
                    icon: 'fas fa-users',
                    title: 'Demographics & Interest Mapping',
                    description: 'Identify whether your group consists of eco-tourists, history enthusiasts, families, or corporate delegates.'
                },
                {
                    icon: 'fas fa-globe',
                    title: 'Cultural Norms & Dietary Restrictions',
                    description: 'Respect dietary needs, religious customs, and language preferences.'
                },
                {
                    icon: 'fas fa-wheelchair',
                    title: 'Special Assistance Needs',
                    description: 'Plan ahead for accessibility requirements and senior mobility support.'
                }
            ]
        },
        'Coordinating with Suppliers': {
            title: 'Coordinating with Suppliers',
            description: 'Seamless tours rely on strong communication with drivers, restaurant owners, local heritage caretakers, and municipal tourism officers.',
            points: [
                {
                    icon: 'fas fa-bus',
                    title: 'Driver & Transport Alignment',
                    description: 'Confirm pickup points, route options, parking zones, and emergency contact channels.'
                },
                {
                    icon: 'fas fa-utensils',
                    title: 'Meal & Attraction Vouchers',
                    description: 'Verify group headcounts and meal reservations prior to arrival at local dining spots.'
                },
                {
                    icon: 'fas fa-phone-alt',
                    title: 'Site Capacity & Operating Hours',
                    description: 'Call ahead to confirm opening hours, weather alerts, and site capacity limits.'
                }
            ]
        },
        'Site Familiarization & Safety Check': {
            title: 'Site Familiarization & Safety Check',
            description: 'Conducting site walk-throughs ensures you are aware of emergency exits, rest facilities, first-aid locations, and potential hazards.',
            points: [
                {
                    icon: 'fas fa-exclamation-triangle',
                    title: 'Hazard Identification',
                    description: 'Note slippery pathways, steep steps, or high-density crowd points.'
                },
                {
                    icon: 'fas fa-first-aid',
                    title: 'Emergency Exit Points',
                    description: 'Locate nearest medical stations, assembly points, and emergency contacts.'
                },
                {
                    icon: 'fas fa-restroom',
                    title: 'Facility Spotting',
                    description: 'Identify clean restrooms, water refill stations, and shaded rest spots for guests.'
                }
            ]
        }
    };

    if (topicsMap[lessonTitle]) {
        return topicsMap[lessonTitle];
    }

    return {
        title: lessonTitle,
        description: `Master the essential competencies for ${lessonTitle}. Being prepared means you are ready to deliver authentic, informative, and engaging experiences for your tour group.`,
        points: [
            {
                icon: 'fas fa-compass',
                title: 'Know the Core Principles',
                description: 'Understand the fundamental guidelines, safety standards, and historical background.'
            },
            {
                icon: 'fas fa-list-check',
                title: 'Review Key Operational Steps',
                description: 'Verify timing, group coordination, and site contingencies before starting.'
            },
            {
                icon: 'fas fa-user-shield',
                title: 'Maintain Professional Standards',
                description: 'Represent local tourism values with accuracy, hospitality, and respect.'
            }
        ]
    };
});

const lessonScores = ref({});

const getAssessmentDetails = (index) => {
    const key = `lesson_${index}`;
    if (completedAttempts.value[key]) {
        return completedAttempts.value[key];
    }
    if (lessonScores.value[index] !== undefined) {
        const score = lessonScores.value[index];
        return {
            correctCount: Math.round((score / 100) * 5),
            totalQuestions: 5,
            score: score,
            passed: score >= 90
        };
    }
    if (index + 1 < unlockedLessonLevel.value) {
        return {
            correctCount: 5,
            totalQuestions: 5,
            score: 100,
            passed: true
        };
    }
    return null;
};

const getLessonScore = (index) => {
    const details = getAssessmentDetails(index);
    return details ? details.score : null;
};

const isAssessmentCompleted = (type, index = null) => {
    const key = type === 'lesson' ? `lesson_${index}` : 'final';
    return completedAttempts.value[key] !== undefined;
};

const loadQuestionsForQuiz = (questionsArray, limit = null) => {
    if (!questionsArray || questionsArray.length === 0) return [];
    
    let pool = questionsArray.map((q, idx) => {
        const rawOptions = (Array.isArray(q.options) && q.options.length > 0)
            ? q.options
            : [q.option_a, q.option_b, q.option_c, q.option_d].filter(Boolean);
        
        let correctIdx = 0;
        if (q.correct_answer_index !== undefined && q.correct_answer_index !== null) {
            correctIdx = Number(q.correct_answer_index);
        } else if (q.correct_option) {
            const char = String(q.correct_option).toLowerCase();
            correctIdx = char === 'b' ? 1 : char === 'c' ? 2 : char === 'd' ? 3 : 0;
        }

        return {
            id: q.id,
            text: q.question_text || q.question || `Question ${idx + 1}`,
            options: rawOptions.length > 0 ? rawOptions : ['Option A', 'Option B', 'Option C', 'Option D'],
            correctIndex: correctIdx
        };
    });

    for (let i = pool.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [pool[i], pool[j]] = [pool[j], pool[i]];
    }

    if (limit && limit > 0) {
        return pool.slice(0, limit);
    }
    return pool;
};

const openLessonTopic = (index) => {
    if (index + 1 <= unlockedLessonLevel.value) {
        activeLessonIndex.value = index;
        activeView.value = 'lesson_info';
    } else {
        alert("This lesson is locked. Complete the previous lesson first.");
    }
};

const markLessonCompleteWithoutQuiz = () => {
    const details = getAssessmentDetails(activeLessonIndex.value);
    if (details && details.passed) return;

    const lessonKey = 'lesson_' + activeLessonIndex.value;
    completedAttempts.value[lessonKey] = {
        score: 100, // Conceptually passed
        passed: true,
        correctCount: 0,
        totalQuestions: 0
    };
    
    // Auto unlock next lesson if applicable
    if (activeLessonIndex.value + 1 === unlockedLessonLevel.value) {
        unlockedLessonLevel.value += 1;
    }
    
    saveProgressToServer();
    
    activeView.value = 'module';
};

const startLessonQuizFromTopic = () => {
    const details = getAssessmentDetails(activeLessonIndex.value);
    if (details && details.passed) {
        alert(`You have already passed this Quick Check with a score of ${details.correctCount}/${details.totalQuestions}. Passed exams cannot be retaken.`);
        return;
    }
    
    activeAssessmentType.value = 'lesson';
    const lesson = props.module.lessons[activeLessonIndex.value];
    const limit = lesson.quiz_question_count || 5;
    
    if (lesson.questions && lesson.questions.length > 0) {
        currentQuestions.value = loadQuestionsForQuiz(lesson.questions, limit);
    } else {
        const fallback = [
            { text: 'What is the primary responsibility of a tour guide before starting a tour?', options: ['Ensuring tourist safety and reviewing the itinerary', 'Buying souvenirs for guests', 'Ignoring weather forecasts', 'Changing ticket prices'], correct_answer_index: 0 },
            { text: 'Why is it important to research your incoming guest group in advance?', options: ['To tailor commentary and accommodate dietary/accessibility needs', 'To cancel the tour early', 'To skip historical landmarks', 'To force everyone to buy extra meals'], correct_answer_index: 0 },
            { text: 'How should a tour guide coordinate with transport drivers and local suppliers?', options: ['Confirm pickup times, route options, and contact channels in advance', 'Arrive unannounced without reservation', 'Let guests drive the bus', 'Ignore supplier schedules'], correct_answer_index: 0 },
            { text: 'What is the first step during site familiarization before guests arrive?', options: ['Identify emergency exit paths, rest facilities, and potential hazards', 'Leave the site immediately', 'Start selling merchandise', 'Take a nap'], correct_answer_index: 0 },
            { text: 'How should unexpected delays or route changes be handled during a live tour?', options: ['Execute contingency plans and communicate clearly with guests', 'Panic and abandon the group', 'Blame guests for the delay', 'Refuse to answer questions'], correct_answer_index: 0 }
        ];
        currentQuestions.value = loadQuestionsForQuiz(fallback, limit);
    }

    currentQuestionIndex.value = 0;
    userAnswers.value = {};
    quizFinished.value = false;
    showExitConfirmModal.value = false;
    activeView.value = 'quiz';
};

const startFinalEvaluation = () => {
    if (!isEvaluationUnlocked.value) {
        alert("The End-of-Module Evaluation is locked. You must complete all lessons first before taking this evaluation.");
        return;
    }
    const key = 'final';
    if (completedAttempts.value[key] && completedAttempts.value[key].passed) {
        alert(`You have already passed the End-of-Module Evaluation with a score of ${completedAttempts.value[key].correctCount}/${completedAttempts.value[key].totalQuestions}. Passed exams cannot be retaken.`);
        return;
    }
    
    activeAssessmentType.value = 'final';
    if (props.module.questions && props.module.questions.length > 0) {
        currentQuestions.value = loadQuestionsForQuiz(props.module.questions, 10); // Or module.quiz_question_count if it exists
    } else {
        const fallback = [
            { text: 'What is the cornerstone duty of care for a DOT-accredited tour guide?', options: ['Prioritizing tourist health, emergency readiness, and safety protocols', 'Maximizing personal tips', 'Rushing through site commentary', 'Ignoring guest feedback'], correct_answer_index: 0 },
            { text: 'In crisis management, what is the best initial action during a minor medical issue?', options: ['Contact local first-aid responders and follow established emergency protocols', 'Ignore the guest and keep walking', 'Tell the group the tour is over', 'Offer unapproved medication'], correct_answer_index: 0 },
            { text: 'How should historical facts and cultural narratives be presented to visitors?', options: ['Accurately, respectfully, and engagingly without fabrication', 'By inventing fictional stories as real history', 'By skipping all historical details', 'By arguing with guests about beliefs'], correct_answer_index: 0 },
            { text: 'What is essential when concluding a tour at the final destination?', options: ['Conducting debriefing, collecting feedback, and ensuring all guests depart safely', 'Leaving guests at the site without assistance', 'Refusing to answer final questions', 'Demanding extra cash tips'], correct_answer_index: 0 },
            { text: 'Why is group dynamics management vital during multi-stop municipality tours?', options: ['It maintains tour pacing, inclusivity, and guest satisfaction', 'It allows aggressive guests to dominate the tour', 'It eliminates rest stops', 'It ensures no guest enjoys the experience'], correct_answer_index: 0 }
        ];
        currentQuestions.value = loadQuestionsForQuiz(fallback, 10);
    }

    currentQuestionIndex.value = 0;
    userAnswers.value = {};
    quizFinished.value = false;
    showExitConfirmModal.value = false;
    activeView.value = 'quiz';
};

const selectQuizOption = (optIndex) => {
    userAnswers.value[currentQuestionIndex.value] = optIndex;
};

const goToNextQuestion = () => {
    if (currentQuestionIndex.value < currentQuestions.value.length - 1) {
        currentQuestionIndex.value++;
    }
};

const goToPrevQuestion = () => {
    if (currentQuestionIndex.value > 0) {
        currentQuestionIndex.value--;
    }
};

const attemptExitQuiz = () => {
    showExitConfirmModal.value = true;
};

const confirmExitQuiz = () => {
    showExitConfirmModal.value = false;
    calculateAndSubmitScore();
};

const calculateAndSubmitScore = () => {
    let correctCount = 0;
    const total = currentQuestions.value.length;
    currentQuestions.value.forEach((q, idx) => {
        if (userAnswers.value[idx] === q.correctIndex) {
            correctCount++;
        }
    });

    const scorePct = Math.round((correctCount / total) * 100);
    const passed = scorePct >= 90;

    const resultObj = {
        score: scorePct,
        passed: passed,
        correctCount: correctCount,
        totalQuestions: total
    };

    latestAttemptResult.value = resultObj;

    const key = activeAssessmentType.value === 'lesson' ? `lesson_${activeLessonIndex.value}` : 'final';
    completedAttempts.value[key] = resultObj;

    if (activeAssessmentType.value === 'lesson') {
        lessonScores.value[activeLessonIndex.value] = scorePct;
    }

    quizFinished.value = true;
    saveProgressToServer();
};

const returnToModule = () => {
    let shouldSave = false;
    if (latestAttemptResult.value?.passed) {
        if (activeAssessmentType.value === 'lesson' && activeLessonIndex.value + 1 === unlockedLessonLevel.value) {
            unlockedLessonLevel.value++;
            shouldSave = true;
        } else if (activeAssessmentType.value === 'final') {
            alert("Module fully completed! You can now proceed to the next module.");
            shouldSave = true;
        }
    }
    activeView.value = 'module';
    if (shouldSave) {
        saveProgressToServer();
    }
};
</script>

<style scoped>
.max-w-4xl {
    max-width: 56rem;
}

.hover-lift {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.hover-lift:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important;
}

.hover-text-primary:hover {
    color: var(--mmsu-green) !important;
}

.option-card {
    border-color: #cbd5e1 !important;
}

.option-card:hover {
    border-color: #0a472e !important;
    background-color: #f8fafc;
}
</style>
