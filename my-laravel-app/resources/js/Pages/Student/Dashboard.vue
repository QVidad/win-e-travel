<template>
    <StudentLayout>
        <div class="dashboard-container">
            <div>
                <!-- Welcome Banner matching dashboard.html -->
                <div class="welcome-banner">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <h1 class="display-5 fw-bold mb-3">
                                Welcome back, <span id="welcomeName">{{ $page.props.auth.user.name }}</span>! 👋
                            </h1>
                            <p class="motivation-quote mb-0" id="motivationMessage">
                                "{{ motivationQuote }}"
                            </p>
                        </div>
                        <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                            <div class="d-flex justify-content-lg-end">
                                <div class="text-center">
                                    <div class="overall-progress-circle mb-2 mx-auto" :style="progressCircleStyle">
                                        <span class="progress-percentage">{{ overallPercent }}%</span>
                                    </div>
                                    <span class="small fw-bold text-white opacity-75">Current Progress</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Show when new user (0 progress entries) -->
                <div v-if="!progress.hasStarted" class="card shadow-sm bg-white p-4 mb-4 rounded-4" style="border: none; border-left: 4px solid var(--bs-success);">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-success text-white rounded-circle p-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 50px; height: 50px;">
                                <i class="fas fa-compass fa-xl"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1 text-dark">New to WIN e-Travel? Start Your First Step!</h5>
                                <p class="text-muted small mb-0">Begin with <strong>Go Beyond Books</strong> to learn core tour guiding skills and unlock town chapters.</p>
                            </div>
                        </div>
                        <Link :href="route('foundation.index')" class="btn btn-mmsu px-4 rounded-pill fw-bold">
                            Start Module 1 <i class="fas fa-arrow-right ms-2"></i>
                        </Link>
                    </div>
                </div>
                
                <!-- Show when user has started but 0 modules are completely passed -->
                <div v-else-if="overallPercent === 0 && progress.hasStarted" class="card shadow-sm bg-white p-4 mb-4 rounded-4" style="border: none; border-left: 4px solid var(--bs-warning);">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-warning text-dark rounded-circle p-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 50px; height: 50px;">
                                <i class="fas fa-running fa-xl"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1 text-dark">You're making progress!</h5>
                                <p class="text-muted small mb-0">You've passed some lessons, but you need to pass the End-of-Module Evaluation to complete your first chapter.</p>
                            </div>
                        </div>
                        <Link :href="route('foundation.index')" class="btn btn-warning px-4 rounded-pill fw-bold">
                            Continue Module 1 <i class="fas fa-arrow-right ms-2"></i>
                        </Link>
                    </div>
                </div>

                <!-- Progress Overview Card -->
                <div v-if="progress.continueModule" class="progress-overview-card">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-success text-white rounded-circle p-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 60px; height: 60px;">
                                <i class="fas fa-book-open fa-xl"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold mb-1 text-dark">Pick up where you left off</h4>
                                <p class="text-muted mb-0">Continue with <strong>{{ progress.continueModule.title }}</strong></p>
                            </div>
                        </div>
                        <Link :href="route('student.modules.show', progress.continueModule.id)" class="btn btn-success px-4 py-2 rounded-pill fw-bold shadow-sm">
                            Continue Learning <i class="fas fa-play ms-2"></i>
                        </Link>
                    </div>
                </div>

                <!-- 3 Journey Stage Cards matching dashboard.html -->
                <div class="row g-4 mb-4">
                    <!-- Stage 1: Go Beyond Books -->
                    <div class="col-lg-4">
                        <div class="journey-stage-card stage-foundation" id="foundationStage">
                            <div class="stage-icon">
                                <i class="fas fa-book-open"></i>
                            </div>
                            <h4 class="fw-bold mb-2">Go Beyond Books</h4>
                            <p class="text-muted small mb-3">Foundation Modules 1-4</p>

                            <div class="progress-bar-custom mb-3">
                                <div class="progress-fill" :style="{ width: (foundationCompleted / 4) * 100 + '%' }"></div>
                            </div>

                            <div class="d-flex justify-content-between mb-3">
                                <span class="small text-muted">
                                    <span>{{ foundationCompleted }}</span>/4 Completed
                                </span>
                                <span class="small fw-bold text-success">{{ Math.round((foundationCompleted / 4) * 100) }}%</span>
                            </div>

                            <div class="chapter-progress mb-3">
                                <div v-for="i in 4" :key="i" class="chapter-dot" :class="{ completed: i <= foundationCompleted, current: i === foundationCompleted + 1 }"></div>
                            </div>

                            <Link :href="route('foundation.index')" class="btn btn-outline-success w-100 btn-journey">
                                <i class="fas fa-arrow-right me-2"></i>Continue Learning
                            </Link>
                        </div>
                    </div>

                    <!-- Stage 2: Dare to Discover -->
                    <div class="col-lg-4">
                        <div class="journey-stage-card stage-discover" :class="{ locked: foundationCompleted < 4 }" id="discoverStage">
                            <div class="stage-icon">
                                <i class="fas fa-compass"></i>
                            </div>
                            <h4 class="fw-bold mb-2">Dare to Discover</h4>
                            <p class="text-muted small mb-3">21 Town Chapters</p>

                            <div class="progress-bar-custom mb-3">
                                <div class="progress-fill" :style="{ width: (discoverCompleted / 21) * 100 + '%' }"></div>
                            </div>

                            <div class="d-flex justify-content-between mb-3">
                                <span class="small text-muted">
                                    <span>{{ discoverCompleted }}</span>/21 Completed
                                </span>
                                <span class="small fw-bold text-success">{{ Math.round((discoverCompleted / 21) * 100) }}%</span>
                            </div>

                            <div class="chapter-progress mb-3">
                                <div class="chapter-dot completed"></div>
                                <div class="chapter-dot"></div>
                                <div class="chapter-dot"></div>
                                <div class="chapter-dot">...</div>
                            </div>

                            <div v-if="foundationCompleted < 4" class="lock-overlay">
                                <div class="lock-icon">
                                    <i class="fas fa-lock"></i>
                                </div>
                                <p class="small text-muted mb-0">Complete Go Beyond Books first</p>
                            </div>

                            <Link v-if="foundationCompleted >= 4" :href="route('towns.index')" class="btn btn-journey w-100" style="background-color: #f5576c; color: white;">
                                <i class="fas fa-arrow-right me-2"></i>Explore Towns
                            </Link>
                            <button v-else class="btn btn-outline-secondary w-100 btn-journey" disabled>
                                <i class="fas fa-lock me-2"></i>Locked
                            </button>
                        </div>
                    </div>

                    <!-- Stage 3: Adventure Awaits -->
                    <div class="col-lg-4">
                        <div class="journey-stage-card stage-adventure" :class="{ locked: discoverCompleted < 21 }" id="adventureStage">
                            <div class="stage-icon">
                                <i class="fas fa-mountain"></i>
                            </div>
                            <h4 class="fw-bold mb-2">Adventure Awaits</h4>
                            <p class="text-muted small mb-3">Simulation Practice</p>

                            <div class="progress-bar-custom mb-3">
                                <div class="progress-fill" :style="{ width: (adventureCompleted / 22) * 100 + '%' }"></div>
                            </div>

                            <div class="d-flex justify-content-between mb-3">
                                <span class="small text-muted">
                                    <span>{{ adventureCompleted }}</span>/22 Unlocked
                                </span>
                                <span class="small fw-bold text-success">{{ Math.round((adventureCompleted / 22) * 100) }}%</span>
                            </div>

                            <div class="chapter-progress mb-3">
                                <div class="chapter-dot"></div>
                                <div class="chapter-dot"></div>
                                <div class="chapter-dot"></div>
                                <div class="chapter-dot">...</div>
                            </div>

                            <div v-if="discoverCompleted < 21" class="lock-overlay">
                                <div class="lock-icon">
                                    <i class="fas fa-lock"></i>
                                </div>
                                <p class="small text-muted mb-0">Complete Dare to Discover first</p>
                            </div>

                            <Link v-if="discoverCompleted >= 21" :href="route('simulation.index')" class="btn btn-journey w-100" style="background-color: #00f2fe; color: white;">
                                <i class="fas fa-arrow-right me-2"></i>Start Simulation
                            </Link>
                            <button v-else class="btn btn-outline-secondary w-100 btn-journey" disabled>
                                <i class="fas fa-lock me-2"></i>Locked
                            </button>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </StudentLayout>
