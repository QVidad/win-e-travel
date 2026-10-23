<template>
    <Head title="Adventure Awaits" />
    <StudentLayout>
        <div class="dashboard-container">
            <div>
                <!-- Welcome Banner -->
                <!-- Welcome Banner (Refined Layout & Watermark Depth) -->
                <div class="welcome-banner position-relative overflow-hidden mb-4 shadow-sm">
                    <!-- Subtle Watermark Icon for Background Depth (Preserves Blue Gradient) -->
                    <div class="banner-watermark" aria-hidden="true">
                        <i class="fas fa-mountain"></i>
                    </div>

                    <div class="row align-items-center g-3 position-relative z-1">
                        <div class="col-12 col-md-7 col-lg-8">
                            <div class="d-flex align-items-center flex-wrap gap-2 mb-2">
                                <span class="stage-tag-pill">
                                    <i class="fas fa-mountain text-warning me-1"></i> STAGE 3 &bull; ADVENTURE
                                </span>
                                <span class="stage-status-pill" :class="completedTowns >= totalTowns && totalTowns > 0 ? 'status-completed' : 'status-progress'">
                                    <span class="status-indicator-dot"></span>
                                    {{ completedTowns >= totalTowns && totalTowns > 0 ? 'Stage Completed' : 'In Progress' }}
                                </span>
                                <button 
                                    type="button" 
                                    class="stage-tag-pill border-0 text-white shadow-xs" 
                                    style="background: rgba(255, 255, 255, 0.22) !important; cursor: pointer;"
                                    @click="openStageTutorial"
                                    title="How Stage 3 works"
                                >
                                    <i class="fas fa-gamepad text-warning me-1"></i> Stage Guide
                                </button>
                            </div>
                            <h1 class="banner-title fw-bold mb-1">
                                Adventure Awaits
                            </h1>
                            <p class="banner-desc mb-0 opacity-90">
                                The Ultimate Virtual Tour Challenge & Speech Simulation.
                            </p>
                        </div>
                        <div class="col-12 col-md-5 col-lg-4">
                            <div class="banner-progress-card">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="overall-progress-circle flex-shrink-0" :style="progressCircleStyle">
                                        <span class="progress-percentage">{{ completedTowns }}/{{ totalTowns }}</span>
                                    </div>
                                    <div class="flex-grow-1 min-w-0">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="progress-card-label">Boss Unlock</span>
                                            <span class="progress-card-percent fw-bold">{{ Math.round((completedTowns / Math.max(1, totalTowns)) * 100) }}%</span>
                                        </div>
                                        <div class="progress banner-mini-track mb-1">
                                            <div 
                                                class="progress-bar bg-warning" 
                                                role="progressbar" 
                                                :style="{ width: `${(completedTowns / Math.max(1, totalTowns)) * 100}%` }"
                                                :aria-valuenow="Math.round((completedTowns / Math.max(1, totalTowns)) * 100)" 
                                                aria-valuemin="0" 
                                                aria-valuemax="100"
                                            ></div>
                                        </div>
                                        <span class="small text-white-50 text-truncate d-block" style="font-size: 0.76rem;">
                                            {{ completedTowns }} of {{ totalTowns }} Towns Completed
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <!-- Towns Carousel -->
            <div class="mb-5">
                <h4 class="fw-bold mb-3 text-dark">Explore the Municipalities</h4>
                <div class="carousel-container position-relative overflow-hidden">
                    <div class="d-flex gap-3 overflow-auto pb-3 custom-scrollbar">
                        <Link v-for="town in towns" :key="town.id" :href="route('dare-to-discover.show', town.slug)" class="town-card flex-shrink-0 position-relative rounded overflow-hidden shadow-sm text-decoration-none" style="width: 200px; height: 120px; cursor: pointer;">
                            <img :src="town.hero_image || '/assets/images/Laoag.jpg'" :alt="town.name" class="w-100 h-100 object-fit-cover">
                            <div class="position-absolute bottom-0 w-100 p-2" style="background: linear-gradient(transparent, rgba(0,0,0,0.8));">
                                <span class="text-white fw-bold small">{{ town.name }}</span>
                            </div>
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Final Boss Card -->
            <div class="final-boss-card p-4 rounded-4 shadow-sm d-flex justify-content-between align-items-center flex-wrap gap-3" :style="isUnlocked ? 'background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: white;' : 'background-color: #92999f; color: white;'">
                <div>
                    <h3 class="fw-bold mb-2 text-white">
                        <i class="fas fa-crown text-warning me-2"></i>Final Virtual Tour: Ilocos Norte
                    </h3>
                    <p class="mb-2 opacity-75">Complete all {{ totalTowns }} town simulations to unlock the ultimate challenge!</p>
                    <div class="small opacity-75 d-flex align-items-center">
                        <i class="fas fa-check-circle me-1"></i> {{ completedTowns }} of {{ totalTowns }} towns completed 
                        <span v-if="completedTowns < totalTowns" class="ms-1">(demo)</span>
                    </div>
                </div>
                
                <div>
                    <Link v-if="finalSimPassed" :href="route('adventure-awaits.final')" class="btn btn-success fw-bold px-4 py-2 rounded shadow-sm text-white">
                        <i class="fas fa-redo text-white me-2"></i> Replay Tour
                    </Link>
                    <Link v-else-if="isUnlocked" :href="route('adventure-awaits.final')" class="btn btn-light fw-bold px-4 py-2 rounded shadow-sm text-dark">
                        <i class="fas fa-play text-success me-2"></i> Start Virtual Tour
                    </Link>
                    <button v-else class="btn btn-light opacity-75 fw-bold px-4 py-2 rounded text-dark" disabled>
                        <i class="fas fa-lock me-2"></i> Locked
                    </button>
                </div>
            </div>
        </div>
        </div>
    </StudentLayout>
