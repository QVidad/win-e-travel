<template>
    <Head title="Go Beyond Books" />
    <StudentLayout>
        <div class="dashboard-container">
            <div>
                <!-- Page Header (Refined Stage Banner Layout) -->
                <div class="welcome-banner text-white mb-4 shadow-sm position-relative overflow-hidden">
                    <!-- Subtle Watermark Icon for Background Depth (Preserves Color) -->
                    <div class="banner-watermark" aria-hidden="true">
                        <i class="fas fa-book-open"></i>
                    </div>

                    <div class="row align-items-center g-3 position-relative z-1">
                        <!-- Left: Stage Meta & Info -->
                        <div class="col-12 col-md-7 col-lg-8">
                            <div class="d-flex align-items-center flex-wrap gap-2 mb-2">
                                <span class="stage-tag-pill">
                                    <i class="fas fa-book-open text-warning me-1"></i> STAGE 1 &bull; FOUNDATION
                                </span>
                                <span class="stage-status-pill" :class="isStageCompleted ? 'status-completed' : 'status-progress'">
                                    <span class="status-indicator-dot"></span>
                                    {{ isStageCompleted ? 'Stage Completed' : 'In Progress' }}
                                </span>
                                <button 
                                    type="button" 
                                    class="stage-tag-pill border-0 text-white shadow-xs" 
                                    style="background: rgba(255, 255, 255, 0.22) !important; cursor: pointer;"
                                    @click="openStageTutorial"
                                    title="How Stage 1 works"
                                >
                                    <i class="fas fa-gamepad text-warning me-1"></i> Stage Guide
                                </button>
                            </div>
                            
                            <h1 class="banner-title fw-bold mb-1">
                                Go Beyond Books
                            </h1>
                            <p class="banner-desc mb-0 opacity-90">
                                Master essential tour guide principles before taking on live simulation practice.
                            </p>
                        </div>
                        
                        <!-- Right: Sleek Glass Progress Card -->
                        <div class="col-12 col-md-5 col-lg-4">
                            <div class="banner-progress-card">
                                <div class="d-flex align-items-center gap-3">
                                    <!-- Circular Gauge -->
                                    <div class="overall-progress-circle flex-shrink-0" :style="progressCircleStyle">
                                        <span class="progress-percentage">{{ completedModulesCount }}/{{ foundationModules.length }}</span>
                                    </div>
                                    
                                    <!-- Progress Stats & Mini Track -->
                                    <div class="flex-grow-1 min-w-0">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="progress-card-label">Stage Progress</span>
                                            <span class="progress-card-percent fw-bold">{{ progressPercent }}%</span>
                                        </div>
                                        <div class="progress banner-mini-track mb-1">
                                            <div 
                                                class="progress-bar bg-warning" 
                                                role="progressbar" 
                                                :style="{ width: `${progressPercent}%` }"
                                                :aria-valuenow="progressPercent" 
                                                aria-valuemin="0" 
                                                aria-valuemax="100"
                                            ></div>
                                        </div>
                                        <div class="small text-white-50 text-truncate" style="font-size: 0.76rem;">
                                            {{ completedModulesCount }} of {{ foundationModules.length }} Modules Completed
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Module Cards List -->
                <div class="d-flex flex-column gap-3 mb-5">
                    <div 
                        v-for="(mod, index) in foundationModules" 
                        :key="mod.id" 
                        class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden transition-all hover-lift text-decoration-none"
                        :class="isModuleUnlocked(index) ? 'cursor-pointer' : 'opacity-50'"
                        :style="!isModuleUnlocked(index) ? 'cursor: not-allowed; filter: grayscale(100%);' : 'cursor: pointer;'"
                        @click="selectModule(mod, index)"
                    >
                        <div class="card-body p-3 p-md-4 d-flex align-items-center gap-3">
                            <!-- Icon Box -->
                            <div class="module-icon-box flex-shrink-0">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                            
                            <!-- Content & Badges (Flexbox Prevents Overlaps) -->
                            <div class="flex-grow-1 min-w-0">
                                <div class="d-flex align-items-center justify-content-between gap-2 mb-1">
                                    <span class="module-step-label">MODULE {{ index + 1 }}</span>
                                    
                                    <!-- Status Badges (Never Collides with Title) -->
                                    <span 
                                        v-if="mod.is_passed" 
                                        class="badge rounded-pill bg-success text-white px-3 py-1"
                                        style="font-size: 0.75rem;"
                                    >
                                        <i class="fas fa-check me-1"></i> Completed
                                    </span>
                                    <span 
                                        v-else-if="isModuleUnlocked(index)" 
                                        class="badge rounded-pill bg-primary text-white px-3 py-1"
                                        style="font-size: 0.75rem;"
                                    >
                                        Available
                                    </span>
                                    <span 
                                        v-else 
                                        class="badge rounded-pill bg-secondary text-white px-3 py-1"
                                        style="font-size: 0.75rem;"
                                    >
                                        <i class="fas fa-lock me-1"></i> Locked
                                    </span>
                                </div>
                                <h3 class="module-card-title text-dark mb-0 text-truncate">{{ mod.title }}</h3>
                            </div>

                            <!-- Right Chevron Indicator on Desktop -->
                            <div class="text-muted d-none d-sm-block ps-2">
                                <i class="fas fa-chevron-right text-secondary opacity-50"></i>
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
import { ref, computed } from 'vue';
import { router, Head, usePage } from '@inertiajs/vue3';

