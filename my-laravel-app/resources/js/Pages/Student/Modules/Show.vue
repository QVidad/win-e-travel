<template>
    <StudentLayout>
        <div>
            <!-- Back Navigation -->
            <div class="mb-4">
                <Link :href="route('foundation.index')" class="text-decoration-none text-secondary fw-bold hover-text-primary transition-all">
                    <i class="fas fa-arrow-left me-2"></i> Back to Go Beyond Books
                </Link>
            </div>

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
                        @click="startLessonQuiz(index)"
                    >
                        <div class="card-body p-4 d-flex align-items-center justify-content-between">
                            <div>
                                <h5 class="fw-bold text-dark mb-1">Lesson {{ index + 1 }}: {{ lesson.title }}</h5>
                                <p class="mb-0 text-secondary small">Quick Check • {{ lesson.questions }} questions • 90% to pass</p>
                            </div>
                            <div class="text-secondary fs-5">
                                <i v-if="index + 1 < unlockedLessonLevel" class="fas fa-check-circle text-success"></i>
                                <i v-else-if="index + 1 === unlockedLessonLevel" class="fas fa-chevron-right text-dark"></i>
                                <i v-else class="fas fa-lock"></i>
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

            <!-- Mock Quiz View for Lessons & Final Eval -->
            <div v-else class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-bottom p-4 d-flex justify-content-between align-items-center">
                    <button @click="activeView = 'module'" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-bold">
                        <i class="fas fa-times me-1"></i> Exit Assessment
                    </button>
                    <span class="badge bg-primary rounded-pill px-3 py-2 fw-bold">
                        {{ activeAssessmentType === 'lesson' ? `Lesson ${activeLessonIndex + 1} Quick Check` : 'End-of-Module Evaluation' }}
                    </span>
                </div>
                
                <div v-if="!quizFinished" class="card-body p-4 p-md-5">
                    <span class="badge bg-light text-primary border border-primary-subtle mb-3 px-3 py-1 rounded-pill">Question 1 of 5</span>
                    <h4 class="fw-bold text-dark mb-4">What is the most critical aspect of this topic?</h4>

                    <div class="d-flex flex-column gap-3">
                        <div
                            v-for="(opt, idx) in ['Proper communication with guests', 'Ignoring the itinerary', 'Rushing through sites']"
                            :key="idx"
                            class="p-3 border rounded-3 cursor-pointer transition-all option-card"
                            :class="{ 'border-success bg-success bg-opacity-10 shadow-sm': selectedOption === idx }"
                            @click="selectedOption = idx"
                        >
                            <strong class="me-2" :class="selectedOption === idx ? 'text-success' : 'text-secondary'">{{ String.fromCharCode(65 + idx) }}.</strong> 
                            <span :class="selectedOption === idx ? 'text-dark fw-bold' : 'text-dark'">{{ opt }}</span>
                        </div>
                    </div>

                    <div class="mt-5 text-end">
                        <button @click="finishQuiz" class="btn btn-success rounded-pill px-5 py-2 fw-bold shadow-sm" :disabled="selectedOption === null">
                            Submit Final Answer
                        </button>
                    </div>
                </div>

                <div v-else class="card-body p-5 text-center">
                    <i class="fas fa-check-circle text-success display-2 mb-4"></i>
                    <h2 class="fw-bold text-dark mb-2">Assessment Passed!</h2>
                    <p class="text-secondary mb-5 fs-5">You have successfully mastered this section's concepts.</p>
                    
                    <button @click="returnToModule" class="btn btn-primary rounded-pill px-5 py-3 fw-bold shadow-sm fs-5">
                        Continue Learning <i class="fas fa-arrow-right ms-2"></i>
                    </button>
                </div>
            </div>
        </div>
    </StudentLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';
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

const activeView = ref('module'); // 'module' | 'quiz'
const activeAssessmentType = ref('lesson'); // 'lesson' | 'final'
const activeLessonIndex = ref(0);
const unlockedLessonLevel = ref(1);
const selectedOption = ref(null);
const quizFinished = ref(false);

// Dynamic lessons from database
const lessons = computed(() => {
    if (!props.module.lessons) return [];
    return props.module.lessons.map(lesson => ({
        id: lesson.id,
        title: lesson.title,
        questions: lesson.questions ? lesson.questions.length : 0
    }));
});

const isEvaluationUnlocked = computed(() => {
    return lessons.value.length > 0 && unlockedLessonLevel.value > lessons.value.length;
});

const startLessonQuiz = (index) => {
    if (index + 1 <= unlockedLessonLevel.value) {
        activeView.value = 'quiz';
        activeAssessmentType.value = 'lesson';
        activeLessonIndex.value = index;
        selectedOption.value = null;
        quizFinished.value = false;
    }
};

const startFinalEvaluation = () => {
    if (isEvaluationUnlocked.value) {
        activeView.value = 'quiz';
        activeAssessmentType.value = 'final';
        selectedOption.value = null;
        quizFinished.value = false;
    } else {
        alert("The End-of-Module Evaluation is locked. You must complete all lessons first before taking this evaluation.");
    }
};

const finishQuiz = () => {
    quizFinished.value = true;
};

const returnToModule = () => {
    if (activeAssessmentType.value === 'lesson' && activeLessonIndex.value + 1 === unlockedLessonLevel.value) {
        unlockedLessonLevel.value++;
    } else if (activeAssessmentType.value === 'final') {
        alert("Module fully completed! You can now proceed to the next module.");
    }
    activeView.value = 'module';
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

.option-card:hover {
    border-color: #cbd5e1 !important;
    background-color: #f8fafc;
}
</style>
