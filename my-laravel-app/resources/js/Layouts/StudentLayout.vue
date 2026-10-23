<template>
    <div class="d-flex flex-column min-vh-100 bg-light">
        <nav class="navbar navbar-expand-lg bg-white border-bottom py-2 shadow-sm">
            <div class="container-fluid px-4">
                <!-- Left: Brand Logo -->
                <Link class="navbar-brand d-flex align-items-center fw-bold" :href="route('dashboard')">
                    <img src="/assets/images/WINLogo.png" alt="WIN Logo" style="height: 32px;" class="me-2">
                    <span class="text-success fw-bold" style="color: var(--mmsu-green) !important;">WIN e-Travel</span>
                </Link>

                <!-- Center: Navigation Items with Gamified Stage Indicators -->
                <div class="d-none d-lg-flex mx-auto align-items-center gap-2 gap-xl-3 py-1">
                    <!-- Dashboard -->
                    <Link 
                        :href="route('dashboard')" 
                        class="nav-stage-item text-decoration-none" 
                        :class="{ 'is-active': $page.url === '/dashboard' }"
                    >
                        <div class="stage-nav-pill pill-dashboard">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <div class="stage-text-group">
                            <span class="stage-tag tag-dashboard">Home</span>
                            <span class="stage-title">Dashboard</span>
                        </div>
                    </Link>

                    <div class="nav-stage-divider"></div>

                    <!-- Stage 1: Go Beyond Books (Always Unlocked) -->
                    <Link 
                        :href="route('go-beyond-books.index')" 
                        class="nav-stage-item is-unlocked text-decoration-none" 
                        :class="{ 'is-active': $page.url.startsWith('/go-beyond-books') }"
                        title="Stage 1: Foundation Modules (Unlocked)"
                    >
                        <div class="stage-nav-pill pill-foundation">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <div class="stage-text-group">
                            <span class="stage-tag tag-foundation">Stage 1</span>
                            <span class="stage-title">Go Beyond Books</span>
                        </div>
                    </Link>

                    <!-- Stage 2: Dare to Discover -->
                    <Link 
                        v-if="$page.props.isDareToDiscoverUnlocked"
                        :href="route('dare-to-discover.index')" 
                        class="nav-stage-item is-unlocked text-decoration-none" 
                        :class="{ 'is-active': $page.url.startsWith('/dare-to-discover') }"
                        title="Stage 2: Town Chapters (Unlocked)"
                    >
                        <div class="stage-nav-pill pill-discover">
                            <i class="fas fa-compass"></i>
                        </div>
                        <div class="stage-text-group">
                            <span class="stage-tag tag-discover">Stage 2</span>
                            <span class="stage-title">Dare to Discover</span>
                        </div>
                    </Link>

                    <button 
                        v-else
                        type="button"
                        class="nav-stage-item is-locked text-decoration-none" 
                        title="Click to see how to unlock Stage 2"
                        @click="handleLockedClick(2)"
                    >
                        <div class="stage-nav-pill pill-discover position-relative is-locked-pill">
                            <i class="fas fa-compass"></i>
                            <span class="pill-lock-pin" title="Locked">
                                <i class="fas fa-lock"></i>
                            </span>
                        </div>
                        <div class="stage-text-group">
                            <span class="stage-tag tag-locked">Stage 2</span>
                            <span class="stage-title">Dare to Discover</span>
                        </div>
                    </button>

                    <!-- Stage 3: Adventure Awaits -->
                    <Link 
                        v-if="$page.props.isAdventureAwaitsUnlocked"
                        :href="route('adventure-awaits.index')" 
                        class="nav-stage-item is-unlocked text-decoration-none" 
                        :class="{ 'is-active': $page.url.startsWith('/adventure-awaits') }"
                        title="Stage 3: Virtual Tour & Speech Evaluation (Unlocked)"
                    >
                        <div class="stage-nav-pill pill-adventure">
                            <i class="fas fa-mountain"></i>
                        </div>
                        <div class="stage-text-group">
                            <span class="stage-tag tag-adventure">Stage 3</span>
                            <span class="stage-title">Adventure Awaits</span>
                        </div>
                    </Link>

                    <button 
                        v-else
                        type="button"
                        class="nav-stage-item is-locked text-decoration-none" 
                        title="Click to see how to unlock Stage 3"
                        @click="handleLockedClick(3)"
                    >
                        <div class="stage-nav-pill pill-adventure position-relative is-locked-pill">
                            <i class="fas fa-mountain"></i>
                            <span class="pill-lock-pin" title="Locked">
                                <i class="fas fa-lock"></i>
                            </span>
                        </div>
                        <div class="stage-text-group">
                            <span class="stage-tag tag-locked">Stage 3</span>
                            <span class="stage-title">Adventure Awaits</span>
                        </div>
                    </button>
                </div>

                <!-- Right: Tutorial Replay Button & User Avatar Dropdown -->
                <div class="d-flex align-items-center gap-2">
                    <Link 
                        v-if="$page.props.auth?.user?.role === 'educator' || $page.props.auth?.user?.role === 'admin'"
                        :href="route('educator.dashboard')"
                        class="btn-tutorial-nav d-inline-flex align-items-center gap-1 shadow-xs text-decoration-none"
                        style="background: #e8f5e9; border: 1px solid #c8e6c9; color: #0d4b38;"
                        title="Return to Educator CMS"
                    >
                        <i class="fas fa-chalkboard-teacher text-success"></i>
                        <span class="d-none d-sm-inline">Back to CMS</span>
                    </Link>

                    <button 
                        type="button" 
                        class="btn-tutorial-nav d-inline-flex align-items-center gap-1 shadow-xs"
                        @click="openTutorial"
                        title="Interactive System Tutorial"
                    >
                        <i class="fas fa-gamepad text-warning"></i>
                        <span class="d-none d-sm-inline">Tutorial</span>
                    </button>

                    <div class="dropdown position-relative me-1 me-sm-2" :class="{ show: isMenuOpen }">
                        <button 
                            class="btn btn-link text-dark text-decoration-none dropdown-toggle d-flex align-items-center gap-2 border-0 bg-transparent p-0" 
                            type="button" 
                            id="userProfileDropdown" 
                            data-bs-toggle="dropdown" 
                            :aria-expanded="isMenuOpen"
                            @click="toggleMenu"
                        >
                        <div class="avatar-circle bg-success text-white rounded-circle d-flex align-items-center justify-content-center overflow-hidden shadow-sm flex-shrink-0" style="width: 34px; height: 34px;">
                            <img v-if="$page.props.auth?.user?.avatar" :src="$page.props.auth.user.avatar" alt="Avatar" class="w-100 h-100 object-fit-cover">
                            <i v-else class="fas fa-user"></i>
                        </div>
                        <span class="fw-bold text-dark d-none d-sm-inline text-truncate" style="max-width: 140px;">
                            {{ $page.props.auth?.user ? $page.props.auth.user.name : 'Queenee' }}
                        </span>
                    </button>
                    
                    <ul 
                        class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2 py-2 px-3 end-0" 
                        :class="{ show: isMenuOpen }"
                        aria-labelledby="userProfileDropdown" 
                        style="min-width: 230px; border-radius: 14px; right: 0; left: auto; z-index: 1050;"
                    >
                        <li class="pb-2 pt-1 border-bottom mb-2">
                            <div class="fw-bold text-dark fs-6">{{ $page.props.auth?.user ? $page.props.auth.user.name : 'Queenee' }}</div>
                            <div class="text-muted small text-truncate" style="max-width: 190px;">
                                {{ $page.props.auth?.user ? $page.props.auth.user.email : 'qvidad@gmail.com' }}
                            </div>
                            <span v-if="$page.props.auth?.user?.role === 'educator'" class="badge bg-success bg-opacity-10 text-success border border-success-subtle rounded-pill mt-1 fs-8">Educator (Student View)</span>
                            <span v-else-if="$page.props.auth?.user?.role === 'admin'" class="badge bg-danger bg-opacity-10 text-danger border border-danger-subtle rounded-pill mt-1 fs-8">Admin (Student View)</span>
                            <span v-else class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle rounded-pill mt-1 fs-8">Active Student</span>
                        </li>
                        
                        <li class="my-1">
                            <Link :href="route('profile.edit')" class="dropdown-item rounded-3 py-2 px-2 d-flex align-items-center text-dark fw-medium" @click="isMenuOpen = false">
                                <i class="fas fa-user-cog text-primary me-3 fa-lg" style="width: 20px;"></i>
                                <span>Profile Settings</span>
                            </Link>
                        </li>

                        <li class="my-1">
                            <Link :href="route('achievements.index')" class="dropdown-item rounded-3 py-2 px-2 d-flex align-items-center text-dark fw-medium" @click="isMenuOpen = false">
                                <i class="fas fa-trophy text-warning me-3 fa-lg" style="width: 20px;"></i>
                                <span>View Achievements</span>
                            </Link>
                        </li>

                        <li v-if="$page.props.auth?.user?.role === 'educator' || $page.props.auth?.user?.role === 'admin'" class="my-1">
                            <Link :href="route('educator.dashboard')" class="dropdown-item rounded-3 py-2 px-2 d-flex align-items-center text-success fw-medium" @click="isMenuOpen = false">
                                <i class="fas fa-chalkboard-teacher me-3 fa-lg" style="width: 20px;"></i>
                                <span>Educator CMS</span>
                            </Link>
                        </li>

                        <li v-if="$page.props.auth?.user?.role === 'admin'" class="my-1">
                            <Link :href="route('admin.dashboard')" class="dropdown-item rounded-3 py-2 px-2 d-flex align-items-center text-danger fw-medium" @click="isMenuOpen = false">
                                <i class="fas fa-user-shield me-3 fa-lg" style="width: 20px;"></i>
                                <span>Admin Panel</span>
                            </Link>
                        </li>
                        
                        <li class="my-1">
                            <button 
                                type="button" 
                                class="dropdown-item rounded-3 py-2 px-2 d-flex align-items-center text-dark fw-medium border-0 bg-transparent w-100 text-start" 
                                @click="openTutorial(); isMenuOpen = false"
                            >
                                <i class="fas fa-gamepad text-warning me-3 fa-lg" style="width: 20px;"></i>
                                <span>Guide Tutorial</span>
                            </button>
                        </li>

                        <li class="my-1"><hr class="dropdown-divider my-1"></li>
                        
                        <li class="my-1">
                            <Link :href="route('logout')" method="post" as="button" class="dropdown-item rounded-3 py-2 px-2 d-flex align-items-center text-danger fw-medium w-100 text-start" @click="isMenuOpen = false">
                                <i class="fas fa-sign-out-alt me-3 fa-lg" style="width: 20px;"></i>
                                <span>Sign Out</span>
                            </Link>
                        </li>
                    </ul>
                </div>
            </div>
            </div>
        </nav>

        <!-- Sub-nav for Mobile Screens with Distinct Stage Badges -->
        <div class="bg-white border-bottom d-lg-none py-2 px-1 shadow-xs">
            <div class="d-flex justify-content-between align-items-center w-100">
                <!-- Mobile Dashboard -->
                <Link 
                    :href="route('dashboard')" 
                    class="mobile-stage-btn text-decoration-none" 
                    :class="$page.url === '/dashboard' ? 'is-active text-dark' : 'text-muted'"
                >
                    <div class="mobile-icon-box">
                        <i class="fas fa-chart-pie text-success"></i>
                    </div>
                    <span class="mobile-stage-text">Dashboard</span>
                </Link>

                <!-- Mobile Stage 1 -->
                <Link 
                    :href="route('go-beyond-books.index')" 
                    class="mobile-stage-btn text-decoration-none" 
                    :class="$page.url.startsWith('/go-beyond-books') ? 'is-active text-dark' : 'text-dark'"
                >
                    <div class="mobile-icon-box">
                        <i class="fas fa-book-open text-foundation"></i>
                    </div>
                    <span class="mobile-stage-text">Books</span>
                </Link>

                <!-- Mobile Stage 2 -->
                <Link 
                    v-if="$page.props.isDareToDiscoverUnlocked"
                    :href="route('dare-to-discover.index')" 
                    class="mobile-stage-btn text-decoration-none" 
                    :class="$page.url.startsWith('/dare-to-discover') ? 'is-active text-dark' : 'text-dark'"
                >
                    <div class="mobile-icon-box">
                        <i class="fas fa-compass text-discover"></i>
                    </div>
                    <span class="mobile-stage-text">Discover</span>
                </Link>
                <button 
                    v-else
                    type="button"
                    class="mobile-stage-btn is-locked border-0 bg-transparent p-0" 
                    @click="handleLockedClick(2)"
                >
                    <div class="mobile-icon-box position-relative">
                        <i class="fas fa-compass text-secondary"></i>
                        <span class="mobile-lock-pin">
                            <i class="fas fa-lock"></i>
                        </span>
                    </div>
                    <span class="mobile-stage-text text-muted">Discover</span>
                </button>

                <!-- Mobile Stage 3 -->
                <Link 
                    v-if="$page.props.isAdventureAwaitsUnlocked"
                    :href="route('adventure-awaits.index')" 
                    class="mobile-stage-btn text-decoration-none" 
                    :class="$page.url.startsWith('/adventure-awaits') ? 'is-active text-dark' : 'text-dark'"
                >
                    <div class="mobile-icon-box">
                        <i class="fas fa-mountain text-adventure"></i>
                    </div>
                    <span class="mobile-stage-text">Adventure</span>
                </Link>
                <button 
                    v-else
                    type="button"
                    class="mobile-stage-btn is-locked border-0 bg-transparent p-0" 
                    @click="handleLockedClick(3)"
                >
                    <div class="mobile-icon-box position-relative">
                        <i class="fas fa-mountain text-secondary"></i>
                        <span class="mobile-lock-pin">
                            <i class="fas fa-lock"></i>
                        </span>
                    </div>
                    <span class="mobile-stage-text text-muted">Adventure</span>
                </button>
            </div>
        </div>

        <!-- Main Content Area -->
        <main class="flex-grow-1 p-3 p-md-4">
            <div class="container-fluid max-w-7xl">
                <!-- Educator / Admin Preview Notice Banner -->
                <div 
                    v-if="$page.props.auth?.user?.role === 'educator' || $page.props.auth?.user?.role === 'admin'" 
                    class="alert d-flex align-items-center justify-content-between flex-wrap gap-2 py-2 px-3 rounded-4 shadow-xs mb-3 border-0"
                    style="background: #e8f5e9; color: #0a472e;"
                >
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-eye text-success fs-5"></i>
                        <span class="small fw-semibold">
                            <strong>Educator Preview Mode:</strong> All Stages, Chapters, and Simulations are unlocked for UI & content inspection.
                        </span>
                    </div>
                    <Link :href="route('educator.dashboard')" class="btn btn-sm btn-success rounded-pill px-3 fw-bold" style="background-color: #0d4b38; border: none;">
                        <i class="fas fa-arrow-left me-1"></i> Return to CMS
                    </Link>
                </div>

                <slot />
            </div>
        </main>
        <!-- Locked Stage Notification Modal -->
        <div 
            v-if="lockedStageModal.show" 
            class="modal fade show d-block" 
            tabindex="-1" 
            role="dialog"
            aria-modal="true"
            style="background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(4px); z-index: 1065;" 
            @click.self="closeLockedModal"
        >
            <div class="modal-dialog modal-dialog-centered" style="max-width: 440px;">
                <div class="modal-content rounded-4 border-0 shadow-lg p-2 p-sm-3 position-relative overflow-hidden">
                    <!-- Close button in top-right -->
                    <button 
                        type="button" 
                        class="btn-close position-absolute top-0 end-0 m-3 z-3" 
                        aria-label="Close" 
                        @click="closeLockedModal"
                    ></button>

                    <div class="modal-body text-center p-3 p-sm-4">
                        <!-- Stage Graphic with Corner Lock Pin (Mirrors Navbar) -->
                        <div class="position-relative d-inline-block mb-3">
                            <div 
                                class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" 
                                style="width: 76px; height: 76px; background: #f1f5f9; border: 2px dashed #cbd5e1;"
                            >
                                <i class="fas fa-2x" :class="[lockedStageModal.stageIcon, lockedStageModal.themeColorClass]"></i>
                            </div>
                            <div 
                                class="position-absolute bottom-0 end-0 rounded-circle shadow-sm bg-danger text-white d-flex align-items-center justify-content-center border border-2 border-white" 
                                style="width: 30px; height: 30px;"
                                title="Locked"
                            >
                                <i class="fas fa-lock" style="font-size: 0.75rem;"></i>
                            </div>
                        </div>

                        <div class="badge rounded-pill bg-danger-subtle text-danger border border-danger-subtle px-3 py-1 mb-2 fw-bold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.7rem;">
                            Stage Locked
                        </div>

                        <h4 class="fw-bold text-dark mb-1">
                            {{ lockedStageModal.stageTitle }}
                        </h4>
                        <p class="text-muted small mb-3">
                            Stage {{ lockedStageModal.stageNumber }} is currently locked.
                        </p>

                        <!-- Prerequisite Explanation Box -->
                        <div class="p-3 rounded-3 bg-light border text-start mb-4">
                            <div class="d-flex align-items-center gap-2 mb-2 text-secondary small fw-bold text-uppercase">
                                <i class="fas fa-key text-warning"></i> How to Unlock
                            </div>
                            <p class="mb-0 text-dark small" style="line-height: 1.55;">
                                {{ lockedStageModal.description }}
                            </p>
                        </div>

                        <!-- Direct Action Buttons -->
                        <div class="d-grid gap-2">
                            <Link 
                                :href="route('dashboard')" 
                                class="btn btn-success fw-bold py-2 rounded-pill shadow-sm d-flex align-items-center justify-content-center gap-2 text-white"
                                style="background-color: var(--mmsu-green, #0a472e); border-color: var(--mmsu-green, #0a472e);"
                                @click="closeLockedModal"
                            >
                                <i class="fas fa-chart-pie"></i>
                                <span>Back to Dashboard</span>
                            </Link>
                            <button 
                                type="button" 
                                class="btn btn-outline-secondary py-2 rounded-pill fw-medium border-0 text-muted" 
                                @click="closeLockedModal"
                            >
                                Got it, I'll complete it later
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Guided Tutorial Modal Component -->
        <GuidedTutorialModal 
            :show="isTutorialOpen" 
            :initial-step="tutorialStep"
            @close="closeTutorial" 
            @finish="handleTutorialFinish" 
        />

        <!-- Toast Container -->
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
import { ref, onMounted, onUnmounted, watch } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import GuidedTutorialModal from '@/Components/GuidedTutorialModal.vue';

