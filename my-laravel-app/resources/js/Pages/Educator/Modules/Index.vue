<template>
    <EducatorLayout>
        <!-- Dark Green Gradient Hero Banner Section -->
        <div 
            class="card border-0 text-white p-4 p-md-5 mb-4 shadow-sm" 
            style="background: linear-gradient(135deg, #0a472e 0%, #1a5f7a 100%); border-radius: 20px;"
        >
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 class="display-6 fw-bold mb-2 text-white" style="letter-spacing: -0.5px;">
                        <i class="fas fa-book-open me-2 opacity-75"></i>Educator Course Content & Modules
                    </h1>
                    <p class="mb-0 text-white fst-italic fs-6 opacity-90">
                        "Edit course text, configure draft/published visibility, and audit faculty updates across all modules."
                    </p>
                </div>
                
                <button @click="openCreateModal" class="btn btn-warning px-4 py-2.5 rounded-pill fw-bold text-dark shadow-sm border-0 d-flex align-items-center gap-2">
                    <i class="fas fa-plus-circle"></i>
                    <span>Create Module</span>
                </button>
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

            <div class="input-group shadow-sm rounded-pill overflow-hidden border d-none d-md-flex" style="min-width: 300px; max-width: 300px;">
                <span class="input-group-text bg-white border-0 ps-3"><i class="fas fa-search text-muted"></i></span>
                <input type="text" v-model="searchQuery" class="form-control border-0 ps-2" placeholder="Search module title...">
                <button class="btn text-white px-4 fw-medium border-0" style="background-color: #0d4b38;">
                    Search
                </button>
            </div>
        </div>

        <!-- Module Cards Grid -->
        <div class="row g-4 mb-5">
            <div 
                v-for="(mod, index) in displayedModules" 
                :key="mod.id" 
                class="col-md-6 col-lg-4"
            >
                <div class="card border-0 shadow-sm rounded-4 h-100 bg-white transition-all hover-lift overflow-hidden">
                    <div class="card-body p-4 d-flex flex-column">
                        <!-- Module Category Icon & Status Badge -->
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <div 
                                    class="rounded-circle d-flex align-items-center justify-content-center text-white" 
                                    style="width: 46px; height: 46px; background-color: #0d4b38;"
                                >
                                    <i :class="mod.icon || 'fas fa-book'"></i>
                                </div>
                                <div v-if="!searchQuery" class="d-flex gap-1 ms-1">
                                    <button @click="moveModule(index, -1)" class="btn btn-sm btn-light border shadow-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" :disabled="index === 0 || isUpdatingOrder" title="Move Earlier">
                                        <i class="fas fa-arrow-left fa-xs text-secondary"></i>
                                    </button>
                                    <button @click="moveModule(index, 1)" class="btn btn-sm btn-light border shadow-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" :disabled="index === displayedModules.length - 1 || isUpdatingOrder" title="Move Later">
                                        <i class="fas fa-arrow-right fa-xs text-secondary"></i>
                                    </button>
                                </div>
                            </div>

                            <button 
                                @click.prevent="toggleStatus(mod)"
                                class="btn btn-sm rounded-pill px-3 py-1 fw-bold border-0 shadow-sm transition-all" 
                                :class="mod.status === 'published' ? 'btn-success text-white' : 'btn-warning text-dark'"
                                :disabled="mod.isToggling"
                                title="Click to toggle Published/Draft"
                            >
                                <span v-if="mod.isToggling"><i class="fas fa-spinner fa-spin me-1"></i></span>
                                {{ mod.status === 'published' ? 'Published' : 'Draft' }}
                            </button>
                        </div>

                        <!-- Module Title & Category/Description -->
                        <h5 class="fw-bold text-dark mb-1">{{ mod.title }}</h5>
                        <p class="text-muted small mb-3 flex-grow-1" style="min-height: 40px; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ stripHtml(mod.description) || 'Comprehensive learning content and tour guiding specifications.' }}
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
                    <div class="card-footer bg-light border-0 px-4 py-3 d-flex flex-wrap justify-content-between gap-2">
                        <Link 
                            :href="route('educator.modules.edit', mod.id)" 
                            class="btn btn-sm btn-outline-success rounded-pill px-3 fw-bold flex-grow-1"
                        >
                            <i class="fas fa-edit me-1"></i> Edit
                        </Link>
                        <Link 
                            :href="route('educator.quizzes.index')" 
                            class="btn btn-sm text-white rounded-pill px-3 fw-bold flex-grow-1" 
                            style="background-color: #0d4b38;"
                        >
                            <i class="fas fa-tasks me-1"></i> Quiz
                        </Link>
                        <button 
                            @click="deleteModule(mod)" 
                            class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-bold flex-grow-1"
                            title="Delete Module"
                        >
                            <i class="fas fa-trash-alt me-1"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create Module Modal -->
        <div v-if="showCreateModal" class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.55); z-index: 1060;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 shadow-lg border-0">
                    <div class="modal-header text-white rounded-top-4 py-3" style="background-color: #0d4b38;">
                        <h5 class="modal-title fw-bold d-flex align-items-center gap-2 fs-6">
                            <i class="fas fa-plus-circle text-warning"></i>
                            <span>Create New Module</span>
                        </h5>
                        <button type="button" class="btn-close btn-close-white" @click="closeCreateModal"></button>
                    </div>

                    <form @submit.prevent="submitCreateForm">
                        <div class="modal-body p-4">
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-dark">Module Type <span class="text-danger">*</span></label>
                                <select v-model="createForm.type" class="form-select rounded-3" required>
                                    <option value="" disabled>Select module type...</option>
                                    <option value="foundation">Foundation Module</option>
                                    <option value="town_chapter">Town Chapter / Dare to Discover</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-dark">Module Title <span class="text-danger">*</span></label>
                                <input 
                                    v-model="createForm.title" 
                                    type="text" 
                                    class="form-control rounded-3" 
                                    required 
                                    placeholder="e.g. Vigan City Heritage Tour"
                                >
                            </div>
                        </div>

                        <div class="modal-footer bg-light rounded-bottom-4">
                            <button type="button" class="btn btn-light rounded-pill px-4" @click="closeCreateModal">Cancel</button>
                            <button type="submit" class="btn text-white rounded-pill px-4 fw-bold shadow-sm" style="background-color: #0d4b38;" :disabled="createForm.processing">
                                <span v-if="createForm.processing"><i class="fas fa-spinner fa-spin me-1"></i> Creating...</span>
                                <span v-else><i class="fas fa-save me-1"></i> Create Module</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </EducatorLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
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

