<script setup>
import { computed } from 'vue';
import { usePage, Head } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import EducatorLayout from '@/Layouts/EducatorLayout.vue';
import StudentLayout from '@/Layouts/StudentLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';

defineProps({
    mustVerifyEmail: Boolean,
    status: String,
});

const page = usePage();
const layout = computed(() => {
    const role = page.props.auth?.user?.role;
    if (role === 'admin') return AdminLayout;
    if (role === 'educator') return EducatorLayout;
    return StudentLayout;
});
</script>

<template>
    <Head title="My Profile" />

    <component :is="layout">
        <div class="container-fluid py-4">
            <!-- Dark Teal Hero Banner Section (Target Hero Design System) -->
            <div 
                class="card border-0 text-white p-4 p-md-5 mb-4 shadow-sm mx-2" 
                style="background: linear-gradient(135deg, #0a472e 0%, #1a5f7a 100%); border-radius: 20px;"
            >
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <!-- Scaled & Bold Header -->
                        <h1 class="display-6 fw-extrabold mb-2 text-white" style="font-weight: 800; letter-spacing: -0.5px;">
                            My Profile
                        </h1>
                        
                        <!-- Italicized Subtitle Quote Format -->
                        <p class="mb-0 text-white fst-italic fs-6 opacity-90">
                            "Manage your account settings, update your profile details, and control your security preferences."
                        </p>
                    </div>
                </div>
            </div>

            <div class="row g-4 px-2">
                <!-- Profile Information -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white py-3 px-4 border-bottom">
                            <h5 class="fw-bold mb-0 text-dark">Profile Information</h5>
                            <p class="text-muted small mb-0">Update your account's profile information and email address.</p>
                        </div>
                        <div class="card-body p-4">
                            <UpdateProfileInformationForm
                                :must-verify-email="mustVerifyEmail"
                                :status="status"
                            />
                        </div>
                    </div>
                </div>

                <!-- Update Password -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white py-3 px-4 border-bottom">
                            <h5 class="fw-bold mb-0 text-dark">Update Password</h5>
                            <p class="text-muted small mb-0">Ensure your account is using a long, random password to stay secure.</p>
                        </div>
                        <div class="card-body p-4">
                            <UpdatePasswordForm />
                        </div>
                    </div>
                </div>

                <!-- Delete Account -->
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-header bg-white py-3 px-4 border-bottom">
                            <h5 class="fw-bold mb-0 text-danger">Delete Account</h5>
                            <p class="text-muted small mb-0">Once your account is deleted, all of its resources and data will be permanently deleted.</p>
                        </div>
                        <div class="card-body p-4">
                            <DeleteUserForm />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </component>
</template>