const page = usePage();
const isMenuOpen = ref(false);
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
    localStorage.setItem('win_has_seen_tutorial', 'true');
};

const handleTutorialFinish = () => {
    isTutorialOpen.value = false;
    localStorage.setItem('win_has_seen_tutorial', 'true');
    addToast('Tutorial complete! Have a great journey.', 'success', 'fa-rocket');
};

// Locked Stage Modal State
const lockedStageModal = ref({
    show: false,
    stageNumber: 2,
    stageTitle: '',
    stageIcon: '',
    themeColorClass: '',
    description: '',
    requiredStageTitle: '',
    requiredStageRoute: '',
});

const handleLockedClick = (stage, event) => {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    if (stage === 2) {
        lockedStageModal.value = {
            show: true,
            stageNumber: 2,
            stageTitle: 'Dare to Discover',
            stageIcon: 'fa-compass',
            themeColorClass: 'text-discover',
            description: 'You need to finish all Foundation Modules in Stage 1: Go Beyond Books to unlock Town Chapters and explore cultural destinations.',
            requiredStageTitle: 'Stage 1: Go Beyond Books',
            requiredStageRoute: route('go-beyond-books.index'),
        };
        addToast('Stage 2 is locked! Complete Stage 1 (Go Beyond Books) first.', 'warning', 'fa-lock');
    } else if (stage === 3) {
        lockedStageModal.value = {
            show: true,
            stageNumber: 3,
            stageTitle: 'Adventure Awaits',
            stageIcon: 'fa-mountain',
            themeColorClass: 'text-adventure',
            description: 'You need to complete the Town Chapters in Stage 2: Dare to Discover to unlock the final virtual tour guiding simulation.',
            requiredStageTitle: 'Stage 2: Dare to Discover',
            requiredStageRoute: route('dare-to-discover.index'),
        };
        addToast('Stage 3 is locked! Complete Stage 2 (Dare to Discover) first.', 'warning', 'fa-lock');
    }
};