const isUpdatingOrder = ref(false);

const showCreateModal = ref(false);

const createForm = useForm({
    title: '',
    type: '',
});

const openCreateModal = () => {
    createForm.reset();
    createForm.type = activeTab.value === 'towns' ? 'town_chapter' : 'foundation';
    showCreateModal.value = true;
};

const closeCreateModal = () => {
    showCreateModal.value = false;
};

const submitCreateForm = () => {
    createForm.post(route('educator.modules.store'), {
        onSuccess: () => {
            closeCreateModal();
        },
    });
};

const deleteModule = (mod) => {
    if (confirm(`Are you sure you want to delete the module "${mod.title}"? This action cannot be undone and will delete all associated lessons and quiz questions.`)) {
        router.delete(route('educator.modules.destroy', mod.id), {
            preserveScroll: true,
        });
    }
};

const toggleStatus = (mod) => {
    mod.isToggling = true;
    router.patch(route('educator.modules.toggle', mod.id), {}, {
        preserveScroll: true,
        onFinish: () => {
            mod.isToggling = false;
        }
    });
};

const moveModule = (index, direction) => {
    if (searchQuery.value) return; 
    if (isUpdatingOrder.value) return;
    
    const sourceList = activeTab.value === 'foundation' ? props.foundationModules : props.townModules;
    if (index + direction < 0 || index + direction >= sourceList.length) return;
    
    isUpdatingOrder.value = true;
    
    const newList = [...sourceList];
    const temp = newList[index];
    newList[index] = newList[index + direction];
    newList[index + direction] = temp;
    
    const payload = newList.map((item, idx) => ({
        id: item.id,
        order: idx + 1
    }));
    
    router.post(route('educator.modules.reorder'), {
        modules: payload
    }, {
        preserveScroll: true,
        onFinish: () => {
            isUpdatingOrder.value = false;
        }
    });
};

const formatDate = (dateStr) => {
    if (!dateStr) return '';
    return new Date(dateStr).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
    });
};

const stripHtml = (html) => {
    if (!html) return '';
    const doc = new DOMParser().parseFromString(html, 'text/html');
    return doc.body.textContent || "";
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
