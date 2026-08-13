<template>
    <EducatorLayout>
        <!-- Dark Green Gradient Hero Banner Section -->
        <div 
            class="card border-0 text-white p-4 p-md-5 mb-4 shadow-sm" 
            style="background: linear-gradient(135deg, #0a472e 0%, #1a5f7a 100%); border-radius: 20px;"
        >
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <Link :href="route('educator.modules.index')" class="text-decoration-none text-white-50 small fw-bold mb-2 d-inline-block">
                        <i class="fas fa-arrow-left me-1"></i> Back to Modules List
                    </Link>
                    <h1 class="display-6 fw-bold mb-2 text-white" style="font-weight: 800; letter-spacing: -0.5px;">
                        {{ form.title }}
                    </h1>
                    <div class="d-flex align-items-center gap-2">
                        <span 
                            class="badge rounded-pill px-3 py-1 fw-bold fs-8 text-dark shadow-sm" 
                            :class="module.type === 'foundation' ? 'bg-info' : 'bg-success'"
                        >
                            {{ module.type === 'foundation' ? 'Foundation Module' : 'Town Chapter' }}
                        </span>
                        <span class="badge rounded-pill px-3 py-1 fw-bold fs-8 border border-white text-white">
                            {{ form.status === 'published' ? 'Published' : 'Draft' }}
                        </span>
                    </div>
                </div>

                <!-- Action Switcher -->
                <div class="d-flex align-items-center gap-3">
                    <div class="text-end d-none d-md-block me-3">
                        <small class="text-white-50 fs-8 d-block">
                            <i class="fas fa-history me-1"></i>
                            <span v-if="module.updated_by && module.updated_by.name">
                                Last edited by <strong>{{ module.updated_by.name }}</strong><br>on {{ formatDate(module.last_modified_at || module.updated_at) }}
                            </span>
                            <span v-else>
                                Last modified on<br>{{ formatDate(module.updated_at) }}
                            </span>
                        </small>
                    </div>

                    <button 
                        @click="submit" 
                        class="btn text-dark bg-white rounded-pill px-4 py-2 fw-bold shadow-sm d-flex align-items-center gap-2" 
                        :disabled="form.processing"
                    >
                        <i class="fas fa-save text-success"></i>
                        <span>Save Changes</span>
                    </button>
                </div>
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

                <!-- 5. Lessons Management -->
                <div class="mb-4 pt-4 border-top">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <label class="form-label fw-bold text-dark mb-0">Module Lessons</label>
                        <button type="button" @click="openLessonModal()" class="btn btn-sm btn-success rounded-pill fw-bold shadow-sm px-3">
                            <i class="fas fa-plus me-1"></i> Add Lesson
                        </button>
                    </div>
                    
                    <div v-if="!module.lessons || module.lessons.length === 0" class="text-muted small p-3 bg-light rounded-3 text-center border">
                        No lessons added yet. Add lessons to attach specific quick-check quizzes to them.
                    </div>
                    <div v-else class="list-group border-0">
                        <div v-for="(lesson, index) in module.lessons" :key="lesson.id" class="list-group-item d-flex justify-content-between align-items-center bg-white p-3 border shadow-sm mb-2 rounded-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-light text-muted fw-bold rounded-circle d-flex align-items-center justify-content-center border" style="width: 32px; height: 32px;">
                                    {{ index + 1 }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ lesson.title }}</div>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" @click="openLessonModal(lesson)" class="btn btn-sm btn-outline-primary rounded-circle">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" @click="deleteLesson(lesson.id)" class="btn btn-sm btn-outline-danger rounded-circle">
                                    <i class="fas fa-trash"></i>
                                </button>
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

        <!-- Lesson Modal -->
        <div v-if="showLessonModal" class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5); z-index: 1055;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 border-0 shadow">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="fw-bold text-dark">{{ editingLesson ? 'Edit Lesson' : 'Add New Lesson' }}</h5>
                        <button type="button" class="btn-close" @click="closeLessonModal"></button>
                    </div>
                    <div class="modal-body">
                        <label class="form-label small fw-bold text-muted">Lesson Title</label>
                        <input v-model="lessonForm.title" type="text" class="form-control rounded-3" placeholder="e.g. General Preparation Before the Tour">
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" @click="closeLessonModal">Cancel</button>
                        <button type="button" @click="saveLesson" class="btn text-white rounded-pill px-4 fw-bold shadow-sm" style="background-color: #0d4b38;" :disabled="!lessonForm.title">
                            Save Lesson
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </EducatorLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm, Link, router } from '@inertiajs/vue3';
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

// --- Lesson Management Logic ---
const showLessonModal = ref(false);
const editingLesson = ref(null);
const lessonForm = ref({ title: '' });

const openLessonModal = (lesson = null) => {
    editingLesson.value = lesson;
    lessonForm.value.title = lesson ? lesson.title : '';
    showLessonModal.value = true;
};

const closeLessonModal = () => {
    showLessonModal.value = false;
    editingLesson.value = null;
    lessonForm.value.title = '';
};

const saveLesson = () => {
    if (!lessonForm.value.title) return;
    
    if (editingLesson.value) {
        // Update
        router.put(route('educator.modules.lessons.update', { module: props.module.id, lesson: editingLesson.value.id }), {
            title: lessonForm.value.title
        }, { preserveScroll: true, onSuccess: closeLessonModal });
    } else {
        // Create
        router.post(route('educator.modules.lessons.store', props.module.id), {
            title: lessonForm.value.title
        }, { preserveScroll: true, onSuccess: closeLessonModal });
    }
};

const deleteLesson = (lessonId) => {
    if (confirm('Are you sure you want to delete this lesson?')) {
        router.delete(route('educator.modules.lessons.destroy', { module: props.module.id, lesson: lessonId }), {
            preserveScroll: true
        });
    }
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
