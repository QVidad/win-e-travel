<template>
    <Head title="Go Beyond Books" />
    <StudentLayout>
        <div class="dashboard-container">
            <div>
                <!-- Page Header matching dashboard banner -->
                <div class="welcome-banner text-white mb-5 shadow-sm" style="background: linear-gradient(135deg, #4a148c 0%, #7b1fa2 100%); border-radius: 30px; padding: 40px; position: relative; overflow: hidden;">
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
                            <div class="d-flex justify-content-lg-end">
                                <div class="text-center">
                                    <div class="overall-progress-circle mb-2 mx-auto" :style="progressCircleStyle">
                                        <span class="progress-percentage fs-4">{{ completedModulesCount }}/{{ foundationModules.length }}</span>
                                    </div>
                                    <span class="small fw-bold text-white opacity-75">Modules Completed</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Module Cards List -->
                <div class="d-flex flex-column gap-4 mb-5">
                    <div 
                        v-for="(mod, index) in foundationModules" 
                        :key="mod.id" 
                        class="card border-0 shadow-sm rounded-4 h-100 bg-white overflow-hidden transition-all hover-lift text-decoration-none d-flex flex-column justify-content-center"
                        style="padding: 10px;"
                        :class="index + 1 <= unlockedLevel ? 'cursor-pointer' : 'opacity-50'"
                        :style="index + 1 > unlockedLevel ? 'cursor: not-allowed; filter: grayscale(100%);' : 'cursor: pointer;'"
                        @click="selectModule(mod, index)"
                    >
                        <div class="card-body p-3 p-md-4 d-flex align-items-center position-relative">
                            <!-- Absolutely Positioned Locked Badge -->
                            <span 
                                v-if="index + 1 > unlockedLevel" 
                                class="badge rounded-pill fw-bold position-absolute"
                                style="top: 15px; right: 15px; background: rgba(108, 117, 125, 0.85); color: white; padding: 6px 14px; font-size: 0.85rem;"
                            >
                                Locked <i class="fas fa-lock ms-1"></i>
                            </span>

                            <!-- Status Badges for Completed/Available (if unlocked) -->
                            <span 
                                v-else-if="index + 1 < unlockedLevel" 
                                class="badge bg-success rounded-pill px-4 py-2 fs-8 fw-bold position-absolute"
                                style="top: 15px; right: 15px;"
                            >
                                Completed
                            </span>
                            <span 
                                v-else-if="index + 1 === unlockedLevel" 
                                class="badge bg-primary rounded-pill px-4 py-2 fs-8 fw-bold position-absolute"
                                style="top: 15px; right: 15px;"
                            >
                                Available
                            </span>

                            <!-- Icon Box -->
                            <div class="rounded-4 d-flex align-items-center justify-content-center me-4" 
                                 style="width: 72px; height: 72px; background-color: #eaf5f0; flex-shrink: 0;">
                                <i class="fas fa-clipboard-list" style="color: #198754; font-size: 28px;"></i>
                            </div>
                            
                            <!-- Text Content -->
                            <div>
                                <div class="text-dark fw-bold mb-1" style="font-size: 0.85rem; letter-spacing: 0.5px; text-transform: uppercase;">MODULE {{ index + 1 }}</div>
                                <h3 class="fw-bolder text-dark mb-0 pe-5" style="font-size: 1.75rem; letter-spacing: -0.5px;">{{ mod.title }}</h3>
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
import { router, Head } from '@inertiajs/vue3';

const props = defineProps({
    foundationModules: {
        type: Array,
        default: () => []
    },
    unlockedLevel: {
        type: Number,
        default: 1
    }
});

const completedModulesCount = computed(() => Math.min(props.unlockedLevel - 1, props.foundationModules.length));

const selectModule = (mod, index) => {
    if (index + 1 <= props.unlockedLevel) {
        router.visit(route('go-beyond-books.modules.show', mod.id));
    }
};

const progressCircleStyle = computed(() => {
    const degrees = props.foundationModules.length > 0 ? (completedModulesCount.value / props.foundationModules.length) * 360 : 0;
    return {
        background: `conic-gradient(#ffc107 0deg ${degrees}deg, rgba(255,255,255,0.2) ${degrees}deg 360deg)`
    };
});

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

.overall-progress-circle {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: conic-gradient(#ffc107 0deg 0deg, rgba(255,255,255,0.2) 0deg 360deg);
    position: relative;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.overall-progress-circle::before {
    content: '';
    position: absolute;
    width: 80px;
    height: 80px;
    background-color: #7b1fa2; /* matching banner */
    border-radius: 50%;
}

.progress-percentage {
    position: relative;
    font-weight: 800;
    color: white;
}
</style>
