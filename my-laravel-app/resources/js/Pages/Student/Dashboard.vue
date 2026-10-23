<template>
    <Head title="Dashboard" />
    <StudentLayout>
        <div class="dashboard-container">
            <div>
                <!-- Welcome Banner matching dashboard.html -->
                <!-- Welcome Banner (Refined Layout & Watermark Depth) -->
                <div class="welcome-banner position-relative overflow-hidden mb-4 shadow-sm">
                    <!-- Subtle Watermark Icon for Background Depth (Preserves Green Gradient) -->
                    <div class="banner-watermark" aria-hidden="true">
                        <i class="fas fa-graduation-cap"></i>
                    </div>

                    <div class="row align-items-center g-3 position-relative z-1">
                        <div class="col-12 col-md-7 col-lg-8">
                            <div class="d-flex align-items-center flex-wrap gap-2 mb-2">
                                <span class="stage-tag-pill">
                                    <i class="fas fa-award text-warning me-1"></i> TRAINEE DASHBOARD
                                </span>
                                <span class="stage-status-pill" :class="overallPercent >= 100 ? 'status-completed' : 'status-progress'">
                                    <span class="status-indicator-dot"></span>
                                    {{ overallPercent >= 100 ? 'Journey Completed' : 'Active Training' }}
                                </span>
                                <button 
                                    type="button" 
                                    class="stage-tag-pill border-0 text-white shadow-xs" 
                                    style="background: rgba(255, 255, 255, 0.22) !important; cursor: pointer;"
                                    @click="triggerTutorial"
                                    title="View Interactive Tutorial"
                                >
                                    <i class="fas fa-gamepad text-warning me-1"></i> How It Works
                                </button>
                            </div>
                            <h1 class="banner-title fw-bold mb-2">
                                Welcome back, <span id="welcomeName">{{ $page.props.auth.user.name }}</span>! 👋
                            </h1>
                            <p class="motivation-quote mb-0 opacity-90" id="motivationMessage">
                                "{{ motivationQuote }}"
                            </p>
                        </div>
                        <div class="col-12 col-md-5 col-lg-4">
                            <div class="banner-progress-card">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="overall-progress-circle flex-shrink-0" :style="progressCircleStyle">
                                        <span class="progress-percentage">{{ overallPercent }}%</span>
                                    </div>
                                    <div class="flex-grow-1 min-w-0">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="progress-card-label">Current Progress</span>
                                            <span class="progress-card-percent fw-bold">{{ overallPercent }}%</span>
                                        </div>
                                        <div class="progress banner-mini-track mb-1">
                                            <div 
                                                class="progress-bar bg-warning" 
                                                role="progressbar" 
                                                :style="{ width: `${overallPercent}%` }"
                                                :aria-valuenow="overallPercent" 
                                                aria-valuemin="0" 
                                                aria-valuemax="100"
                                            ></div>
                                        </div>
                                        <span class="small text-white-50 text-truncate d-block" style="font-size: 0.76rem;">
                                            Overall Guide Progression
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gamification Stats Card -->
                <div class="progress-overview-card mb-4" style="border-left: 4px solid var(--bs-warning);">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-4">
                        <div class="d-flex align-items-center gap-3 flex-grow-1">
                            <div class="bg-warning text-dark rounded-circle p-3 d-flex align-items-center justify-content-center flex-shrink-0 shadow-sm" style="width: 60px; height: 60px;">
                                <i class="fas fa-star fa-xl"></i>
                            </div>
                            <div class="flex-grow-1" style="max-width: 500px;">
                                <div class="d-flex justify-content-between align-items-end mb-1">
                                    <h5 class="fw-bold mb-0 text-dark">Overall Experience</h5>
                                    <div class="fw-bold text-warning">{{ $page.props.auth.user.xp || 0 }} XP</div>
                                </div>
                                <div class="progress mb-1 shadow-sm" style="height: 10px; border-radius: 10px; background-color: #e9ecef;">
                                    <div class="progress-bar bg-warning progress-bar-striped progress-bar-animated" role="progressbar" :style="`width: ${Math.min((($page.props.auth.user.xp || 0) / maxTargetXp) * 100, 100)}%`"></div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <small class="text-muted fw-bold">0 XP</small>
                                    <small class="text-muted fw-bold">Target: {{ maxTargetXp }} XP</small>
                                </div>
                            </div>
                        </div>
                        <Link :href="route('achievements.index')" class="btn btn-warning px-4 py-2 rounded-pill fw-bold shadow-sm">
                            View Badges <i class="fas fa-trophy ms-2"></i>
                        </Link>
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
                        <div class="d-flex align-items-center flex-wrap gap-2">
                            <button 
                                type="button" 
                                class="btn btn-outline-success px-3 rounded-pill fw-bold"
                                @click="triggerTutorial"
                            >
                                <i class="fas fa-gamepad me-1"></i> View Tutorial
                            </button>
                            <Link :href="route('go-beyond-books.index')" class="btn btn-mmsu px-4 rounded-pill fw-bold">
                                Start Module 1 <i class="fas fa-arrow-right ms-2"></i>
                            </Link>
                        </div>
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
                        <Link :href="route('go-beyond-books.index')" class="btn btn-warning px-4 rounded-pill fw-bold">
                            Continue Module 1 <i class="fas fa-arrow-right ms-2"></i>
                        </Link>
                    </div>
                </div>

                <!-- Progress Overview Card -->
                <div v-if="progress.simulationsCompleted > 0" class="progress-overview-card mb-4" style="border-left: 4px solid var(--bs-warning); background: linear-gradient(135deg, #fffdf2 0%, #fff 100%);">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-4">
                        <div class="d-flex align-items-center gap-3 flex-grow-1">
                            <div class="bg-warning text-dark rounded-circle p-3 d-flex align-items-center justify-content-center flex-shrink-0 shadow-sm" style="width: 60px; height: 60px;">
                                <i class="fas fa-certificate fa-xl"></i>
                            </div>
                            <div class="flex-grow-1" style="max-width: 500px;">
                                <h5 class="fw-bold mb-1 text-dark">Congratulations!</h5>
                                <p class="text-muted mb-0">You have successfully completed all training modules and the final virtual tour.</p>
                            </div>
                        </div>
                        <Link :href="route('achievements.index')" class="btn btn-warning px-4 py-2 rounded-pill fw-bold shadow-sm">
                            View Your Certificate <i class="fas fa-award ms-2"></i>
                        </Link>
                    </div>
                </div>
                <div v-else-if="progress.continueModule && progress.hasStarted" class="progress-overview-card mb-4" style="border-left: 4px solid var(--bs-success);">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-4">
                        <div class="d-flex align-items-center gap-3 flex-grow-1">
                            <div class="bg-success text-white rounded-circle p-3 d-flex align-items-center justify-content-center flex-shrink-0 shadow-sm" style="width: 60px; height: 60px;">
                                <i class="fas fa-book-open fa-xl"></i>
                            </div>
                            <div class="flex-grow-1" style="max-width: 500px;">
                                <h5 class="fw-bold mb-1 text-dark">Pick up where you left off</h5>
                                <p class="text-muted mb-0">Continue with <strong>{{ progress.continueModule.title }}</strong></p>
                            </div>
                        </div>
                        <Link :href="getContinueLink(progress.continueModule)" class="btn btn-success px-4 py-2 rounded-pill fw-bold shadow-sm">
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
                            <p class="text-muted small mb-3">Foundation Modules ({{ foundationTotal }})</p>

                            <div class="progress-bar-custom mb-3">
                                <div class="progress-fill" :style="{ width: (foundationCompleted / Math.max(1, foundationTotal)) * 100 + '%' }"></div>
                            </div>

                            <div class="d-flex justify-content-between mb-3">
                                <span class="small text-muted">
                                    <span>{{ foundationCompleted }}</span>/{{ foundationTotal }} Completed
                                </span>
                                <span class="small fw-bold text-success">{{ Math.round((foundationCompleted / Math.max(1, foundationTotal)) * 100) }}%</span>
                            </div>

                            <div class="chapter-progress mb-3">
                                <div v-for="i in Math.min(foundationTotal, 5)" :key="i" class="chapter-dot" :class="{ completed: i <= foundationCompleted, current: i === foundationCompleted + 1 }"></div>
                                <div v-if="foundationTotal > 5" class="chapter-dot">...</div>
                            </div>

                            <Link :href="route('go-beyond-books.index')" class="btn btn-outline-purple btn-journey w-100">
                                <i class="fas fa-arrow-right me-2"></i>Continue Learning
                            </Link>
                        </div>
                    </div>

                    <!-- Stage 2: Dare to Discover -->
                    <div class="col-lg-4">
                        <div class="journey-stage-card stage-discover position-relative" :class="{ 'opacity-75': isDareToDiscoverLocked }" id="discoverStage">
                            <div v-if="isDareToDiscoverLocked" class="position-absolute top-0 end-0 m-3 text-secondary">
                                <i class="fas fa-lock fs-5"></i>
                            </div>
                            <div class="stage-icon">
                                <i class="fas fa-compass"></i>
                            </div>
                            <h4 class="fw-bold mb-2">Dare to Discover</h4>
                            <p class="text-muted small mb-3">{{ townsTotal }} Town Chapters</p>

                            <div class="progress-bar-custom mb-3">
                                <div class="progress-fill" :style="{ width: (discoverCompleted / Math.max(1, townsTotal)) * 100 + '%' }"></div>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="small text-muted">
                                    <span>{{ discoverCompleted }}</span>/{{ townsTotal }} Completed
                                </span>
                                <span class="small fw-bold text-success">{{ Math.round((discoverCompleted / Math.max(1, townsTotal)) * 100) }}%</span>
                            </div>

                            <div class="chapter-progress mb-3">
                                <div v-for="i in Math.min(townsTotal, 5)" :key="i" class="chapter-dot" :class="{ completed: i <= discoverCompleted, current: i === discoverCompleted + 1 }"></div>
                                <div v-if="townsTotal > 5" class="chapter-dot">...</div>
                            </div>

                            <Link v-if="!isDareToDiscoverLocked" :href="route('dare-to-discover.index')" class="btn btn-outline-pink btn-journey w-100">
                                <i class="fas fa-arrow-right me-2"></i>Explore Towns
                            </Link>
                            <button v-else class="btn btn-secondary text-white w-100 fw-bold border-0" disabled>
                                <i class="fas fa-lock me-2"></i>Complete Go Beyond Books First
                            </button>
                        </div>
                    </div>

                    <!-- Stage 3: Adventure Awaits -->
                    <div class="col-lg-4">
                        <div class="journey-stage-card stage-adventure position-relative" :class="{ 'opacity-75': isAdventureAwaitsLocked }" id="adventureStage">
                            <div v-if="isAdventureAwaitsLocked" class="position-absolute top-0 end-0 m-3 text-secondary">
                                <i class="fas fa-lock fs-5"></i>
                            </div>
                            <div class="stage-icon">
                                <i class="fas fa-mountain"></i>
                            </div>
                            <h4 class="fw-bold mb-2">Adventure Awaits</h4>
                            <p class="text-muted small mb-3">Final Virtual Tour ({{ simulationsTotal }})</p>

                            <div class="progress-bar-custom mb-3">
                                <div class="progress-fill" :style="{ width: (adventureCompleted / Math.max(1, simulationsTotal)) * 100 + '%' }"></div>
                            </div>

                            <div class="d-flex justify-content-between mb-3">
                                <span class="small text-muted">
                                    <span>{{ adventureCompleted }}</span>/{{ simulationsTotal }} Completed
                                </span>
                                <span class="small fw-bold text-success">{{ Math.round((adventureCompleted / Math.max(1, simulationsTotal)) * 100) }}%</span>
                            </div>

                            <div class="chapter-progress mb-3">
                                <div v-for="i in Math.min(simulationsTotal, 5)" :key="i" class="chapter-dot" :class="{ completed: i <= adventureCompleted, current: i === adventureCompleted + 1 }"></div>
                                <div v-if="simulationsTotal > 5" class="chapter-dot">...</div>
                            </div>

                            <Link v-if="!isAdventureAwaitsLocked" :href="route('adventure-awaits.index')" class="btn btn-outline-blue btn-journey w-100">
                                <i class="fas fa-arrow-right me-2"></i>Adventure Awaits
                            </Link>
                            <button v-else class="btn btn-secondary text-white w-100 fw-bold border-0" disabled>
                                <i class="fas fa-lock me-2"></i>Complete Dare to Discover First
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
import { Link, Head, usePage } from '@inertiajs/vue3';

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

