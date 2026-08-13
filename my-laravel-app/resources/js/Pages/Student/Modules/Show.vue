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
                                <p class="mb-0 text-secondary small">Quick Check • {{ lesson.questions || 5 }} questions • 90% to pass</p>
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

            <!-- Lesson Topic View -->
            <div v-else-if="activeView === 'lesson_info'">
                <!-- Top Hero Banner for Lesson matching system UI design -->
                <div class="welcome-banner text-white mb-4 shadow-sm position-relative overflow-hidden" style="background: linear-gradient(135deg, #0a472e 0%, #1a5f7a 100%); border-radius: 30px; padding: 35px 40px;">
                    <div class="row align-items-center position-relative z-1">
                        <div class="col-md-7">
                            <h2 class="fw-bold mb-2 display-6">{{ currentLessonTopic.title }}</h2>
                            <p class="fs-5 opacity-75 mb-0">Module {{ module.id }}: {{ module.title }} • Lesson {{ activeLessonIndex + 1 }} of {{ lessons.length }}</p>
                        </div>
                        <div class="col-md-5 text-md-end mt-3 mt-md-0">
                            <!-- Display Student Quiz Score when completed -->
                            <div v-if="getLessonScore(activeLessonIndex) !== null" class="d-inline-flex flex-column align-items-md-end bg-white bg-opacity-10 rounded-4 p-3 px-4 border border-white border-opacity-20 shadow-sm">
                                <span class="fs-8 text-uppercase tracking-wider opacity-75 fw-bold mb-1 text-white">
                                    <i class="fas fa-trophy text-warning me-1"></i> Quick Check Score
                                </span>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="display-6 fw-bold text-warning">{{ getLessonScore(activeLessonIndex) }}%</span>
                                    <span class="badge bg-success rounded-pill px-3 py-1 fs-8 fw-bold">PASSED</span>
                                </div>
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
                            <h6 class="fw-bold text-dark mb-1">Quick Check</h6>
                            <p class="text-secondary small mb-0">5 questions • 90% required to pass</p>
                        </div>
                        <button 
                            @click="startLessonQuizFromTopic" 
                            class="btn text-white rounded-pill px-4 py-2-5 fw-bold shadow-sm d-flex align-items-center gap-2 transition-all hover-lift"
                            style="background-color: #0a472e;"
                        >
                            <i class="fas fa-play"></i>
                            <span>Take Quick Check</span>
                        </button>
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

const activeView = ref('module'); // 'module' | 'lesson_info' | 'quiz'
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

const getLessonScore = (index) => {
    if (lessonScores.value[index] !== undefined) {
        return lessonScores.value[index];
    }
    if (index + 1 < unlockedLessonLevel.value) {
        return 100;
    }
    return null;
};

const openLessonTopic = (index) => {
    if (index + 1 <= unlockedLessonLevel.value) {
        activeLessonIndex.value = index;
        activeView.value = 'lesson_info';
    } else {
        alert("This lesson is locked. Complete the previous lesson first.");
    }
};

const startLessonQuizFromTopic = () => {
    activeView.value = 'quiz';
    activeAssessmentType.value = 'lesson';
    selectedOption.value = null;
    quizFinished.value = false;
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
    if (activeAssessmentType.value === 'lesson') {
        const score = (selectedOption.value === 0) ? 100 : 80;
        lessonScores.value[activeLessonIndex.value] = score;
    }
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
