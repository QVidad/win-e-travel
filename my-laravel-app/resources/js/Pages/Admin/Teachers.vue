<template>
    <AdminLayout>
        <!-- Page Header -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark mb-1">Teacher Management</h2>
                <p class="text-muted mb-0">Oversee educator accounts, faculty section assignments, and system access rights.</p>
            </div>
            <button 
                @click="showAddTeacherModal = true" 
                class="btn btn-success rounded-pill px-4 fw-bold shadow-sm d-flex align-items-center gap-2"
                style="background-color: #0a472e; border-color: #0a472e;"
            >
                <i class="fas fa-user-plus"></i>
                <span>Add New Teacher</span>
            </button>
        </div>

        <!-- Teacher Table or Empty State Card -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="card-header bg-white py-3 px-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
                <h5 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                    <i class="fas fa-chalkboard-teacher text-success" style="color: #0a472e !important;"></i>
                    <span>Educator Roster</span>
                </h5>
                <div class="position-relative">
                    <input 
                        v-model="searchQuery" 
                        type="text" 
                        class="form-control form-control-sm ps-4 rounded-pill" 
                        placeholder="Search teacher or section..."
                        style="min-width: 250px;"
                    >
                    <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-2.5 text-muted small"></i>
                </div>
            </div>

            <!-- Empty State Box -->
            <div v-if="filteredTeachers.length === 0 && searchQuery === ''" class="p-5 text-center my-3">
                <div class="mx-auto rounded-circle bg-light d-flex align-items-center justify-content-center mb-3 shadow-xs" style="width: 80px; height: 80px;">
                    <i class="fas fa-user-graduate fa-2x text-muted opacity-50"></i>
                </div>
                <h4 class="fw-bold text-dark mb-2">No Teachers Registered Yet</h4>
                <p class="text-muted max-w-md mx-auto mb-4">You haven't added any educators or teachers to the platform. Click below to add your first educator.</p>
                <button 
                    @click="showAddTeacherModal = true" 
                    class="btn btn-success rounded-pill px-4 py-2 fw-bold shadow-sm"
                    style="background-color: #0a472e; border-color: #0a472e;"
                >
                    <i class="fas fa-plus me-1"></i> Add First Teacher
                </button>
            </div>

            <!-- No Search Results Empty State -->
            <div v-else-if="filteredTeachers.length === 0 && searchQuery !== ''" class="p-5 text-center my-3">
                <i class="fas fa-search fa-2x text-muted mb-3 opacity-50"></i>
                <h5 class="fw-bold text-dark mb-1">No Matching Teachers Found</h5>
                <p class="text-muted mb-0">No records found matching "{{ searchQuery }}".</p>
            </div>

            <!-- Teacher Table -->
            <div v-else class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Name</th>
                            <th>Email</th>
                            <th>Role Tag</th>
                            <th>Date Created</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="teacher in filteredTeachers" :key="teacher.id">
                            <!-- Name -->
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <img 
                                        :src="teacher.avatar || '/assets/images/facilitator-female.jpg'" 
                                        alt="Teacher Avatar"
                                        class="rounded-circle border"
                                        style="width: 40px; height: 40px; object-fit: cover;"
                                    >
                                    <div>
                                        <span class="fw-bold text-dark d-block">{{ teacher.name }}</span>
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill fs-8">Educator / Faculty</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Email -->
                            <td>
                                <span class="text-dark font-mono">{{ teacher.email }}</span>
                            </td>

                            <!-- Role Tag -->
                            <td>
                                <span class="badge bg-success text-white rounded-pill px-3 py-1 fw-normal">
                                    <i class="fas fa-chalkboard-teacher me-1"></i>
                                    Educator
                                </span>
                            </td>

                            <!-- Date Created -->
                            <td>
                                <small class="text-muted">{{ new Date(teacher.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) }}</small>
                            </td>

                            <!-- Actions (Edit / Delete) -->
                            <td class="text-end pe-4">
                                <button 
                                    @click="openEditModal(teacher)" 
                                    class="btn btn-sm btn-outline-primary rounded-circle me-1" 
                                    title="Edit Teacher"
                                >
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button 
                                    @click="deleteTeacher(teacher)" 
                                    class="btn btn-sm btn-outline-danger rounded-circle" 
                                    title="Delete Teacher"
                                >
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Teacher Creation Modal -->
        <div v-if="showAddTeacherModal" class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5); z-index: 1060;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 shadow-lg border-0">
                    <div class="modal-header text-white rounded-top-4 py-3" style="background-color: #0a472e;">
                        <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
                            <i class="fas fa-user-plus text-warning"></i>
                            <span>Register New Teacher</span>
                        </h5>
                        <button type="button" class="btn-close btn-close-white" @click="showAddTeacherModal = false"></button>
                    </div>

                    <form @submit.prevent="submitAddTeacher">
                        <div class="modal-body p-4">
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-dark">Teacher Name</label>
                                <input 
                                    v-model="createForm.name" 
                                    type="text" 
                                    class="form-control rounded-3" 
                                    placeholder="e.g. Prof. Maria Santos" 
                                    required
                                >
                                <div v-if="createForm.errors.name" class="text-danger small mt-1">{{ createForm.errors.name }}</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-dark">Email Address</label>
                                <input 
                                    v-model="createForm.email" 
                                    type="email" 
                                    class="form-control rounded-3" 
                                    placeholder="teacher@mmsu.edu.ph" 
                                    required
                                >
                                <div v-if="createForm.errors.email" class="text-danger small mt-1">{{ createForm.errors.email }}</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-dark">Password</label>
                                <input 
                                    v-model="createForm.password" 
                                    type="password" 
                                    class="form-control rounded-3" 
                                    placeholder="Minimum 8 characters" 
                                    required
                                >
                                <div v-if="createForm.errors.password" class="text-danger small mt-1">{{ createForm.errors.password }}</div>
                            </div>
                        </div>

                        <div class="modal-footer bg-light rounded-bottom-4">
                            <button type="button" class="btn btn-light rounded-pill px-4" @click="showAddTeacherModal = false">Cancel</button>
                            <button 
                                type="submit" 
                                class="btn btn-success rounded-pill px-4 fw-bold" 
                                style="background-color: #0a472e; border-color: #0a472e;"
                                :disabled="createForm.processing"
                            >
                                Submit & Create Account
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Teacher Modal -->
        <div v-if="showEditModal" class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5); z-index: 1060;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 shadow-lg border-0">
                    <div class="modal-header bg-dark text-white rounded-top-4 py-3">
                        <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
                            <i class="fas fa-edit text-primary"></i>
                            <span>Edit Teacher Details</span>
                        </h5>
                        <button type="button" class="btn-close btn-close-white" @click="showEditModal = false"></button>
                    </div>

                    <form @submit.prevent="submitEditTeacher">
                        <div class="modal-body p-4">
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-dark">Teacher Name</label>
                                <input v-model="editForm.name" type="text" class="form-control rounded-3" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-dark">Email Address</label>
                                <input v-model="editForm.email" type="email" class="form-control rounded-3" required>
                            </div>
                        </div>
                        <div class="modal-footer bg-light rounded-bottom-4">
                            <button type="button" class="btn btn-light rounded-pill px-4" @click="showEditModal = false">Cancel</button>
                            <button type="submit" class="btn btn-dark rounded-pill px-4 fw-bold" :disabled="editForm.processing">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
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
const showEditModal = ref(false);

const createForm = useForm({
    name: '',
    email: '',
    password: '',
});

const editForm = useForm({
    id: null,
    name: '',
    email: '',
});

const filteredTeachers = computed(() => {
    return (props.teachers || []).filter(t => 
        t.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        t.email.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const submitAddTeacher = () => {
    createForm.post(route('admin.teachers.store'), {
        onSuccess: () => {
            showAddTeacherModal.value = false;
            createForm.reset();
        },
    });
};

const openEditModal = (teacher) => {
    editForm.id = teacher.id;
    editForm.name = teacher.name;
    editForm.email = teacher.email;
    showEditModal.value = true;
};

const submitEditTeacher = () => {
    editForm.put(route('admin.users.update', editForm.id), {
        onSuccess: () => {
            showEditModal.value = false;
        },
    });
};

const deleteTeacher = (teacher) => {
    if (confirm(`Are you sure you want to remove ${teacher.name}?`)) {
        router.delete(route('admin.users.destroy', teacher.id));
    }
};
</script>

<style scoped>
.max-w-md {
    max-width: 28rem;
}
.fs-8 {
    font-size: 0.7rem;
}
</style>
