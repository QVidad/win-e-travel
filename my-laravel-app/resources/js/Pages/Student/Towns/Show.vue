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
            <div class="town-hero" :style="{ 
                backgroundImage: `url('${module?.cover_image || town.hero_image || '/assets/images/Laoag.jpg'}')`,
                backgroundPosition: module?.cover_image_position || 'center'
            }">
                <div class="town-hero-overlay">
                    <span v-if="isCompleted" class="badge bg-success mb-2 px-3 py-2 rounded-pill"><i class="fas fa-check-circle me-1"></i>Completed</span>
                    <span v-else class="badge bg-warning text-dark mb-2 px-3 py-2 rounded-pill"><i class="fas fa-clock me-1"></i>In Progress</span>
                    <h1 class="display-5 fw-bold mb-2">{{ town.name }}</h1>
                    <p class="lead mb-0">{{ module?.subtitle || town.title || town.description }}</p>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-8">
                    <!-- About Section -->
                    <div class="info-section">
                        <h3 class="fw-bold mb-3 text-dark fs-2">
                            <i class="fas fa-info-circle text-primary me-2"></i>
                            About {{ town.name }}
                        </h3>
                        <div class="text-secondary mb-3 fs-5" style="white-space: pre-wrap; line-height: 1.7;" v-html="module?.description || town.description"></div>
                    </div>

                    <!-- Key Attractions List -->
                    <div class="info-section">
                        <h3 class="fw-bold mb-4 text-dark fs-2">
                            <i class="fas fa-map-pin text-danger me-2"></i>
                            Key Attractions
                        </h3>

                        <template v-if="module && module.lessons && module.lessons.length > 0">
                            <div v-for="lesson in module.lessons" :key="'lesson-'+lesson.id" class="attraction-card mb-4 border rounded overflow-hidden shadow-sm">
                                <img v-if="lesson.cover_image" :src="lesson.cover_image" class="w-100 object-fit-cover" :style="{ height: '250px', objectPosition: lesson.cover_image_position || 'center 50%' }" :alt="lesson.title">
                                <div class="p-3">
                                    <h5 class="fw-bold mb-2 text-dark fs-4">{{ lesson.title }}</h5>
                                    <div class="text-muted mb-0 html-content fs-6" style="line-height: 1.6;" v-html="lesson.content"></div>
                                </div>
                            </div>
                        </template>
                        <div v-else class="attraction-card p-3 border rounded shadow-sm">
                            <h5 class="fw-bold mb-2 text-dark fs-4">Historical Landmarks</h5>
                            <p class="text-muted mb-0 fs-6">Includes historical cathedrals, bell towers, heritage parks, and cultural museums.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Quick Facts Card -->
                    <div class="info-section" v-if="module && module.quick_facts && module.quick_facts.length > 0">
                        <h5 class="fw-bold mb-3 text-dark fs-3">
                            <i class="fas fa-lightbulb text-warning me-2"></i>
                            Quick Facts
                        </h5>
                        <ul class="list-unstyled mb-0">
                            <li v-for="(fact, index) in module.quick_facts" :key="'fact-'+index" class="mb-3 py-1 border-bottom d-flex align-items-center">
                                <i class="fas me-2 text-center" style="width: 20px;" :class="getFactIcon(fact)"></i>
                                <span class="text-dark flex-grow-1 fs-5" style="line-height: 1.5;" v-html="formatFact(fact)"></span>
                            </li>
                        </ul>
                    </div>
                    <div v-else class="info-section">
                        <h5 class="fw-bold mb-3 text-dark fs-3">
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
                        </ul>
                    </div>

                    <!-- Training Video Embed -->
                    <div class="info-section" v-if="module && module.video_references && module.video_references.length > 0">
                        <h5 class="fw-bold mb-3 text-dark fs-3">
                            <i class="fas fa-play-circle text-primary me-2"></i>
                            Training Video References
                        </h5>
                        <div v-for="(video, index) in module.video_references" :key="'video-'+index" class="video-container mb-3">
                            <!-- Attempt to convert standard youtube url to embed format if needed, else assume it works in iframe or just provide link -->
                            <iframe v-if="video.includes('youtube.com/embed') || video.includes('youtu.be')"
                                width="100%"
                                height="200"
                                :src="video.replace('watch?v=', 'embed/').replace('youtu.be/', 'youtube.com/embed/')"
                                frameborder="0"
                                allowfullscreen
                                style="border-radius: 15px;"
                            ></iframe>
                            <a v-else :href="video" target="_blank" class="btn btn-outline-primary w-100 rounded-pill shadow-sm">
                                <i class="fas fa-external-link-alt me-1"></i> Watch Video Reference {{ index + 1 }}
                            </a>
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
    module: Object,
    isCompleted: Boolean,
});

const getFactIcon = (fact) => {
    const lowerFact = fact.toLowerCase();
    if (lowerFact.includes('status') || lowerFact.includes('date') || lowerFact.includes('founded')) return 'fa-calendar-alt text-secondary';
    if (lowerFact.includes('area') || lowerFact.includes('size')) return 'fa-map text-secondary';
    if (lowerFact.includes('barangay') || lowerFact.includes('population') || lowerFact.includes('people') || lowerFact.includes('demographic')) return 'fa-users text-secondary';
    if (lowerFact.includes('nickname') || lowerFact.includes('region') || lowerFact.includes('province')) return 'fa-globe text-secondary';
    if (lowerFact.includes('meaning') || lowerFact.includes('name')) return 'fa-id-card text-secondary';
    if (lowerFact.includes('patron') || lowerFact.includes('saint') || lowerFact.includes('religion') || lowerFact.includes('church') || lowerFact.includes('cathedral')) return 'fa-church text-secondary';
    if (lowerFact.includes('level') || lowerFact.includes('difficulty')) return 'fa-signal text-secondary';
    if (lowerFact.includes('attraction') || lowerFact.includes('spot') || lowerFact.includes('site')) return 'fa-landmark text-secondary';
    return 'fa-check text-success';
};

const formatFact = (fact) => {
    if (fact.includes(':')) {
        const parts = fact.split(':');
        const label = parts.shift();
        return `<strong class="text-dark">${label}:</strong> ${parts.join(':')}`;
    }
    return fact;
};
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
