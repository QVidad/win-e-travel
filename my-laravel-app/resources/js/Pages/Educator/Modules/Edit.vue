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
                <!-- Global Form Errors -->
                <div v-if="Object.keys(form.errors).length > 0" class="alert alert-danger mb-4">
                    <strong><i class="fas fa-exclamation-triangle me-2"></i> Save Failed</strong>
                    <ul class="mb-0 mt-2">
                        <li v-for="(error, field) in form.errors" :key="field">{{ error }}</li>
                    </ul>
                </div>

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

                <!-- Town Chapter Specifics -->
                <div v-if="module.type === 'town_chapter'">
                    <!-- Quick Facts -->
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark">Quick Facts</label>
                        <div v-for="(fact, index) in form.quick_facts" :key="'fact-'+index" class="d-flex gap-2 mb-2">
                            <input 
                                v-model="form.quick_facts[index]" 
                                type="text" 
                                class="form-control rounded-3" 
                                placeholder="e.g. Founded in 1580"
                            >
                            <button type="button" @click="removeQuickFact(index)" class="btn btn-outline-danger px-3 rounded-3">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <button type="button" @click="addQuickFact" class="btn btn-sm btn-outline-primary rounded-pill px-3 mt-1">
                            <i class="fas fa-plus me-1"></i> Add Quick Fact
                        </button>
                    </div>

                    <!-- Video References -->
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark">Video References (YouTube URLs)</label>
                        <div v-for="(video, index) in form.video_references" :key="'video-'+index" class="d-flex gap-2 mb-2">
                            <input 
                                v-model="form.video_references[index]" 
                                type="url" 
                                class="form-control rounded-3" 
                                placeholder="e.g. https://youtube.com/watch?v=..."
                            >
                            <button type="button" @click="removeVideoReference(index)" class="btn btn-outline-danger px-3 rounded-3">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <button type="button" @click="addVideoReference" class="btn btn-sm btn-outline-primary rounded-pill px-3 mt-1">
                            <i class="fas fa-plus me-1"></i> Add Video Reference
                        </button>
                    </div>
                </div>

                <!-- 3. Key Spots / Itinerary Points (For Foundation) -->
                <div v-else class="mb-4">
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
                                    v-if="imagePreview || form.cover_image || defaultTownImage" 
                                    :src="imagePreview || form.cover_image || defaultTownImage" 
                                    class="w-100 h-100 object-fit-cover rounded"
                                    alt="Cover preview"
                                >
                                <div v-else class="h-100 d-flex flex-column align-items-center justify-content-center text-muted">
                                    <i class="fas fa-image fs-3 mb-1"></i>
                                    <small>No image set</small>
                                </div>
                            </div>
                            <small v-if="!imagePreview && !form.cover_image && defaultTownImage" class="text-muted mt-1 d-block text-center" style="font-size: 0.75rem;">
                                Showing default town image. Upload a new one to override.
                            </small>
                        </div>
                    </div>
                </div>

                    <!-- Quiz Settings -->
                    <div v-if="module.type !== 'town_chapter'" class="row g-4 mb-4 pt-4 border-top">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">Module Quick Check Quiz (Questions)</label>
                            <div class="input-group">
                                <input 
                                    v-model.number="form.quiz_question_count" 
                                    type="number" 
                                    min="0" 
                                    :max="questionBankCount"
                                    class="form-control rounded-start-3" 
                                >
                                <span class="input-group-text bg-light text-muted rounded-end-3">
                                    of {{ questionBankCount }} available in bank
                                </span>
                            </div>
                            <small class="text-muted d-block mt-1">Set to 0 if this module shouldn't have a final quiz.</small>
                        </div>
                    </div>

                <!-- 5. Lessons / Attractions Management -->
                <div class="mb-4 pt-4 border-top">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <label class="form-label fw-bold text-dark mb-0">
                            {{ module.type === 'town_chapter' ? 'Key Attractions' : 'Module Lessons' }}
                        </label>
                        <button type="button" @click="openLessonModal()" class="btn btn-sm btn-success rounded-pill fw-bold shadow-sm px-3">
                            <i class="fas fa-plus me-1"></i> Add {{ module.type === 'town_chapter' ? 'Attraction' : 'Lesson' }}
                        </button>
                    </div>
                    
                    <div v-if="!module.lessons || module.lessons.length === 0" class="text-muted small p-3 bg-light rounded-3 text-center border">
                        No {{ module.type === 'town_chapter' ? 'attractions' : 'lessons' }} added yet. 
                    </div>
                    <div v-else class="list-group border-0">
                        <div v-for="(lesson, index) in module.lessons" :key="lesson.id" class="list-group-item d-flex justify-content-between align-items-center bg-white p-3 border shadow-sm mb-2 rounded-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-light text-muted fw-bold rounded-circle d-flex align-items-center justify-content-center border" style="width: 32px; height: 32px;">
                                    {{ index + 1 }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ lesson.title }}</div>
                                    <small v-if="module.type !== 'town_chapter'" class="text-secondary fs-8">Quick Check: {{ lesson.quiz_question_count > 0 ? lesson.quiz_question_count + ' Questions' : 'No Quiz' }}</small>
                                    <small v-else class="text-secondary fs-8">Town Attraction</small>
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
                <div class="d-flex justify-content-end gap-2 mt-5 border-top pt-4">
                    <Link :href="route('educator.modules.index')" class="btn btn-light border fw-bold px-4">Cancel / Back</Link>
                    <button type="submit" class="btn fw-bold px-4" :class="form.recentlySuccessful ? 'btn-success' : 'btn-primary'" :disabled="form.processing">
                        <span v-if="form.processing"><i class="fas fa-spinner fa-spin me-2"></i> Saving...</span>
                        <span v-else-if="form.recentlySuccessful"><i class="fas fa-check me-2"></i> Saved Successfully!</span>
                        <span v-else><i class="fas fa-save me-2"></i> Save Changes</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Lesson Modal -->
        <div v-if="showLessonModal" class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.55); z-index: 1055;">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content rounded-4 border-0 shadow-lg">
                    <div class="modal-header border-bottom p-4 text-white" style="background-color: #0d4b38; border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
                        <h5 class="fw-bold mb-0 text-white d-flex align-items-center gap-2 fs-6">
                            <i class="fas" :class="editingLesson ? 'fa-edit text-warning' : 'fa-plus-circle text-warning'"></i>
                            <span>{{ editingLesson ? (module.type === 'town_chapter' ? 'Edit Attraction' : 'Edit Lesson Content & Quiz') : (module.type === 'town_chapter' ? 'Add New Attraction' : 'Add New Lesson') }}</span>
                        </h5>
                        <button type="button" class="btn-close btn-close-white" @click="closeLessonModal"></button>
                    </div>
                    <div class="modal-body p-4 max-h-75vh overflow-y-auto">
                        <!-- Prominent Validation Error Alert when save changes fails -->
                        <div v-if="lessonError" class="alert alert-danger shadow-sm rounded-4 mb-4 border-0 d-flex align-items-start gap-3 p-3">
                            <i class="fas fa-exclamation-triangle fa-2x text-danger mt-1"></i>
                            <div>
                                <h6 class="fw-bold text-danger mb-1">Save Changes Failed!</h6>
                                <p class="mb-0 text-dark small leading-relaxed">{{ lessonError }}</p>
                            </div>
                        </div>

                        <!-- Lesson Title -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">{{ module.type === 'town_chapter' ? 'Attraction Name' : 'Lesson Title' }} <span class="text-danger">*</span></label>
                            <input 
                                v-model="lessonForm.title" 
                                type="text" 
                                class="form-control rounded-3" 
                                placeholder="e.g. General Preparation Before the Tour"
                                required
                            >
                        </div>

                        <!-- Quick Check Quiz Questions Configuration -->
                        <div v-if="module.type !== 'town_chapter'" class="mb-4 p-3.5 rounded-3 bg-light border">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                                    <i class="fas fa-tasks text-success"></i>
                                    <span>Quick Check Quiz Configuration</span>
                                </label>
                                <span class="badge bg-white text-dark border px-3 py-1 rounded-pill fs-8">
                                    Bank Questions for this Lesson: <strong>{{ currentLessonBankCount }}</strong>
                                </span>
                            </div>

                            <div class="row g-3 align-items-center">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-dark mb-1">
                                        Number of Quiz Questions <span class="text-danger">*</span>
                                    </label>
                                    <input 
                                        v-model="lessonForm.quiz_question_count" 
                                        type="number" 
                                        min="0"
                                        max="100"
                                        class="form-control rounded-3" 
                                        :class="lessonError ? 'is-invalid border-danger' : ''"
                                        required
                                    >
                                    <small class="text-muted d-block mt-1">
                                        Number of questions asked in this lesson's quick check. Cannot exceed bank count.
                                    </small>
                                </div>

                                <div class="col-md-6">
                                    <div class="p-2.5 rounded-3 border bg-white">
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <span class="small fw-bold text-dark">Question Bank Availability:</span>
                                            <span 
                                                class="badge rounded-pill px-2.5 py-1"
                                                :class="lessonForm.quiz_question_count <= currentLessonBankCount ? 'bg-success text-white' : 'bg-danger text-white'"
                                            >
                                                {{ lessonForm.quiz_question_count <= currentLessonBankCount ? 'Sufficient Questions' : 'Insufficient Questions' }}
                                            </span>
                                        </div>
                                        <small class="text-muted d-block">
                                            {{ currentLessonBankCount }} in bank vs {{ lessonForm.quiz_question_count || 0 }} requested.
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Lesson Description / Overview -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">{{ module.type === 'town_chapter' ? 'Attraction Information' : 'Topic Overview & Introduction Paragraph' }}</label>
                            <textarea 
                                v-model="lessonForm.content" 
                                class="form-control rounded-3" 
                                rows="4" 
                                placeholder="Describe the lesson context (e.g. Even if you've guided the same tour many times, no two are ever the same...)"
                            ></textarea>
                            <small class="text-muted">This overview will be displayed to students when they open this lesson topic.</small>
                        </div>

                        <!-- Attraction Cover Image (Town Chapters Only) -->
                        <div v-if="module.type === 'town_chapter'" class="mb-4">
                            <label class="form-label fw-bold text-dark">Attraction Image</label>
                            <div class="row g-3 align-items-center">
                                <div class="col-md-8">
                                    <input 
                                        v-model="lessonForm.cover_image" 
                                        type="text" 
                                        class="form-control rounded-3 mb-2" 
                                        placeholder="Image URL (e.g. /assets/images/attraction.jpg)"
                                    >
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-light text-muted">Or upload file</span>
                                        <input 
                                            type="file" 
                                            class="form-control" 
                                            accept="image/*"
                                            @change="handleLessonFileUpload"
                                        >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="border rounded-3 bg-light text-center overflow-hidden position-relative" style="height: 90px;">
                                        <img v-if="lessonImagePreview || lessonForm.cover_image" :src="lessonImagePreview || lessonForm.cover_image" class="w-100 h-100 object-fit-cover">
                                        <div v-else class="h-100 d-flex flex-column align-items-center justify-content-center text-muted small">
                                            <i class="fas fa-image fs-4 mb-1"></i>
                                            <span>No image set</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Key Topics & Subtopics List -->
                        <div v-if="module.type !== 'town_chapter'" class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label class="form-label fw-bold text-dark mb-0">Key Subtopic Points & Guidelines</label>
                                <button type="button" @click="addKeyPoint" class="btn btn-sm btn-outline-success rounded-pill fw-bold">
                                    <i class="fas fa-plus me-1"></i> Add Subtopic
                                </button>
                            </div>

                            <div v-if="lessonForm.key_points.length === 0" class="text-muted small p-3 bg-light rounded-3 text-center border">
                                No key points added. Click "Add Subtopic" to include key bullet points.
                            </div>

                            <div class="d-flex flex-column gap-3">
                                <div 
                                    v-for="(point, pIdx) in lessonForm.key_points" 
                                    :key="pIdx"
                                    class="p-3 border rounded-3 bg-light-subtle position-relative"
                                >
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="badge bg-secondary rounded-pill px-3 py-1 fs-8 fw-bold">Point #{{ pIdx + 1 }}</span>
                                        <button 
                                            type="button" 
                                            @click="removeKeyPoint(pIdx)" 
                                            class="btn btn-sm btn-link text-danger p-0 text-decoration-none"
                                            title="Remove Point"
                                        >
                                            <i class="fas fa-trash me-1"></i> Remove
                                        </button>
                                    </div>

                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <label class="form-label small fw-bold text-muted mb-1">Icon Class</label>
                                            <input 
                                                v-model="point.icon" 
                                                type="text" 
                                                class="form-control form-control-sm rounded-2" 
                                                placeholder="e.g. fas fa-walking"
                                            >
                                        </div>
                                        <div class="col-md-8">
                                            <label class="form-label small fw-bold text-muted mb-1">Subtopic Title</label>
                                            <input 
                                                v-model="point.title" 
                                                type="text" 
                                                class="form-control form-control-sm rounded-2" 
                                                placeholder="e.g. Know the tour style"
                                            >
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label small fw-bold text-muted mb-1">Description / Guideline</label>
                                            <textarea 
                                                v-model="point.description" 
                                                class="form-control form-control-sm rounded-2" 
                                                rows="2" 
                                                placeholder="Walking tours require stamina and weather planning..."
                                            ></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top p-4">
                        <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" @click="closeLessonModal">Cancel</button>
                        <button 
                            type="button" 
                            @click="saveLesson" 
                            class="btn text-white rounded-pill px-4 fw-bold shadow-sm" 
                            style="background-color: #0d4b38;" 
                            :disabled="!lessonForm.title"
                        >
                            <i class="fas fa-save me-1"></i> {{ module.type === 'town_chapter' ? 'Save Attraction' : 'Save Lesson Content' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </EducatorLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useForm, Link, router } from '@inertiajs/vue3';
import EducatorLayout from '@/Layouts/EducatorLayout.vue';

const props = defineProps({
    module: {
        type: Object,
        required: true,
    },
    questionBankCount: {
        type: Number,
        default: 5,
    },
    defaultTownImage: String,
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
    quiz_question_count: props.module.quiz_question_count ?? 0,
    quick_facts: props.module.quick_facts && props.module.quick_facts.length ? props.module.quick_facts : [''],
    video_references: props.module.video_references && props.module.video_references.length ? props.module.video_references : [''],
});

const handleFileUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.cover_image_file = file;
        imagePreview.value = URL.createObjectURL(file);
    }
};

