<template>
    <EducatorLayout>
        <Head :title="`Performance: ${student.name}`" />

        <!-- Top Actions -->
        <div class="mb-4">
            <Link :href="route('educator.performance.index')" class="text-decoration-none text-muted">
                <i class="fas fa-arrow-left me-2"></i>Back to Student Roster
            </Link>
        </div>

        <!-- Student Profile Header (Refined 2-Column Responsive Layout) -->
        <div class="welcome-banner position-relative overflow-hidden mb-4 shadow-sm text-white">
            <!-- Subtle Watermark Icon for Background Depth -->
            <div class="banner-watermark" aria-hidden="true">
                <i class="fas fa-user-graduate"></i>
            </div>

            <div class="row align-items-center g-3 position-relative z-1">
                <div class="col-12 col-md-7 col-lg-8">
                    <div class="d-flex align-items-center flex-wrap gap-2 mb-2">
                        <Link :href="route('educator.performance.index')" class="stage-tag-pill text-decoration-none text-white shadow-xs" style="background: rgba(255, 255, 255, 0.22) !important;">
                            <i class="fas fa-arrow-left me-1"></i> Roster
                        </Link>
                        <span class="stage-tag-pill">
                            <i class="fas fa-id-card text-warning me-1"></i> STUDENT AUDIT PROFILE
                        </span>
                        <span class="stage-status-pill" :class="student.status === 'Eligible' ? 'status-completed' : 'status-progress'">
                            <span class="status-indicator-dot"></span>
                            {{ student.status === 'Eligible' ? 'Certified Tour Guide' : 'Training In Progress' }}
                        </span>
                        <button 
                            type="button" 
                            class="stage-tag-pill border-0 text-white shadow-xs" 
                            style="background: rgba(255, 255, 255, 0.22) !important; cursor: pointer;"
                            @click="triggerTutorial(5)"
                            title="How Student Analytics Work"
                        >
                            <i class="fas fa-chalkboard-teacher text-warning me-1"></i> Analytics Guide
                        </button>
                    </div>

                    <div class="d-flex align-items-center mt-2">
                        <img :src="student.avatar || '/assets/images/default-avatar.png'" class="rounded-circle shadow-sm me-3 border border-white border-2" style="width: 64px; height: 64px; object-fit: cover;">
                        <div>
                            <h1 class="banner-title fw-bold mb-1">
                                {{ student.name }}
                            </h1>
                            <p class="banner-desc mb-0 opacity-90">
                                <i class="fas fa-envelope me-1.5"></i>{{ student.email }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-5 col-lg-4">
                    <div class="banner-progress-card">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="progress-card-label">Certificate Status</span>
                            <span class="badge rounded-pill px-2.5" :class="student.status === 'Eligible' ? 'bg-success text-white' : 'bg-warning text-dark'">
                                {{ student.status === 'Eligible' ? 'Eligible' : 'In Progress' }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between text-white-50 small mb-2" style="font-size: 0.76rem;">
                            <span>Progress Completion</span>
                            <span class="text-warning fw-bold">{{ summary?.overall_percentage || 0 }}%</span>
                        </div>
                        <div class="progress banner-mini-track">
                            <div 
                                class="progress-bar bg-warning" 
                                role="progressbar" 
                                :style="{ width: `${summary?.overall_percentage || 0}%` }"
                                aria-valuemin="0" 
                                aria-valuemax="100"
                            ></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            
            <!-- Timeline Metrics Card -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
                <div class="card-body px-4 px-md-5 py-4">
                    <div class="row g-4">
                        <div class="col-md-3">
                            <div class="d-flex flex-column">
                                <span class="text-muted small fw-bold text-uppercase mb-1">Date Registered</span>
                                <div class="d-flex align-items-center">
                                    <i class="far fa-calendar-alt text-primary me-2 fa-lg"></i>
                                    <span class="fw-medium text-dark">{{ student.date_registered }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="d-flex flex-column">
                                <span class="text-muted small fw-bold text-uppercase mb-1">Date Completed</span>
                                <div class="d-flex align-items-center">
                                    <i class="far fa-calendar-check me-2 fa-lg" :class="student.date_completed ? 'text-success' : 'text-muted'"></i>
                                    <span class="fw-medium text-dark" v-if="student.date_completed">{{ student.date_completed }}</span>
                                    <span class="text-muted fst-italic" v-else>Ongoing</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="d-flex flex-column">
                                <span class="text-muted small fw-bold text-uppercase mb-1">Progress</span>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-tasks text-info me-2 fa-lg"></i>
                                    <span class="fw-medium text-dark">{{ summary.completed_count }} <span class="text-muted">/ {{ summary.total_count }} Modules</span></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="d-flex flex-column">
                                <span class="text-muted small fw-bold text-uppercase mb-1">Evaluation Mastery</span>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-award text-warning me-2 fa-lg"></i>
                                    <span class="fw-medium text-dark">{{ summary.overall_percentage || 0 }}% Score</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <!-- Detailed Module Progress -->
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-bottom pt-4 pb-3 px-4">
                <h5 class="fw-bold mb-0"><i class="fas fa-book-open me-2 text-primary"></i>Detailed Module Progress</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Module Details</th>
                                <th class="text-center">Score</th>
                                <th class="text-center">Status</th>
                                <th class="text-end pe-4">Completion Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="module in modules" :key="module.id">
                                <td class="ps-4">
                                    <div class="fw-bold text-dark">{{ module.code }}: {{ module.title }}</div>
                                </td>
                                <td class="py-3 text-center">
                                    <template v-if="module.score !== null">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <span class="fw-bold me-2" :class="getScoreBadgeColor(module.score)">{{ module.score }}%</span>
                                            <div class="progress flex-grow-1" style="height: 6px; max-width: 60px;">
                                                <div class="progress-bar" :class="getScoreBadgeColor(module.score, true)" role="progressbar" :style="{ width: module.score + '%' }"></div>
                                            </div>
                                        </div>
                                    </template>
                                    <span class="text-muted small" v-else>—</span>
                                </td>
                                <td class="py-3 text-center">
                                    <span class="badge rounded-pill px-3 py-1" :class="getStatusBadgeClass(module.status)">
                                        {{ module.status }}
                                    </span>
                                </td>
                                <td class="text-end pe-4 text-muted small">
                                    {{ module.completed_at || '—' }}
                                </td>
                            </tr>
                            <tr v-if="modules.length === 0">
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <p class="mb-0">No published modules found.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </EducatorLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import EducatorLayout from '@/Layouts/EducatorLayout.vue';

const props = defineProps({
    student: Object,
    modules: Array,
    summary: Object,
});

const getScoreBadgeColor = (score, isBgClass = false) => {
    if (score >= 90) return isBgClass ? 'bg-success' : 'text-success';
    if (score >= 75) return isBgClass ? 'bg-warning' : 'text-warning';
    return isBgClass ? 'bg-danger' : 'text-danger';
};

const getStatusBadgeClass = (status) => {
    switch(status) {
        case 'Passed': return 'bg-success';
        case 'Failed': return 'bg-danger';
        default: return 'bg-secondary bg-opacity-10 text-secondary border border-secondary-subtle';
    }
};

const triggerTutorial = (step = 5) => {
    window.dispatchEvent(new CustomEvent('win:open-educator-tutorial', { detail: { step } }));
};
</script>

<style scoped>
.tracking-wide {
    letter-spacing: 0.05em;
}
.table > :not(caption) > * > * {
    background-color: transparent;
}
.table-hover tbody tr:hover {
    background-color: #f8f9fa;
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

@media (max-width: 576px) {
    .welcome-banner {
        padding: 20px 18px;
        border-radius: 18px;
    }
    .banner-title {
        font-size: 1.4rem;
    }
}
</style>
