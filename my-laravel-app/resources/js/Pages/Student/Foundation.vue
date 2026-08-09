<template>
    <StudentLayout>
        <div class="foundation-container py-4">
            <div class="container">
                <!-- Page Header matching foundation.html -->
                <div class="page-header">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <h2 class="fw-bold mb-2">
                                <i class="fas fa-book-open me-2"></i>
                                Go Beyond Books
                            </h2>
                            <p class="mb-0 opacity-90">Master essential tour guide principles before taking on live simulation practice</p>
                        </div>
                        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                            <div class="d-inline-block bg-white bg-opacity-25 rounded-4 px-4 py-2 fw-bold">
                                <span>{{ completedModulesCount }}</span>/4 Modules Completed
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Active View Toggle (Module List vs Quiz/Lesson) -->
                <div v-if="activeView === 'modules'">
                    <div class="row g-4">
                        <div class="col-lg-8">
                            <!-- Module Cards List matching foundation.html -->
                            <div v-for="mod in modules" :key="mod.id" class="module-card" :class="{ completed: mod.completed, locked: mod.locked }" @click="selectModule(mod)">
                                <div class="d-flex align-items-center">
                                    <div class="module-icon" :style="{ background: mod.iconBg, color: 'white' }">
                                        <i :class="mod.icon"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <h5 class="fw-bold mb-0 text-dark">Module {{ mod.id }}: {{ mod.title }}</h5>
                                            <span class="badge" :class="mod.completed ? 'bg-success' : mod.locked ? 'bg-secondary' : 'bg-primary'">
                                                {{ mod.completed ? 'Completed' : mod.locked ? 'Locked' : 'In Progress' }}
                                            </span>
                                        </div>
                                        <p class="text-muted small mb-2">{{ mod.description }}</p>
                                        <div class="progress-module-bar">
                                            <div class="progress-fill bg-success" :style="{ width: (mod.completed ? 100 : 0) + '%', height: '6px', borderRadius: '10px' }"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Final Evaluation Card -->
                            <div class="evaluation-card shadow-sm" :class="{ locked: completedModulesCount < 4 }">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <h5 class="fw-bold mb-1"><i class="fas fa-graduation-cap me-2"></i>Foundation Evaluation Test</h5>
                                        <p class="small mb-0 opacity-90">Pass all 4 module knowledge checks to unlock Dare to Discover</p>
                                    </div>
                                    <button class="btn btn-light rounded-pill px-4 fw-bold text-dark" :disabled="completedModulesCount < 4" @click="startWarmup">
                                        <i class="fas fa-play-circle me-1"></i> Start Evaluation
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Side Guidance Card -->
                        <div class="col-lg-4">
                            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                                <h5 class="fw-bold mb-3 text-dark"><i class="fas fa-lightbulb text-warning me-2"></i>Instructor Tip</h5>
                                <p class="text-muted small">Welcome to the core foundation modules! Completing these topics will prepare you with commentary techniques, crowd safety protocols, and professional ethics.</p>
                                <button @click="startWarmup" class="btn btn-brain-warmup w-100 rounded-pill mt-2">
                                    <i class="fas fa-brain me-2"></i>Brain Warm-up Quiz
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quiz View ("Brain Warm-up" / Knowledge Assessment) -->
                <div v-else class="quiz-container max-w-2xl mx-auto">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <button @click="activeView = 'modules'" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                            <i class="fas fa-arrow-left me-1"></i> Back to Modules
                        </button>
                        <span class="timer-badge" :class="{ warning: timerSeconds < 10 }">
                            <i class="fas fa-clock me-1"></i> 00:{{ timerSeconds < 10 ? '0' + timerSeconds : timerSeconds }}
                        </span>
                    </div>

                    <div v-if="!quizFinished" class="question-card">
                        <span class="badge bg-primary mb-2">Question 1 of 1</span>
                        <h5 class="fw-bold text-dark mb-3">What is the primary duty of care of a licensed tour guide during site visits?</h5>

                        <div
                            v-for="(opt, idx) in quizOptions"
                            :key="idx"
                            class="option-item"
                            :class="{ selected: selectedOption === idx }"
                            @click="selectedOption = idx"
                        >
                            <strong class="me-2 text-success">{{ String.fromCharCode(65 + idx) }}.</strong> {{ opt }}
                        </div>

                        <button @click="finishQuiz" class="btn btn-mmsu w-100 rounded-pill mt-4 fw-bold" :disabled="selectedOption === null">
                            Submit Answer
                        </button>
                    </div>

                    <div v-else class="text-center py-5">
                        <i class="fas fa-trophy text-warning display-3 mb-3"></i>
                        <h3 class="fw-bold text-success mb-2">Knowledge Check Passed!</h3>
                        <p class="text-muted mb-4">You scored 100% on the Principles of Tour Guiding module.</p>
                        <button @click="activeView = 'modules'" class="btn btn-success rounded-pill px-5 fw-bold">
                            Continue Journey
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </StudentLayout>
</template>

