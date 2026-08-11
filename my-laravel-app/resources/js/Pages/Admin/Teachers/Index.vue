<template>
    <AdminLayout>
        <!-- Page Header -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark mb-1">Teachers & Educators Management</h2>
                <p class="text-muted mb-0">Manage instructor accounts, permissions, and faculty credentials across the platform.</p>
            </div>
            <button @click="showAddTeacherModal = true" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm d-flex align-items-center gap-2">
                <i class="fas fa-user-plus"></i>
                <span>Add Teacher / Educator</span>
            </button>
        </div>

        <!-- Stats Bar -->
        <div class="row g-4 mb-4">
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white d-flex flex-row align-items-center">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3 text-success me-3">
                        <i class="fas fa-chalkboard-teacher fa-2x"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold text-dark mb-0">{{ stats?.totalTeachers || teachers.length }}</h3>
                        <small class="text-muted">Total Instructors</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white d-flex flex-row align-items-center">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3 text-primary me-3">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold text-dark mb-0">{{ stats?.activeTeachers || teachers.length }}</h3>
                        <small class="text-muted">Active Instructors</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Teachers Directory Table Card -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="card-header bg-white py-3 px-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
                <h5 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                    <i class="fas fa-list text-primary"></i>
                    <span>Faculty Directory</span>
                </h5>
                <div class="position-relative">
                    <input 
                        v-model="searchQuery" 
                        type="text" 
                        class="form-control form-control-sm ps-4 rounded-pill" 
                        placeholder="Search by name or email..."
                        style="min-width: 250px;"
                    >
                    <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-2.5 text-muted small"></i>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Teacher / Educator</th>
                            <th>Role Tag</th>
                            <th>Account Status</th>
                            <th>Created Date</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="filteredTeachers.length === 0">
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fas fa-chalkboard-teacher fa-3x mb-3 text-light"></i>
                                <p class="mb-0">No teachers found matching your search query.</p>
                            </td>
                        </tr>
                        <tr v-for="teacher in filteredTeachers" :key="teacher.id">
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <img 
                                        :src="teacher.avatar || '/assets/images/facilitator-female.jpg'" 
                                        alt="Avatar"
                                        class="rounded-circle border"
                                        style="width: 42px; height: 42px; object-fit: cover;"
                                    >
                                    <div>
                                        <span class="fw-bold text-dark d-block">{{ teacher.name }}</span>
                                        <small class="text-muted">{{ teacher.email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle rounded-pill px-3 py-1 text-capitalize">
                                    <i class="fas fa-award me-1"></i> {{ teacher.role }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-success text-white rounded-pill px-3 py-1">
                                    {{ teacher.status || 'active' }}
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">{{ new Date(teacher.created_at).toLocaleDateString() }}</small>
                            </td>
                            <td class="text-end pe-4">
                                <button class="btn btn-sm btn-outline-secondary rounded-circle me-1" title="View Profile">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-primary rounded-circle me-1" title="Edit Credentials">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Teacher Modal -->
        <div v-if="showAddTeacherModal" class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 shadow-lg border-0">
                    <div class="modal-header bg-dark text-white rounded-top-4 py-3">
                        <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
                            <i class="fas fa-user-plus text-primary"></i>
                            <span>Register New Teacher</span>
                        </h5>
                        <button type="button" class="btn-close btn-close-white" @click="showAddTeacherModal = false"></button>
                    </div>

                    <form @submit.prevent="submitAddTeacher">
                        <div class="modal-body p-4">
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-dark">Full Name</label>
                                <input 
                                    v-model="form.name" 
                                    type="text" 
                                    class="form-control rounded-3" 
                                    placeholder="e.g. Maria Santos, Ph.D." 
                                    required
                                >
                                <div v-if="form.errors.name" class="text-danger small mt-1">{{ form.errors.name }}</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-dark">Email Address</label>
                                <input 
                                    v-model="form.email" 
                                    type="email" 
                                    class="form-control rounded-3" 
                                    placeholder="teacher@winetravel.edu.ph" 
                                    required
                                >
                                <div v-if="form.errors.email" class="text-danger small mt-1">{{ form.errors.email }}</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-dark">Default Password</label>
                                <input 
                                    v-model="form.password" 
                                    type="password" 
                                    class="form-control rounded-3" 
                                    placeholder="Minimum 8 characters" 
                                    required
                                >
                                <div v-if="form.errors.password" class="text-danger small mt-1">{{ form.errors.password }}</div>
                            </div>
                        </div>

                        <div class="modal-footer bg-light rounded-bottom-4">
                            <button type="button" class="btn btn-light rounded-pill px-4" @click="showAddTeacherModal = false">Cancel</button>
                            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold" :disabled="form.processing">
                                Create Account
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    teachers: {
        type: Array,
        default: () => [],
    },
    stats: {
        type: Object,
        default: () => ({ totalTeachers: 0, activeTeachers: 0 }),
    },
});

const searchQuery = ref('');
const showAddTeacherModal = ref(false);

const form = useForm({
    name: '',
    email: '',
    password: '',
});

const filteredTeachers = computed(() => {
    return props.teachers.filter(t => 
        t.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        t.email.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const submitAddTeacher = () => {
    form.post(route('admin.teachers.store'), {
        onSuccess: () => {
            showAddTeacherModal.value = false;
            form.reset();
        },
    });
};
</script>
