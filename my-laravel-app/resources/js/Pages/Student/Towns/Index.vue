<template>
    <StudentLayout>
        <div class="discover-container py-4">
            <div class="container">
                <!-- Welcome Banner matching dashboard.html -->
                <div class="welcome-banner">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <h2 class="display-6 fw-bold mb-3">
                                <i class="fas fa-compass me-2"></i>
                                Dare to Discover
                            </h2>
                            <p class="mb-0 opacity-90 fs-5">Explore the municipalities of Ilocos Norte and master their stories</p>
                        </div>
                        <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                            <div class="d-flex justify-content-lg-end">
                                <div class="text-center">
                                    <div class="overall-progress-circle mb-2 mx-auto" :style="progressCircleStyle">
                                        <span class="progress-percentage fs-4">{{ completedCount }}/{{ towns.length }}</span>
                                    </div>
                                    <span class="small fw-bold text-white opacity-75">Towns Completed</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search & Filter Controls -->
                <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-search text-muted"></i></span>
                                <input
                                    v-model="searchQuery"
                                    type="text"
                                    class="form-control bg-light border-0"
                                    placeholder="Search municipality or landmark..."
                                />
                            </div>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <div class="btn-group" role="group">
                                <button
                                    v-for="status in ['All', 'Completed', 'Available', 'Locked']"
                                    :key="status"
                                    type="button"
                                    class="btn btn-sm"
                                    :class="selectedStatus === status ? 'btn-success fw-bold' : 'btn-outline-secondary'"
                                    @click="selectedStatus = status"
                                >
                                    {{ status }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Town Cards Grid Header with Map Toggle -->
                <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
                    <h5 class="fw-bold mb-0 text-dark">Available Destinations</h5>
                    <button @click="showMap = !showMap" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" style="background-color: #0a472e; border: none;">
                        <i class="fas fa-map-marked-alt me-2"></i>
                        {{ showMap ? 'Hide Map' : 'View Interactive Map' }}
                    </button>
                </div>

                <!-- Interactive Map Section -->
                <div v-if="showMap" class="mb-5">
                    <TownMap :towns="towns" @town-clicked="handleTownClick" />
                </div>
                <div class="town-grid">
                    <template v-for="town in filteredTowns" :key="town.id">
                        <Link
                            :id="'town-card-' + town.slug"
                            v-if="town.progress_status !== 'locked'"
                            :href="route('towns.show', town.slug)"
                            class="town-card"
                            :class="getTownCardClass(town)"
                        >
                            <div class="town-image" :style="{ backgroundImage: `url('${town.hero_image || '/assets/images/INBackground.jpg'}')` }">
                                <span class="town-badge" :class="getBadgeClass(town)">
                                    {{ getBadgeLabel(town) }}
                                    <i v-if="town.progress_status === 'completed'" class="fas fa-check-circle ms-1"></i>
                                </span>
                            </div>
                            <div class="town-content">
                                <h5 class="fw-bold mb-1 text-dark">{{ town.name }}</h5>
                                <p class="text-muted small mb-2">{{ town.title || town.description }}</p>
                                <div class="progress-bar-custom">
                                    <div class="progress-fill bg-success" :style="{ width: (town.progress_status === 'completed' ? 100 : 0) + '%', height: '6px', borderRadius: '10px' }"></div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="small text-muted">{{ town.progress_status === 'completed' ? '100% Complete' : '0% Complete' }}</span>
                                    <i :class="town.progress_status === 'completed' ? 'fas fa-check-circle text-success' : 'fas fa-arrow-right text-muted'"></i>
                                </div>
                                <div v-if="town.destinations && town.destinations.length > 0" class="mt-2">
                                    <span v-for="dest in town.destinations.slice(0, 2)" :key="dest.id" class="attraction-tag">
                                        {{ dest.name }}
                                    </span>
                                    <span v-if="town.destinations.length > 2" class="attraction-tag">+{{ town.destinations.length - 2 }} more</span>
                                </div>
                            </div>
                        </Link>

                        <div v-else :id="'town-card-' + town.slug" class="town-card locked opacity-75">
                            <div class="town-image" :style="{ backgroundImage: `url('${town.hero_image || '/assets/images/INBackground.jpg'}')` }">
                                <span class="town-badge bg-secondary text-white">
                                    Locked <i class="fas fa-lock ms-1"></i>
                                </span>
                            </div>
                            <div class="town-content">
                                <h5 class="fw-bold mb-1 text-dark">{{ town.name }}</h5>
                                <p class="text-muted small mb-2">{{ town.title || town.description }}</p>
                                <p class="small text-muted mb-0"><i class="fas fa-lock me-1"></i>Complete Previous Town First</p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </StudentLayout>
</template>

<script setup>
import StudentLayout from '@/Layouts/StudentLayout.vue';
import { ref, computed, defineAsyncComponent } from 'vue';
import { Link } from '@inertiajs/vue3';
import 'leaflet/dist/leaflet.css';

const TownMap = defineAsyncComponent(() => import('@/Components/TownMap.vue'));

const showMap = ref(false);

const handleTownClick = (slug) => {
    const el = document.getElementById('town-card-' + slug);
    if (el) {
        el.scrollIntoView({ behavior: 'smooth', block: 'center' });
        el.classList.add('highlight-pulse');
        setTimeout(() => el.classList.remove('highlight-pulse'), 2000);
    }
};

const props = defineProps({
    towns: Array,
    completedCount: Number,
});

const searchQuery = ref('');
const selectedStatus = ref('All');

const filteredTowns = computed(() => {
    return props.towns.filter(town => {
        const matchesSearch = town.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                              (town.description && town.description.toLowerCase().includes(searchQuery.value.toLowerCase())) ||
                              (town.title && town.title.toLowerCase().includes(searchQuery.value.toLowerCase()));

        let matchesStatus = true;
        if (selectedStatus.value === 'Completed') matchesStatus = town.progress_status === 'completed';
        else if (selectedStatus.value === 'Available') matchesStatus = town.progress_status === 'available';
        else if (selectedStatus.value === 'Locked') matchesStatus = town.progress_status === 'locked';

        return matchesSearch && matchesStatus;
    });
});

const getTownCardClass = (town) => {
    return town.progress_status;
};

const getBadgeClass = (town) => {
    if (town.progress_status === 'completed') return 'bg-success text-white';
    if (town.progress_status === 'available') return 'bg-primary text-white';
    return 'bg-secondary text-white';
};

const getBadgeLabel = (town) => {
    if (town.progress_status === 'completed') return 'Completed';
    if (town.progress_status === 'available') return 'Available';
    return 'Locked';
};

const progressCircleStyle = computed(() => {
    const degrees = props.towns.length > 0 ? (props.completedCount / props.towns.length) * 360 : 0;
    return {
        background: `conic-gradient(#0a472e 0deg ${degrees}deg, rgba(255,255,255,0.3) ${degrees}deg 360deg)`
    };
});
</script>

<style scoped>
.discover-container {
    min-height: 100vh;
}

.welcome-banner {
    background: linear-gradient(135deg, #0a472e 0%, #1a5f7a 100%);
    border-radius: 30px;
    padding: 40px;
    color: white;
    margin-bottom: 30px;
    position: relative;
    overflow: hidden;
}

.overall-progress-circle {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: conic-gradient(#0a472e 0deg 0deg, rgba(255,255,255,0.3) 0deg 360deg);
    position: relative;
}

.overall-progress-circle::before {
    content: '';
    position: absolute;
    width: 80px;
    height: 80px;
    background-color: #1a5f7a; /* matching banner */
    border-radius: 50%;
}

.progress-percentage {
    position: relative;
    font-weight: 800;
    color: white;
}

.town-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 25px;
}

.town-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    cursor: pointer;
    border: 1px solid #eef2f6;
    text-decoration: none;
    color: inherit;
    display: block;
}

.town-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
    color: inherit;
}

.town-card.completed {
    border-left: 6px solid #28a745;
}

.town-card.available {
    border-left: 6px solid #007bff;
}

.town-card.locked {
    opacity: 0.7;
    cursor: not-allowed;
}

.town-image {
    height: 180px;
    background-size: cover;
    background-position: center;
    position: relative;
}

.town-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    padding: 6px 14px;
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 600;
}

.town-content {
    padding: 20px;
}

.progress-bar-custom {
    height: 6px;
    border-radius: 10px;
    background: #e9ecef;
    margin: 12px 0;
}

.attraction-tag {
    background: #f8f9fa;
    border-radius: 30px;
    padding: 4px 12px;
    margin: 3px;
    display: inline-block;
    font-size: 0.75rem;
    color: #495057;
}

@keyframes pulse {
    0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(0, 123, 255, 0.7); }
    50% { transform: scale(1.02); box-shadow: 0 0 0 15px rgba(0, 123, 255, 0); }
    100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(0, 123, 255, 0); }
}

.highlight-pulse {
    animation: pulse 2s;
    border: 2px solid #007bff !important;
}
</style>
