<template>
    <StudentLayout>
        <div class="container py-4">
            <!-- Breadcrumb Navigation matching town-laoag.html -->
            <nav class="breadcrumb-nav">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <Link :href="route('towns.index')" class="text-decoration-none">
                            <i class="fas fa-compass me-1"></i>Dare to Discover
                        </Link>
                    </li>
                    <li class="breadcrumb-item active text-dark fw-semibold">{{ town.name }}</li>
                </ol>
            </nav>

            <!-- Hero Image Banner matching town-laoag.html -->
            <div class="town-hero" :style="{ backgroundImage: `url('${town.hero_image || '/assets/images/Laoag.jpg'}')` }">
                <div class="town-hero-overlay">
                    <span class="badge bg-success mb-2 px-3 py-2 rounded-pill"><i class="fas fa-check-circle me-1"></i>Completed</span>
                    <h1 class="display-5 fw-bold mb-2">{{ town.name }}</h1>
                    <p class="lead mb-0">{{ town.title || town.description }}</p>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-8">
                    <!-- About Section -->
                    <div class="info-section">
                        <h3 class="fw-bold mb-3 text-dark">
                            <i class="fas fa-info-circle text-primary me-2"></i>
                            About {{ town.name }}
                        </h3>
                        <p class="text-secondary">{{ town.description }}</p>
                        <p class="text-secondary">The name "{{ town.name }}" highlights the rich historical, architectural, and cultural heritage of Ilocos Norte. As part of your tour guide training, mastering site narratives and historical accuracy is essential for delivering engaging guest commentary.</p>
                    </div>

                    <!-- Key Attractions List matching town-laoag.html -->
                    <div class="info-section">
                        <h3 class="fw-bold mb-4 text-dark">
                            <i class="fas fa-map-pin text-danger me-2"></i>
                            Key Attractions
                        </h3>

                        <div v-for="dest in town.destinations" :key="dest.id" class="attraction-card">
                            <h5 class="fw-bold mb-2 text-dark">{{ dest.name }}</h5>
                            <p class="text-muted small mb-2">{{ dest.description }}</p>
                            <p v-if="dest.history" class="text-secondary small mb-0"><strong>Historical Note:</strong> {{ dest.history }}</p>
                        </div>

                        <div v-if="!town.destinations || town.destinations.length === 0" class="attraction-card">
                            <h5 class="fw-bold mb-2 text-dark">Historical Landmarks</h5>
                            <p class="text-muted small mb-0">Includes historical cathedrals, bell towers, heritage parks, and cultural museums.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Quick Facts Card -->
                    <div class="info-section">
                        <h5 class="fw-bold mb-3 text-dark">
                            <i class="fas fa-lightbulb text-warning me-2"></i>
                            Quick Facts
                        </h5>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-3 py-1 border-bottom d-flex justify-content-between">
                                <span class="text-muted"><i class="fas fa-globe me-2 text-muted"></i>Province:</span>
                                <strong class="text-dark">{{ town.region || 'Ilocos Norte' }}</strong>
                            </li>
                            <li class="mb-3 py-1 border-bottom d-flex justify-content-between">
                                <span class="text-muted"><i class="fas fa-landmark me-2 text-muted"></i>Attractions:</span>
                                <strong class="text-dark">{{ town.destinations ? town.destinations.length : 0 }} Sites</strong>
                            </li>
                            <li class="mb-3 py-1 border-bottom d-flex justify-content-between">
                                <span class="text-muted"><i class="fas fa-signal me-2 text-muted"></i>Level:</span>
                                <span class="badge bg-success bg-opacity-10 text-success">{{ town.difficulty_level }}</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Training Video Embed -->
                    <div class="info-section">
                        <h5 class="fw-bold mb-3 text-dark">
                            <i class="fas fa-play-circle text-primary me-2"></i>
                            Training Video
                        </h5>
                        <div class="video-container mb-3">
                            <iframe
                                width="100%"
                                height="200"
                                src="https://www.youtube.com/embed/dQw4w9WgXcQ"
                                frameborder="0"
                                allowfullscreen
                                style="border-radius: 15px;"
                            ></iframe>
                        </div>
                        <p class="small text-muted mb-0">Review the training video anytime for voice & pacing tips.</p>
                    </div>

                    <!-- Completed / Launch Simulation Button -->
                    <Link v-if="town.simulation" :href="route('simulation.show', town.simulation.id)" class="btn btn-success simulation-btn w-100 shadow-sm text-center text-white text-decoration-none">
                        <i class="fas fa-play me-2"></i>
                        Launch {{ town.name }} Simulation
                    </Link>
                    <button v-else disabled class="btn btn-secondary simulation-btn w-100 shadow-sm text-center text-white">
                        <i class="fas fa-lock me-2"></i>
                        Simulation Not Available
                    </button>
                </div>
            </div>
        </div>
    </StudentLayout>
</template>

<script setup>
import StudentLayout from '@/Layouts/StudentLayout.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    town: Object,
});
</script>

<style scoped>
.town-hero {
    height: 300px;
    background-size: cover;
    background-position: center;
    border-radius: 20px;
    position: relative;
    margin-bottom: 30px;
}

.town-hero-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(transparent, rgba(0,0,0,0.7));
    padding: 40px 30px 30px;
    border-radius: 0 0 20px 20px;
    color: white;
}

.attraction-card {
    background: white;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    margin-bottom: 20px;
    border-left: 4px solid #0a472e;
}

.info-section {
    background: white;
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    margin-bottom: 30px;
}

.simulation-btn {
    background: #28a745;
    color: white;
    border: none;
    border-radius: 30px;
    padding: 15px 30px;
    font-weight: 700;
    font-size: 1.1rem;
    transition: all 0.3s ease;
}

.simulation-btn:hover {
    background: #218838;
    transform: translateY(-2px);
}

.breadcrumb-nav {
    background: transparent;
    padding: 15px 0 0;
}
</style>
