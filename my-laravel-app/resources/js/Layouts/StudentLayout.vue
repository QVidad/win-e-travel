<template>
    <div class="d-flex flex-column min-vh-100">
        <nav class="navbar navbar-expand-lg bg-white border-bottom py-2 shadow-sm">
            <div class="container-fluid px-4">
                <!-- Left: Brand Logo -->
                <Link class="navbar-brand d-flex align-items-center fw-bold" :href="route('dashboard')">
                    <img src="/assets/images/WINLogo.png" alt="WIN Logo" style="height: 32px;" class="me-2">
                    <span class="text-success fw-bold" style="color: var(--mmsu-green) !important;">WIN e-Travel</span>
                </Link>

                <!-- Center: Navigation Items with Icons Above Text -->
                <div class="d-none d-md-flex mx-auto align-items-center gap-4">
                    <Link 
                        :href="route('dashboard')" 
                        class="text-decoration-none text-center small" 
                        :class="$page.url === '/dashboard' ? 'text-dark fw-bold opacity-100' : 'text-muted opacity-75'"
                    >
                        <div class="mb-1"><i class="fas fa-chart-pie fa-lg" :class="$page.url === '/dashboard' ? 'text-dark' : ''"></i></div>
                        <div>Dashboard</div>
                    </Link>
                    <Link 
                        :href="route('foundation.index')" 
                        class="text-decoration-none text-center small" 
                        :class="$page.url.startsWith('/foundation') ? 'text-dark fw-bold opacity-100' : 'text-muted opacity-75'"
                    >
                        <div class="mb-1"><i class="fas fa-book-open fa-lg"></i></div>
                        <div>Go Beyond Books</div>
                    </Link>
                    <Link 
                        :href="route('towns.index')" 
                        class="text-decoration-none text-center small" 
                        :class="$page.url.startsWith('/towns') ? 'text-dark fw-bold opacity-100' : 'text-muted opacity-75'"
                    >
                        <div class="mb-1"><i class="fas fa-compass fa-lg"></i></div>
                        <div>Dare to Discover</div>
                    </Link>
                    <Link 
                        :href="route('simulation.index')" 
                        class="text-decoration-none text-center small" 
                        :class="$page.url.startsWith('/simulation') ? 'text-dark fw-bold opacity-100' : 'text-muted opacity-75'"
                    >
                        <div class="mb-1"><i class="fas fa-mountain fa-lg"></i></div>
                        <div>Adventure Awaits</div>
                    </Link>
                </div>

                <!-- Right: User Avatar Dropdown -->
                <div class="dropdown position-relative me-2" :class="{ show: isMenuOpen }">
                    <button 
                        class="btn btn-link text-dark text-decoration-none dropdown-toggle d-flex align-items-center gap-2 border-0 bg-transparent p-0" 
                        type="button" 
                        id="userProfileDropdown" 
                        data-bs-toggle="dropdown" 
                        :aria-expanded="isMenuOpen"
                        @click="toggleMenu"
                    >
                        <div class="avatar-circle bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="fas fa-user"></i>
                        </div>
                        <span class="fw-bold text-dark">{{ $page.props.auth?.user ? $page.props.auth.user.name : 'Queenee' }}</span>
                    </button>
                    
                    <ul 
                        class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2 py-2 px-3 end-0" 
                        :class="{ show: isMenuOpen }"
                        aria-labelledby="userProfileDropdown" 
                        style="min-width: 230px; border-radius: 14px; right: 0; left: auto;"
                    >
                        <li class="pb-2 pt-1 border-bottom mb-2">
                            <div class="fw-bold text-dark fs-6">{{ $page.props.auth?.user ? $page.props.auth.user.name : 'Queenee' }}</div>
                            <div class="text-muted small text-truncate" style="max-width: 190px;">
                                {{ $page.props.auth?.user ? $page.props.auth.user.email : 'qvidad@gmail.com' }}
                            </div>
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
        </nav>

        <!-- Main Content Area -->
        <main class="flex-grow-1">
            <slot />
        </main>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Link } from '@inertiajs/vue3';

const isMenuOpen = ref(false);

const toggleMenu = () => {
    isMenuOpen.value = !isMenuOpen.value;
};

const closeMenuOnOutsideClick = (event) => {
    const dropdown = document.querySelector('.dropdown');
    if (dropdown && !dropdown.contains(event.target)) {
        isMenuOpen.value = false;
    }
};

onMounted(() => {
    document.addEventListener('click', closeMenuOnOutsideClick);
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
});
</script>