const closeLockedModal = () => {
    lockedStageModal.value.show = false;
};

const addToast = (message, type = 'info', icon = 'fa-info-circle') => {
    const id = Date.now() + Math.random();
    toasts.value.push({ id, message, type, icon });
    setTimeout(() => {
        removeToast(id);
    }, 5000);
};

const removeToast = (id) => {
    toasts.value = toasts.value.filter(t => t.id !== id);
};

watch(() => page.props.flash?.gamification, (newVal) => {
    if (newVal) {
        if (newVal.leveled_up) {
            addToast(`Level Up! You are now Level ${newVal.new_level}!`, 'success', 'fa-arrow-up');
        }
        if (newVal.unlocked_badges && newVal.unlocked_badges.length > 0) {
            newVal.unlocked_badges.forEach(badge => {
                addToast(`Badge Unlocked: ${badge.title}`, 'warning', 'fa-trophy');
            });
        }
        if (newVal.xp_awarded > 0) {
            addToast(`You earned ${newVal.xp_awarded} XP!`, 'info', 'fa-star');
        }
    }
}, { immediate: true });

onMounted(() => {
    window.addEventListener('gamification-event', (e) => {
        const gamification = e.detail;
        if (gamification) {
            if (gamification.leveled_up) {
                addToast(`Level Up! You are now Level ${gamification.new_level}!`, 'success', 'fa-arrow-up');
            }
            if (gamification.unlocked_badges && gamification.unlocked_badges.length > 0) {
                gamification.unlocked_badges.forEach(badge => {
                    addToast(`Badge Unlocked: ${badge.title}`, 'warning', 'fa-trophy');
                });
            }
            if (gamification.xp_awarded > 0) {
                addToast(`You earned ${gamification.xp_awarded} XP!`, 'info', 'fa-star');
            }
        }
    });
});

