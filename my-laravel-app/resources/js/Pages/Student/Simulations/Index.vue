<template>
    <StudentLayout>
        <div class="container py-4">
            <!-- Welcome Banner -->
            <div class="welcome-banner mb-5">
                <div class="row align-items-center">
                    <div class="col-lg-12">
                        <h2 class="display-6 fw-bold mb-3 text-white">
                            <i class="fas fa-mountain me-2"></i>
                            Adventure Awaits
                        </h2>
                        <p class="mb-0 opacity-90 fs-5 text-white">The Ultimate Virtual Tour Challenge</p>
                    </div>
                </div>
            </div>

            <!-- Towns Carousel -->
            <div class="mb-5">
                <h4 class="fw-bold mb-3 text-dark">Explore the Municipalities</h4>
                <div class="carousel-container position-relative overflow-hidden">
                    <div class="d-flex gap-3 overflow-auto pb-3 custom-scrollbar">
                        <Link v-for="town in towns" :key="town.id" :href="route('towns.show', town.slug)" class="town-card flex-shrink-0 position-relative rounded overflow-hidden shadow-sm text-decoration-none" style="width: 200px; height: 120px; cursor: pointer;">
                            <img :src="town.hero_image || '/assets/images/Laoag.jpg'" :alt="town.name" class="w-100 h-100 object-fit-cover">
                            <div class="position-absolute bottom-0 w-100 p-2" style="background: linear-gradient(transparent, rgba(0,0,0,0.8));">
                                <span class="text-white fw-bold small">{{ town.name }}</span>
                            </div>
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Final Boss Card -->
            <div class="final-boss-card p-4 rounded shadow-sm d-flex justify-content-between align-items-center" style="background-color: #92999f; color: white;">
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
                    <Link v-if="isUnlocked && finalSimulationId" :href="route('simulation.final')" class="btn btn-light fw-bold px-4 py-2 rounded shadow-sm text-dark">
                        <i class="fas fa-play text-success me-2"></i> Start Virtual Tour
                    </Link>
                    <button v-else class="btn btn-light opacity-75 fw-bold px-4 py-2 rounded text-dark" disabled>
                        <i class="fas fa-lock me-2"></i> Locked
                    </button>
                </div>
            </div>
        </div>
    </StudentLayout>
</template>

<script setup>
import StudentLayout from '@/Layouts/StudentLayout.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    towns: Array,
    completedTowns: Number,
    totalTowns: Number,
    finalSimulationId: Number,
});

const isUnlocked = computed(() => {
    return usePage().props.isFinalBossUnlocked || props.completedTowns >= props.totalTowns;
});
</script>

<style scoped>
.welcome-banner {
    background: linear-gradient(135deg, #0a472e 0%, #1a5f7a 100%);
    border-radius: 30px;
    padding: 40px;
    color: white;
    position: relative;
    overflow: hidden;
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
