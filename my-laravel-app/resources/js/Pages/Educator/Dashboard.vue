<template>
    <EducatorLayout>
        <!-- Dark Green Hero Banner (Refined 2-Column Responsive Layout & Watermark Depth) -->
        <div class="welcome-banner position-relative overflow-hidden mb-4 shadow-sm text-white">
            <!-- Subtle Watermark Icon for Background Depth (Preserves Green Gradient) -->
            <div class="banner-watermark" aria-hidden="true">
                <i class="fas fa-desktop"></i>
            </div>

            <div class="row align-items-center g-3 position-relative z-1">
                <div class="col-12 col-md-7 col-lg-8">
                    <div class="d-flex align-items-center flex-wrap gap-2 mb-2">
                        <span class="stage-tag-pill">
                            <i class="fas fa-chalkboard-teacher text-warning me-1"></i> FACULTY CMS CONTROL
                        </span>
                        <span class="stage-status-pill status-completed">
                            <span class="status-indicator-dot"></span>
                            Curriculum Active
                        </span>
                        <button 
                            type="button" 
                            class="stage-tag-pill border-0 text-white shadow-xs" 
                            style="background: rgba(255, 255, 255, 0.22) !important; cursor: pointer;"
                            @click="triggerTutorial(1)"
                            title="How Educator CMS Works"
                        >
                            <i class="fas fa-chalkboard-teacher text-warning me-1"></i> How It Works
                        </button>
                    </div>

                    <h1 class="banner-title fw-bold mb-1">
                        Faculty CMS & Curriculum Control
                    </h1>
                    <p class="banner-desc mb-0 opacity-90">
                        Manage curriculum modules, assessment question banks, and monitor student completion progress.
                    </p>
                </div>

                <div class="col-12 col-md-5 col-lg-4">
                    <!-- Frosted Glass Progress Card -->
                    <div class="banner-progress-card">
                        <div class="d-flex align-items-center gap-3">
                            <div class="overall-progress-circle flex-shrink-0" :style="progressCircleStyle">
                                <span class="progress-percentage">{{ stats.publishedModulesCount || 0 }}/{{ stats.totalModules || 0 }}</span>
                            </div>
                            <div class="flex-grow-1 min-w-0">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="progress-card-label">Published Modules</span>
                                    <span class="progress-card-percent fw-bold">{{ publishedPercent }}%</span>
                                </div>
                                <div class="progress banner-mini-track mb-1">
                                    <div 
                                        class="progress-bar bg-warning" 
                                        role="progressbar" 
                                        :style="{ width: `${publishedPercent}%` }"
                                        :aria-valuenow="publishedPercent" 
                                        aria-valuemin="0" 
                                        aria-valuemax="100"
                                    ></div>
                                </div>
                                <div class="d-flex justify-content-between text-white-50" style="font-size: 0.72rem;">
                                    <span>{{ stats.totalQuestions || 0 }} Questions</span>
                                    <span>{{ stats.totalStudentAttempts || 0 }} Attempts</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Activity & Status Breakdown -->
        <div class="row g-4 mb-4">
            <!-- Recent Module Edits -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 bg-white h-100">
                    <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center border-bottom">
                        <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                            <i class="fas fa-history text-success" style="color: #0d4b38 !important;"></i>
                            <span>Recent Content Activity</span>
                        </h5>
                        <Link :href="route('educator.modules.index')" class="btn btn-sm btn-light rounded-pill px-3 fw-bold">View All</Link>
                    </div>
                    <div class="card-body p-0">
                        <div v-if="!recentModules || recentModules.length === 0" class="p-4 text-center text-muted">
                            No recent updates logged yet.
                        </div>
                        <div v-else class="list-group list-group-flush">
                            <div 
                                v-for="mod in recentModules" 
                                :key="mod.id" 
                                class="list-group-item p-3 px-4 d-flex justify-content-between align-items-center bg-transparent"
                            >
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-light p-2.5 text-center text-dark" style="width: 40px; height: 40px;">
                                        <i :class="mod.icon || 'fas fa-book'"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0">{{ mod.title }}</h6>
                                        <small class="text-muted fs-8">
                                            Last modified by <strong>{{ mod.updated_by ? mod.updated_by.name : 'System' }}</strong>
                                        </small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <button 
                                        @click.prevent="toggleStatus(mod)"
                                        class="btn btn-sm rounded-pill px-3 py-1 fw-bold border-0 shadow-sm transition-all text-white" 
                                        :class="mod.status === 'published' ? 'btn-success' : 'btn-warning text-dark'"
                                        :disabled="mod.isToggling"
                                        title="Click to toggle Published/Draft"
                                    >
                                        <span v-if="mod.isToggling"><i class="fas fa-spinner fa-spin me-1"></i></span>
                                        {{ mod.status === 'published' ? 'Published' : 'Draft' }}
                                    </button>
                                    <Link :href="route('educator.modules.edit', mod.id)" class="btn btn-sm btn-outline-secondary rounded-circle ms-1">
                                        <i class="fas fa-edit"></i>
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Controls Panel -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 bg-white h-100">
                    <div class="card-header bg-white py-3 px-4 border-bottom">
                        <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                            <i class="fas fa-rocket text-warning"></i>
                            <span>Quick Educator Actions</span>
                        </h5>
                    </div>
                    <div class="card-body p-4 d-flex flex-column gap-3">
                        <Link :href="route('educator.modules.index')" class="p-3 rounded-4 bg-light text-decoration-none text-dark d-flex align-items-center gap-3 hover-lift border">
                            <div class="rounded-circle text-white p-2.5 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background-color: #0d4b38;">
                                <i class="fas fa-book-open"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0">Course Modules</h6>
                                <small class="text-muted">Edit text, media & draft states</small>
                            </div>
                        </Link>

                        <Link :href="route('educator.quizzes.index')" class="p-3 rounded-4 bg-light text-decoration-none text-dark d-flex align-items-center gap-3 hover-lift border">
                            <div class="rounded-circle text-white p-2.5 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background-color: #0d4b38;">
                                <i class="fas fa-tasks"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0">Quiz Question Bank</h6>
                                <small class="text-muted">Add multiple-choice items</small>
                            </div>
                        </Link>

                        <Link :href="route('educator.performance.index')" class="p-3 rounded-4 bg-light text-decoration-none text-dark d-flex align-items-center gap-3 hover-lift border">
                            <div class="rounded-circle text-white p-2.5 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background-color: #0d4b38;">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0">Student Analytics</h6>
                                <small class="text-muted">Track progress & retakes</small>
                            </div>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </EducatorLayout>
