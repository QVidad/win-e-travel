<template>
    <div class="d-flex flex-column min-vh-100 bg-light">
        <!-- Top Navbar: Solid White with Border & Shadow -->
        <nav class="navbar navbar-expand-lg bg-white border-bottom py-2 shadow-sm sticky-top">
            <div class="container-fluid px-4">
                <!-- Left: Brand Logo -->
                <Link class="navbar-brand d-flex align-items-center fw-bold" :href="route('educator.dashboard')">
                    <img src="/assets/images/WINLogo.png" alt="WIN Logo" style="height: 32px;" class="me-2">
                    <span class="fw-bold" style="color: #0d4b38 !important; font-size: 1.25rem;">WIN e-Travel</span>
                    <span class="badge bg-success ms-2 fs-8 rounded-pill d-none d-sm-inline">Educator Portal</span>
                </Link>

                <!-- Center: Navigation Tabs with Sleek Pill Styling -->
                <div class="d-none d-lg-flex mx-auto align-items-center gap-1 gap-xl-2 py-1">
                    <Link 
                        :href="route('educator.dashboard')" 
                        class="educator-nav-tab text-decoration-none" 
                        :class="{ 'is-active': isRouteActive('/educator/dashboard') }"
                    >
                        <i class="fas fa-desktop me-1.5"></i>
                        <span>Dashboard</span>
                    </Link>

                    <Link 
                        :href="route('educator.modules.index')" 
                        class="educator-nav-tab text-decoration-none" 
                        :class="{ 'is-active': isRouteActive('/educator/modules') }"
                    >
                        <i class="fas fa-book-open me-1.5"></i>
                        <span>Modules</span>
                    </Link>

                    <Link 
                        :href="route('educator.quizzes.index')" 
                        class="educator-nav-tab text-decoration-none" 
                        :class="{ 'is-active': isRouteActive('/educator/quizzes') }"
                    >
                        <i class="fas fa-tasks me-1.5"></i>
                        <span>Quiz Banks</span>
                    </Link>

                    <Link 
                        :href="route('educator.performance.index')" 
                        class="educator-nav-tab text-decoration-none"
                        :class="{ 'is-active': isRouteActive('/educator/performance') }"
                    >
                        <i class="fas fa-user-graduate me-1.5"></i>
                        <span>Performance</span>
                    </Link>

                    <Link 
                        :href="route('educator.certificate.edit')" 
                        class="educator-nav-tab text-decoration-none"
                        :class="{ 'is-active': isRouteActive('/educator/certificate') }"
                    >
                        <i class="fas fa-certificate me-1.5"></i>
                        <span>Certificate</span>
                    </Link>
                </div>

                <!-- Right: Student View Switcher, Educator CMS Guide Button & Profile Dropdown -->
                <div class="d-flex align-items-center gap-2">
                    <Link 
                        :href="route('dashboard')" 
                        class="btn-tutorial-nav d-inline-flex align-items-center gap-1 shadow-xs text-decoration-none"
                        style="background: #e8f5e9; border: 1px solid #c8e6c9; color: #0d4b38;"
                        title="Inspect Student Experience (All Stages Unlocked)"
                    >
                        <i class="fas fa-eye text-success"></i>
                        <span class="d-none d-sm-inline">Student View</span>
                    </Link>

                    <button 
                        type="button" 
                        class="btn-tutorial-nav d-inline-flex align-items-center gap-1 shadow-xs"
                        @click="openTutorial(1)"
                        title="Faculty CMS Guide & Walkthrough"
                    >
                        <i class="fas fa-chalkboard-teacher text-warning"></i>
                        <span class="d-none d-sm-inline">CMS Guide</span>
                    </button>

                    <div class="dropdown position-relative me-1 me-sm-2" :class="{ show: isProfileMenuOpen }">
                        <button 
                            class="btn btn-link text-dark text-decoration-none dropdown-toggle d-flex align-items-center gap-2 border-0 bg-transparent p-0" 
                            type="button" 
                            id="educatorUserProfileDropdown" 
                            data-bs-toggle="dropdown" 
                            :aria-expanded="isProfileMenuOpen"
                            @click.stop="toggleProfileMenu"
                        >
                            <div 
                                class="avatar-circle text-white rounded-circle d-flex align-items-center justify-content-center shadow-xs overflow-hidden" 
                                style="width: 36px; height: 36px; background-color: #0d4b38;"
                            >
                                <img v-if="$page.props.auth?.user?.avatar" :src="$page.props.auth.user.avatar" alt="Avatar" class="w-100 h-100 object-fit-cover">
                                <i v-else class="fas fa-chalkboard-teacher"></i>
                            </div>
                            <span class="fw-bold text-dark d-none d-sm-inline text-truncate" style="max-width: 140px;">
                                {{ $page.props.auth?.user ? $page.props.auth.user.name : 'Educator' }}
                            </span>
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
                                <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle rounded-pill mt-1 fs-8">Active Faculty</span>
                            </li>
                            
                            <li class="my-1">
                                <Link :href="route('profile.edit')" class="dropdown-item rounded-3 py-2 px-2 d-flex align-items-center text-dark fw-medium" @click="isProfileMenuOpen = false">
                                    <i class="fas fa-user-cog me-3 fa-lg text-primary" style="width: 20px;"></i>
                                    <span>My Profile</span>
                                </Link>
                            </li>

                            <li class="my-1">
                                <Link :href="route('dashboard')" class="dropdown-item rounded-3 py-2 px-2 d-flex align-items-center text-dark fw-medium" @click="isProfileMenuOpen = false">
                                    <i class="fas fa-graduation-cap me-3 fa-lg text-success" style="width: 20px;"></i>
                                    <span>Student View</span>
                                </Link>
                            </li>

                            <li class="my-1">
                                <button 
                                    type="button" 
                                    class="dropdown-item rounded-3 py-2 px-2 d-flex align-items-center text-dark fw-medium border-0 bg-transparent w-100 text-start" 
                                    @click="openTutorial(1); isProfileMenuOpen = false"
                                >
                                    <i class="fas fa-chalkboard-teacher text-warning me-3 fa-lg" style="width: 20px;"></i>
                                    <span>Educator CMS Guide</span>
                                </button>
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
            </div>
        </nav>

        <!-- Sub-nav for Mobile Screens with Distinct Icon Boxes -->
        <div class="bg-white border-bottom d-lg-none py-2 px-2 shadow-xs">
            <div class="d-flex justify-content-around align-items-center w-100">
                <Link 
                    :href="route('educator.dashboard')" 
                    class="mobile-stage-btn text-decoration-none" 
                    :class="isRouteActive('/educator/dashboard') ? 'is-active text-dark' : 'text-muted'"
                >
                    <div class="mobile-icon-box">
                        <i class="fas fa-desktop" :class="isRouteActive('/educator/dashboard') ? 'text-success' : ''"></i>
                    </div>
                    <span class="mobile-stage-text">Dashboard</span>
                </Link>

                <Link 
                    :href="route('educator.modules.index')" 
                    class="mobile-stage-btn text-decoration-none" 
                    :class="isRouteActive('/educator/modules') ? 'is-active text-dark' : 'text-muted'"
                >
                    <div class="mobile-icon-box">
                        <i class="fas fa-book-open" :class="isRouteActive('/educator/modules') ? 'text-success' : ''"></i>
                    </div>
                    <span class="mobile-stage-text">Modules</span>
                </Link>

                <Link 
                    :href="route('educator.quizzes.index')" 
                    class="mobile-stage-btn text-decoration-none" 
                    :class="isRouteActive('/educator/quizzes') ? 'is-active text-dark' : 'text-muted'"
                >
                    <div class="mobile-icon-box">
                        <i class="fas fa-tasks" :class="isRouteActive('/educator/quizzes') ? 'text-success' : ''"></i>
                    </div>
                    <span class="mobile-stage-text">Quizzes</span>
                </Link>

                <Link 
                    :href="route('educator.performance.index')" 
                    class="mobile-stage-btn text-decoration-none" 
                    :class="isRouteActive('/educator/performance') ? 'is-active text-dark' : 'text-muted'"
                >
                    <div class="mobile-icon-box">
                        <i class="fas fa-user-graduate" :class="isRouteActive('/educator/performance') ? 'text-success' : ''"></i>
                    </div>
                    <span class="mobile-stage-text">Students</span>
                </Link>

                <Link 
                    :href="route('educator.certificate.edit')" 
                    class="mobile-stage-btn text-decoration-none" 
                    :class="isRouteActive('/educator/certificate') ? 'is-active text-dark' : 'text-muted'"
                >
                    <div class="mobile-icon-box">
                        <i class="fas fa-certificate" :class="isRouteActive('/educator/certificate') ? 'text-success' : ''"></i>
                    </div>
                    <span class="mobile-stage-text">Certificate</span>
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

        <!-- Educator Guided Tutorial Modal -->
        <EducatorTutorialModal 
            :show="isTutorialOpen" 
            :initial-step="tutorialStep"
            @close="closeTutorial" 
            @finish="handleTutorialFinish" 
        />

        <!-- Toast Notifications -->
        <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1055;">
            <div 
                v-for="toast in toasts" 
                :key="toast.id" 
                class="toast show align-items-center text-white border-0 mb-2"
                :class="`bg-${toast.type}`"
                role="alert" 
                aria-live="assertive" 
                aria-atomic="true"
            >
                <div class="d-flex">
                    <div class="toast-body fw-bold">
                        <i class="fas me-2" :class="toast.icon"></i>
                        {{ toast.message }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" @click="removeToast(toast.id)"></button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import EducatorTutorialModal from '@/Components/EducatorTutorialModal.vue';

const page = usePage();
const isProfileMenuOpen = ref(false);
const toasts = ref([]);

// Tutorial Modal State
const isTutorialOpen = ref(false);
const tutorialStep = ref(1);

const openTutorial = (step = 1) => {
    tutorialStep.value = typeof step === 'number' ? step : 1;
    isTutorialOpen.value = true;
};

const closeTutorial = () => {
    isTutorialOpen.value = false;
    localStorage.setItem('win_has_seen_educator_tutorial', 'true');
};

const handleTutorialFinish = () => {
    isTutorialOpen.value = false;
    localStorage.setItem('win_has_seen_educator_tutorial', 'true');
    addToast('Educator CMS Guide completed!', 'success', 'fa-check-circle');
};

const handleTutorialEvent = (e) => {
    const step = e?.detail?.step || 1;
    openTutorial(step);
};

const addToast = (message, type = 'info', icon = 'fa-info-circle') => {
    const id = Date.now() + Math.random();
    toasts.value.push({ id, message, type, icon });
    setTimeout(() => {
        removeToast(id);
    }, 4500);
};

const removeToast = (id) => {
    toasts.value = toasts.value.filter(t => t.id !== id);
};

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
    window.addEventListener('win:open-educator-tutorial', handleTutorialEvent);
});