<script setup>
import StudentLayout from '@/Layouts/StudentLayout.vue';
import { ref, computed } from 'vue';

const props = defineProps({
    overview: Object,
});

const activeView = ref('modules');
const selectedOption = ref(null);
const quizFinished = ref(false);
const timerSeconds = ref(45);

const completedModulesCount = computed(() => 4);

const modules = [
    { id: 1, title: 'Tour Preparation', description: 'Destination research, itinerary planning, logistics management.', completed: true, locked: false, icon: 'fas fa-clipboard-list', iconBg: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)' },
    { id: 2, title: 'Tour Briefings', description: 'Engaging briefings, timing, humor, guest communication.', completed: true, locked: false, icon: 'fas fa-users', iconBg: 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)' },
    { id: 3, title: 'Tour Information Delivery', description: 'Commentary techniques, voice modulation, storytelling.', completed: true, locked: false, icon: 'fas fa-landmark', iconBg: 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)' },
    { id: 4, title: 'Conclude the Tour', description: 'Debriefing, feedback collection, guest farewells.', completed: true, locked: false, icon: 'fas fa-flag-checkered', iconBg: 'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)' },
];

const quizOptions = [
    'Ensuring tourist health, emergency preparedness, and safety protocols.',
    'Focusing strictly on selling souvenirs to guests.',
    'Ignoring schedule timing to take extended breaks.',
];

const selectModule = (mod) => {
    if (!mod.locked) {
        startWarmup();
    }
};

const startWarmup = () => {
    activeView.value = 'quiz';
    selectedOption.value = null;
    quizFinished.value = false;
};

const finishQuiz = () => {
    quizFinished.value = true;
};
</script>

<style scoped>
.foundation-container {
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

.module-card {
    background: white;
    border-radius: 20px;
    padding: 25px 30px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    cursor: pointer;
    border: 1px solid #eef2f6;
    margin-bottom: 20px;
}

.module-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
    border-color: var(--mmsu-green);
}

.module-card.completed {
    border-left: 6px solid #28a745;
}

.module-card.locked {
    opacity: 0.85;
    cursor: not-allowed;
}

.module-icon {
    width: 65px;
    height: 65px;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    margin-right: 25px;
    flex-shrink: 0;
}

.quiz-container {
    background: white;
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
}

.question-card {
    background: #f8f9fa;
    border-radius: 15px;
    padding: 25px;
}

.option-item {
    padding: 15px 20px;
    margin: 10px 0;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.option-item:hover, .option-item.selected {
    border-color: #0a472e;
    background: rgba(10, 71, 46, 0.08);
}

.timer-badge {
    background: #ffc107;
    color: #000;
    padding: 8px 16px;
    border-radius: 30px;
    font-weight: 600;
}

.btn-brain-warmup {
    background: linear-gradient(135deg, var(--mmsu-gold) 0%, #e6b422 100%);
    color: white;
    border: none;
    border-radius: 30px;
    padding: 12px 30px;
    font-weight: 600;
}

.evaluation-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 15px;
    padding: 25px;
    color: white;
    margin-top: 20px;
}
</style>
