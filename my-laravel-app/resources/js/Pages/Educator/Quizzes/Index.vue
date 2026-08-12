<template>
    <EducatorLayout>
        <!-- Hero Banner Section -->
        <div 
            class="card border-0 text-white p-4 p-md-5 mb-4 shadow-sm" 
            style="background: linear-gradient(135deg, #0d4b38 0%, #155e46 100%); border-radius: 20px;"
        >
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <span class="badge bg-warning text-dark px-3 py-1 rounded-pill fw-bold mb-2">Quiz Management</span>
                    <h1 class="display-6 fw-bold mb-2 text-white" style="font-weight: 800; letter-spacing: -0.5px;">
                        Question Bank & Quiz Pool Manager
                    </h1>
                    <p class="mb-0 text-white fst-italic fs-6 opacity-90">
                        "Interface to add and organize multiple-choice questions into the randomized pool per module."
                    </p>
                </div>

                <button @click="showAddModal = true" class="btn btn-warning px-4 py-2 rounded-pill fw-bold text-dark shadow-sm border-0">
                    <i class="fas fa-plus me-1"></i> Add Pool Question
                </button>
            </div>
        </div>

        <!-- Module Selector & Search Filter -->
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4 mb-4">
            <div class="row g-3 align-items-center">
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-dark mb-1">Select Module Pool</label>
                    <select v-model="selectedModuleId" class="form-select rounded-pill">
                        <option value="all">All Modules ({{ totalQuestions }} Total Questions)</option>
                        <option v-for="mod in modules" :key="mod.id" :value="mod.id">
                            {{ mod.title }} ({{ mod.questions ? mod.questions.length : 0 }} Qs)
                        </option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-dark mb-1">Search Question Stem</label>
                    <div class="position-relative">
                        <input 
                            v-model="searchQuery" 
                            type="text" 
                            class="form-control rounded-pill ps-4" 
                            placeholder="Filter questions by keyword..."
                        >
                        <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted small"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Question Cards List -->
        <div v-if="filteredQuestions.length === 0" class="card border-0 shadow-sm rounded-4 bg-white p-5 text-center my-3 text-muted">
            <i class="fas fa-question-circle fa-3x mb-3 text-light"></i>
            <h5 class="fw-bold text-dark mb-1">No Quiz Questions Found</h5>
            <p class="mb-3">No multiple-choice questions have been added for the selected criteria.</p>
            <div>
                <button @click="showAddModal = true" class="btn btn-sm text-white rounded-pill px-4 py-2 fw-bold" style="background-color: #0d4b38;">
                    <i class="fas fa-plus me-1"></i> Add First Question Item
                </button>
            </div>
        </div>

        <div v-else class="d-flex flex-column gap-3 mb-5">
            <div 
                v-for="(item, idx) in filteredQuestions" 
                :key="item.id" 
                class="card border-0 shadow-sm rounded-4 bg-white p-4"
            >
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <span class="badge bg-success bg-opacity-10 text-success border me-2 rounded-pill fs-8">
                            {{ item.module_title }}
                        </span>
                        <span class="badge bg-secondary rounded-pill">Item #{{ idx + 1 }}</span>
                    </div>

                    <button @click="deleteQuestion(item.id)" class="btn btn-sm btn-outline-danger rounded-circle" title="Delete Question">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>

                <h5 class="fw-bold text-dark my-2">{{ item.question }}</h5>

                <div class="row g-2 my-2">
                    <div 
                        v-for="(opt, optIdx) in item.options" 
                        :key="optIdx" 
                        class="col-md-6"
                    >
                        <div 
                            class="p-2.5 rounded-3 small border" 
                            :class="optIdx === item.correct_answer_index ? 'bg-success bg-opacity-10 border-success fw-bold text-success' : 'bg-light text-muted'"
                        >
                            <strong>{{ String.fromCharCode(65 + optIdx) }}.</strong> {{ opt }}
                            <i v-if="optIdx === item.correct_answer_index" class="fas fa-check-circle ms-1 text-success"></i>
                        </div>
                    </div>
                </div>

                <div v-if="item.explanation" class="mt-2 text-muted small fst-italic pt-2 border-top">
                    <i class="fas fa-info-circle me-1 text-primary"></i> <strong>Explanation:</strong> {{ item.explanation }}
                </div>
            </div>
        </div>

        <!-- Add Question Item Modal -->
        <div v-if="showAddModal" class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5); z-index: 1060;">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content rounded-4 shadow-lg border-0">
                    <div class="modal-header text-white rounded-top-4 py-3" style="background-color: #0d4b38;">
                        <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
                            <i class="fas fa-plus-circle text-warning"></i>
                            <span>Add Question to Quiz Pool</span>
                        </h5>
                        <button type="button" class="btn-close btn-close-white" @click="showAddModal = false"></button>
                    </div>

                    <form @submit.prevent="submitAddQuestion">
                        <div class="modal-body p-4">
                            <!-- Module Selection -->
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-dark">Target Module</label>
                                <select v-model="questionForm.course_module_id" class="form-select rounded-3" required>
                                    <option value="" disabled>Select course module...</option>
                                    <option v-for="mod in modules" :key="mod.id" :value="mod.id">
                                        {{ mod.title }}
                                    </option>
                                </select>
                            </div>

                            <!-- Question Stem -->
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-dark">Question Stem</label>
                                <textarea v-model="questionForm.question" class="form-control rounded-3" rows="3" required placeholder="Enter multiple choice question text..."></textarea>
                            </div>

                            <!-- Options A-D -->
                            <label class="form-label small fw-bold text-dark">Answer Options (Click checkmark to select correct answer)</label>
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

                            <!-- Explanation -->
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-dark">Explanation / Feedback (Optional)</label>
                                <input v-model="questionForm.explanation" type="text" class="form-control form-control-sm rounded-3" placeholder="Explanation for students after submission...">
                            </div>
                        </div>

                        <div class="modal-footer bg-light rounded-bottom-4">
                            <button type="button" class="btn btn-light rounded-pill px-4" @click="showAddModal = false">Cancel</button>
                            <button type="submit" class="btn text-white rounded-pill px-4 fw-bold" style="background-color: #0d4b38;" :disabled="questionForm.processing">
                                Save to Pool Bank
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
import { useForm, router } from '@inertiajs/vue3';
import EducatorLayout from '@/Layouts/EducatorLayout.vue';

