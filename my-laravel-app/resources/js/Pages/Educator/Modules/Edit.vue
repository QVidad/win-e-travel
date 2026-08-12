<template>
    <EducatorLayout>
        <!-- Header & Breadcrumbs -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
            <div>
                <Link :href="route('educator.modules.index')" class="text-decoration-none text-muted small fw-bold mb-1 d-inline-block">
                    <i class="fas fa-arrow-left me-1"></i> Back to Modules List
                </Link>
                <div class="d-flex align-items-center gap-2">
                    <h2 class="fw-bold text-dark mb-0">{{ form.title }}</h2>
                    <span 
                        class="badge rounded-pill px-3 py-1 fw-bold fs-8" 
                        :class="module.type === 'foundation' ? 'bg-primary text-white' : 'bg-success text-white'"
                    >
                        {{ module.type === 'foundation' ? 'Foundation Module' : 'Town Chapter' }}
                    </span>
                </div>
            </div>

            <!-- Audit Tag & Action Switcher -->
            <div class="d-flex align-items-center gap-3">
                <div class="text-end d-none d-md-block">
                    <small class="text-muted fs-8 d-block">
                        <i class="fas fa-history me-1"></i>
                        <span v-if="module.updated_by && module.updated_by.name">
                            Last edited by <strong>{{ module.updated_by.name }}</strong> on {{ formatDate(module.last_modified_at || module.updated_at) }}
                        </span>
                        <span v-else>
                            Last modified on {{ formatDate(module.updated_at) }}
                        </span>
                    </small>
                </div>

                <button 
                    @click="submit" 
                    class="btn text-white rounded-pill px-4 py-2 fw-bold shadow-sm d-flex align-items-center gap-2" 
                    style="background-color: #0d4b38;"
                    :disabled="form.processing"
                >
                    <i class="fas fa-save"></i>
                    <span>Save Changes</span>
                </button>
            </div>
        </div>

        <!-- Form Card Container -->
        <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden max-w-5xl mx-auto mb-5">
            <!-- Status & Actions Bar -->
            <div class="card-header bg-light border-bottom p-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="form-check form-switch d-flex align-items-center gap-2 m-0 p-0">
                        <label class="form-check-label fw-bold text-dark me-2 mb-0">Publication Status:</label>
                        <span class="badge rounded-pill px-3 py-1 fw-bold" :class="form.status === 'published' ? 'bg-success text-white' : 'bg-warning text-dark'">
                            {{ form.status === 'published' ? 'Published' : 'Draft' }}
                        </span>
                        <input 
                            class="form-check-input ms-2" 
                            type="checkbox" 
                            role="switch" 
                            :checked="form.status === 'published'"
                            @change="form.status = form.status === 'published' ? 'draft' : 'published'"
                            style="cursor: pointer; width: 2.5em; height: 1.3em;"
                        >
                    </div>
                </div>

                <small class="text-muted fst-italic">
                    Draft items are hidden from student chapter progression until set to Published.
                </small>
            </div>

            <form @submit.prevent="submit" class="p-4 p-md-5">
                <!-- 1. Module Title & Subtitle -->
                <div class="row g-4 mb-4">
                    <div class="col-md-7">
                        <label class="form-label fw-bold text-dark">Module Title <span class="text-danger">*</span></label>
                        <input 
                            v-model="form.title" 
                            type="text" 
                            class="form-control rounded-3" 
                            placeholder="e.g. Laoag City — Sunshine City of the North" 
                            required
                        >
                        <div v-if="form.errors.title" class="text-danger small mt-1">{{ form.errors.title }}</div>
                    </div>

                    <div class="col-md-5">
                        <label class="form-label fw-bold text-dark">Subtitle / Tagline</label>
                        <input 
                            v-model="form.subtitle" 
                            type="text" 
                            class="form-control rounded-3" 
                            placeholder="e.g. Heritage, Culture, and Commerce Hub"
                        >
                        <div v-if="form.errors.subtitle" class="text-danger small mt-1">{{ form.errors.subtitle }}</div>
                    </div>
                </div>

                <!-- 2. Overview / Learning Objectives (Rich Text / Formatted Area) -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label class="form-label fw-bold text-dark mb-0">
                            Overview / Learning Objectives
                        </label>
                        <small class="text-muted">Supports Markdown & HTML formatting</small>
                    </div>
                    <textarea 
                        v-model="form.description" 
                        class="form-control rounded-3 font-sans" 
                        rows="6" 
                        placeholder="Detail the educational goals, background lore, and tour guiding competencies required for this module..."
                    ></textarea>
                    <div v-if="form.errors.description" class="text-danger small mt-1">{{ form.errors.description }}</div>
                </div>

                <!-- 3. Key Spots / Itinerary Points (For Town Chapters) -->
                <div class="mb-4">
                    <label class="form-label fw-bold text-dark">
                        Key Spots & Itinerary Highlights
                    </label>
                    <textarea 
                        v-model="form.key_spots" 
                        class="form-control rounded-3" 
                        rows="4" 
                        placeholder="e.g. St. William's Cathedral & Sinking Bell Tower, Aurora Park, La Paz Sand Dunes (Include location names, descriptions, and highlight tags)..."
                    ></textarea>
                    <small class="text-muted d-block mt-1">Specify key heritage stops, landmark descriptions, and student commentary focal points.</small>
                    <div v-if="form.errors.key_spots" class="text-danger small mt-1">{{ form.errors.key_spots }}</div>
                </div>

                <!-- 4. Media Upload / Cover Image URL -->
                <div class="mb-4">
                    <label class="form-label fw-bold text-dark">Cover Image & Media Asset</label>
                    
                    <div class="row g-3 align-items-center">
                        <div class="col-md-7">
                            <input 
                                v-model="form.cover_image" 
                                type="text" 
                                class="form-control rounded-3 mb-2" 
                                placeholder="Image URL (e.g. /assets/images/laoag-belltower.jpg)"
                            >
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light text-muted">Or upload file</span>
                                <input 
                                    type="file" 
                                    class="form-control" 
                                    accept="image/*"
                                    @change="handleFileUpload"
                                >
                            </div>
                        </div>

                        <!-- Image Live Preview -->
                        <div class="col-md-5">
                            <div class="border rounded-3 p-2 bg-light text-center position-relative overflow-hidden" style="height: 110px;">
                                <img 
                                    v-if="imagePreview || form.cover_image" 
                                    :src="imagePreview || form.cover_image" 
                                    alt="Cover Preview" 
                                    class="w-100 h-100 object-fit-cover rounded-2"
                                    @error="imageError = true"
                                >
                                <div v-else class="h-100 d-flex flex-column align-items-center justify-content-center text-muted">
                                    <i class="fas fa-image fa-2x mb-1 opacity-50"></i>
                                    <span class="fs-8">Image Preview</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button Bar -->
                <div class="pt-4 border-top d-flex justify-content-end gap-3">
                    <Link :href="route('educator.modules.index')" class="btn btn-light rounded-pill px-4">Cancel</Link>
                    <button 
                        type="submit" 
                        class="btn text-white rounded-pill px-5 fw-bold shadow-sm" 
                        style="background-color: #0d4b38;"
                        :disabled="form.processing"
                    >
                        <i class="fas fa-save me-1"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </EducatorLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import EducatorLayout from '@/Layouts/EducatorLayout.vue';

const props = defineProps({
    module: {
        type: Object,
        required: true,
    },
});

const imagePreview = ref(null);
const imageError = ref(false);

const form = useForm({
    _method: 'PUT',
    title: props.module.title,
    subtitle: props.module.subtitle || '',
    description: props.module.description || '',
    key_spots: props.module.key_spots || '',
    cover_image: props.module.cover_image || '',
    cover_image_file: null,
    status: props.module.status || 'published',
});

const handleFileUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.cover_image_file = file;
        imagePreview.value = URL.createObjectURL(file);
    }
};

const submit = () => {
    form.post(route('educator.modules.update', props.module.id), {
        preserveScroll: true,
    });
};

const formatDate = (dateStr) => {
    if (!dateStr) return '';
    return new Date(dateStr).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};
</script>

<style scoped>
.fs-8 {
    font-size: 0.7rem;
}
.max-w-5xl {
    max-width: 64rem;
}
</style>