const addQuickFact = () => form.quick_facts.push('');
const removeQuickFact = (index) => form.quick_facts.splice(index, 1);

const addVideoReference = () => form.video_references.push('');
const removeVideoReference = (index) => form.video_references.splice(index, 1);

const submit = () => {
    form.quick_facts = form.quick_facts.filter(fact => fact.trim() !== '');
    form.video_references = form.video_references.filter(url => url.trim() !== '');

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
const lessonError = ref('');
const currentLessonBankCount = computed(() => {
    if (!editingLesson.value) return 0;
    if (editingLesson.value.questions && Array.isArray(editingLesson.value.questions)) {
        return editingLesson.value.questions.length;
    }
    return editingLesson.value.questions_count || 0;
});

const lessonImagePreview = ref(null);

const lessonForm = ref({
    title: '',
    content: '',
    quiz_question_count: 0,
    cover_image: '',
    cover_image_file: null,
    key_points: [
        { title: '', description: '', icon: 'fas fa-check' }
    ]
});

const handleLessonFileUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        lessonForm.value.cover_image_file = file;
        lessonImagePreview.value = URL.createObjectURL(file);
    }
};

const openLessonModal = (lesson = null) => {
    editingLesson.value = lesson;
    lessonError.value = '';
    lessonImagePreview.value = null;
    if (lesson) {
        let parsedPoints = [];
        if (Array.isArray(lesson.key_points)) {
            parsedPoints = JSON.parse(JSON.stringify(lesson.key_points));
        } else if (typeof lesson.key_points === 'string') {
            try { parsedPoints = JSON.parse(lesson.key_points); } catch(e) { parsedPoints = []; }
        }
        
        lessonForm.value = {
            title: lesson.title || '',
            content: lesson.content || '',
            quiz_question_count: lesson.quiz_question_count ?? 0,
            cover_image: lesson.cover_image || '',
            cover_image_file: null,
            key_points: parsedPoints.length > 0 ? parsedPoints : [{ title: '', description: '', icon: 'fas fa-check' }]
        };
    } else {
        lessonForm.value = {
            title: '',
            content: '',
            quiz_question_count: 0,
            cover_image: '',
            cover_image_file: null,
            key_points: [{ title: '', description: '', icon: 'fas fa-check' }]
        };
    }
    showLessonModal.value = true;
};

