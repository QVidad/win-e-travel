<template>
    <EducatorLayout>
        <!-- Hero Banner Section -->
        <div 
            class="card border-0 text-white p-4 p-md-5 mb-4 shadow-sm" 
            style="background: linear-gradient(135deg, #0d4b38 0%, #155e46 100%); border-radius: 20px;"
        >
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 class="display-6 fw-bold mb-2 text-white" style="font-weight: 800; letter-spacing: -0.5px;">
                        Quiz Question Banks
                    </h1>
                    <p class="mb-0 text-white fst-italic fs-6 opacity-90">
                        Manage multiple-choice question banks and correct answer keys across all CBEA modules.
                    </p>
                </div>

                <button @click="openAddModal" class="btn btn-warning px-4 py-2.5 rounded-pill fw-bold text-dark shadow-sm border-0 d-flex align-items-center gap-2">
                    <i class="fas fa-plus-circle"></i>
                    <span>Add Question</span>
                </button>
            </div>
        </div>

        <!-- Module Selector & Search Filter Bar -->
        <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
            <div class="row g-3 align-items-center">
                <div class="col-md-5">
                    <label class="form-label small fw-bold text-muted mb-1">Select Module Quiz Bank</label>
                    <select v-model="selectedModuleId" class="form-select border-1 rounded-3">
                        <option value="all">All Modules ({{ totalQuestions }} Total Questions)</option>
                        <option v-for="mod in modules" :key="mod.id" :value="mod.id">
                            {{ mod.title }} ({{ mod.questions ? mod.questions.length : 0 }} Qs)
                        </option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted mb-1">Search Question Stem</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" v-model="searchQuery" class="form-control border-start-0 ps-0" placeholder="Search questions by keyword...">
                    </div>
                </div>
                <div class="col-md-3 text-md-end">
                    <span class="badge bg-success-subtle text-success fw-bold p-2.5 rounded-pill border border-success-subtle">
                        <i class="fas fa-layer-group me-1"></i> {{ currentBankCount }} Questions Bank
                    </span>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div v-if="filteredQuestions.length === 0" class="card border-0 shadow-sm rounded-4 bg-white p-5 text-center my-3 text-muted">
            <i class="fas fa-question-circle fa-3x mb-3 text-light"></i>
            <h5 class="fw-bold text-dark mb-1">No Quiz Questions Found</h5>
            <p class="mb-3">No multiple-choice questions match the selected module bank filter or search criteria.</p>
            <div>
                <button @click="openAddModal" class="btn btn-sm text-white rounded-pill px-4 py-2 fw-bold shadow-sm" style="background-color: #0d4b38;">
                    <i class="fas fa-plus me-1"></i> Add First Question Item
                </button>
            </div>
        </div>

        <!-- Question Cards List -->
        <div v-else class="d-flex flex-column gap-3 mb-5">
            <div 
                v-for="(item, idx) in filteredQuestions" 
                :key="item.id" 
                class="card border-0 shadow-sm rounded-4 bg-white p-4 transition-all hover-lift"
            >
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill fs-8 fw-semibold px-3 py-1">
                            <i class="fas fa-book me-1"></i> {{ item.module_title }}
                        </span>
                        <span class="badge bg-light text-muted border rounded-pill fs-8 px-2.5 py-1">
                            Item #{{ idx + 1 }}
                        </span>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <button 
                            @click="openEditModal(item)" 
                            class="btn btn-sm btn-outline-primary rounded-circle action-btn" 
                            title="Edit Question"
                        >
                            <i class="fas fa-edit"></i>
                        </button>
                        <button 
                            @click="deleteQuestion(item.id)" 
                            class="btn btn-sm btn-outline-danger rounded-circle action-btn" 
                            title="Delete Question"
                        >
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </div>

                <h5 class="fw-bold text-dark my-2 fs-6 leading-normal">
                    {{ item.question_text || item.question }}
                </h5>

                <!-- Options A-D Grid -->
                <div class="row g-2 my-2">
                    <div 
                        v-for="(optText, optKey) in getOptionsMap(item)" 
                        :key="optKey" 
                        class="col-md-6"
                    >
                        <div 
                            class="p-3 rounded-3 small border d-flex align-items-center justify-content-between" 
                            :class="isCorrectOption(item, optKey) ? 'bg-success-subtle border-success text-success fw-semibold' : 'border bg-light-subtle rounded-3 text-dark'"
                        >
                            <div>
                                <span class="badge me-2" :class="isCorrectOption(item, optKey) ? 'bg-success text-white' : 'bg-secondary bg-opacity-25 text-dark'">
                                    Option {{ optKey.toUpperCase() }}
                                </span>
                                <span>{{ optText }}</span>
                            </div>
                            <span v-if="isCorrectOption(item, optKey)" class="badge bg-success text-white rounded-pill px-2.5 py-1 small ms-2">
                                <i class="fas fa-check-circle me-1"></i> Correct Key
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Explanation Note -->
                <div v-if="item.explanation" class="mt-2 text-muted small fst-italic pt-2.5 border-top d-flex align-items-start gap-2">
                    <i class="fas fa-info-circle text-success mt-1"></i>
                    <div>
                        <strong class="text-dark">Explanation / Learning Note:</strong> {{ item.explanation }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Add / Edit Question Item Modal -->
        <div v-if="showModal" class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.55); z-index: 1060;">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content rounded-4 shadow-lg border-0">
                    <div class="modal-header text-white rounded-top-4 py-3" style="background-color: #0d4b38;">
                        <h5 class="modal-title fw-bold d-flex align-items-center gap-2 fs-6">
                            <i class="fas" :class="isEditing ? 'fa-edit text-warning' : 'fa-plus-circle text-warning'"></i>
                            <span>{{ isEditing ? 'Edit Quiz Question' : 'Add Question to Quiz Bank' }}</span>
                        </h5>
                        <button type="button" class="btn-close btn-close-white" @click="closeModal"></button>
                    </div>

                    <form @submit.prevent="submitQuestionForm">
                        <div class="modal-body p-4">
                            <!-- Module Selection -->
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-dark">Target Module <span class="text-danger">*</span></label>
                                <select v-model="questionForm.module_id" class="form-select rounded-3" required>
                                    <option value="" disabled>Select target module bank...</option>
                                    <option v-for="mod in modules" :key="mod.id" :value="mod.id">
                                        {{ mod.title }}
                                    </option>
                                </select>
                            </div>

                            <!-- Question Stem -->
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-dark">Question Text Stem <span class="text-danger">*</span></label>
                                <textarea 
                                    v-model="questionForm.question_text" 
                                    class="form-control rounded-3" 
                                    rows="3" 
                                    required 
                                    placeholder="Enter multiple choice question statement..."
                                ></textarea>
                            </div>

                            <!-- Options A-D Inputs -->
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-dark mb-2">
                                    Answer Options (Fill in all 4 options) <span class="text-danger">*</span>
                                </label>
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text fw-bold bg-light">A</span>
                                            <input v-model="questionForm.option_a" type="text" class="form-control" placeholder="Option A text..." required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text fw-bold bg-light">B</span>
                                            <input v-model="questionForm.option_b" type="text" class="form-control" placeholder="Option B text..." required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text fw-bold bg-light">C</span>
                                            <input v-model="questionForm.option_c" type="text" class="form-control" placeholder="Option C text..." required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text fw-bold bg-light">D</span>
                                            <input v-model="questionForm.option_d" type="text" class="form-control" placeholder="Option D text..." required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Correct Answer Designation -->
                            <div class="mb-3 bg-light p-3 rounded-3 border">
                                <label class="form-label small fw-bold text-dark mb-2">
                                    Correct Answer Key <span class="text-danger">*</span>
                                </label>
                                <div class="d-flex align-items-center gap-4 flex-wrap">
                                    <div v-for="opt in ['a', 'b', 'c', 'd']" :key="opt" class="form-check">
                                        <input 
                                            class="form-check-input" 
                                            type="radio" 
                                            :id="'opt_' + opt" 
                                            :value="opt" 
                                            v-model="questionForm.correct_option"
                                            name="correct_option"
                                        >
                                        <label class="form-check-label fw-bold text-dark cursor-pointer" :for="'opt_' + opt">
                                            Option {{ opt.toUpperCase() }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Explanation / Learning Note -->
                            <div class="mb-2">
                                <label class="form-label small fw-bold text-dark">Explanation / Learning Note (Optional)</label>
                                <textarea 
                                    v-model="questionForm.explanation" 
                                    class="form-control rounded-3" 
                                    rows="2" 
                                    placeholder="Provide context or explanation shown to students after answering..."
                                ></textarea>
                            </div>
                        </div>

                        <div class="modal-footer bg-light rounded-bottom-4">
                            <button type="button" class="btn btn-light rounded-pill px-4" @click="closeModal">Cancel</button>
                            <button type="submit" class="btn text-white rounded-pill px-4 fw-bold shadow-sm" style="background-color: #0d4b38;" :disabled="questionForm.processing">
                                <i class="fas fa-save me-1"></i> {{ isEditing ? 'Update Question' : 'Save to Quiz Bank' }}
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
const showModal = ref(false);
const isEditing = ref(false);
const currentEditId = ref(null);

const questionForm = useForm({
    module_id: '',
    course_module_id: '',
    question_text: '',
    option_a: '',
    option_b: '',
    option_c: '',
    option_d: '',
    correct_option: 'a',
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
        const stem = q.question_text || q.question || '';
        const matchesSearch = stem.toLowerCase().includes(searchQuery.value.toLowerCase());
        return matchesModule && matchesSearch;
    });
});