const props = defineProps({
    modules: {
        type: Array,
        default: () => [],
    },
    totalQuestions: {
        type: Number,
        default: 0,
    },
});

const selectedModuleId = ref('all');
const searchQuery = ref('');
const showAddModal = ref(false);

const questionForm = useForm({
    course_module_id: '',
    question: '',
    options: ['', '', '', ''],
    correct_answer_index: 0,
    explanation: '',
});

const allQuestionsList = computed(() => {
    const list = [];
    (props.modules || []).forEach(m => {
        if (m.questions && m.questions.length > 0) {
            m.questions.forEach(q => {
                list.push({
                    ...q,
                    module_id: m.id,
                    module_title: m.title,
                });
            });
        }
    });
    return list;
});

const filteredQuestions = computed(() => {
    return allQuestionsList.value.filter(q => {
        const matchesModule = selectedModuleId.value === 'all' || q.module_id == selectedModuleId.value;
        const matchesSearch = q.question.toLowerCase().includes(searchQuery.value.toLowerCase());
        return matchesModule && matchesSearch;
    });
});

const submitAddQuestion = () => {
    questionForm.post(route('educator.quizzes.store'), {
        onSuccess: () => {
            showAddModal.value = false;
            questionForm.reset();
        },
    });
};

const deleteQuestion = (id) => {
    if (confirm('Are you sure you want to remove this question item from the pool bank?')) {
        router.delete(route('educator.quizzes.destroy', id), {
            preserveScroll: true,
        });
    }
};
</script>

<style scoped>
.fs-8 {
    font-size: 0.7rem;
}
</style>
