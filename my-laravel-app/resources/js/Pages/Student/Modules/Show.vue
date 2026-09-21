<template>
    <StudentLayout>
        <div>
            <!-- Breadcrumb Navigation -->
            <nav class="mb-4" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 align-items-center">
                    <li class="breadcrumb-item">
                        <Link :href="route('go-beyond-books.index')" class="text-decoration-none text-dark fw-semibold hover-text-primary">
                            <i class="fas fa-book-open me-1"></i> Go Beyond Books
                        </Link>
                    </li>
                    <li class="breadcrumb-item" :class="{ 'active text-dark fw-bold': activeView === 'module' }">
                        <button 
                            v-if="activeView !== 'module'" 
                            @click="activeView = 'module'" 
                            class="btn btn-link p-0 text-decoration-none text-dark fw-semibold hover-text-primary border-0 bg-transparent align-baseline"
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
                            <p class="fs-5  mb-0">Complete all {{ lessons.length }} lessons to unlock the End-of-Module Evaluation</p>
                        </div>
                    </div>
                </div>

                <!-- Overview / Description -->
                <div class="card border-0 shadow-sm rounded-4 bg-white mb-4 position-relative overflow-hidden">
                    <!-- Green left border accent -->
                    <div class="position-absolute top-0 start-0 h-100 bg-success" style="width: 5px;"></div>
                    
                    <div class="card-body p-4 p-md-5 ms-2">
                        <div class="text-dark fs-6 lh-lg  html-content ql-editor" style="padding: 0;" v-html="module.description"></div>
                    </div>
                </div>

                <!-- Quick Facts and Videos (If Available) -->
                <div class="row g-4 mb-5" v-if="(module.quick_facts && module.quick_facts.length > 0) || (module.video_references && module.video_references.length > 0)">
                    <div class="col-md-6" v-if="module.quick_facts && module.quick_facts.length > 0">
                        <div class="card border-0 shadow-sm rounded-4 bg-white h-100">
                            <div class="card-header bg-white border-bottom py-3 px-4">
                                <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-lightbulb text-warning me-2"></i> Quick Facts</h5>
                            </div>
                            <div class="card-body p-4">
                                <ul class="list-unstyled mb-0">
                                    <li v-for="(fact, index) in module.quick_facts" :key="'fact-'+index" class="mb-3 d-flex align-items-start">
                                        <i class="fas fa-check-circle text-success mt-1 me-2"></i>
                                        <span class="text-dark" v-html="fact.includes(':') ? `<strong class='text-dark'>${fact.split(':')[0]}:</strong> ${fact.split(':').slice(1).join(':')}` : fact"></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6" v-if="module.video_references && module.video_references.length > 0">
                        <div class="card border-0 shadow-sm rounded-4 bg-white h-100">
                            <div class="card-header bg-white border-bottom py-3 px-4">
                                <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-play-circle text-primary me-2"></i> Video References</h5>
                            </div>
                            <div class="card-body p-4">
                                <div v-for="(video, index) in module.video_references" :key="'video-'+index" class="mb-3">
                                    <iframe v-if="video.includes('youtube.com/embed') || video.includes('youtu.be')"
                                        class="w-100 rounded-3"
                                        height="200"
                                        :src="video.replace('watch?v=', 'embed/').replace('youtu.be/', 'youtube.com/embed/')"
                                        frameborder="0"
                                        allowfullscreen
                                    ></iframe>
                                    <a v-else :href="video" target="_blank" class="btn btn-outline-primary rounded-pill w-100 shadow-sm">
                                        <i class="fas fa-external-link-alt me-1"></i> Watch Video {{ index + 1 }}
                                    </a>
                                </div>
                            </div>
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
                        :class="index + 1 <= unlockedLessonLevel ? 'cursor-pointer hover-lift' : ' bg-light-subtle'"
                        :style="index + 1 > unlockedLessonLevel ? 'cursor: not-allowed;' : ''"
                        @click="openLessonTopic(index)"
                    >
                        <div class="card-body p-4 d-flex align-items-center justify-content-between">
                            <div>
                                <h5 class="fw-bold text-dark mb-1">Lesson {{ index + 1 }}: {{ lesson.title }}</h5>
                                <p class="mb-0 text-dark small d-flex align-items-center gap-2 flex-wrap">
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
                            <div class="text-dark fs-5 d-flex align-items-center">
                                <i v-if="getAssessmentDetails(index)?.passed" class="fas fa-check-circle text-success fs-4"></i>
                                <i v-else-if="index + 1 <= unlockedLessonLevel" class="fas fa-chevron-right text-dark fs-5"></i>
                                <span v-else class="badge rounded-pill fw-bold" style="background: rgba(108, 117, 125, 0.85); color: white; padding: 6px 14px; font-size: 0.85rem;">
                                    Locked <i class="fas fa-lock ms-1"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- End-of-Module Evaluation -->
                    <div 
                        class="card border-0 shadow-sm rounded-4 transition-all mt-2"
                        :class="isEvaluationUnlocked ? 'cursor-pointer hover-lift' : ''"
                        :style="isEvaluationUnlocked ? 'background: linear-gradient(90deg, #8b93d6 0%, #9b88c4 100%); cursor: pointer;' : 'background-color: #8fa0aa; cursor: not-allowed;'"
                        @click="startFinalEvaluation"
                    >
                        <div class="card-body p-4 d-flex align-items-center justify-content-between text-white">
                            <div>
                                <h5 class="fw-bold mb-1 d-flex align-items-center gap-2">
                                    <i class="fas fa-trophy" :class="isEvaluationUnlocked ? 'text-warning' : 'text-white'"></i> 
                                    End-of-Module Evaluation
                                </h5>
                                <p class="mb-0  small">25 questions • 90% required to pass and unlock next module</p>
                            </div>
                            <div class="fs-4 d-flex align-items-center">
                                <i v-if="isEvaluationUnlocked" class="fas fa-play-circle"></i>
                                <span v-else class="badge rounded-pill fw-bold" style="background: rgba(108, 117, 125, 0.85); color: white; padding: 6px 14px; font-size: 0.85rem;">
                                    Locked <i class="fas fa-lock ms-1"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lesson Topic View -->
            <div v-else-if="activeView === 'lesson_info'">
                <!-- Top Hero Banner for Lesson matching system UI design -->
                <div class="welcome-banner text-white mb-4 shadow-sm position-relative overflow-hidden" 
                    :style="lessons[activeLessonIndex]?.cover_image ? `background: url('${lessons[activeLessonIndex].cover_image}') ${lessons[activeLessonIndex].cover_image_position || 'center'} / cover; border-radius: 30px; padding: 35px 40px;` : 'background: linear-gradient(135deg, #0a472e 0%, #1a5f7a 100%); border-radius: 30px; padding: 35px 40px;'">
                    <div v-if="lessons[activeLessonIndex]?.cover_image" class="position-absolute top-0 start-0 w-100 h-100" style="background: rgba(0,0,0,0.6);"></div>
                    <div class="row align-items-center position-relative z-1">
                        <div class="col-md-9 col-lg-10">
                            <h2 class="fw-bold mb-2 display-6 text-white">{{ currentLessonTopic.title }}</h2>
                            <p class="fs-5  mb-0">Module {{ module.id }}: {{ module.title }} • Lesson {{ activeLessonIndex + 1 }} of {{ lessons.length }}</p>
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
                                <span class="fs-8 text-uppercase tracking-wider  fw-bold mb-1 text-white">
                                    <i class="fas fa-clipboard-check text-warning me-1"></i> Status
                                </span>
                                <span class="fs-6 fw-bold text-white ">Not Taken Yet (90% required)</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lesson Content Card -->
                <div class="card border-0 shadow-sm rounded-4 bg-white p-4 p-md-5 mb-4 position-relative">
                    <!-- Intro description paragraph -->
                    <div class="text-dark fs-6 lh-base mb-4 html-content ql-editor" style="padding: 0;" v-html="currentLessonTopic.description"></div>



                    <!-- Footer Row with Quick Check Action -->
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div>
                            <h6 class="fw-bold text-dark mb-1">{{ currentLessonTopic.quiz_question_count > 0 ? 'Quick Check' : 'Lesson Completion' }}</h6>
                            <p v-if="currentLessonTopic.quiz_question_count > 0" class="text-dark small mb-0">{{ currentLessonTopic.quiz_question_count }} questions • 90% required to pass</p>
                            <p v-else class="text-dark small mb-0">No quiz for this lesson. Simply mark as complete to proceed.</p>
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
                            <p class="fs-5  mb-0 text-white">
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
                        <small class="text-dark fw-bold">
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
                                    :class="userAnswers[currentQuestionIndex] === idx ? 'bg-success text-white' : 'bg-light text-dark border'"
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
                        <p class="text-dark fs-6 mb-4">
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
                        <p class="text-dark small lh-base mb-4">
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
import '@vueup/vue-quill/dist/vue-quill.snow.css';

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
    
    router.post(route('go-beyond-books.modules.progress', props.module.id), payload, {
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
        questions: lesson.questions ? lesson.questions.length : 0,
        cover_image: lesson.cover_image,
        cover_image_position: lesson.cover_image_position
    }));
});

const isEvaluationUnlocked = computed(() => {
    return lessons.value.length > 0 && unlockedLessonLevel.value > lessons.value.length;
});

const currentLessonTopic = computed(() => {
    const lesson = lessons.value[activeLessonIndex.value];
    const lessonTitle = lesson ? lesson.title : `Lesson ${activeLessonIndex.value + 1}`;

    if (lesson) {
        let educatorPoints = [];
        if (Array.isArray(lesson.key_points)) {
            educatorPoints = lesson.key_points;
        } else if (typeof lesson.key_points === 'string') {
            try { educatorPoints = JSON.parse(lesson.key_points); } catch(e) {}
        }

        return {
            title: lesson.title,
            description: lesson.content || 'No content provided for this lesson yet.',
            quiz_question_count: lesson.questions || 0,
            points: educatorPoints.length > 0 ? educatorPoints : []
        };
    }

    return {
        title: lessonTitle,
        description: 'Lesson content not found.',
        quiz_question_count: 0,
        points: []
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
        currentQuestions.value = [];
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
        currentQuestions.value = [];
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
