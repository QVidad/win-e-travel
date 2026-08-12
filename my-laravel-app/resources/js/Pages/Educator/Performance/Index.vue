<template>
    <EducatorLayout>
        <Head title="Student Performance & Quiz Analytics" />

        <!-- Hero Banner Section -->
        <div 
            class="card border-0 text-white p-4 p-md-5 mb-4 shadow-sm" 
            style="background: linear-gradient(135deg, #0a472e 0%, #1a5f7a 100%); border-radius: 20px;"
        >
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 class="display-6 fw-bold mb-2 text-white" style="font-weight: 800; letter-spacing: -0.5px;">
                        Student Performance & Quiz Analytics
                    </h1>
                    <p class="mb-0 text-white fst-italic fs-6 opacity-90">
                        Track class mastery, average assessment scores, and module pass rates.
                    </p>
                </div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <ul class="nav nav-pills mb-4 gap-2">
            <li class="nav-item">
                <button 
                    class="nav-link rounded-pill px-4 fw-bold shadow-sm border transition-all" 
                    :class="activeTab === 'analytics' ? 'active bg-success text-white border-success' : 'bg-white text-dark hover-lift'"
                    style="background-color: activeTab === 'analytics' ? '#0d4b38' : '';"
                    @click="activeTab = 'analytics'"
                >
                    <i class="fas fa-chart-pie me-2"></i>Data Analytics
                </button>
            </li>
            <li class="nav-item">
                <button 
                    class="nav-link rounded-pill px-4 fw-bold shadow-sm border transition-all" 
                    :class="activeTab === 'roster' ? 'active bg-success text-white border-success' : 'bg-white text-dark hover-lift'"
                    @click="activeTab = 'roster'"
                >
                    <i class="fas fa-users me-2"></i>Student Roster
                </button>
            </li>
        </ul>

        <!-- Tab Content: Data Analytics -->
        <div v-if="activeTab === 'analytics'">
            <!-- Stat Cards Grid -->
            <div class="row g-4 mb-4">
                <!-- Class Average Score -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
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
                    <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
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
                    <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
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

            <!-- Hardest Chapters Alert Widget -->
            <div class="row g-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                        <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                            <h5 class="fw-bold mb-0 text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Struggling Chapters</h5>
                            <p class="text-muted small mt-1">Modules with lowest pass rates requiring attention.</p>
                        </div>
                        <div class="card-body p-4 pt-2">
                            <div v-if="hardestChapters.length > 0" class="row g-3">
                                <div class="col-md-4" v-for="(chapter, index) in hardestChapters" :key="index">
                                    <div class="p-3 bg-light rounded-3 border h-100 d-flex flex-column">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <h6 class="fw-bold text-dark mb-0 text-truncate me-2" :title="chapter.title">
                                                {{ chapter.code }}
                                            </h6>
                                            <span class="badge bg-danger rounded-pill">{{ chapter.pass_rate }}% Pass</span>
                                        </div>
                                        <div class="mb-3 flex-grow-1 text-dark small fw-medium text-truncate" :title="chapter.title">
                                            {{ chapter.title }}
                                        </div>
                                        <div class="d-flex justify-content-between small text-muted mt-auto pt-2 border-top">
                                            <span>Avg Score: <strong>{{ chapter.average_score }}%</strong></span>
                                            <span>Attempts: <strong>{{ chapter.attempts }}</strong></span>
                                        </div>
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
            </div>
        </div>

        <!-- Tab Content: Student Roster -->
        <div v-if="activeTab === 'roster'">
            <div class="card border-0 shadow-sm rounded-4 bg-white">
                <div class="card-header bg-white border-bottom pt-4 pb-3 px-4">
                    <div class="d-flex flex-row justify-content-between align-items-center gap-3 overflow-auto pb-1">
                        <h5 class="fw-bold mb-0 text-nowrap"><i class="fas fa-list me-2 text-success"></i>Student Class Roster</h5>
                        
                        <div class="d-flex flex-row gap-2 flex-nowrap align-items-center">
                            <!-- Export Button -->
                            <a :href="route('educator.performance.export')" target="_blank" class="btn btn-outline-success border-success-subtle shadow-sm rounded-3 d-flex align-items-center gap-2 fw-medium me-2" style="white-space: nowrap;">
                                <i class="fas fa-download"></i> Export CSV
                            </a>

                            <!-- Year Filter -->
                            <select v-model="filterYear" class="form-select bg-light border-0 shadow-sm" style="min-width: 150px;">
                                <option value="">All Registration Years</option>
                                <option v-for="year in availableYears" :key="year" :value="year">Class of {{ year }}</option>
                            </select>
                            
                            <!-- Status Filter -->
                            <select v-model="filterStatus" class="form-select bg-light border-0 shadow-sm" style="min-width: 150px;">
                                <option value="">All Statuses</option>
                                <option value="Eligible">Certified</option>
                                <option value="In Progress">In Progress</option>
                            </select>

                            <!-- Search -->
                            <div class="input-group shadow-sm rounded-pill overflow-hidden border" style="min-width: 300px;">
                                <span class="input-group-text bg-white border-0 ps-3"><i class="fas fa-search text-muted"></i></span>
                                <input type="text" class="form-control border-0 ps-2" placeholder="Search students..." v-model="searchQuery">
                                <button class="btn text-white px-4 fw-medium border-0" style="background-color: #0d4b38;">
                                    Search
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Student Name & Email</th>
                                    <th style="min-width: 180px;">Overall Progress</th>
                                    <th>Avg Score</th>
                                    <th>Attempts</th>
                                    <th>Status</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="student in filteredRoster" :key="student.id">
                                    <!-- Student Name & Email -->
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-3">
                                            <img 
                                                :src="student.avatar || '/assets/images/default-avatar.png'" 
                                                alt="Student Avatar"
                                                class="rounded-circle border"
                                                style="width: 40px; height: 40px; object-fit: cover;"
                                            >
                                            <div>
                                                <span class="fw-bold text-dark d-block">{{ student.name }}</span>
                                                <small class="text-muted">{{ student.email }} &bull; Class of {{ student.year_registered }}</small>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Overall Progress -->
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1" style="height: 8px; border-radius: 10px;">
                                                <div 
                                                    class="progress-bar"
                                                    :class="student.completed_chapters === student.total_chapters && student.total_chapters > 0 ? 'bg-success' : 'bg-primary'"
                                                    role="progressbar" 
                                                    :style="{ width: (student.total_chapters > 0 ? (student.completed_chapters / student.total_chapters) * 100 : 0) + '%' }"
                                                    :aria-valuenow="student.total_chapters > 0 ? (student.completed_chapters / student.total_chapters) * 100 : 0" 
                                                    aria-valuemin="0" 
                                                    aria-valuemax="100"
                                                ></div>
                                            </div>
                                            <span class="fw-bold text-dark fs-7">
                                                {{ student.completed_chapters }} / {{ student.total_chapters }}
                                            </span>
                                        </div>
                                    </td>

                                    <!-- Average Score -->
                                    <td>
                                        <span class="badge rounded-pill px-3 py-1" :class="getBadgeClass(student.badge_color)">
                                            {{ student.average_score }}%
                                        </span>
                                    </td>

                                    <!-- Attempts -->
                                    <td>
                                        <span class="fw-medium text-dark">{{ student.total_attempts }}</span>
                                    </td>

                                    <!-- Status -->
                                    <td>
                                        <span 
                                            class="badge rounded-pill px-3 py-1"
                                            :class="{
                                                'bg-success': student.status === 'Eligible',
                                                'bg-warning text-dark': student.status === 'In Progress'
                                            }"
                                        >
                                            <i 
                                                class="fas me-1" 
                                                :class="{
                                                    'fa-award': student.status === 'Eligible',
                                                    'fa-hourglass-half': student.status === 'In Progress'
                                                }"
                                            ></i>
                                            {{ student.status }}
                                        </span>
                                    </td>

                                    <!-- Actions -->
                                    <td class="text-end pe-4">
                                        <Link 
                                            :href="route('educator.performance.show', student.id)" 
                                            class="btn btn-sm btn-outline-info rounded-circle" 
                                            title="View Details"
                                        >
                                            <i class="fas fa-eye"></i>
                                        </Link>
                                    </td>
                                </tr>
                                <tr v-if="filteredRoster.length === 0">
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="fas fa-users-slash fa-3x mb-3 text-light d-block"></i>
                                        <h6 class="fw-bold text-dark">No Students Found</h6>
                                        <p class="mb-0 small">Try adjusting your filters or search query.</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </EducatorLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import EducatorLayout from '@/Layouts/EducatorLayout.vue';

const props = defineProps({
    classAverageScore: Number,
    quizPassRate: Number,
    completedCertificates: Number,
    hardestChapters: Array,
    roster: Array,
});

const activeTab = ref('analytics');
const filterYear = ref('');
const filterStatus = ref('');
const searchQuery = ref('');

const availableYears = computed(() => {
    const years = props.roster.map(s => s.year_registered).filter(Boolean);
    return [...new Set(years)].sort((a, b) => b - a);
});

const filteredRoster = computed(() => {
    let result = props.roster;
    
    if (filterYear.value) {
        result = result.filter(s => s.year_registered == filterYear.value);
    }
    
    if (filterStatus.value) {
        result = result.filter(s => s.status === filterStatus.value);
    }

    if (searchQuery.value) {
        const q = searchQuery.value.toLowerCase();
        result = result.filter(s => 
            s.name.toLowerCase().includes(q) || 
            s.email.toLowerCase().includes(q)
        );
    }
    
    return result;
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
.transition-all {
    transition: all 0.2s ease-in-out;
}
.hover-lift:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.25rem 0.5rem rgba(0,0,0,0.05) !important;
}
.nav-pills .nav-link.active {
    background-color: #0d4b38 !important;
    border-color: #0d4b38 !important;
}
</style>