const toggleMenu = () => {
    isMenuOpen.value = !isMenuOpen.value;
};

const closeMenuOnOutsideClick = (event) => {
    const dropdown = document.querySelector('.dropdown');
    if (dropdown && !dropdown.contains(event.target)) {
        isMenuOpen.value = false;
    }
};

const handleTutorialEvent = (e) => {
    const step = e?.detail?.step || 1;
    openTutorial(step);
};

onMounted(() => {
    document.addEventListener('click', closeMenuOnOutsideClick);
    window.addEventListener('win:open-tutorial', handleTutorialEvent);

    if (window.bootstrap?.Dropdown) {
        const dropdownElementList = document.querySelectorAll('.dropdown-toggle');
        [...dropdownElementList].forEach(dropdownToggleEl => {
            try {
                new window.bootstrap.Dropdown(dropdownToggleEl);
            } catch (e) {
                // Fallback to Vue reactive state
            }
        });
    }
});

onUnmounted(() => {
    document.removeEventListener('click', closeMenuOnOutsideClick);
    window.removeEventListener('win:open-tutorial', handleTutorialEvent);
});
</script>

<style scoped>
.btn-tutorial-nav {
    background: #f1f5f9;
    color: #1e293b;
    border: 1px solid #cbd5e1;
    border-radius: 9999px;
    padding: 5px 12px;
    font-size: 0.8rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s ease;
}
.btn-tutorial-nav:hover {
    background: #e2e8f0;
    border-color: #94a3b8;
    color: #0f172a;
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
}
.max-w-7xl {
    max-width: 80rem;
    margin-left: auto;
    margin-right: auto;
}