const addKeyPoint = () => {
    lessonForm.value.key_points.push({ title: '', description: '', icon: 'fas fa-check' });
};

const removeKeyPoint = (index) => {
    lessonForm.value.key_points.splice(index, 1);
};

const closeLessonModal = () => {
    showLessonModal.value = false;
    editingLesson.value = null;
    lessonError.value = '';
    lessonImagePreview.value = null;
    lessonForm.value = {
        title: '',
        content: '',
        quiz_question_count: 0,
        cover_image: '',
        cover_image_file: null,
        key_points: [{ title: '', description: '', icon: 'fas fa-check' }]
    };
};

const saveLesson = () => {
    if (!lessonForm.value.title) return;
    lessonError.value = '';
    
    const payload = {
        title: lessonForm.value.title,
        content: lessonForm.value.content,
        quiz_question_count: lessonForm.value.quiz_question_count,
        cover_image: lessonForm.value.cover_image,
        cover_image_file: lessonForm.value.cover_image_file,
        key_points: lessonForm.value.key_points.filter(p => p.title || p.description)
    };

    if (editingLesson.value) {
        // Update (use POST with _method = PUT for file uploads)
        payload._method = 'PUT';
        router.post(route('educator.modules.lessons.update', { module: props.module.id, lesson: editingLesson.value.id }), payload, { 
            preserveScroll: true, 
            onSuccess: closeLessonModal,
            onError: (errors) => {
                if (errors.quiz_question_count) {
                    lessonError.value = errors.quiz_question_count;
                }
            }
        });
    } else {
        // Create
        router.post(route('educator.modules.lessons.store', props.module.id), payload, { 
            preserveScroll: true, 
            onSuccess: closeLessonModal,
            onError: (errors) => {
                if (errors.quiz_question_count) {
                    lessonError.value = errors.quiz_question_count;
                }
            }
        });
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
.max-h-75vh {
    max-height: 75vh;
}
</style>