const getContinueLink = (module) => {
    if (!module) return '#';
    if (module.type === 'final_simulation') {
        return `/adventure-awaits`;
    }
    if (module.type === 'town_chapter' && module.code) {
        const slug = module.code.replace('town-', '');
        return `/dare-to-discover/${slug}`;
    }
    return `/go-beyond-books/modules/${module.id}`;
};

const foundationCompleted = computed(() => props.progress?.foundationCompleted ?? 0);
const foundationTotal = computed(() => props.progress?.foundationTotal ?? 0);
const discoverCompleted = computed(() => props.progress?.townsCompleted ?? 0);
const townsTotal = computed(() => props.progress?.townsTotal ?? 0);
const maxTargetXp = computed(() => props.progress?.maxTargetXp ?? 1000);
const adventureCompleted = computed(() => props.progress?.simulationsCompleted ?? 0);
const simulationsTotal = computed(() => props.progress?.simulationsTotal ?? 0);

const isDareToDiscoverLocked = computed(() => {
    const role = usePage().props.auth?.user?.role;
    if (['educator', 'admin'].includes(role)) return false;
    return foundationTotal.value > 0 && foundationCompleted.value < foundationTotal.value;
});
const isAdventureAwaitsLocked = computed(() => {
    const role = usePage().props.auth?.user?.role;
    if (['educator', 'admin'].includes(role)) return false;
    return isDareToDiscoverLocked.value || (townsTotal.value > 0 && discoverCompleted.value < townsTotal.value);
});