</template>

<script setup>
import StudentLayout from '@/Layouts/StudentLayout.vue';
import { ref, computed, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    towns: Array,
    achievements: Array,
    userStats: Object,
    progress: {
        type: Object,
        default: () => ({
            overallPercentage: 0,
            completedChapters: 0,
            totalChapters: 25,
            foundationCompleted: 0,
            townsCompleted: 0,
            simulationsUnlocked: 0,
        })
    },
    activities: {
        type: Array,
        default: () => []
    }
});

const foundationCompleted = computed(() => props.progress?.foundationCompleted ?? 0);
const discoverCompleted = computed(() => props.progress?.townsCompleted ?? 0);
const adventureCompleted = computed(() => props.progress?.simulationsUnlocked ?? 0);

const totalCompletedChapters = computed(() => props.progress?.completedChapters ?? (foundationCompleted.value + discoverCompleted.value + adventureCompleted.value));
const overallPercent = computed(() => props.progress?.overallPercentage ?? Math.round((totalCompletedChapters.value / (props.progress?.totalChapters || 25)) * 100));

const progressCircleStyle = computed(() => {
    const degrees = (overallPercent.value / 100) * 360;
    return {
        background: `conic-gradient(#0a472e 0deg ${degrees}deg, #e9ecef ${degrees}deg 360deg)`
    };
});

