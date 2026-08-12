<template>
    <AdminLayout>
        <!-- Dark Teal Hero Banner Section (Target Hero Design System) -->
        <div 
            class="card border-0 text-white p-4 p-md-5 mb-4 shadow-sm" 
            style="background: linear-gradient(135deg, #0a472e 0%, #1a5f7a 100%); border-radius: 20px;"
        >
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <!-- Scaled & Bold Header -->
                    <h1 class="display-6 fw-extrabold mb-2 text-white" style="font-weight: 800; letter-spacing: -0.5px;">
                        System Administration & Control
                    </h1>
                    
                    <!-- Italicized Subtitle Quote Format -->
                    <p class="mb-0 text-white fst-italic fs-6 opacity-90">
                        "Manage educator accounts, student enrollments, and global system configurations."
                    </p>
                </div>
                
                <!-- Action Button -->
                <div>
                    <button 
                        @click="showCreateModal = true" 
                        class="btn btn-warning px-4 py-2 rounded-pill fw-bold text-dark shadow-sm border-0 d-flex align-items-center gap-2"
                        style="background-color: #d4a017; font-size: 0.95rem;"
                    >
                        <i class="fas fa-plus-circle"></i>
                        <span>Add Educator / User</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Summary Stats Cards (Matching Student Summary Metrics) -->
        <div class="row g-4 mb-4">
            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm rounded-4 p-3 text-center bg-white">
                    <div class="rounded-circle bg-light mx-auto mb-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fas fa-users text-dark fa-lg"></i>
                    </div>
                    <h3 class="fw-bold text-dark mb-0">{{ stats?.totalUsers || 0 }}</h3>
                    <small class="text-muted">Total Accounts</small>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm rounded-4 p-3 text-center bg-white">
                    <div class="rounded-circle bg-danger bg-opacity-10 mx-auto mb-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fas fa-user-shield text-danger fa-lg"></i>
                    </div>
                    <h3 class="fw-bold text-danger mb-0">{{ stats?.totalAdmins || 0 }}</h3>
                    <small class="text-muted">Super Admins</small>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm rounded-4 p-3 text-center bg-white">
                    <div class="rounded-circle bg-success bg-opacity-10 mx-auto mb-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fas fa-chalkboard-teacher text-success fa-lg" style="color: #0d4b38 !important;"></i>
                    </div>
                    <h3 class="fw-bold mb-0" style="color: #0d4b38;">{{ stats?.totalEducators || 0 }}</h3>
                    <small class="text-muted">Educators / Teachers</small>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm rounded-4 p-3 text-center bg-white">
                    <div class="rounded-circle bg-primary bg-opacity-10 mx-auto mb-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fas fa-user-graduate text-primary fa-lg"></i>
                    </div>
                    <h3 class="fw-bold text-primary mb-0">{{ stats?.totalStudents || 0 }}</h3>
                    <small class="text-muted">Enrolled Students</small>
                </div>
            </div>
        </div>

        <!-- Users Table Card -->
        <div id="students" class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
            <div class="card-header bg-white py-3 px-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
                <h5 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                    <i class="fas fa-users" style="color: #0d4b38;"></i>
                    <span>User Directory & Role Permissions</span>
                </h5>
                <div class="input-group shadow-sm rounded-pill overflow-hidden border" style="min-width: 300px;">
                    <span class="input-group-text bg-white border-0 ps-3"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" v-model="searchQuery" class="form-control border-0 ps-2" placeholder="Search name or email...">
                    <button class="btn text-white px-4 fw-medium border-0" style="background-color: #0d4b38;">
                        Search
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">User</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Joined Date</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="filteredUsers.length === 0">
                            <td colspan="5" class="text-center py-4 text-muted">No system users found.</td>
                        </tr>
                        <tr v-for="user in filteredUsers" :key="user.id">
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <img :src="user.avatar || '/assets/images/WINLogo.png'" class="rounded-circle me-3 border" style="width: 40px; height: 40px; object-fit: cover;">
                                    <div>
                                        <span class="fw-bold d-block text-dark">{{ user.name }}</span>
                                        <small class="text-muted">{{ user.email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span
                                    class="badge text-capitalize px-3 py-1 rounded-pill"
                                    :class="{
                                        'bg-danger': user.role === 'admin',
                                        'bg-success': user.role === 'educator' || user.role === 'teacher',
                                        'bg-primary': user.role === 'student'
                                    }"
                                    :style="user.role === 'educator' || user.role === 'teacher' ? 'background-color: #0d4b38 !important;' : ''"
                                >
                                    {{ user.role }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1">{{ user.status || 'active' }}</span>
                            </td>
                            <td><small class="text-muted">{{ new Date(user.created_at).toLocaleDateString() }}</small></td>
                            <td class="text-end pe-4">
                                <button @click="openEditModal(user)" class="btn btn-sm btn-outline-secondary me-2 rounded-circle" title="Edit User">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button @click="deleteUser(user)" class="btn btn-sm btn-outline-danger rounded-circle" title="Delete User" :disabled="user.id === $page.props.auth?.user?.id">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Create User Modal -->
        <div v-if="showCreateModal" class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5); z-index: 1060;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 shadow-lg border-0">
                    <div class="modal-header text-white rounded-top-4 py-3" style="background-color: #0d4b38;">
                        <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
                            <i class="fas fa-user-plus text-warning"></i>
                            <span>Create New User Account</span>
                        </h5>
                        <button type="button" class="btn-close btn-close-white" @click="showCreateModal = false"></button>
                    </div>
                    <form @submit.prevent="submitCreate">
                        <div class="modal-body p-4">
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-dark">Full Name</label>
                                <input v-model="createForm.name" type="text" class="form-control rounded-3" required placeholder="e.g. Prof. Juan Santos">
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-dark">Email Address</label>
                                <input v-model="createForm.email" type="email" class="form-control rounded-3" required placeholder="teacher@mmsu.edu.ph">
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-dark">Initial Password</label>
                                <input v-model="createForm.password" type="password" class="form-control rounded-3" required placeholder="Minimum 8 characters">
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-dark">Role Assignment</label>
                                <select v-model="createForm.role" class="form-select rounded-3" required>
                                    <option value="educator">Educator / Teacher</option>
                                    <option value="student">Student Trainee</option>
                                    <option value="admin">Super Admin</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer bg-light rounded-bottom-4">
                            <button type="button" class="btn btn-light rounded-pill px-4" @click="showCreateModal = false">Cancel</button>
                            <button type="submit" class="btn text-white rounded-pill px-4 fw-bold" style="background-color: #0d4b38;" :disabled="createForm.processing">Create Account</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit User Modal -->
        <div v-if="showEditModal" class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5); z-index: 1060;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 shadow-lg border-0">
                    <div class="modal-header bg-dark text-white rounded-top-4 py-3">
                        <h5 class="modal-title fw-bold"><i class="fas fa-user-edit me-2"></i>Edit User Account</h5>
                        <button type="button" class="btn-close btn-close-white" @click="showEditModal = false"></button>
                    </div>
                    <form @submit.prevent="submitEdit">
                        <div class="modal-body p-4">
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-dark">Full Name</label>
                                <input v-model="editForm.name" type="text" class="form-control rounded-3" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-dark">Email Address</label>
                                <input v-model="editForm.email" type="email" class="form-control rounded-3" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-dark">Role Assignment</label>
                                <select v-model="editForm.role" class="form-select rounded-3" required>
                                    <option value="admin">Super Admin</option>
                                    <option value="educator">Educator / Teacher</option>
                                    <option value="student">Student Trainee</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-dark">Account Status</label>
                                <select v-model="editForm.status" class="form-select rounded-3" required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
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
import { router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    users: {
        type: Array,
        default: () => [],
    },
    stats: {
        type: Object,
        default: () => ({ totalUsers: 0, totalAdmins: 0, totalEducators: 0, totalStudents: 0 }),
    },
});

const searchQuery = ref('');
const showCreateModal = ref(false);
const showEditModal = ref(false);
const selectedUser = ref(null);

const createForm = useForm({
    name: '',
    email: '',
    password: '',
    role: 'educator',
});

const editForm = useForm({
    id: null,
    name: '',
    email: '',
    role: 'student',
    status: 'active',
});

const filteredUsers = computed(() => {
    return (props.users || []).filter(u =>
        u.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        u.email.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const submitCreate = () => {
    createForm.post(route('admin.users.store'), {
        onSuccess: () => {
            showCreateModal.value = false;
            createForm.reset();
        },
    });
};

const openEditModal = (user) => {
    selectedUser.value = user;
    editForm.id = user.id;
    editForm.name = user.name;
    editForm.email = user.email;
    editForm.role = user.role;
    editForm.status = user.status || 'active';
    showEditModal.value = true;
};

const submitEdit = () => {
    editForm.put(route('admin.users.update', editForm.id), {
        onSuccess: () => {
            showEditModal.value = false;
        },
    });
};

const deleteUser = (user) => {
    if (confirm(`Are you sure you want to delete the user account for ${user.name}?`)) {
        router.delete(route('admin.users.destroy', user.id));
    }
};
</script>