/* Nav Stage Divider */
.nav-stage-divider {
    width: 1px;
    height: 28px;
    background: #e2e8f0;
    margin: 0 4px;
}

/* Stage Navigation Item */
.nav-stage-item {
    display: inline-flex;
    align-items: center;
    gap: 0.65rem;
    padding: 0.4rem 0.85rem;
    border-radius: 50rem;
    border: 1.5px solid transparent;
    background: transparent;
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    position: relative;
    user-select: none;
}

.nav-stage-item:hover:not(.is-locked) {
    background: #f8fafc;
    border-color: #e2e8f0;
    transform: translateY(-1px);
}

/* Active Page State */
.nav-stage-item.is-active {
    background: #ffffff;
    border-color: #cbd5e1;
    box-shadow: 0 3px 10px rgba(15, 23, 42, 0.06);
}

.nav-stage-item.is-active:has(.pill-dashboard) {
    border-color: #a7f3d0;
    background: #f0fdf4;
}

.nav-stage-item.is-active:has(.pill-foundation) {
    border-color: #c4b5fd;
    background: #faf5ff;
}

.nav-stage-item.is-active:has(.pill-discover) {
    border-color: #fbcfe8;
    background: #fdf2f8;
}

.nav-stage-item.is-active:has(.pill-adventure) {
    border-color: #bae6fd;
    background: #f0f9ff;
}

