<template>
    <AdminLayout>
        <!-- Dark Teal Hero Banner Section -->
        <div 
            class="card border-0 text-white p-4 p-md-5 mb-4 shadow-sm" 
            style="background: linear-gradient(135deg, #0d4b38 0%, #155e46 100%); border-radius: 20px;"
        >
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <span class="badge bg-warning text-dark px-3 py-1 rounded-pill fw-bold mb-2">Curriculum Management</span>
                    <h1 class="display-6 fw-extrabold mb-2 text-white" style="font-weight: 800; letter-spacing: -0.5px;">
                        Curriculum & Module Management
                    </h1>
                    <p class="mb-0 text-white fst-italic fs-6 opacity-90">
                        "Overview of global learning paths, foundation modules, and municipality chapters."
                    </p>
                </div>
            </div>
        </div>

        <!-- Global Rule Banner: >= 90% Passing & CBEA Certificate -->
        <div class="alert alert-warning border-0 rounded-4 shadow-sm p-3 mb-4 d-flex align-items-center gap-3" style="background-color: #fff8e6; border-left: 5px solid #d4a017 !important;">
            <div class="rounded-circle bg-warning bg-opacity-25 p-3 text-dark d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; flex-shrink: 0;">
                <i class="fas fa-award fa-lg text-warning-emphasis"></i>
            </div>
            <div>
                <h6 class="fw-bold mb-1 text-dark">Global Student Mastery Rule</h6>
                <p class="mb-0 small text-dark opacity-90">
                    Students must score <strong>&ge; 90%</strong> on randomized pool questions to unlock the next chapter and qualify for the <strong>CBEA Tour Guiding Certificate</strong>.
                </p>
            </div>
        </div>

        <!-- Summary Stats Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm rounded-4 p-3 text-center bg-white">
                    <h3 class="fw-bold text-dark mb-0">{{ stats?.totalModules || 0 }}</h3>
                    <small class="text-muted">Total Modules</small>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm rounded-4 p-3 text-center bg-white">
                    <h3 class="fw-bold text-success mb-0" style="color: #0d4b38 !important;">{{ stats?.publishedCount || 0 }}</h3>
                    <small class="text-muted">Published</small>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm rounded-4 p-3 text-center bg-white">
                    <h3 class="fw-bold text-secondary mb-0">{{ stats?.draftCount || 0 }}</h3>
                    <small class="text-muted">Draft State</small>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm rounded-4 p-3 text-center bg-white">
                    <h3 class="fw-bold text-primary mb-0">{{ stats?.totalQuestions || 0 }}</h3>
                    <small class="text-muted">Quiz Pool Questions</small>
                </div>
            </div>
        </div>

        <!-- Tab Navigation (Foundation Modules vs Dare to Discover Towns) -->
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

        <!-- Module Grid View -->
        <div class="row g-4 mb-5">
            <div 
                v-for="mod in displayedModules" 
                :key="mod.id" 
                class="col-md-6 col-lg-4"
            >
                <div class="card border-0 shadow-sm rounded-4 h-100 bg-white transition-all hover-lift overflow-hidden">
                    <div class="card-body p-4 d-flex flex-column">
                        <!-- Module Header & Status Switch -->
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div 
                                class="rounded-circle d-flex align-items-center justify-content-center text-white" 
                                style="width: 46px; height: 46px; background-color: #0d4b38;"
                            >
                                <i :class="mod.icon || 'fas fa-book'"></i>
                            </div>

                            <div class="form-check form-switch d-flex align-items-center gap-2 m-0 p-0" title="Toggle Draft/Published">
                                <span class="badge rounded-pill px-2.5 py-1" :class="mod.status === 'published' ? 'bg-success text-white' : 'bg-warning text-dark'">
                                    {{ mod.status === 'published' ? 'Published' : 'Draft' }}
                                </span>
                                <input 
                                    class="form-check-input ms-0" 
                                    type="checkbox" 
                                    role="switch"
                                    :checked="mod.status === 'published'"
                                    @change="toggleStatus(mod)"
                                    style="cursor: pointer; width: 2.2em; height: 1.2em;"
                                >
                            </div>
                        </div>

                        <!-- Title & Description -->
                        <h5 class="fw-bold text-dark mb-1">{{ mod.title }}</h5>
                        <p class="text-muted small mb-3 flex-grow-1" style="min-height: 40px;">
                            {{ mod.description }}
                        </p>

                        <!-- Question Pool Badge -->
                        <div class="d-flex align-items-center justify-content-between bg-light rounded-3 p-2 px-3 mb-3">
                            <small class="fw-bold text-dark">
                                <i class="fas fa-question-circle text-primary me-1"></i> Question Pool
                            </small>
                            <span class="badge bg-primary rounded-pill">{{ mod.questions ? mod.questions.length : 0 }} Qs</span>
                        </div>

                        <!-- Audit Trail Badge -->
                        <div class="pt-2 border-top mt-auto">
                            <small class="text-muted fs-8 d-block text-truncate">
                                <i class="fas fa-history me-1 text-secondary"></i>
                                <span v-if="mod.updated_by && mod.updated_by.name">
                                    Last updated by <strong>{{ mod.updated_by.name }}</strong> on {{ formatDate(mod.last_modified_at || mod.updated_at) }}
                                </span>
                                <span v-else>
                                    Updated on {{ formatDate(mod.updated_at) }}
                                </span>
                            </small>
                        </div>
                    </div>

                    <!-- Card Footer Actions -->
                    <div class="card-footer bg-light border-0 px-4 py-3 text-end">
                        <button 
                            @click="openQuestionPoolModal(mod)" 
                            class="btn btn-sm text-white rounded-pill px-3 fw-bold shadow-xs" 
                            style="background-color: #0d4b38;"
                        >
                            <i class="fas fa-tasks me-1"></i> Manage Quiz Pool
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Question Pool Management Modal -->
        <div v-if="selectedModule" class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5); z-index: 1060;">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content rounded-4 shadow-lg border-0">
                    <div class="modal-header text-white rounded-top-4 py-3" style="background-color: #0d4b38;">
                        <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
                            <i class="fas fa-tasks text-warning"></i>
                            <span>Quiz Question Pool &mdash; {{ selectedModule.title }}</span>
                        </h5>
                        <button type="button" class="btn-close btn-close-white" @click="selectedModule = null"></button>
                    </div>

                    <div class="modal-body p-4">
                        <!-- Questions Pool List -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold text-dark mb-0">
                                Existing Pool Questions ({{ selectedModule.questions ? selectedModule.questions.length : 0 }})
                            </h6>
                            <button @click="showAddQuestionForm = !showAddQuestionForm" class="btn btn-sm btn-outline-success rounded-pill px-3 fw-bold">
                                <i class="fas" :class="showAddQuestionForm ? 'fa-minus me-1' : 'fa-plus me-1'"></i>
                                {{ showAddQuestionForm ? 'Close Question Form' : 'Add Pool Question' }}
                            </button>
                        </div>

                        <!-- Add Question Form Collapse -->
                        <div v-if="showAddQuestionForm" class="card border border-success border-opacity-50 rounded-4 p-3 bg-light mb-4 shadow-xs">
                            <h6 class="fw-bold text-dark mb-3"><i class="fas fa-plus-circle text-success me-1"></i> New Pool Question</h6>
                            <form @submit.prevent="submitAddQuestion">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-dark">Question Stem</label>
                                    <textarea v-model="questionForm.question" class="form-control rounded-3" rows="2" required placeholder="Enter multiple choice question stem..."></textarea>
                                </div>

                                <div class="row g-2 mb-3">
                                    <div class="col-md-6" v-for="(opt, idx) in questionForm.options" :key="idx">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text fw-bold" :class="questionForm.correct_answer_index === idx ? 'bg-success text-white' : 'bg-white'">
                                                {{ String.fromCharCode(65 + idx) }}
                                            </span>
                                            <input v-model="questionForm.options[idx]" type="text" class="form-control" placeholder="Option text..." required>
                                            <button 
                                                type="button" 
                                                class="btn btn-outline-secondary" 
                                                :class="{ 'btn-success text-white active': questionForm.correct_answer_index === idx }"
                                                @click="questionForm.correct_answer_index = idx"
                                                title="Mark as correct answer"
                                            >
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-dark">Answer Explanation (Optional)</label>
                                    <input v-model="questionForm.explanation" type="text" class="form-control form-control-sm rounded-3" placeholder="Brief rationale for students after submission...">
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-sm btn-success rounded-pill px-4 fw-bold" :disabled="questionForm.processing">
                                        Save to Quiz Pool
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Questions List -->
                        <div v-if="!selectedModule.questions || selectedModule.questions.length === 0" class="text-center py-4 text-muted">
                            <i class="fas fa-question-circle fa-2x mb-2 opacity-50"></i>
                            <p class="mb-0">No quiz questions added to this module's shared pool yet.</p>
                        </div>

                        <div v-else class="d-flex flex-column gap-3">
                            <div 
                                v-for="(q, index) in selectedModule.questions" 
                                :key="q.id" 
                                class="card border rounded-3 p-3 bg-white shadow-xs"
                            >
                                <div class="d-flex justify-content-between align-items-start">
                                    <h6 class="fw-bold text-dark mb-2">
                                        <span class="badge bg-secondary me-2">Q{{ index + 1 }}</span>
                                        {{ q.question }}
                                    </h6>
                                    <button @click="deleteQuestion(q)" class="btn btn-sm btn-outline-danger rounded-circle ms-2" title="Delete Question">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>

                                <div class="row g-2 my-1">
                                    <div 
                                        v-for="(opt, optIdx) in q.options" 
                                        :key="optIdx" 
                                        class="col-md-6"
                                    >
                                        <div 
                                            class="p-2 rounded-2 small border" 
                                            :class="optIdx === q.correct_answer_index ? 'bg-success bg-opacity-10 border-success fw-bold text-success' : 'bg-light text-muted'"
                                        >
                                            <strong>{{ String.fromCharCode(65 + optIdx) }}.</strong> {{ opt }}
                                            <i v-if="optIdx === q.correct_answer_index" class="fas fa-check-circle ms-1 text-success"></i>
                                        </div>
                                    </div>
                                </div>

                                <div v-if="q.explanation" class="mt-2 text-muted small fst-italic">
                                    <i class="fas fa-info-circle me-1 text-primary"></i> {{ q.explanation }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer bg-light rounded-bottom-4">
                        <button type="button" class="btn btn-secondary rounded-pill px-4" @click="selectedModule = null">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    foundationModules: {
        type: Array,
        default: () => [],
    },
    townModules: {
        type: Array,
        default: () => [],
    },
    stats: {
        type: Object,
        default: () => ({ totalModules: 0, publishedCount: 0, draftCount: 0, totalQuestions: 0 }),
    },
});