const quotes = [
    "Your next simulation awaits. Let's practice to perfection!",
    "Every great tour guide started exactly where you are now.",
    "Ready to explore Ilocos Norte? Your journey continues!",
    "Practice makes perfect. Your next chapter is waiting."
];

const motivationQuote = ref('');

onMounted(() => {
    motivationQuote.value = quotes[Math.floor(Math.random() * quotes.length)];
});
</script>

<style scoped>

.welcome-banner {
    background: linear-gradient(135deg, #0a472e 0%, #1a5f7a 100%);
    border-radius: 30px;
    padding: 40px;
    color: white;
    margin-bottom: 30px;
    position: relative;
    overflow: hidden;
}

.progress-overview-card {
    background: white;
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    margin-bottom: 30px;
}

.journey-stage-card {
    background: white;
    border-radius: 20px;
    padding: 25px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    height: 100%;
    transition: all 0.3s ease;
    position: relative;
}

.journey-stage-card.locked {
    opacity: 0.8;
}

.journey-stage-card.locked::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255,255,255,0.7);
    border-radius: 20px;
    z-index: 1;
}

.stage-icon {
    width: 70px;
    height: 70px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    margin-bottom: 20px;
}

.stage-foundation .stage-icon {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.stage-discover .stage-icon {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
}

.stage-adventure .stage-icon {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
}

.progress-bar-custom {
    height: 12px;
    border-radius: 10px;
    background-color: #e9ecef;
    margin: 15px 0;
}

.progress-fill {
    height: 100%;
    border-radius: 10px;
    background: linear-gradient(90deg, #0a472e, #1a5f7a);
    transition: width 0.5s ease;
}

.lock-overlay {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 2;
    text-align: center;
}

.lock-icon {
    font-size: 48px;
    color: #6c757d;
    margin-bottom: 10px;
}

.chapter-progress {
    display: flex;
    align-items: center;
    margin-top: 15px;
}

.chapter-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background-color: #dee2e6;
    margin-right: 8px;
    transition: all 0.3s ease;
}

.chapter-dot.completed {
    background-color: #28a745;
}

.chapter-dot.current {
    background-color: #ffc107;
    width: 16px;
    height: 16px;
}

.stat-badge {
    display: inline-block;
    padding: 5px 15px;
    border-radius: 30px;
    font-size: 0.85rem;
    font-weight: 500;
}

.btn-journey {
    border-radius: 15px;
    padding: 12px 24px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.overall-progress-circle {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.overall-progress-circle::before {
    content: '';
    position: absolute;
    width: 90px;
    height: 90px;
    border-radius: 50%;
    background: white;
}

.progress-percentage {
    position: relative;
    z-index: 1;
    font-size: 24px;
    font-weight: 700;
    color: #0a472e;
}
</style>