</template>

<script setup>
import StudentLayout from '@/Layouts/StudentLayout.vue';
import { Link, usePage, Head } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    towns: Array,
    completedTowns: Number,
    totalTowns: Number,
    finalSimulationId: Number,
    finalSimPassed: Boolean,
    isEducatorOrAdmin: Boolean,
});

const isUnlocked = computed(() => {
    const userRole = usePage().props.auth?.user?.role;
    const isPrivileged = props.isEducatorOrAdmin || ['educator', 'admin'].includes(userRole);
    return isPrivileged || usePage().props.isFinalBossUnlocked || props.completedTowns >= props.totalTowns;
});

const progressCircleStyle = computed(() => {
    const degrees = props.totalTowns > 0 ? (props.completedTowns / props.totalTowns) * 360 : 0;
    return {
        background: `conic-gradient(#ffc107 0deg ${degrees}deg, rgba(255,255,255,0.2) ${degrees}deg 360deg)`
    };
});

const openStageTutorial = () => {
    window.dispatchEvent(new CustomEvent('win:open-tutorial', { detail: { step: 5 } }));
};
</script>

<style scoped>
.welcome-banner {
    background: linear-gradient(135deg, #01579b 0%, #0277bd 100%);
    border-radius: 24px;
    padding: 26px 30px;
    color: white;
    margin-bottom: 24px;
    position: relative;
    overflow: hidden;
}

.banner-watermark {
    position: absolute;
    right: -10px;
    bottom: -22px;
    font-size: 135px;
    color: rgba(255, 255, 255, 0.08);
    transform: rotate(-12deg);
    pointer-events: none;
    z-index: 0;
}

.stage-tag-pill {
    display: inline-flex;
    align-items: center;
    padding: 4px 12px;
    border-radius: 9999px;
    background: rgba(255, 255, 255, 0.18) !important;
    border: 1px solid rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(4px);
    font-size: 0.72rem;
    letter-spacing: 0.5px;
    font-weight: 700;
    color: #ffffff !important;
}

.stage-status-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 11px;
    border-radius: 9999px;
    font-size: 0.72rem;
    font-weight: 600;
}

.stage-status-pill.status-progress {
    background: rgba(255, 255, 255, 0.12) !important;
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: #fef08a !important;
}

.stage-status-pill.status-completed {
    background: rgba(34, 197, 94, 0.25) !important;
    border: 1px solid rgba(74, 222, 128, 0.4);
    color: #86efac !important;
}

.status-indicator-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background-color: currentColor;
    display: inline-block;
    box-shadow: 0 0 6px currentColor;
}

.banner-title {
    font-size: 1.85rem;
    letter-spacing: -0.5px;
    line-height: 1.2;
}

.banner-desc {
    font-size: 0.95rem;
    max-width: 620px;
    line-height: 1.5;
}

.banner-progress-card {
    background: rgba(255, 255, 255, 0.12);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.22);
    border-radius: 18px;
    padding: 12px 16px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
}

.progress-card-label {
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: rgba(255, 255, 255, 0.85);
}

.progress-card-percent {
    font-size: 0.95rem;
    color: #ffc107;
}

.banner-mini-track {
    height: 6px;
    border-radius: 6px;
    background-color: rgba(255, 255, 255, 0.2);
    overflow: hidden;
}

.overall-progress-circle {
    width: 58px;
    height: 58px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: conic-gradient(#ffc107 0deg 0deg, rgba(255,255,255,0.2) 0deg 360deg);
    position: relative;
    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
}

.overall-progress-circle::before {
    content: '';
    position: absolute;
    width: 44px;
    height: 44px;
    background-color: #0277bd; /* matching rich ocean blue */
    border-radius: 50%;
}

.progress-percentage {
    position: relative;
    font-weight: 800;
    color: white;
    font-size: 0.82rem;
}

@media (max-width: 768px) {
    .welcome-banner {
        padding: 18px 16px !important;
        border-radius: 20px !important;
        margin-bottom: 20px !important;
    }
    .banner-watermark {
        font-size: 90px !important;
        right: -10px !important;
        bottom: -15px !important;
    }
    .banner-title {
        font-size: 1.35rem !important;
    }
    .banner-desc {
        font-size: 0.85rem !important;
    }
    .banner-progress-card {
        padding: 10px 14px !important;
        border-radius: 14px !important;
    }
    .overall-progress-circle {
        width: 48px !important;
        height: 48px !important;
    }
    .overall-progress-circle::before {
        width: 36px !important;
        height: 36px !important;
    }
    .progress-percentage {
        font-size: 0.75rem !important;
    }
}
.custom-scrollbar::-webkit-scrollbar {
    height: 8px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f1f1; 
    border-radius: 4px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #c1c1c1; 
    border-radius: 4px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8; 
}
.town-card img {
    transition: transform 0.3s ease;
}
.town-card:hover img {
    transform: scale(1.1);
}
</style>