onUnmounted(() => {
    document.removeEventListener('click', closeMenuOnOutsideClick);
    window.removeEventListener('win:open-educator-tutorial', handleTutorialEvent);
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

/* Nav Tabs */
.educator-nav-tab {
    display: inline-flex;
    align-items: center;
    padding: 7px 16px;
    border-radius: 999px;
    color: #475569;
    font-size: 0.86rem;
    font-weight: 600;
    transition: all 0.2s ease;
}
.educator-nav-tab:hover {
    color: #0d4b38;
    background-color: #f1f5f9;
}
.educator-nav-tab.is-active {
    color: #ffffff;
    background-color: #0d4b38;
    box-shadow: 0 4px 12px rgba(13, 75, 56, 0.2);
}

/* Tutorial Nav Button */
.btn-tutorial-nav {
    background: #f1f5f9;
    color: #1e293b;
    border: 1px solid #e2e8f0;
    font-size: 0.82rem;
    font-weight: 700;
    padding: 6px 12px;
    border-radius: 999px;
    cursor: pointer;
    transition: all 0.2s ease;
}
.btn-tutorial-nav:hover {
    background: #e2e8f0;
    color: #0f172a;
    transform: translateY(-1px);
}

/* Mobile Stages Sub-Nav */
.mobile-stage-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 3px;
    padding: 2px 6px;
    border-radius: 8px;
    transition: all 0.2s ease;
}
.mobile-icon-box {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.95rem;
    color: #64748b;
    transition: all 0.2s ease;
}
.mobile-stage-btn.is-active .mobile-icon-box {
    background-color: #e6f4ea;
    border-color: #a7f3d0;
    color: #0d4b38;
}
.mobile-stage-text {
    font-size: 0.68rem;
    font-weight: 600;
    letter-spacing: -0.2px;
}
</style>