const props = defineProps({
    foundationModules: {
        type: Array,
        default: () => []
    },
    unlockedLevel: {
        type: Number,
        default: 1
    },
    isEducatorOrAdmin: {
        type: Boolean,
        default: false
    }
});

const isUserPrivileged = computed(() => {
    const userRole = usePage().props.auth?.user?.role;
    return props.isEducatorOrAdmin || ['educator', 'admin'].includes(userRole);
});

const isModuleUnlocked = (index) => {
    return isUserPrivileged.value || (index + 1 <= props.unlockedLevel);
};

const completedModulesCount = computed(() => {
    if (isUserPrivileged.value) {
        return props.foundationModules.filter(m => m.is_passed).length;
    }
    return Math.min(props.unlockedLevel - 1, props.foundationModules.length);
});

const progressPercent = computed(() => {
    if (!props.foundationModules || props.foundationModules.length === 0) return 0;
    return Math.round((completedModulesCount.value / props.foundationModules.length) * 100);
});

const isStageCompleted = computed(() => {
    return props.foundationModules.length > 0 && completedModulesCount.value >= props.foundationModules.length;
});

const selectModule = (mod, index) => {
    if (isModuleUnlocked(index)) {
        router.visit(route('go-beyond-books.modules.show', mod.id));
    }
};

const openStageTutorial = () => {
    window.dispatchEvent(new CustomEvent('win:open-tutorial', { detail: { step: 3 } }));
};

const progressCircleStyle = computed(() => {
    const degrees = props.foundationModules.length > 0 ? (completedModulesCount.value / props.foundationModules.length) * 360 : 0;
    return {
        background: `conic-gradient(#ffc107 0deg ${degrees}deg, rgba(255,255,255,0.2) ${degrees}deg 360deg)`
    };
});

// Dynamic icon background colors
const getIconColorClass = (index) => {
    const colors = [
        'bg-success bg-opacity-10 text-success',
        'bg-primary bg-opacity-10 text-primary',
        'bg-warning bg-opacity-10 text-warning',
        'bg-info bg-opacity-10 text-info',
    ];
    return colors[index % colors.length];
};
</script>

<style scoped>
.foundation-container {
    background: #f8fafc;
    min-height: 100vh;
}

.welcome-banner {
    background: linear-gradient(135deg, #4a148c 0%, #7b1fa2 100%);
    border-radius: 24px;
    padding: 26px 30px;
    position: relative;
    overflow: hidden;
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
    background: rgba(255, 255, 255, 0.18);
    font-size: 0.72rem;
    letter-spacing: 0.5px;
    font-weight: 700;
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
    background: rgba(255, 255, 255, 0.12);
    color: #fef08a;
}

.stage-status-pill.status-completed {
    background: rgba(34, 197, 94, 0.25);
    color: #86efac;
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

.banner-desc {
    font-size: 0.95rem;
    max-width: 620px;
    line-height: 1.5;
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
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
}

.overall-progress-circle::before {
    content: '';
    position: absolute;
    width: 44px;
    height: 44px;
    background: #631983;
    border-radius: 50%;
}

.progress-percentage {
    position: relative;
    font-weight: 800;
    color: white;
    font-size: 0.82rem;
}

/* Module Card Styles */
.hover-lift {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.hover-lift:hover:not(.opacity-50) {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08) !important;
}

.module-icon-box {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    background-color: #eaf5f0;
    color: #198754;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}

.module-step-label {
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    color: #64748b;
}

.module-card-title {
    font-size: 1.3rem;
    font-weight: 800;
    letter-spacing: -0.3px;
    line-height: 1.25;
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
    .banner-desc {
        font-size: 0.85rem !important;
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
    .module-icon-box {
        width: 46px !important;
        height: 46px !important;
        font-size: 20px !important;
        border-radius: 12px !important;
    }
    .module-card-title {
        font-size: 1.05rem !important;
    }
}
</style>