/* Stage Pill Icon Container */
.stage-nav-pill {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
    flex-shrink: 0;
    transition: all 0.2s ease;
}

.pill-dashboard {
    background: #ecfdf5;
    color: #059669;
}

.pill-foundation {
    background: #f3e8ff;
    color: #7c3aed;
    box-shadow: 0 2px 6px rgba(124, 58, 237, 0.15);
}

.pill-discover {
    background: #fce7f3;
    color: #db2777;
    box-shadow: 0 2px 6px rgba(219, 39, 119, 0.15);
}

.pill-adventure {
    background: #e0f2fe;
    color: #0284c7;
    box-shadow: 0 2px 6px rgba(2, 132, 199, 0.15);
}

/* Locked Pill & Item Styles */
.nav-stage-item.is-locked {
    cursor: not-allowed;
    opacity: 0.65;
}

button.nav-stage-item {
    font-family: inherit;
    font-size: inherit;
    line-height: inherit;
    text-align: left;
    border: 1.5px solid transparent;
    background: transparent;
}

button.mobile-stage-btn {
    font-family: inherit;
    color: inherit;
}

.nav-stage-item.is-locked:hover {
    background: transparent;
    border-color: transparent;
    transform: none;
}

.stage-nav-pill.is-locked-pill {
    background: #f1f5f9 !important;
    color: #94a3b8 !important;
    box-shadow: none !important;
    border: 1px dashed #cbd5e1;
}