const activeTab = ref('foundation');
const searchQuery = ref('');
const selectedModule = ref(null);
const showAddQuestionForm = ref(false);

const questionForm = useForm({
    question: '',
    options: ['', '', '', ''],
    correct_answer_index: 0,
    explanation: '',
});

const displayedModules = computed(() => {
    const sourceList = activeTab.value === 'foundation' ? props.foundationModules : props.townModules;
    return sourceList.filter(m => 
        m.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        (m.description && m.description.toLowerCase().includes(searchQuery.value.toLowerCase()))
    );
});

const toggleStatus = (module) => {
    router.patch(route('admin.modules.toggle', module.id), {}, {
        preserveScroll: true,
    });
};

const openQuestionPoolModal = (module) => {
    selectedModule.value = module;
    showAddQuestionForm.value = false;
    questionForm.reset();
};

const submitAddQuestion = () => {
    if (!selectedModule.value) return;

    questionForm.post(route('admin.modules.questions.store', selectedModule.value.id), {
        onSuccess: () => {
            questionForm.reset();
            showAddQuestionForm.value = false;
        },
        preserveScroll: true,
    });
};

const deleteQuestion = (question) => {
    if (confirm('Are you sure you want to remove this question from the shared pool?')) {
        router.delete(route('admin.modules.questions.destroy', question.id), {
            preserveScroll: true,
        });
    }
};

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
