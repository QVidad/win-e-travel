<template>
    <AdminLayout>
        <!-- Dark Green Gradient Hero Banner Section -->
        <div 
            class="card border-0 text-white p-4 p-md-5 mb-4 shadow-sm" 
            style="background: linear-gradient(135deg, #0a472e 0%, #1a5f7a 100%); border-radius: 20px;"
        >
            <h1 class="display-6 fw-bold mb-2 text-white" style="font-weight: 800; letter-spacing: -0.5px;">
                Student Directory & Progress Tracker
            </h1>
            <p class="mb-0 text-white fst-italic fs-6 opacity-90">
                "Monitor student engagement, chapter completions, and CBEA certificate eligibility across all modules."
            </p>
        </div>

        <!-- 3 Stat Summary Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-3 h-100 d-flex flex-row align-items-center gap-3 bg-white">
                    <div class="bg-primary-subtle text-primary rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                        <i class="fas fa-users fa-lg"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0 text-dark">{{ stats?.totalEnrolled || students.length }}</h4>
                        <small class="text-muted fw-medium">Total Enrolled Students</small>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-3 h-100 d-flex flex-row align-items-center gap-3 bg-white">
                    <div class="bg-warning-subtle text-warning-emphasis rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                        <i class="fas fa-spinner fa-lg text-warning"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0 text-dark">{{ stats?.activeLearners || 0 }}</h4>
                        <small class="text-muted fw-medium">Active Learners (In Progress)</small>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-3 h-100 d-flex flex-row align-items-center gap-3 bg-white">
                    <div class="bg-success-subtle text-success rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                        <i class="fas fa-graduation-cap fa-lg" style="color: #0d4b38 !important;"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0 text-success" style="color: #0d4b38 !important;">{{ stats?.certifiedGraduates || 0 }}</h4>
                        <small class="text-muted fw-medium">Certified Graduates (100% Completed)</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Student Roster Card -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
            <!-- Search Bar & Filter Pills -->
            <div class="card-header bg-white py-3 px-4 d-flex flex-row justify-content-between align-items-center gap-3 border-bottom overflow-auto">
                <!-- Title -->
                <h5 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2 text-nowrap">
                    <i class="fas fa-user-graduate" style="color: #0d4b38;"></i>
                    <span>Student Roster & Progress</span>
                </h5>
                
                <div class="d-flex flex-row align-items-center gap-3 flex-nowrap">
                    <!-- Filter Pills -->
                    <div class="d-flex flex-row align-items-center gap-2 flex-nowrap">
                        <button 
                            @click="activeFilter = 'all'" 
                            class="btn rounded-pill px-3 fw-bold transition-all text-nowrap"
                            :class="activeFilter === 'all' ? 'text-white' : 'btn-light text-dark border'"
                            :style="activeFilter === 'all' ? 'background-color: #0d4b38 !important;' : ''"
                        >
                            All ({{ students.length }})
                        </button>
                        <button 
                            @click="activeFilter = 'in_progress'" 
                            class="btn rounded-pill px-3 fw-bold transition-all text-nowrap"
                            :class="activeFilter === 'in_progress' ? 'text-white' : 'btn-light text-dark border'"
                            :style="activeFilter === 'in_progress' ? 'background-color: #0d4b38 !important;' : ''"
                        >
                            In Progress
                        </button>
                        <button 
                            @click="activeFilter = 'certified'" 
                            class="btn rounded-pill px-3 fw-bold transition-all text-nowrap"
                            :class="activeFilter === 'certified' ? 'text-white' : 'btn-light text-dark border'"
                            :style="activeFilter === 'certified' ? 'background-color: #0d4b38 !important;' : ''"
                        >
                            Certified
                        </button>
                    </div>

                    <!-- Enhanced Search Input -->
                    <div class="input-group shadow-sm rounded-pill overflow-hidden border" style="min-width: 300px;">
                        <span class="input-group-text bg-white border-0 ps-3"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" v-model="searchQuery" class="form-control border-0 ps-2" placeholder="Search student name or email...">
                        <button class="btn text-white px-4 fw-medium border-0" style="background-color: #0d4b38;">
                            Search
                        </button>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-if="filteredStudents.length === 0" class="p-5 text-center my-3 text-muted">
                <i class="fas fa-user-slash fa-3x mb-3 text-light"></i>
                <h5 class="fw-bold text-dark mb-1">No Student Records Found</h5>
                <p class="mb-0">No student accounts match your filter criteria or search query.</p>
            </div>

            <!-- Student Table -->
            <div v-else class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Student Name & Email</th>
                            <th style="min-width: 180px;">Overall Progress</th>
                            <th>Chapters Completed</th>
                            <th>Certificate Status</th>
                            <th>Joined Date</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="student in filteredStudents" :key="student.id">
                            <!-- Student Name & Email -->
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <img 
                                        :src="student.avatar || '/assets/images/facilitator-male.jpg'" 
                                        alt="Student Avatar"
                                        class="rounded-circle border"
                                        style="width: 40px; height: 40px; object-fit: cover;"
                                    >
                                    <div>
                                        <span class="fw-bold text-dark d-block">{{ student.name }}</span>
                                        <small class="text-muted">{{ student.email }}</small>
                                    </div>
                                </div>
                            </td>

                            <!-- Overall Progress Bar + Badge -->
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress flex-grow-1" style="height: 8px; border-radius: 10px;">
                                        <div 
                                            class="progress-bar"
                                            :class="student.overall_percentage >= 100 ? 'bg-success' : 'bg-primary'"
                                            role="progressbar" 
                                            :style="{ width: student.overall_percentage + '%' }"
                                            :aria-valuenow="student.overall_percentage" 
                                            aria-valuemin="0" 
                                            aria-valuemax="100"
                                        ></div>
                                    </div>
                                    <span class="badge rounded-pill fw-bold" :class="student.overall_percentage >= 100 ? 'bg-success' : 'bg-light text-dark border'">
                                        {{ student.overall_percentage }}%
                                    </span>
                                </div>
                            </td>

                            <!-- Chapters Completed -->
                            <td>
                                <span class="fw-bold text-dark">
                                    {{ student.completed_chapters }} / {{ student.total_chapters }}
                                </span>
                                <small class="text-muted d-block fs-8">chapters passed</small>
                            </td>

                            <!-- Certificate Status -->
                            <td>
                                <span 
                                    class="badge rounded-pill px-3 py-1"
                                    :class="{
                                        'bg-success': student.certificate_status === 'Ready to Issue' || student.certificate_status === 'Issued',
                                        'bg-warning text-dark': student.certificate_status === 'In Progress',
                                        'bg-secondary bg-opacity-10 text-secondary border': student.certificate_status === 'Ineligible'
                                    }"
                                >
                                    <i 
                                        class="fas me-1" 
                                        :class="{
                                            'fa-award': student.certificate_status === 'Ready to Issue' || student.certificate_status === 'Issued',
                                            'fa-hourglass-half': student.certificate_status === 'In Progress',
                                            'fa-lock': student.certificate_status === 'Ineligible'
                                        }"
                                    ></i>
                                    {{ student.certificate_status }}
                                </span>
                            </td>

                            <!-- Joined Date -->
                            <td>
                                <small class="text-muted">{{ new Date(student.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) }}</small>
                            </td>

                            <!-- Actions -->
                            <td class="text-end pe-4">
                                <button 
                                    @click="openDetailsModal(student)" 
                                    class="btn btn-sm btn-outline-info rounded-circle me-1" 
                                    title="View Student Details"
                                >
                                    <i class="fas fa-eye"></i>
                                </button>

                                <button 
                                    @click="resetProgress(student)" 
                                    class="btn btn-sm btn-outline-warning text-dark rounded-circle me-1" 
                                    title="Reset Progress for Retake"
                                >
                                    <i class="fas fa-undo"></i>
                                </button>

                                <button 
                                    @click="deleteStudent(student)" 
                                    class="btn btn-sm btn-outline-danger rounded-circle" 
                                    title="Delete Student Account"
                                >
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- View Student Progress Details Modal -->
        <div v-if="selectedStudent" class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5); z-index: 1060;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 shadow-lg border-0">
                    <div class="modal-header text-white rounded-top-4 py-3" style="background-color: #0d4b38;">
                        <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
                            <i class="fas fa-user-graduate text-warning"></i>
                            <span>Student Performance Profile</span>
                        </h5>
                        <button type="button" class="btn-close btn-close-white" @click="selectedStudent = null"></button>
                    </div>

                    <div class="modal-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                            <img 
                                :src="selectedStudent.avatar" 
                                alt="Avatar" 
                                class="rounded-circle border"
                                style="width: 54px; height: 54px; object-fit: cover;"
                            >
                            <div>
                                <h5 class="fw-bold text-dark mb-0">{{ selectedStudent.name }}</h5>
                                <span class="text-muted small">{{ selectedStudent.email }}</span>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <div class="p-3 bg-light rounded-3 text-center border">
                                    <small class="text-muted d-block mb-1">Chapters Passed</small>
                                    <h4 class="fw-bold text-dark mb-0">{{ selectedStudent.completed_chapters }} / {{ selectedStudent.total_chapters }}</h4>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="p-3 bg-light rounded-3 text-center border">
                                    <small class="text-muted d-block mb-1">CBEA Certificate</small>
                                    <span class="badge bg-success rounded-pill px-3 py-1 fs-8">{{ selectedStudent.certificate_status }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info border-0 rounded-3 small mb-0">
                            <i class="fas fa-info-circle me-1"></i>
                            Students must score <strong>&ge; 90%</strong> on module quizzes to complete chapters and unlock the CBEA Certificate.
                        </div>
                    </div>

                    <div class="modal-footer bg-light rounded-bottom-4 justify-content-between">
                        <button 
                            type="button" 
                            class="btn btn-outline-warning text-dark rounded-pill px-3 fw-bold btn-sm"
                            @click="resetProgress(selectedStudent); selectedStudent = null;"
                        >
                            <i class="fas fa-undo me-1"></i> Reset Progress
                        </button>

                        <button type="button" class="btn btn-secondary rounded-pill px-4 btn-sm" @click="selectedStudent = null">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    students: {
        type: Array,
        default: () => [],
    },
    stats: {
        type: Object,
        default: () => ({ totalEnrolled: 0, activeLearners: 0, certifiedGraduates: 0, totalModulesCount: 25 }),
    },
});

