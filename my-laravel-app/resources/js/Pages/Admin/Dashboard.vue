<template>
    <div class="d-flex flex-column min-vh-100 bg-light">
        <!-- Admin Header -->
        <nav class="navbar navbar-expand-lg bg-dark text-white shadow-sm py-3">
            <div class="container">
                <Link :href="route('home')" class="navbar-brand text-white fw-bold d-flex align-items-center">
                    <img src="/assets/images/WINLogo.png" alt="WIN Logo" style="height: 35px;" class="me-2">
                    WIN e-Travel — Admin Panel
                </Link>
                <div class="ms-auto d-flex align-items-center gap-3">
                    <span class="badge bg-danger"><i class="fas fa-user-shield me-1"></i> Super Admin</span>
                    <Link :href="route('educator.dashboard')" class="btn btn-sm btn-outline-success rounded-pill">
                        <i class="fas fa-chalkboard-teacher me-1"></i> Educator CMS
                    </Link>
                    <Link :href="route('dashboard')" class="btn btn-sm btn-outline-light rounded-pill">
                        <i class="fas fa-eye me-1"></i> Student Portal
                    </Link>
                    <Link :href="route('logout')" method="post" as="button" class="btn btn-sm btn-light text-danger rounded-pill">
                        Log Out
                    </Link>
                </div>
            </div>
        </nav>

        <!-- Main Body -->
        <div class="container py-5 flex-grow-1">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-dark mb-0">System User Management</h2>
                    <p class="text-muted">Create, configure, and manage Educator and Student accounts across the WIN e-Travel system.</p>
                </div>
                <button @click="showCreateModal = true" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                    <i class="fas fa-user-plus me-1"></i> Add Educator / User
                </button>
            </div>

            <!-- Flash Banner -->
            <div v-if="$page.props.flash && $page.props.flash.success" class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ $page.props.flash.success }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <div v-if="$page.props.flash && $page.props.flash.error" class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm mb-4" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ $page.props.flash.error }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>

            <!-- Stats Bar -->
            <div class="row g-4 mb-4">
                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 text-center bg-white">
                        <h3 class="fw-bold text-dark mb-0">{{ stats.totalUsers }}</h3>
                        <small class="text-muted">Total Accounts</small>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 text-center bg-white">
                        <h3 class="fw-bold text-danger mb-0">{{ stats.totalAdmins }}</h3>
                        <small class="text-muted">Super Admins</small>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 text-center bg-white">
                        <h3 class="fw-bold text-success mb-0">{{ stats.totalEducators }}</h3>
                        <small class="text-muted">Educators / Teachers</small>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 text-center bg-white">
                        <h3 class="fw-bold text-primary mb-0">{{ stats.totalStudents }}</h3>
                        <small class="text-muted">Enrolled Students</small>
                    </div>
                </div>
            </div>

            <!-- Users Table Card -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-users text-primary me-2"></i>User Directory</h5>
                    <input v-model="searchQuery" type="text" class="form-control form-control-sm w-auto" placeholder="Search name or email...">
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>User</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Joined Date</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="user in filteredUsers" :key="user.id">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img :src="user.avatar || '/assets/images/WINLogo.png'" class="rounded-circle me-3" style="width: 40px; height: 40px; object-fit: cover;">
                                        <div>
                                            <span class="fw-bold d-block text-dark">{{ user.name }}</span>
                                            <small class="text-muted">{{ user.email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span
                                        class="badge text-capitalize"
                                        :class="{
                                            'bg-danger': user.role === 'admin',
                                            'bg-success': user.role === 'educator',
                                            'bg-primary': user.role === 'student'
                                        }"
                                    >
                                        {{ user.role }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-success bg-opacity-10 text-success">{{ user.status || 'active' }}</span>
                                </td>
                                <td><small class="text-muted">{{ new Date(user.created_at).toLocaleDateString() }}</small></td>
                                <td class="text-end">
                                    <button @click="openEditModal(user)" class="btn btn-sm btn-outline-primary me-2 rounded-circle" title="Edit User">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button @click="deleteUser(user)" class="btn btn-sm btn-outline-danger rounded-circle" title="Delete User" :disabled="user.id === $page.props.auth.user.id">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Create User Modal -->
        <div v-if="showCreateModal" class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.6);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 shadow-lg border-0">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title fw-bold"><i class="fas fa-user-plus me-2"></i>Create New Account</h5>
                        <button type="button" class="btn-close btn-close-white" @click="showCreateModal = false"></button>
                    </div>
                    <form @submit.prevent="submitCreate">
                        <div class="modal-body p-4">
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Full Name</label>
                                <input v-model="createForm.name" type="text" class="form-control" required placeholder="e.g. Prof. Juan Santos">
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Email Address</label>
                                <input v-model="createForm.email" type="email" class="form-control" required placeholder="teacher@winetravel.com">
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Initial Password</label>
                                <input v-model="createForm.password" type="password" class="form-control" required placeholder="Minimum 8 characters">
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Role Assignment</label>
                                <select v-model="createForm.role" class="form-select" required>
                                    <option value="educator">Educator / Teacher</option>
                                    <option value="student">Student Trainee</option>
                                    <option value="admin">Super Admin</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-secondary rounded-pill px-4" @click="showCreateModal = false">Cancel</button>
                            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold" :disabled="createForm.processing">Create Account</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit User Modal -->
        <div v-if="showEditModal" class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.6);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 shadow-lg border-0">
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title fw-bold"><i class="fas fa-user-edit me-2"></i>Edit User Account</h5>
                        <button type="button" class="btn-close btn-close-white" @click="showEditModal = false"></button>
                    </div>
                    <form @submit.prevent="submitEdit">
                        <div class="modal-body p-4">
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Full Name</label>
                                <input v-model="editForm.name" type="text" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Email Address</label>
                                <input v-model="editForm.email" type="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Role Assignment</label>
                                <select v-model="editForm.role" class="form-select" required>
                                    <option value="admin">Super Admin</option>
                                    <option value="educator">Educator / Teacher</option>
                                    <option value="student">Student Trainee</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Account Status</label>
                                <select v-model="editForm.status" class="form-select" required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-secondary rounded-pill px-4" @click="showEditModal = false">Cancel</button>
                            <button type="submit" class="btn btn-dark rounded-pill px-4 fw-bold" :disabled="editForm.processing">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';

const props = defineProps({
    users: Array,
    stats: Object,
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
    return props.users.filter(u =>
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
