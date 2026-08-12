<template>
    <EducatorLayout>
        <Head title="Student Performance & Quiz Analytics" />

        <!-- Hero Banner -->
        <div class="rounded-4 p-4 p-md-5 mb-4 text-white shadow-sm position-relative overflow-hidden" 
             style="background: linear-gradient(135deg, #0d4b38 0%, #1a7457 100%);">
            <div class="position-relative" style="z-index: 2;">
                <h1 class="display-6 fw-bold mb-2">
                    <i class="fas fa-chart-line me-2"></i>Student Performance & Quiz Analytics
                </h1>
                <p class="lead mb-0 opacity-75">
                    Track class mastery, average assessment scores, and module pass rates.
                </p>
            </div>
            <!-- Decorative circle -->
            <div class="position-absolute rounded-circle bg-white opacity-10" 
                 style="width: 300px; height: 300px; top: -100px; right: -50px;"></div>
        </div>

        <!-- Stat Cards Grid -->
        <div class="row g-4 mb-4">
            <!-- Class Average Score -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                <i class="fas fa-star fa-lg"></i>
                            </div>
                            <h5 class="card-title fw-bold mb-0 text-muted">Class Average Score</h5>
                        </div>
                        <div class="mt-auto">
                            <h2 class="display-5 fw-bold text-dark mb-2">{{ classAverageScore }}%</h2>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar" :class="getScoreBadgeColor(classAverageScore, true)" role="progressbar" :style="{ width: classAverageScore + '%' }"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quiz Pass Rate -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                <i class="fas fa-check-double fa-lg"></i>
                            </div>
                            <h5 class="card-title fw-bold mb-0 text-muted">Quiz Pass Rate</h5>
                        </div>
                        <div class="mt-auto">
                            <h2 class="display-5 fw-bold text-dark mb-0">{{ quizPassRate }}%</h2>
                            <p class="text-muted small mt-1 mb-0"><i class="fas fa-arrow-up text-success me-1"></i> Passing is &ge; 90%</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Completed Certificates -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                <i class="fas fa-award fa-lg"></i>
                            </div>
                            <h5 class="card-title fw-bold mb-0 text-muted">Completed Certificates</h5>
                        </div>
                        <div class="mt-auto">
                            <h2 class="display-5 fw-bold text-dark mb-0">{{ completedCertificates }} <span class="fs-4 text-muted fw-normal">/ {{ roster.length }}</span></h2>
                            <p class="text-muted small mt-1 mb-0">Students who finished all modules</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <!-- Hardest Chapters Alert Widget -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                        <h5 class="fw-bold mb-0 text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Struggling Chapters</h5>
                        <p class="text-muted small mt-1">Modules with lowest pass rates requiring attention.</p>
                    </div>
                    <div class="card-body p-4 pt-2">
                        <div v-if="hardestChapters.length > 0">
                            <div v-for="(chapter, index) in hardestChapters" :key="index" class="mb-3 last-mb-0 p-3 bg-light rounded-3 border">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="fw-bold text-dark mb-0 text-truncate me-2" :title="chapter.title">
                                        {{ chapter.code }}: {{ chapter.title }}
                                    </h6>
                                    <span class="badge bg-danger rounded-pill">{{ chapter.pass_rate }}% Pass</span>
                                </div>
                                <div class="d-flex justify-content-between small text-muted">
                                    <span>Avg Score: <strong>{{ chapter.average_score }}%</strong></span>
                                    <span>Attempts: <strong>{{ chapter.attempts }}</strong></span>
                                </div>
                            </div>
                        </div>
                        <div v-else class="text-center p-4 bg-light rounded-3 text-muted">
                            <i class="fas fa-check-circle fa-2x mb-2 text-success"></i>
                            <p class="mb-0 small">No struggling chapters detected yet.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Student Performance Roster Table -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white border-bottom pt-4 pb-3 px-4 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Student Roster</h5>
                        <div class="input-group" style="width: 250px;">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                            <input type="text" class="form-control bg-light border-start-0" placeholder="Search students..." v-model="searchQuery">
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4 py-3 text-muted small fw-bold text-uppercase">Student</th>
                                        <th class="py-3 text-muted small fw-bold text-uppercase text-center">Progress</th>
                                        <th class="py-3 text-muted small fw-bold text-uppercase text-center">Avg Score</th>
                                        <th class="py-3 text-muted small fw-bold text-uppercase text-center">Attempts</th>
                                        <th class="px-4 py-3 text-muted small fw-bold text-uppercase text-end">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="student in filteredRoster" :key="student.id">
                                        <td class="px-4 py-3">
                                            <div class="d-flex align-items-center">
                                                <img :src="student.avatar || '/assets/images/default-avatar.png'" class="rounded-circle me-3" style="width: 40px; height: 40px; object-fit: cover;">
                                                <div>
                                                    <div class="fw-bold text-dark">{{ student.name }}</div>
                                                    <div class="small text-muted">{{ student.email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 text-center">
                                            <span class="fw-bold">{{ student.completed_chapters }}</span> <span class="text-muted small">/ {{ student.total_chapters }}</span>
                                        </td>
                                        <td class="py-3 text-center">
                                            <span class="badge rounded-pill" :class="getBadgeClass(student.badge_color)">
                                                {{ student.average_score }}%
                                            </span>
                                        </td>
                                        <td class="py-3 text-center text-muted">
                                            {{ student.total_attempts }}
                                        </td>
                                        <td class="px-4 py-3 text-end">
                                            <span class="badge" :class="student.status === 'Eligible' ? 'bg-success' : 'bg-secondary bg-opacity-10 text-secondary border border-secondary-subtle'">
                                                {{ student.status }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr v-if="filteredRoster.length === 0">
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <i class="fas fa-users-slash fa-2x mb-3 text-light"></i>
                                            <p class="mb-0">No students found matching your search.</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </EducatorLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import EducatorLayout from '@/Layouts/EducatorLayout.vue';

const props = defineProps({
    classAverageScore: Number,
    quizPassRate: Number,
    completedCertificates: Number,
    hardestChapters: Array,
    roster: Array,
});

const searchQuery = ref('');

const filteredRoster = computed(() => {
    if (!searchQuery.value) return props.roster;
    const q = searchQuery.value.toLowerCase();
    return props.roster.filter(s => 
        s.name.toLowerCase().includes(q) || 
        s.email.toLowerCase().includes(q)
    );
});

const getBadgeClass = (color) => {
    switch(color) {
        case 'Green': return 'bg-success';
        case 'Yellow': return 'bg-warning text-dark';
        case 'Red': return 'bg-danger';
        default: return 'bg-secondary';
    }
};

const getScoreBadgeColor = (score, isBgClass = false) => {
    if (score >= 90) return isBgClass ? 'bg-success' : 'text-success';
    if (score >= 75) return isBgClass ? 'bg-warning' : 'text-warning';
    return isBgClass ? 'bg-danger' : 'text-danger';
};
</script>

<style scoped>
.last-mb-0:last-child {
    margin-bottom: 0 !important;
}
.table > :not(caption) > * > * {
    background-color: transparent;
}
.table-hover tbody tr:hover {
    background-color: #f8f9fa;
}
</style>