const currentBankCount = computed(() => {
    if (selectedModuleId.value === 'all') {
        return allQuestionsList.value.length;
    }
    const targetModule = props.modules.find(m => m.id == selectedModuleId.value);
    return targetModule && targetModule.questions ? targetModule.questions.length : 0;
});

const getOptionsMap = (item) => {
    if (item.option_a || item.option_b || item.option_c || item.option_d) {
        return {
            a: item.option_a || '',
            b: item.option_b || '',
            c: item.option_c || '',
            d: item.option_d || '',
        };
    }
    if (Array.isArray(item.options)) {
        return {
            a: item.options[0] || '',
            b: item.options[1] || '',
            c: item.options[2] || '',
            d: item.options[3] || '',
        };
    }
    return { a: '', b: '', c: '', d: '' };
};

const isCorrectOption = (item, optKey) => {
    if (item.correct_option) {
        return item.correct_option.toLowerCase() === optKey.toLowerCase();
    }
    const idxMap = { a: 0, b: 1, c: 2, d: 3 };
    return item.correct_answer_index === idxMap[optKey];
};

const openAddModal = () => {
    isEditing.value = false;
    currentEditId.value = null;
    questionForm.reset();
    questionForm.module_id = selectedModuleId.value !== 'all' ? selectedModuleId.value : (props.modules[0]?.id || '');
    questionForm.course_module_id = questionForm.module_id;
    questionForm.correct_option = 'a';
    showModal.value = true;
};

