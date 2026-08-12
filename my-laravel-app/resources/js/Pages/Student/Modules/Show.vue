<template>
    <StudentLayout>
        <div class="py-4 bg-light min-vh-100">
            <div class="container max-w-5xl">
                <!-- Back Navigation Header -->
                <div class="mb-4 d-flex justify-content-between align-items-center">
                    <Link :href="route('dashboard')" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-bold">
                        <i class="fas fa-arrow-left me-1"></i> Return to Student Dashboard
                    </Link>

                    <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle rounded-pill px-3 py-1.5 fw-bold">
                        Published Curriculum
                    </span>
                </div>

                <!-- Dynamic Hero Card with Cover Image -->
                <div 
                    class="card border-0 text-white shadow-sm overflow-hidden mb-4 rounded-4 position-relative"
                    style="min-height: 240px; background: linear-gradient(135deg, #0a472e 0%, #1a5f7a 100%);"
                >
                    <img 
                        v-if="module.cover_image" 
                        :src="module.cover_image" 
                        alt="Cover Image" 
                        class="w-100 h-100 position-absolute top-0 start-0 object-fit-cover opacity-25"
                    >

                    <div class="card-body p-4 p-md-5 position-relative z-1 d-flex flex-column justify-content-end">
                        <h1 class="display-5 fw-extrabold text-white mb-2" style="font-weight: 800;">
                            {{ module.title }}
                        </h1>
                        <p v-if="module.subtitle" class="lead text-white opacity-90 fst-italic mb-0">
                            "{{ module.subtitle }}"
                        </p>
                    </div>
                </div>

                <!-- Main Module Content Section -->
                <div class="row g-4 mb-4">
                    <div class="col-lg-8">
                        <!-- Overview / Learning Objectives -->
                        <div class="card border-0 shadow-sm rounded-4 bg-white p-4 mb-4">
                            <h4 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                                <i class="fas fa-book-open text-success" style="color: #0d4b38 !important;"></i>
                                <span>Overview & Learning Objectives</span>
                            </h4>
                            <div class="text-dark lead fs-6 lh-lg opacity-90" style="white-space: pre-line;">
                                {{ module.description || 'No detailed overview provided for this module.' }}
                            </div>
                        </div>

                        <!-- Key Spots & Itinerary Highlights -->
                        <div v-if="module.key_spots" class="card border-0 shadow-sm rounded-4 bg-white p-4">
                            <h4 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                                <i class="fas fa-map-marked-alt text-warning"></i>
                                <span>Key Spots & Itinerary Highlights</span>
                            </h4>
                            <div class="text-dark fs-6 lh-lg opacity-90" style="white-space: pre-line;">
                                {{ module.key_spots }}
                            </div>
                        </div>
                    </div>

                    <!-- Right Sidebar: Progress & Quiz Action -->
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 bg-white p-4 sticky-top" style="top: 90px;">
                            <h5 class="fw-bold text-dark mb-3">Module Progress & Mastery</h5>

                            <div class="p-3 bg-light rounded-3 mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="small fw-bold text-dark">Required Score:</span>
                                    <span class="badge bg-warning text-dark rounded-pill fw-bold">90% Passing Rule</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="small text-muted">Your Best Attempt:</span>
                                    <span class="fw-bold" :class="userProgress && userProgress.passed ? 'text-success' : 'text-dark'">
                                        {{ userProgress ? userProgress.score_percentage + '%' : 'Not Attempted' }}
                                    </span>
                                </div>
                            </div>

                            <button 
                                v-if="module.questions && module.questions.length > 0" 
                                class="btn text-white w-100 rounded-pill py-2.5 fw-bold shadow-sm"
                                style="background-color: #0d4b38;"
                                @click="startQuiz"
                            >
                                <i class="fas fa-edit me-1"></i> Start Module Quiz ({{ module.questions.length }} Qs)
                            </button>
                            <button v-else class="btn btn-secondary w-100 rounded-pill py-2.5 fw-bold" disabled>
                                No Quiz Pool Questions Available
                            </button>
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

const props = defineProps({
    module: {
        type: Object,
        required: true,
    },
    userProgress: {
        type: Object,
        default: null,
    },
});

const startQuiz = () => {
    alert(`Starting randomized quiz pool for ${props.module.title}. Score ≥ 90% required to complete.`);
};
</script>

<style scoped>
.max-w-5xl {
    max-width: 64rem;
}
</style>
