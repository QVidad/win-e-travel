<template>
    <EducatorLayout>
        <Head :title="`Performance: ${student.name}`" />

        <!-- Top Actions -->
        <div class="mb-4">
            <Link :href="route('educator.performance.index')" class="text-decoration-none text-muted">
                <i class="fas fa-arrow-left me-2"></i>Back to Student Roster
            </Link>
        </div>

        <!-- Student Profile Header -->
        <div class="card border-0 shadow-sm mb-4 overflow-hidden text-white" style="border-radius: 20px;">
            <div class="py-4 px-4 px-md-5" style="background: linear-gradient(135deg, #0d4b38 0%, #155e46 100%);">
                <div class="d-flex flex-column flex-md-row align-items-center justify-content-between">
                    <div class="d-flex align-items-center mb-3 mb-md-0">
                        <img :src="student.avatar || '/assets/images/default-avatar.png'" class="rounded-circle shadow-sm me-4 border border-white border-3" style="width: 80px; height: 80px; object-fit: cover;">
                        <div>
                            <h2 class="fw-bold mb-1" style="font-weight: 800; letter-spacing: -0.5px;">{{ student.name }}</h2>
                            <p class="text-white opacity-90 mb-0 fst-italic"><i class="fas fa-envelope me-2"></i>{{ student.email }}</p>
                        </div>
                    </div>
                    <div class="text-center text-md-end">
                        <p class="text-white opacity-75 small mb-1 text-uppercase fw-bold tracking-wide">MMSU Certificate Status</p>
                        <span class="badge fs-6 px-4 py-2 rounded-pill shadow-sm" :class="student.status === 'Eligible' ? 'bg-success text-white border border-success' : 'bg-light text-dark'">
                            <i class="fas fa-award me-2" v-if="student.status === 'Eligible'"></i>
                            <i class="fas fa-hourglass-half me-2 text-warning" v-else></i>
                            {{ student.status === 'Eligible' ? 'Certified' : 'In Progress' }}
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Timeline Metrics -->
            <div class="card-body px-4 px-md-5 py-4 bg-white">
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
                                <th class="px-4 py-3 text-muted small fw-bold text-uppercase">Module</th>
                                <th class="py-3 text-muted small fw-bold text-uppercase text-center">Score</th>
                                <th class="py-3 text-muted small fw-bold text-uppercase text-center">Status</th>
                                <th class="px-4 py-3 text-muted small fw-bold text-uppercase text-end">Completion Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="module in modules" :key="module.id">
                                <td class="px-4 py-3">
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
                                    <span class="badge rounded-pill px-3" :class="getStatusBadgeClass(module.status)">
                                        {{ module.status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-end text-muted small">
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
</style>