const searchQuery = ref('');
const activeFilter = ref('all');
const selectedStudent = ref(null);

const filteredStudents = computed(() => {
    return (props.students || []).filter(s => {
        const matchesSearch = s.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                              s.email.toLowerCase().includes(searchQuery.value.toLowerCase());

        if (!matchesSearch) return false;

        if (activeFilter.value === 'in_progress') {
            return s.completed_chapters > 0 && s.completed_chapters < s.total_chapters;
        } else if (activeFilter.value === 'certified') {
            return s.completed_chapters >= s.total_chapters;
        }

        return true;
    });
});

const openDetailsModal = (student) => {
    selectedStudent.value = student;
};

const resetProgress = (student) => {
    if (confirm(`Are you sure you want to reset quiz progress for ${student.name}? This will allow the student to retake module quizzes.`)) {
        router.post(route('admin.students.reset', student.id), {}, {
            preserveScroll: true,
        });
    }
};

const deleteStudent = (student) => {
    if (confirm(`Are you sure you want to delete the student account for ${student.name}?`)) {
        router.delete(route('admin.students.destroy', student.id), {
            preserveScroll: true,
        });
    }
};
</script>

<style scoped>
.fs-8 {
    font-size: 0.7rem;
}
.transition-all {
    transition: all 0.2s ease-in-out;
}
</style>
