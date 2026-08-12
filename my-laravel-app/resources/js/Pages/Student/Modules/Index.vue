<template>
    <StudentLayout>
        <div class="py-4 bg-light min-vh-100">
            <div class="container max-w-6xl">
                <!-- Hero Header -->
                <div 
                    class="card border-0 text-white p-4 p-md-5 mb-4 shadow-sm" 
                    style="background: linear-gradient(135deg, #0d4b38 0%, #155e46 100%); border-radius: 20px;"
                >
                    <span class="badge bg-warning text-dark px-3 py-1 rounded-pill fw-bold mb-2 align-self-start">Published Learning Paths</span>
                    <h1 class="display-6 fw-bold text-white mb-2" style="font-weight: 800;">
                        Curriculum & Town Chapters
                    </h1>
                    <p class="mb-0 text-white fst-italic fs-6 opacity-90">
                        "Explore published foundation modules and municipality tour guiding chapters."
                    </p>
                </div>

                <!-- Modules Grid -->
                <div v-if="!modules || modules.length === 0" class="card border-0 shadow-sm rounded-4 bg-white p-5 text-center text-muted">
                    <i class="fas fa-book-open fa-3x mb-3 text-light"></i>
                    <h5 class="fw-bold text-dark mb-1">No Published Modules Available</h5>
                    <p class="mb-0">Check back soon as faculty members publish new course content.</p>
                </div>

                <div v-else class="row g-4">
                    <div 
                        v-for="mod in modules" 
                        :key="mod.id" 
                        class="col-md-6 col-lg-4"
                    >
                        <div class="card border-0 shadow-sm rounded-4 h-100 bg-white overflow-hidden transition-all hover-lift">
                            <div class="position-relative" style="height: 140px; background-color: #0d4b38;">
                                <img 
                                    v-if="mod.cover_image" 
                                    :src="mod.cover_image" 
                                    alt="Cover" 
                                    class="w-100 h-100 object-fit-cover opacity-50"
                                >
                                <div class="position-absolute top-0 start-0 p-3">
                                    <span class="badge bg-warning text-dark rounded-pill fw-bold fs-8">
                                        {{ mod.type === 'foundation' ? 'Foundation' : 'Town Chapter' }}
                                    </span>
                                </div>
                            </div>

                            <div class="card-body p-4 d-flex flex-column">
                                <h5 class="fw-bold text-dark mb-1">{{ mod.title }}</h5>
                                <p v-if="mod.subtitle" class="text-muted small fst-italic mb-2">{{ mod.subtitle }}</p>
                                <p class="text-muted small mb-3 flex-grow-1" style="min-height: 40px;">
                                    {{ mod.description }}
                                </p>

                                <div class="pt-3 border-top mt-auto d-flex justify-content-between align-items-center">
                                    <small class="text-muted fw-bold">
                                        <i class="fas fa-question-circle text-primary me-1"></i>
                                        {{ mod.questions ? mod.questions.length : 0 }} Qs
                                    </small>

                                    <Link 
                                        :href="route('student.modules.show', mod.id)" 
                                        class="btn btn-sm text-white rounded-pill px-3 fw-bold" 
                                        style="background-color: #0d4b38;"
                                    >
                                        Open Module <i class="fas fa-arrow-right ms-1"></i>
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </StudentLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import StudentLayout from '@/Layouts/StudentLayout.vue';

defineProps({
    modules: {
        type: Array,
        default: () => [],
    },
    userProgress: {
        type: Object,
        default: () => ({}),
    },
});
</script>

<style scoped>
.fs-8 {
    font-size: 0.7rem;
}
.hover-lift:hover {
    transform: translateY(-3px);
}
.max-w-6xl {
    max-width: 72rem;
}
</style>
