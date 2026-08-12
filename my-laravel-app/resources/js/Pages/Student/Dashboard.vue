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
                                <div class="overall-progress-circle" :style="progressCircleStyle">
                                    <span class="progress-percentage">{{ overallPercent }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Show when new user (0% progress) -->
                <div v-if="overallPercent === 0" class="card border-0 shadow-sm bg-white p-4 mb-4 rounded-4 border-start border-4 border-success">
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

                <!-- Progress Overview Card -->
                <div class="progress-overview-card">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h4 class="fw-bold mb-3 text-dark">
                                <i class="fas fa-chart-line text-success me-2"></i>
                                Current Journey
                            </h4>
                            <div class="d-flex align-items-center mb-3">
                                <span class="stat-badge bg-success bg-opacity-10 text-success">
                                    <i class="fas fa-check-circle me-1"></i>
                                    <span>{{ totalCompletedChapters }}</span> of <span>25</span> Chapters Completed
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="chapter-progress justify-content-md-end">
                                <span class="me-3 fw-bold text-dark">Progress:</span>
                                <div style="width: 200px;">
                                    <div class="progress-bar-custom">
                                        <div class="progress-fill" :style="{ width: overallPercent + '%' }"></div>
                                    </div>
                                </div>
                                <span class="ms-3 fw-bold text-dark">{{ overallPercent }}%</span>
                            </div>
                        </div>
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

                <!-- Recent Activity & Next Steps -->
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100 rounded-4 bg-white p-4">
                            <h5 class="fw-bold mb-4 d-flex align-items-center gap-2 text-dark">
                                <i class="fas fa-clock text-primary"></i> Recent Activity
                            </h5>
                            <!-- Dynamic Activity List -->
                            <div v-if="activities && activities.length > 0" class="activity-list">
                                <div v-for="(item, index) in activities" :key="index" class="d-flex align-items-center gap-3 mb-3">
                                    <div class="bg-light-subtle rounded-3 p-2 text-primary">
                                        <i :class="item.icon || 'fas fa-check-circle'"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ item.title }}</div>
                                        <small class="text-muted">{{ item.time }}</small>
                                    </div>
                                </div>
                            </div>
                            <!-- Empty State for Brand New Accounts -->
                            <div v-else class="text-center py-4 my-2">
                                <div class="text-muted opacity-50 mb-2">
                                    <i class="fas fa-history fa-3x"></i>
                                </div>
                                <p class="fw-medium text-dark mb-1">No Recent Activity</p>
                                <small class="text-muted d-block">Activities will appear here as you complete lessons and quizzes.</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100 rounded-4 bg-white p-4">
                            <h5 class="fw-bold mb-3 text-dark">
                                <i class="fas fa-forward text-warning me-2"></i>
                                Next Steps
                            </h5>
                            <div id="nextSteps">
                                <div class="list-group list-group-flush">
                                    <div class="list-group-item px-0 d-flex align-items-center bg-transparent">
                                        <i class="fas fa-circle text-success me-3 small"></i>
                                        <span>Complete Module 1: Tour Preparation</span>
                                    </div>
                                    <div class="list-group-item px-0 d-flex align-items-center bg-transparent">
                                        <i class="fas fa-circle text-secondary me-3 small"></i>
                                        <span>Pass Module 1 Quiz (90% required)</span>
                                    </div>
                                    <div class="list-group-item px-0 d-flex align-items-center bg-transparent">
                                        <i class="fas fa-circle text-secondary me-3 small"></i>
                                        <span>Continue to Module 2: Tour Briefings</span>
                                    </div>
                                </div>
                            </div>
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
