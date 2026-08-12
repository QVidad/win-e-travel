<template>
    <div class="d-flex flex-column min-vh-100 bg-light">
        <!-- Top Navbar: Solid White with Border & Shadow -->
        <nav class="navbar navbar-expand-lg bg-white border-bottom py-2 shadow-sm sticky-top">
            <div class="container-fluid px-4">
                <!-- Left: Brand Logo -->
                <Link class="navbar-brand d-flex align-items-center fw-bold" :href="route('educator.dashboard')">
                    <img src="/assets/images/WINLogo.png" alt="WIN Logo" style="height: 32px;" class="me-2">
                    <span class="fw-bold" style="color: #0d4b38 !important; font-size: 1.25rem;">WIN e-Travel</span>
                    <span class="badge bg-success ms-2 fs-8 rounded-pill">Educator Portal</span>
                </Link>

                <!-- Center: Navigation Tabs with Icons Above Text -->
                <div class="d-none d-md-flex mx-auto align-items-center gap-4">
                    <Link 
                        :href="route('educator.dashboard')" 
                        class="text-decoration-none text-center small transition-all" 
                        :class="isRouteActive('/educator/dashboard') ? 'text-dark fw-bold opacity-100' : 'text-muted opacity-75'"
                    >
                        <div class="mb-1">
                            <i class="fas fa-desktop fa-lg" :class="isRouteActive('/educator/dashboard') ? 'text-success' : ''" style="color: #0d4b38 !important;"></i>
                        </div>
                        <div>Dashboard</div>
                    </Link>

                    <Link 
                        :href="route('educator.modules.index')" 
                        class="text-decoration-none text-center small transition-all" 
                        :class="isRouteActive('/educator/modules') ? 'text-dark fw-bold opacity-100' : 'text-muted opacity-75'"
                    >
                        <div class="mb-1">
                            <i class="fas fa-book-open fa-lg" :class="isRouteActive('/educator/modules') ? 'text-success' : ''" style="color: #0d4b38 !important;"></i>
                        </div>
                        <div>Content & Modules</div>
                    </Link>

                    <Link 
                        :href="route('educator.quizzes.index')" 
                        class="text-decoration-none text-center small transition-all" 
                        :class="isRouteActive('/educator/quizzes') ? 'text-dark fw-bold opacity-100' : 'text-muted opacity-75'"
                    >
                        <div class="mb-1">
                            <i class="fas fa-tasks fa-lg" :class="isRouteActive('/educator/quizzes') ? 'text-success' : ''" style="color: #0d4b38 !important;"></i>
                        </div>
                        <div>Quiz Pools</div>
                    </Link>

                    <Link 
                        :href="route('admin.students.index')" 
                        class="text-decoration-none text-center small transition-all text-muted opacity-75"
                    >
                        <div class="mb-1">
                            <i class="fas fa-user-graduate fa-lg"></i>
                        </div>
                        <div>Student Performance</div>
                    </Link>
                </div>

                <!-- Right: Educator Profile Dropdown -->
                <div class="dropdown position-relative me-2" :class="{ show: isProfileMenuOpen }">
                    <button 
                        class="btn btn-link text-dark text-decoration-none dropdown-toggle d-flex align-items-center gap-2 border-0 bg-transparent p-0" 
                        type="button" 
                        id="educatorUserProfileDropdown" 
                        data-bs-toggle="dropdown" 
                        :aria-expanded="isProfileMenuOpen"
                        @click.stop="toggleProfileMenu"
                    >
                        <div 
                            class="avatar-circle text-white rounded-circle d-flex align-items-center justify-content-center shadow-xs" 
                            style="width: 36px; height: 36px; background-color: #0d4b38;"
                        >
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <span class="fw-bold text-dark">{{ $page.props.auth?.user ? $page.props.auth.user.name : 'Educator' }}</span>
                    </button>
                    
                    <ul 
                        class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2 py-2 px-3 end-0" 
                        :class="{ show: isProfileMenuOpen }"
                        aria-labelledby="educatorUserProfileDropdown" 
                        style="min-width: 230px; border-radius: 14px; right: 0; left: auto; z-index: 1050;"
                    >
                        <li class="pb-2 pt-1 border-bottom mb-2">
                            <div class="fw-bold text-dark fs-6">{{ $page.props.auth?.user ? $page.props.auth.user.name : 'Educator' }}</div>
                            <div class="text-muted small text-truncate" style="max-width: 190px;">
                                {{ $page.props.auth?.user ? $page.props.auth.user.email : 'educator@winetravel.com' }}
                            </div>
                            <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle rounded-pill mt-1 fs-8">Active Educator</span>
                        </li>
                        
                        <li class="my-1">
                            <Link :href="route('profile.edit')" class="dropdown-item rounded-3 py-2 px-2 d-flex align-items-center text-dark fw-medium" @click="isProfileMenuOpen = false">
                                <i class="fas fa-user-cog me-3 fa-lg text-primary" style="width: 20px;"></i>
                                <span>My Profile</span>
                            </Link>
                        </li>

                        <li v-if="$page.props.auth?.user?.role === 'admin'" class="my-1">
                            <Link :href="route('admin.dashboard')" class="dropdown-item rounded-3 py-2 px-2 d-flex align-items-center text-dark fw-medium" @click="isProfileMenuOpen = false">
                                <i class="fas fa-user-shield me-3 fa-lg text-danger" style="width: 20px;"></i>
                                <span>Admin Panel</span>
                            </Link>
                        </li>
                        
                        <li class="my-1"><hr class="dropdown-divider my-1"></li>
                        
                        <li class="my-1">
                            <Link :href="route('logout')" method="post" as="button" class="dropdown-item rounded-3 py-2 px-2 d-flex align-items-center text-danger fw-medium w-100 text-start" @click="isProfileMenuOpen = false">
                                <i class="fas fa-sign-out-alt me-3 fa-lg" style="width: 20px;"></i>
                                <span>Sign Out</span>
                            </Link>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Sub-nav for Mobile Screens -->
        <div class="bg-white border-bottom d-md-none py-2 px-3 shadow-xs">
            <div class="d-flex justify-content-around">
                <Link :href="route('educator.dashboard')" class="text-decoration-none small text-center" :class="isRouteActive('/educator/dashboard') ? 'fw-bold text-dark' : 'text-muted'">
                    <i class="fas fa-desktop d-block mb-1"></i> Dashboard
                </Link>
                <Link :href="route('educator.modules.index')" class="text-decoration-none small text-center" :class="isRouteActive('/educator/modules') ? 'fw-bold text-dark' : 'text-muted'">
                    <i class="fas fa-book-open d-block mb-1"></i> Modules
                </Link>
                <Link :href="route('educator.quizzes.index')" class="text-decoration-none small text-center" :class="isRouteActive('/educator/quizzes') ? 'fw-bold text-dark' : 'text-muted'">
                    <i class="fas fa-tasks d-block mb-1"></i> Quiz Pools
                </Link>
                <Link :href="route('admin.students.index')" class="text-decoration-none small text-center text-muted">
                    <i class="fas fa-user-graduate d-block mb-1"></i> Students
                </Link>
            </div>
        </div>

        <!-- Main Content Area -->
        <main class="flex-grow-1 p-3 p-md-4">
            <div class="container-fluid max-w-7xl">
                <!-- Global Flash Messages -->
                <div 
                    v-if="$page.props.flash?.success" 
                    class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm mb-4" 
                    role="alert"
                >
                    <i class="fas fa-check-circle me-2"></i>{{ $page.props.flash.success }}
                    <button type="button" class="btn-close" @click="$page.props.flash.success = null"></button>
                </div>

                <div 
                    v-if="$page.props.flash?.error" 
                    class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm mb-4" 
                    role="alert"
                >
                    <i class="fas fa-exclamation-circle me-2"></i>{{ $page.props.flash.error }}
                    <button type="button" class="btn-close" @click="$page.props.flash.error = null"></button>
                </div>

                <slot />
            </div>
        </main>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const isProfileMenuOpen = ref(false);

const isRouteActive = (path) => {
    return page.url.startsWith(path);
};

const toggleProfileMenu = () => {
    isProfileMenuOpen.value = !isProfileMenuOpen.value;
};

const closeMenuOnOutsideClick = (event) => {
    const dropdown = document.getElementById('educatorUserProfileDropdown');
    if (dropdown && !dropdown.contains(event.target)) {
        isProfileMenuOpen.value = false;
    }
};

onMounted(() => {
    document.addEventListener('click', closeMenuOnOutsideClick);
});

onUnmounted(() => {
    document.removeEventListener('click', closeMenuOnOutsideClick);
});
</script>

<style scoped>
.fs-8 {
    font-size: 0.7rem;
}
.transition-all {
    transition: all 0.2s ease-in-out;
}
.max-w-7xl {
    max-width: 80rem;
    margin-left: auto;
    margin-right: auto;
}
</style>
