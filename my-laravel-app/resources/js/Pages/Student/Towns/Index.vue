<template>
    <StudentLayout>
        <div class="discover-container py-4">
            <div class="container">
                <!-- Page Header matching towns.html -->
                <div class="page-header">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <h2 class="fw-bold mb-2">
                                <i class="fas fa-compass me-2"></i>
                                Dare to Discover
                            </h2>
                            <p class="mb-0 opacity-90">Explore the municipalities of Ilocos Norte and master their stories</p>
                        </div>
                        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                            <div class="d-inline-block bg-white bg-opacity-25 rounded-4 px-4 py-2 fw-bold">
                                <span id="townsCompleted">{{ completedCount }}</span>/{{ towns.length }} Towns Completed
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Overall Progress Bar -->
                <div class="progress-bar-custom mb-4">
                    <div class="progress-fill bg-success" :style="{ width: (completedCount / towns.length) * 100 + '%', height: '8px', borderRadius: '10px' }"></div>
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

                <!-- Interactive Map Placeholder -->
                <div class="card border-0 shadow-sm mb-4 rounded-4 bg-white">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-map-marked-alt me-2 text-danger"></i>Ilocos Norte Interactive Map</h5>
                            <span class="badge bg-light text-dark">Click on highlighted towns below</span>
                        </div>
                        <div class="text-center py-4 bg-light rounded-3">
                            <i class="fas fa-map fa-4x text-muted mb-3"></i>
                            <p class="text-muted mb-0 fw-semibold">Interactive Map — 21 Municipalities Loaded</p>
                        </div>
                    </div>
                </div>

                <!-- Town Cards Grid matching towns.html -->
                <h5 class="fw-bold mb-3 text-dark">Available Destinations</h5>
                <div class="town-grid">
                    <template v-for="town in filteredTowns" :key="town.id">
                        <Link
                            v-if="town.status === 'published' || town.status === 'completed' || town.status === 'available'"
                            :href="route('towns.show', town.slug)"
                            class="town-card"
                            :class="getTownCardClass(town)"
                        >
                            <div class="town-image" :style="{ backgroundImage: `url('${town.hero_image || '/assets/images/INBackground.jpg'}')` }">
                                <span class="town-badge" :class="getBadgeClass(town)">
                                    {{ getBadgeLabel(town) }}
                                    <i v-if="town.slug === 'laoag-city'" class="fas fa-check-circle ms-1"></i>
                                </span>
                            </div>
                            <div class="town-content">
                                <h5 class="fw-bold mb-1 text-dark">{{ town.name }}</h5>
                                <p class="text-muted small mb-2">{{ town.title || town.description }}</p>
                                <div class="progress-bar-custom">
                                    <div class="progress-fill bg-success" :style="{ width: (town.slug === 'laoag-city' ? 100 : 0) + '%', height: '6px', borderRadius: '10px' }"></div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="small text-muted">{{ town.slug === 'laoag-city' ? '100% Complete' : '0% Complete' }}</span>
                                    <i :class="town.slug === 'laoag-city' ? 'fas fa-check-circle text-success' : 'fas fa-arrow-right text-muted'"></i>
                                </div>
                                <div v-if="town.destinations && town.destinations.length > 0" class="mt-2">
                                    <span v-for="dest in town.destinations.slice(0, 2)" :key="dest.id" class="attraction-tag">
                                        {{ dest.name }}
                                    </span>
                                    <span v-if="town.destinations.length > 2" class="attraction-tag">+{{ town.destinations.length - 2 }} more</span>
                                </div>
                            </div>
                        </Link>

                        <div v-else class="town-card locked opacity-75">
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
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    towns: Array,
});

const searchQuery = ref('');
const selectedStatus = ref('All');

const completedCount = computed(() => {
    return props.towns.filter(t => t.slug === 'laoag-city').length;
});

const filteredTowns = computed(() => {
    return props.towns.filter(town => {
        const matchesSearch = town.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                              (town.description && town.description.toLowerCase().includes(searchQuery.value.toLowerCase())) ||
                              (town.title && town.title.toLowerCase().includes(searchQuery.value.toLowerCase()));

        let matchesStatus = true;
        if (selectedStatus.value === 'Completed') matchesStatus = town.slug === 'laoag-city';
        else if (selectedStatus.value === 'Available') matchesStatus = town.slug !== 'laoag-city' && town.status === 'published';
        else if (selectedStatus.value === 'Locked') matchesStatus = town.status === 'draft';

        return matchesSearch && matchesStatus;
    });
});

const getTownCardClass = (town) => {
    if (town.slug === 'laoag-city') return 'completed';
    return 'available';
};

const getBadgeClass = (town) => {
    if (town.slug === 'laoag-city') return 'bg-success text-white';
    if (town.slug === 'paoay') return 'bg-primary text-white';
    return 'bg-secondary text-white';
};

const getBadgeLabel = (town) => {
    if (town.slug === 'laoag-city') return 'Completed';
    if (town.slug === 'paoay') return 'Available';
    return 'Locked';
};
</script>

<style scoped>
.discover-container {
    background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
    min-height: 100vh;
}

.page-header {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    border-radius: 20px;
    padding: 30px;
    color: white;
    margin: 30px 0;
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
</style>