const totalCompletedChapters = computed(() => props.progress?.completedChapters ?? (foundationCompleted.value + discoverCompleted.value + adventureCompleted.value));
const overallPercent = computed(() => props.progress?.overallPercentage ?? Math.round((totalCompletedChapters.value / (props.progress?.totalChapters || 25)) * 100));

const progressCircleStyle = computed(() => {
    const degrees = (overallPercent.value / 100) * 360;
    return {
        background: `conic-gradient(#ffc107 0deg ${degrees}deg, rgba(255,255,255,0.2) ${degrees}deg 360deg)`
    };
});

const quotes = [
    "Your next simulation awaits. Let's practice to perfection!",
    "Every great tour guide started exactly where you are now.",
    "Ready to explore Ilocos Norte? Your journey continues!",
    "Practice makes perfect. Your next chapter is waiting."
];

const motivationQuote = ref('');

const triggerTutorial = () => {
    window.dispatchEvent(new CustomEvent('win:open-tutorial'));
};

onMounted(() => {
    motivationQuote.value = quotes[Math.floor(Math.random() * quotes.length)];

    // Automatically launch guided tutorial for newly registered / first-time students
    if (!localStorage.getItem('win_has_seen_tutorial')) {
        setTimeout(() => {
            triggerTutorial();
        }, 600);
    }
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

.locked-badge-top {
    position: absolute;
    top: 15px;
    right: 15px;
    background: rgba(108, 117, 125, 0.85);
    color: white;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    z-index: 2;
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
    background: linear-gradient(135deg, #d3cce3 0%, #e9e4f0 100%);
    color: #6a1b9a;
}

.stage-discover .stage-icon {
    background: linear-gradient(135deg, #ffafbd 0%, #ffc3a0 100%);
    color: #d81b60;
}

.stage-adventure .stage-icon {
    background: linear-gradient(135deg, #c2e9fb 0%, #a1c4fd 100%);
    color: #1565c0;
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

.banner-watermark {
    position: absolute;
    right: -10px;
    bottom: -22px;
    font-size: 135px;
    color: rgba(255, 255, 255, 0.08);
    transform: rotate(-12deg);
    pointer-events: none;
    z-index: 0;
}

.stage-tag-pill {
    display: inline-flex;
    align-items: center;
    padding: 4px 12px;
    border-radius: 9999px;
    background: rgba(255, 255, 255, 0.18) !important;
    border: 1px solid rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(4px);
    font-size: 0.72rem;
    letter-spacing: 0.5px;
    font-weight: 700;
    color: #ffffff !important;
}

.stage-status-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 11px;
    border-radius: 9999px;
    font-size: 0.72rem;
    font-weight: 600;
}

.stage-status-pill.status-progress {
    background: rgba(255, 255, 255, 0.12) !important;
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: #fef08a !important;
}

.stage-status-pill.status-completed {
    background: rgba(34, 197, 94, 0.25) !important;
    border: 1px solid rgba(74, 222, 128, 0.4);
    color: #86efac !important;
}

.status-indicator-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background-color: currentColor;
    display: inline-block;
    box-shadow: 0 0 6px currentColor;
}

.banner-title {
    font-size: 1.85rem;
    letter-spacing: -0.5px;
    line-height: 1.2;
}

.banner-progress-card {
    background: rgba(255, 255, 255, 0.12);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.22);
    border-radius: 18px;
    padding: 12px 16px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
}

.progress-card-label {
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: rgba(255, 255, 255, 0.85);
}

.progress-card-percent {
    font-size: 0.95rem;
    color: #ffc107;
}

.banner-mini-track {
    height: 6px;
    border-radius: 6px;
    background-color: rgba(255, 255, 255, 0.2);
    overflow: hidden;
}

.overall-progress-circle {
    width: 58px;
    height: 58px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: conic-gradient(#ffc107 0deg 0deg, rgba(255,255,255,0.2) 0deg 360deg);
    position: relative;
    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
}

.overall-progress-circle::before {
    content: '';
    position: absolute;
    width: 44px;
    height: 44px;
    background-color: #155566; /* harmonized deep teal */
    border-radius: 50%;
}

.progress-percentage {
    position: relative;
    font-weight: 800;
    color: white;
    font-size: 0.82rem;
}

.btn-outline-purple {
    color: #8e24aa;
    border-color: #8e24aa;
}
.btn-outline-purple:hover {
    color: #fff;
    background-color: #8e24aa;
    border-color: #8e24aa;
}

.btn-outline-pink {
    color: #d81b60;
    border-color: #d81b60;
}
.btn-outline-pink:hover {
    color: #fff;
    background-color: #d81b60;
    border-color: #d81b60;
}

.btn-outline-blue {
    color: #1976d2;
    border-color: #1976d2;
}
.btn-outline-blue:hover {
    color: #fff;
    background-color: #1976d2;
    border-color: #1976d2;
}

@media (max-width: 768px) {
    .welcome-banner {
        padding: 18px 16px !important;
        border-radius: 20px !important;
        margin-bottom: 20px !important;
    }
    .banner-watermark {
        font-size: 90px !important;
        right: -10px !important;
        bottom: -15px !important;
    }
    .banner-title {
        font-size: 1.35rem !important;
    }
    .motivation-quote {
        font-size: 0.82rem !important;
    }
    .banner-progress-card {
        padding: 10px 14px !important;
        border-radius: 14px !important;
    }
    .overall-progress-circle {
        width: 48px !important;
        height: 48px !important;
    }
    .overall-progress-circle::before {
        width: 36px !important;
        height: 36px !important;
    }
    .progress-percentage {
        font-size: 0.75rem !important;
    }
    .progress-overview-card {
        padding: 20px 16px !important;
        border-radius: 16px !important;
        margin-bottom: 20px !important;
    }
    .journey-stage-card {
        padding: 20px 16px !important;
        border-radius: 16px !important;
    }
}
</style>
 