<template>
    <EducatorLayout>
        <!-- Dark Green Gradient Hero Banner Section -->
        <div 
            class="card border-0 text-white p-4 p-md-5 mb-4 shadow-sm" 
            style="background: linear-gradient(135deg, #0d4b38 0%, #155e46 100%); border-radius: 20px;"
        >
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <span class="badge bg-warning text-dark px-3 py-1 rounded-pill fw-bold mb-2">Educator CMS</span>
                    <h1 class="display-6 fw-bold mb-2 text-white" style="font-weight: 800; letter-spacing: -0.5px;">
                        Educator Course Content & Modules
                    </h1>
                    <p class="mb-0 text-white fst-italic fs-6 opacity-90">
                        "Edit course text, configure draft/published visibility, and audit faculty updates across all 25 modules."
                    </p>
                </div>
            </div>
        </div>

        <!-- Tab Switcher: Foundation Modules (4) | Dare to Discover Towns (21) -->
        <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-4">
            <ul class="nav nav-pills gap-2">
                <li class="nav-item">
                    <button 
                        @click="activeTab = 'foundation'" 
                        class="nav-link rounded-pill px-4 fw-bold transition-all" 
                        :class="activeTab === 'foundation' ? 'active text-white' : 'text-dark bg-white border'"
                        :style="activeTab === 'foundation' ? 'background-color: #0d4b38 !important;' : ''"
                    >
                        <i class="fas fa-book-open me-2"></i> Foundation Modules ({{ foundationModules.length }})
                    </button>
                </li>
                <li class="nav-item">
                    <button 
                        @click="activeTab = 'towns'" 
                        class="nav-link rounded-pill px-4 fw-bold transition-all" 
                        :class="activeTab === 'towns' ? 'active text-white' : 'text-dark bg-white border'"
                        :style="activeTab === 'towns' ? 'background-color: #0d4b38 !important;' : ''"
                    >
                        <i class="fas fa-map-marked-alt me-2"></i> Dare to Discover Towns ({{ townModules.length }})
                    </button>
                </li>
            </ul>

            <div class="position-relative d-none d-md-block">
                <input 
                    v-model="searchQuery" 
                    type="text" 
                    class="form-control form-control-sm ps-4 rounded-pill" 
                    placeholder="Search module title..."
                    style="min-width: 240px;"
                >
                <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-2.5 text-muted small"></i>
            </div>
        </div>

        <!-- Module Cards Grid -->
        <div class="row g-4 mb-5">
            <div 
                v-for="mod in displayedModules" 
                :key="mod.id" 
                class="col-md-6 col-lg-4"
            >
                <div class="card border-0 shadow-sm rounded-4 h-100 bg-white transition-all hover-lift overflow-hidden">
                    <div class="card-body p-4 d-flex flex-column">
                        <!-- Module Category Icon & Status Badge -->
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div 
                                class="rounded-circle d-flex align-items-center justify-content-center text-white" 
                                style="width: 46px; height: 46px; background-color: #0d4b38;"
                            >
                                <i :class="mod.icon || 'fas fa-book'"></i>
                            </div>

                            <span class="badge rounded-pill px-3 py-1" :class="mod.status === 'published' ? 'bg-success text-white' : 'bg-warning text-dark'">
                                {{ mod.status === 'published' ? 'Published' : 'Draft' }}
                            </span>
                        </div>

                        <!-- Module Title & Category/Description -->
                        <h5 class="fw-bold text-dark mb-1">{{ mod.title }}</h5>
                        <p class="text-muted small mb-3 flex-grow-1" style="min-height: 40px;">
                            {{ mod.description || 'Comprehensive learning content and tour guiding specifications.' }}
                        </p>

                        <!-- Quiz Question Pool Count Badge -->
                        <div class="d-flex align-items-center justify-content-between bg-light rounded-3 p-2 px-3 mb-3">
                            <small class="fw-bold text-dark">
                                <i class="fas fa-question-circle text-primary me-1"></i> Question Bank
                            </small>
                            <span class="badge bg-primary rounded-pill">{{ mod.questions ? mod.questions.length : 0 }} Questions Bank</span>
                        </div>

                        <!-- Audit Log Badge -->
                        <div class="pt-2 border-top mt-auto">
                            <small class="text-muted fs-8 d-block text-truncate">
                                <i class="fas fa-history me-1 text-secondary"></i>
                                <span v-if="mod.updated_by && mod.updated_by.name">
                                    Last modified by <strong>{{ mod.updated_by.name }}</strong> on {{ formatDate(mod.last_modified_at || mod.updated_at) }}
                                </span>
                                <span v-else>
                                    Updated on {{ formatDate(mod.updated_at) }}
                                </span>
                            </small>
                        </div>
                    </div>

                    <!-- Card Footer Actions: Edit Content & Manage Quiz Bank -->
                    <div class="card-footer bg-light border-0 px-4 py-3 d-flex justify-content-between gap-2">
                        <Link 
                            :href="route('educator.modules.edit', mod.id)" 
                            class="btn btn-sm btn-outline-success rounded-pill px-3 fw-bold flex-grow-1"
                        >
                            <i class="fas fa-edit me-1"></i> Edit Content
                        </Link>
                        <Link 
                            :href="route('educator.quizzes.index')" 
                            class="btn btn-sm text-white rounded-pill px-3 fw-bold flex-grow-1" 
                            style="background-color: #0d4b38;"
                        >
                            <i class="fas fa-tasks me-1"></i> Manage Quiz Bank
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </EducatorLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import EducatorLayout from '@/Layouts/EducatorLayout.vue';

const props = defineProps({
    foundationModules: {
        type: Array,
        default: () => [],
    },
    townModules: {
        type: Array,
        default: () => [],
    },
});

const activeTab = ref('foundation');
const searchQuery = ref('');

const displayedModules = computed(() => {
    const sourceList = activeTab.value === 'foundation' ? props.foundationModules : props.townModules;
    return sourceList.filter(m => 
        m.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        (m.description && m.description.toLowerCase().includes(searchQuery.value.toLowerCase()))
    );
});

const formatDate = (dateStr) => {
    if (!dateStr) return '';
    return new Date(dateStr).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
    });
};
</script>

<style scoped>
.fs-8 {
    font-size: 0.7rem;
}
.transition-all {
    transition: all 0.2s ease-in-out;
}
.hover-lift:hover {
    transform: translateY(-3px);
}
</style>