</template>

<script setup>
import { computed, onMounted } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import EducatorLayout from '@/Layouts/EducatorLayout.vue';

const props = defineProps({
    stats: {
        type: Object,
        default: () => ({ totalModules: 0, totalQuestions: 0, totalStudentAttempts: 0, publishedModulesCount: 0, draftModulesCount: 0 }),
    },
    recentModules: {
        type: Array,
        default: () => [],
    },
});

const publishedPercent = computed(() => {
    if (!props.stats.totalModules || props.stats.totalModules === 0) return 0;
    return Math.round(((props.stats.publishedModulesCount || 0) / props.stats.totalModules) * 100);
});

const progressCircleStyle = computed(() => {
    const degrees = (publishedPercent.value / 100) * 360;
    return {
        background: `conic-gradient(#ffc107 0deg ${degrees}deg, rgba(255,255,255,0.2) ${degrees}deg 360deg)`
    };
});

const triggerTutorial = (step = 1) => {
    window.dispatchEvent(new CustomEvent('win:open-educator-tutorial', { detail: { step } }));
};

const toggleStatus = (mod) => {
    mod.isToggling = true;
    router.patch(window.route('educator.modules.toggle', mod.id), {}, {
        preserveScroll: true,
        onFinish: () => {
            mod.isToggling = false;
        }
    });
};

onMounted(() => {
    // Automatically launch educator tutorial for first-time educators
    if (!localStorage.getItem('win_has_seen_educator_tutorial')) {
        setTimeout(() => {
            triggerTutorial(1);
        }, 600);
    }
});
</script>

<style scoped>
.fs-8 {
    font-size: 0.7rem;
}
.hover-lift:hover {
    transform: translateY(-2px);
    transition: all 0.2s ease-in-out;
}

.welcome-banner {
    background: linear-gradient(135deg, #0a472e 0%, #1a5f7a 100%);
    border-radius: 24px;
    padding: 26px 30px;
    color: white;
    margin-bottom: 24px;
    position: relative;
    overflow: hidden;
}

.overall-progress-circle {
    width: 58px;
    height: 58px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.overall-progress-circle::after {
    content: '';
    position: absolute;
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background-color: #0e3d29;
}

.progress-percentage {
    position: relative;
    z-index: 1;
    font-size: 0.72rem;
    font-weight: 700;
    color: white;
}

@media (max-width: 576px) {
    .welcome-banner {
        padding: 20px 18px;
        border-radius: 18px;
    }
    .banner-title {
        font-size: 1.4rem;
    }
    .overall-progress-circle {
        width: 48px;
        height: 48px;
    }
    .overall-progress-circle::after {
        width: 36px;
        height: 36px;
    }
    .progress-percentage {
        font-size: 0.65rem;
    }
}
</style>