/* Single Neat Lock Pin Badge on Pill */
.pill-lock-pin {
    position: absolute;
    bottom: -3px;
    right: -3px;
    width: 15px;
    height: 15px;
    border-radius: 50%;
    background: #64748b;
    color: #ffffff;
    font-size: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1.5px solid #ffffff;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
}

.mobile-lock-pin {
    position: absolute;
    bottom: 0;
    right: -4px;
    width: 13px;
    height: 13px;
    border-radius: 50%;
    background: #64748b;
    color: #ffffff;
    font-size: 0.45rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #ffffff;
}

/* Text Groups */
.stage-text-group {
    display: flex;
    flex-direction: column;
    line-height: 1.15;
}

.stage-tag {
    font-size: 0.65rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.tag-dashboard { color: #059669; }
.tag-foundation { color: #7c3aed; }
.tag-discover { color: #db2777; }
.tag-adventure { color: #0284c7; }
.tag-locked { color: #94a3b8; }

.stage-title {
    font-size: 0.82rem;
    font-weight: 600;
    color: #1e293b;
    white-space: nowrap;
}

.nav-stage-item.is-locked .stage-title {
    color: #94a3b8;
}

.nav-stage-item.is-active .stage-title {
    color: #0f172a;
    font-weight: 700;
}

/* Mobile Stage Styles */
.mobile-stage-btn {
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    justify-content: center !important;
    text-align: center !important;
    padding: 6px 2px !important;
    border-radius: 8px;
    font-size: 0.72rem;
    line-height: 1.1;
    transition: all 0.2s ease;
    flex: 1 1 0px;
    min-width: 0;
    max-width: 25%;
    text-decoration: none;
    background: transparent;
    border: none;
    outline: none;
}

.mobile-icon-box {
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.15rem;
    margin-bottom: 2px;
    position: relative;
    flex-shrink: 0;
}

.mobile-stage-text {
    font-size: 0.72rem;
    font-weight: 500;
    white-space: nowrap;
    text-overflow: ellipsis;
    overflow: hidden;
    max-width: 100%;
    display: block;
}

.mobile-stage-btn.is-active {
    background: #f8fafc;
    border-bottom: 2.5px solid var(--mmsu-green, #0a472e) !important;
}

.mobile-stage-btn.is-active .mobile-stage-text {
    font-weight: 700;
    color: #0f172a;
}

.mobile-stage-btn.is-locked {
    cursor: not-allowed;
    opacity: 0.6;
}

.text-foundation { color: #7c3aed !important; }
.text-discover { color: #db2777 !important; }
.text-adventure { color: #0284c7 !important; }
</style>
