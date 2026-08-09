<template>
    <div class="d-flex flex-column min-vh-100">
        <!-- Navbar Structure matching raw HTML snippet -->
        <nav class="navbar navbar-expand-lg bg-white shadow-sm py-2">
            <div class="container">
                <Link :href="route('dashboard')" class="navbar-brand">
                    <img src="/assets/images/WINLogo.png" alt="WIN Logo" style="height: 35px; width: 35px; margin-right: 5px;">
                    <span class="fw-bold" style="color: var(--mmsu-green);">WIN e-Travel</span>
                </Link>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item text-center mx-2">
                            <Link
                                :href="route('dashboard')"
                                class="nav-link nav-icon-link"
                                :class="{ 'active': $page.url === '/dashboard' }"
                            >
                                <i class="fas fa-chart-pie nav-icon"></i>
                                <span class="nav-label">Dashboard</span>
                            </Link>
                        </li>
                        <li class="nav-item text-center mx-2">
                            <Link
                                :href="route('foundation.index')"
                                class="nav-link nav-icon-link"
                                :class="{ 'active': $page.url.startsWith('/foundation') }"
                            >
                                <i class="fas fa-book-open nav-icon"></i>
                                <span class="nav-label">Go Beyond Books</span>
                            </Link>
                        </li>
                        <li class="nav-item text-center mx-2">
                            <Link
                                :href="route('towns.index')"
                                class="nav-link nav-icon-link"
                                :class="{ 'active': $page.url.startsWith('/towns') }"
                            >
                                <i class="fas fa-compass nav-icon"></i>
                                <span class="nav-label">Dare to Discover</span>
                            </Link>
                        </li>
                        <li class="nav-item text-center mx-2">
                            <Link
                                :href="route('simulation.index')"
                                class="nav-link nav-icon-link"
                                :class="{ 'active': $page.url.startsWith('/simulation') }"
                            >
                                <i class="fas fa-mountain nav-icon"></i>
                                <span class="nav-label">Adventure Awaits</span>
                            </Link>
                        </li>
                    </ul>

                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center cursor-pointer text-dark" href="#" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle fs-4 me-1"></i>
                            <span id="navUserName">{{ $page.props.auth?.user?.name || 'Trainee' }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
                            <li>
                                <Link :href="route('achievements.index')" class="dropdown-item">
                                    <i class="fas fa-trophy me-2" style="color: #ffd700;"></i>Achievements
                                </Link>
                            </li>
                            <li v-if="$page.props.auth?.user?.role === 'educator' || $page.props.auth?.user?.role === 'admin'">
                                <Link :href="route('educator.dashboard')" class="dropdown-item">
                                    <i class="fas fa-chalkboard-teacher me-2 text-success"></i>Educator CMS
                                </Link>
                            </li>
                            <li v-if="$page.props.auth?.user?.role === 'admin'">
                                <Link :href="route('admin.dashboard')" class="dropdown-item">
                                    <i class="fas fa-user-shield me-2 text-danger"></i>Admin Panel
                                </Link>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <Link :href="route('logout')" method="post" as="button" class="dropdown-item text-danger w-100 text-start">
                                    <i class="fas fa-sign-out-alt me-2"></i>Sign Out
                                </Link>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page content slot -->
        <main class="flex-grow-1">
            <slot />
        </main>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<style scoped>
.nav-icon-link {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 5px 12px !important;
    color: #6c757d;
    transition: all 0.3s ease;
    border-bottom: 3px solid transparent;
}

.nav-icon-link:hover, .nav-icon-link.active {
    color: var(--mmsu-green) !important;
    border-bottom-color: var(--mmsu-green);
}

.nav-icon {
    font-size: 20px;
    margin-bottom: 2px;
}

.nav-label {
    font-size: 0.8rem;
    font-weight: 600;
}
</style>