const openEditModal = (item) => {
    isEditing.value = true;
    currentEditId.value = item.id;
    
    const opts = getOptionsMap(item);
    let correct = 'a';
    if (item.correct_option) {
        correct = item.correct_option.toLowerCase();
    } else if (typeof item.correct_answer_index === 'number') {
        const reverseMap = ['a', 'b', 'c', 'd'];
        correct = reverseMap[item.correct_answer_index] || 'a';
    }

    questionForm.module_id = item.module_id;
    questionForm.course_module_id = item.module_id;
    questionForm.question_text = item.question_text || item.question || '';
    questionForm.option_a = opts.a;
    questionForm.option_b = opts.b;
    questionForm.option_c = opts.c;
    questionForm.option_d = opts.d;
    questionForm.correct_option = correct;
    questionForm.explanation = item.explanation || '';
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    questionForm.reset();
};

const submitQuestionForm = () => {
    questionForm.course_module_id = questionForm.module_id;
    if (isEditing.value && currentEditId.value) {
        questionForm.put(route('educator.quizzes.update', currentEditId.value), {
            onSuccess: () => {
                closeModal();
            },
        });
    } else {
        questionForm.post(route('educator.quizzes.store'), {
            onSuccess: () => {
                closeModal();
            },
        });
    }
};

const deleteQuestion = (id) => {
    if (confirm('Are you sure you want to remove this question item from the question bank?')) {
        router.delete(route('educator.quizzes.destroy', id), {
            preserveScroll: true,
        });
    }
};
</script>

<style scoped>
.fs-7 {
    font-size: 0.8rem;
}
.fs-8 {
    font-size: 0.725rem;
}
.cursor-pointer {
    cursor: pointer;
}
.action-btn {
    width: 32px;
    height: 32px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
.hover-lift {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.hover-lift:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08) !important;
}

.bg-success-subtle {
    background-color: #d1e7dd !important;
}
.border-success-subtle {
    border-color: #a3cfbb !important;
}
.bg-light-subtle {
    background-color: #f8f9fa !important;
}
</style>
