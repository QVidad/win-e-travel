<template>
    <EducatorLayout>
        <!-- Dark Green Hero Banner -->
        <div 
            class="card border-0 text-white p-4 p-md-5 mb-4 shadow-sm" 
            style="background: linear-gradient(135deg, #0a472e 0%, #1a5f7a 100%); border-radius: 20px;"
        >
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 class="display-6 fw-bold mb-2 text-white" style="letter-spacing: -0.5px;">
                        <i class="fas fa-desktop me-2 opacity-75"></i>Faculty CMS & Curriculum Control
                    </h1>
                    <p class="mb-0 text-white fst-italic fs-6 opacity-90">
                        "Manage curriculum modules, assessment question banks, and monitor student completion progress."
                    </p>
                </div>

                <div class="d-flex gap-2">
                    <Link :href="route('educator.modules.index')" class="btn btn-warning px-4 py-2 rounded-pill fw-bold text-dark shadow-sm border-0">
                        <i class="fas fa-edit me-1"></i> Edit Content
                    </Link>
                    <Link :href="route('educator.quizzes.index')" class="btn btn-outline-light px-4 py-2 rounded-pill fw-bold shadow-sm">
                        <i class="fas fa-tasks me-1"></i> Quiz Banks
                    </Link>
                </div>
            </div>
        </div>

        <!-- Content Activity & Status Breakdown -->
        <div class="row g-4 mb-4">
            <!-- Recent Module Edits -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 bg-white h-100">
                    <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center border-bottom">
                        <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                            <i class="fas fa-history text-success" style="color: #0d4b38 !important;"></i>
                            <span>Recent Content Activity</span>
                        </h5>
                        <Link :href="route('educator.modules.index')" class="btn btn-sm btn-light rounded-pill px-3 fw-bold">View All</Link>
                    </div>
                    <div class="card-body p-0">
                        <div v-if="!recentModules || recentModules.length === 0" class="p-4 text-center text-muted">
                            No recent updates logged yet.
                        </div>
                        <div v-else class="list-group list-group-flush">
                            <div 
                                v-for="mod in recentModules" 
                                :key="mod.id" 
                                class="list-group-item p-3 px-4 d-flex justify-content-between align-items-center bg-transparent"
                            >
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-light p-2.5 text-center text-dark" style="width: 40px; height: 40px;">
                                        <i :class="mod.icon || 'fas fa-book'"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0">{{ mod.title }}</h6>
                                        <small class="text-muted fs-8">
                                            Last modified by <strong>{{ mod.updated_by ? mod.updated_by.name : 'System' }}</strong>
                                        </small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <button 
                                        @click.prevent="toggleStatus(mod)"
                                        class="btn btn-sm rounded-pill px-3 py-1 fw-bold border-0 shadow-sm transition-all text-white" 
                                        :class="mod.status === 'published' ? 'btn-success' : 'btn-warning text-dark'"
                                        :disabled="mod.isToggling"
                                        title="Click to toggle Published/Draft"
                                    >
                                        <span v-if="mod.isToggling"><i class="fas fa-spinner fa-spin me-1"></i></span>
                                        {{ mod.status === 'published' ? 'Published' : 'Draft' }}
                                    </button>
                                    <Link :href="route('educator.modules.edit', mod.id)" class="btn btn-sm btn-outline-secondary rounded-circle ms-1">
                                        <i class="fas fa-edit"></i>
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Controls Panel -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 bg-white h-100">
                    <div class="card-header bg-white py-3 px-4 border-bottom">
                        <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                            <i class="fas fa-rocket text-warning"></i>
                            <span>Quick Educator Actions</span>
                        </h5>
                    </div>
                    <div class="card-body p-4 d-flex flex-column gap-3">
                        <Link :href="route('educator.modules.index')" class="p-3 rounded-4 bg-light text-decoration-none text-dark d-flex align-items-center gap-3 hover-lift border">
                            <div class="rounded-circle text-white p-2.5 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background-color: #0d4b38;">
                                <i class="fas fa-book-open"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0">Course Modules</h6>
                                <small class="text-muted">Edit text, media & draft states</small>
                            </div>
                        </Link>

                        <Link :href="route('educator.quizzes.index')" class="p-3 rounded-4 bg-light text-decoration-none text-dark d-flex align-items-center gap-3 hover-lift border">
                            <div class="rounded-circle text-white p-2.5 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background-color: #0d4b38;">
                                <i class="fas fa-tasks"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0">Quiz Question Bank</h6>
                                <small class="text-muted">Add multiple-choice items</small>
                            </div>
                        </Link>

                        <Link :href="route('educator.performance.index')" class="p-3 rounded-4 bg-light text-decoration-none text-dark d-flex align-items-center gap-3 hover-lift border">
                            <div class="rounded-circle text-white p-2.5 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background-color: #0d4b38;">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0">Student Analytics</h6>
                                <small class="text-muted">Track progress & retakes</small>
                            </div>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </EducatorLayout>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import EducatorLayout from '@/Layouts/EducatorLayout.vue';

defineProps({
    stats: {
        type: Object,
        default: () => ({ totalModules: 0, totalQuestions: 0, totalStudentAttempts: 0, publishedModulesCount: 0, draftModulesCount: 0 }),
    },
    recentModules: {
        type: Array,
        default: () => [],
    },
});

// Using imported router directly

const toggleStatus = (mod) => {
    mod.isToggling = true;
    router.patch(window.route('educator.modules.toggle', mod.id), {}, {
        preserveScroll: true,
        onFinish: () => {
            mod.isToggling = false;
        }
    });
};
</script>

<style scoped>
.fs-8 {
    font-size: 0.7rem;
}
.hover-lift:hover {
    transform: translateY(-2px);
    transition: all 0.2s ease-in-out;
}
</style>
