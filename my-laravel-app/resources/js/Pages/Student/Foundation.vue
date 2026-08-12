<template>
    <StudentLayout>
        <div class="foundation-container">
            <div>
                <!-- Page Header matching dashboard banner -->
                <div class="welcome-banner text-white mb-5 shadow-sm" style="background: linear-gradient(135deg, #0a472e 0%, #1a5f7a 100%); border-radius: 30px; padding: 40px; position: relative; overflow: hidden;">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <h1 class="display-5 fw-bold mb-3">
                                <i class="fas fa-book-open me-2 opacity-75"></i> Go Beyond Books
                            </h1>
                            <p class="mb-0 opacity-90 fs-5">
                                Master essential tour guide principles before taking on live simulation practice.
                            </p>
                        </div>
                        <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                            <div class="d-inline-block bg-white bg-opacity-10 rounded-pill px-4 py-3 fw-bold fs-5 border border-white border-opacity-25">
                                <span class="text-warning">{{ completedModulesCount }}</span> / {{ foundationModules.length }} Completed
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Module Cards List -->
                <div class="d-flex flex-column gap-4 mb-5">
                    <div 
                        v-for="(mod, index) in foundationModules" 
                        :key="mod.id" 
                        class="card border-0 shadow-sm rounded-4 overflow-hidden transition-all hover-lift"
                        :class="index + 1 <= unlockedLevel ? 'cursor-pointer' : 'opacity-50'"
                        :style="index + 1 > unlockedLevel ? 'cursor: not-allowed; filter: grayscale(100%);' : 'cursor: pointer;'"
                        @click="selectModule(mod, index)"
                    >
                        <div class="card-body p-4 p-md-5">
                            <div class="d-flex align-items-start gap-4">
                                <!-- Icon Box -->
                                <div 
                                    class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0 mt-1" 
                                    style="width: 70px; height: 70px; font-size: 28px;"
                                    :class="getIconColorClass(index)"
                                >
                                    <i :class="mod.icon || 'fas fa-book-open'"></i>
                                </div>
                                
                                <!-- Content -->
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h3 class="fw-bold text-dark mb-0">Module {{ index + 1 }}: {{ mod.title }}</h3>
                                        
                                        <!-- Status Badge -->
                                        <span 
                                            v-if="index + 1 <= unlockedLevel" 
                                            class="badge bg-success rounded-pill px-4 py-2 fs-8 fw-bold"
                                        >
                                            Available
                                        </span>
                                        <span 
                                            v-else 
                                            class="badge bg-secondary rounded-pill px-4 py-2 fs-8 fw-bold"
                                        >
                                            Locked
                                        </span>
                                    </div>
                                    
                                    <p class="text-muted fs-6 mb-4">{{ mod.description || 'Essential foundational concepts for tour guiding.' }}</p>
                                    
                                    <!-- Lesson Tags (Mocked) -->
                                    <div class="d-flex flex-wrap gap-2">
                                        <span 
                                            v-for="(tag, tIndex) in getTagsForModule(index)" 
                                            :key="tIndex" 
                                            class="badge bg-light text-secondary rounded-pill px-3 py-2 fw-medium border"
                                        >
                                            {{ tag }}
                                        </span>
                                    </div>
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
import StudentLayout from '@/Layouts/StudentLayout.vue';
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    foundationModules: {
        type: Array,
        default: () => []
    },
});

const unlockedLevel = ref(1);
const completedModulesCount = computed(() => Math.min(unlockedLevel.value - 1, props.foundationModules.length));

const selectModule = (mod, index) => {
    if (index + 1 <= unlockedLevel.value) {
        router.visit(route('student.modules.show', mod.id));
    }
};

// Dynamic icon background colors
const getIconColorClass = (index) => {
    const colors = [
        'bg-success bg-opacity-10 text-success',
        'bg-primary bg-opacity-10 text-primary',
        'bg-warning bg-opacity-10 text-warning',
        'bg-info bg-opacity-10 text-info',
    ];
    return colors[index % colors.length];
};

// Mocked lesson tags based on design
const getTagsForModule = (index) => {
    const defaultTags = [
        ['General Preparation Before the Tour', 'Researching Your Incoming Group', 'Coordinating with Suppliers', 'Site Familiarization'],
        ['Welcome', 'Getting Acquainted', 'Tour Essentials', 'Group Conduct Guidelines'],
        ['Strategies for Effective Tour Information Delivery', 'Introduction to Crisis Management During the Tour'],
        ['How to Conclude a Tour']
    ];
    
    return defaultTags[index] || ['Lesson 1', 'Lesson 2'];
};
</script>

<style scoped>
.foundation-container {
    background: #f8fafc;
    min-height: 100vh;
}

.hover-lift {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.hover-lift:hover:not(.opacity-75) {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important;
}
</style>
