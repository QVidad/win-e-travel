<template>
    <Head title="Dare to Discover" />
    <StudentLayout>
        <div class="dashboard-container">
            <div>
                <!-- Welcome Banner matching dashboard.html -->
                <!-- Welcome Banner (Refined Layout & Watermark Depth) -->
                <div class="welcome-banner position-relative overflow-hidden mb-4 shadow-sm">
                    <!-- Subtle Watermark Icon for Background Depth (Preserves Pink Gradient) -->
                    <div class="banner-watermark" aria-hidden="true">
                        <i class="fas fa-compass"></i>
                    </div>

                    <div class="row align-items-center g-3 position-relative z-1">
                        <div class="col-12 col-md-7 col-lg-8">
                            <div class="d-flex align-items-center flex-wrap gap-2 mb-2">
                                <span class="stage-tag-pill">
                                    <i class="fas fa-compass text-warning me-1"></i> STAGE 2 &bull; EXPLORATION
                                </span>
                                <span class="stage-status-pill" :class="completedCount >= towns.length && towns.length > 0 ? 'status-completed' : 'status-progress'">
                                    <span class="status-indicator-dot"></span>
                                    {{ completedCount >= towns.length && towns.length > 0 ? 'Stage Completed' : 'In Progress' }}
                                </span>
                                <button 
                                    type="button" 
                                    class="stage-tag-pill border-0 text-white shadow-xs" 
                                    style="background: rgba(255, 255, 255, 0.22) !important; cursor: pointer;"
                                    @click="openStageTutorial"
                                    title="How Stage 2 works"
                                >
                                    <i class="fas fa-gamepad text-warning me-1"></i> Stage Guide
                                </button>
                            </div>
                            <h1 class="banner-title fw-bold mb-1">
                                Dare to Discover
                            </h1>
                            <p class="banner-desc mb-0 opacity-90">
                                Explore the municipalities of Ilocos Norte and master their stories.
                            </p>
                        </div>
                        <div class="col-12 col-md-5 col-lg-4">
                            <div class="banner-progress-card">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="overall-progress-circle flex-shrink-0" :style="progressCircleStyle">
                                        <span class="progress-percentage">{{ completedCount }}/{{ towns.length }}</span>
                                    </div>
                                    <div class="flex-grow-1 min-w-0">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="progress-card-label">Stage Progress</span>
                                            <span class="progress-card-percent fw-bold">{{ Math.round((completedCount / Math.max(1, towns.length)) * 100) }}%</span>
                                        </div>
                                        <div class="progress banner-mini-track mb-1">
                                            <div 
                                                class="progress-bar bg-warning" 
                                                role="progressbar" 
                                                :style="{ width: `${(completedCount / Math.max(1, towns.length)) * 100}%` }"
                                                :aria-valuenow="Math.round((completedCount / Math.max(1, towns.length)) * 100)" 
                                                aria-valuemin="0" 
                                                aria-valuemax="100"
                                            ></div>
                                        </div>
                                        <span class="small text-white-50 text-truncate d-block" style="font-size: 0.76rem;">
                                            {{ completedCount }} of {{ towns.length }} Towns Mastered
                                        </span>
                                    </div>
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
                            :href="route('dare-to-discover.show', town.slug)"
                            class="town-card"
                            :class="getTownCardClass(town)"
                        >
                            <div class="town-image" :style="{ backgroundImage: `url('${town.module?.cover_image || town.hero_image || '/assets/images/INBackground.jpg'}')`, backgroundPosition: town.module?.cover_image_position || 'center' }">
                                <span class="town-badge" :class="getBadgeClass(town)">
                                    {{ getBadgeLabel(town) }}
                                    <i v-if="town.progress_status === 'completed'" class="fas fa-check-circle ms-1"></i>
                                </span>
                            </div>
                            <div class="town-content">
                                <h5 class="fw-bold mb-1 text-dark">{{ town.module?.title || town.name }}</h5>

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
                            <div class="town-image" :style="{ backgroundImage: `url('${town.module?.cover_image || town.hero_image || '/assets/images/INBackground.jpg'}')`, backgroundPosition: town.module?.cover_image_position || 'center' }">
                                <span class="town-badge bg-secondary text-white">
                                    Locked <i class="fas fa-lock ms-1"></i>
                                </span>
                            </div>
                            <div class="town-content">
                                <h5 class="fw-bold mb-1 text-dark">{{ town.module?.title || town.name }}</h5>
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
import { Link, Head } from '@inertiajs/vue3';
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
        background: `conic-gradient(#ffc107 0deg ${degrees}deg, rgba(255,255,255,0.2) ${degrees}deg 360deg)`
    };
});

const openStageTutorial = () => {
    window.dispatchEvent(new CustomEvent('win:open-tutorial', { detail: { step: 4 } }));
};
</script>

<style scoped>
.discover-container {
    min-height: 100vh;
}

.welcome-banner {
    background: linear-gradient(135deg, #880e4f 0%, #c2185b 100%);
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
    background-color: #9d174d; /* matching rich magenta */
    border-radius: 50%;
}

.progress-percentage {
    position: relative;
    font-weight: 800;
    color: white;
    font-size: 0.82rem;
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
</style>
