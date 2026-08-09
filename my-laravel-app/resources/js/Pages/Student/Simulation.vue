<template>
    <StudentLayout>
        <div class="simulation-container py-4">
            <div class="container">
                <!-- Page Header matching simulation.html -->
                <div class="page-header">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <h2 class="fw-bold mb-2">
                                <i class="fas fa-mountain me-2"></i>
                                Adventure Awaits
                            </h2>
                            <p class="mb-0 opacity-90">Put your tour guiding skills to the test in realistic interactive simulation scenarios</p>
                        </div>
                        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                            <div class="d-inline-block bg-white bg-opacity-25 rounded-4 px-4 py-2 fw-bold">
                                <i class="fas fa-smile text-warning me-1"></i> Satisfaction: {{ satisfactionScore }}%
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Simulation Steps Breadcrumb matching simulation.html -->
                <div class="simulation-steps">
                    <div v-for="(step, idx) in steps" :key="idx" class="step-item" :class="{ completed: idx < currentStepIndex, active: idx === currentStepIndex }">
                        <div class="step-number">
                            <i v-if="idx < currentStepIndex" class="fas fa-check"></i>
                            <span v-else>{{ idx + 1 }}</span>
                        </div>
                        <small class="fw-bold d-block text-dark">{{ step.title }}</small>
                    </div>
                </div>

                <!-- Active Simulation Card Interface -->
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
                            <div class="position-relative">
                                <img :src="currentStepData.image" :alt="currentStepData.title" style="height: 340px; width: 100%; object-fit: cover;">
                                <div class="position-absolute bottom-0 start-0 end-0 p-3 bg-dark bg-opacity-75 text-white d-flex justify-content-between align-items-center">
                                    <span class="fw-bold"><i class="fas fa-map-marker-alt me-2 text-warning"></i>{{ currentStepData.location }}</span>
                                    <span class="badge bg-warning text-dark">Step {{ currentStepIndex + 1 }} of {{ steps.length }}</span>
                                </div>
                            </div>

                            <div class="card-body p-4">
                                <div class="tourist-question-card">
                                    <h5 class="fw-bold text-white mb-2"><i class="fas fa-user-circle me-2"></i>Tourist Scenario Prompt</h5>
                                    <p class="mb-0 opacity-90 fs-6">{{ currentStepData.prompt }}</p>
                                </div>

                                <h6 class="fw-bold text-dark mb-3"><i class="fas fa-comments me-2 text-success"></i>Select Your Commentary Response:</h6>
                                <div class="d-grid gap-3">
                                    <button
                                        v-for="(option, idx) in currentStepData.options"
                                        :key="idx"
                                        type="button"
                                        class="btn btn-outline-secondary text-start p-3 rounded-3 option-btn"
                                        :class="{ selected: selectedOptionIndex === idx }"
                                        @click="chooseOption(option, idx)"
                                        :disabled="stepAnswered"
                                    >
                                        <strong class="text-success me-2">{{ String.fromCharCode(65 + idx) }}.</strong> {{ option.text }}
                                    </button>
                                </div>

                                <!-- Keyword Cloud matching simulation.html -->
                                <div class="mt-4">
                                    <h6 class="fw-bold text-dark mb-2"><i class="fas fa-tags me-2 text-primary"></i>Key Information Keywords:</h6>
                                    <div class="keyword-cloud">
                                        <span v-for="kw in currentStepData.keywords" :key="kw" class="keyword-tag" :class="stepAnswered ? 'covered' : ''">
                                            <i class="fas fa-check me-1" v-if="stepAnswered"></i>{{ kw }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Feedback Section -->
                                <div v-if="feedbackText" class="mt-4 p-3 rounded-3 border" :class="feedbackIsGood ? 'bg-success bg-opacity-10 border-success' : 'bg-warning bg-opacity-10 border-warning'">
                                    <h6 class="fw-bold mb-1" :class="feedbackIsGood ? 'text-success' : 'text-dark'">
                                        <i :class="feedbackIsGood ? 'fas fa-check-circle text-success' : 'fas fa-exclamation-triangle text-warning'" class="me-2"></i>Feedback
                                    </h6>
                                    <p class="mb-3 small text-dark">{{ feedbackText }}</p>
                                    <button @click="proceedNextStep" class="btn btn-success rounded-pill fw-bold px-4">
                                        {{ currentStepIndex + 1 < steps.length ? 'Next Step' : 'Finish Simulation' }} <i class="fas fa-arrow-right ms-1"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Side Facilitator Panel -->
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 p-4 text-center bg-white">
                            <img src="/assets/images/facilitator-female.jpg" alt="Guide Maria" class="rounded-circle mx-auto mb-3 shadow border border-3 border-warning" style="width: 90px; height: 90px; object-fit: cover;">
                            <h5 class="fw-bold text-dark mb-1">Guide Maria</h5>
                            <small class="text-success fw-bold d-block mb-3">Facilitator</small>
                            <div class="p-3 bg-light rounded-3 text-start mb-3 border">
                                <small class="text-muted d-block mb-1 fw-bold">FACILITATOR TIP:</small>
                                <small class="text-secondary">Emphasize architectural history, earthquake baroque construction, and local cultural significance during site commentaries.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Simulation Complete Modal -->
        <div v-if="showCompleteModal" class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.7);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 shadow-lg border-0">
                    <div class="modal-body text-center p-4">
                        <i class="fas fa-trophy text-warning display-3 mb-3"></i>
                        <h3 class="fw-bold text-success mb-2">Simulation Complete!</h3>
                        <p class="text-muted mb-4">You successfully completed the Ilocos Norte Tour Guiding Scenario.</p>

                        <div class="bg-light p-3 rounded-4 mb-4 border">
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted d-block">Satisfaction Score</small>
                                    <h3 class="fw-bold text-success mb-0">{{ satisfactionScore }}%</h3>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">XP Points Earned</small>
                                    <h3 class="fw-bold text-warning mb-0">+{{ totalXpEarned }} XP</h3>
                                </div>
                            </div>
                        </div>

                        <Link :href="route('dashboard')" class="btn btn-success rounded-pill px-5 fw-bold">
                            Return to Dashboard
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </StudentLayout>
</template>

<script setup>
import StudentLayout from '@/Layouts/StudentLayout.vue';
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const currentStepIndex = ref(0);
const satisfactionScore = ref(90);
const totalXpEarned = ref(150);
const stepAnswered = ref(false);
const selectedOptionIndex = ref(null);
const feedbackText = ref('');
const feedbackIsGood = ref(true);
const showCompleteModal = ref(false);

const steps = [
    { title: 'Preparation' },
    { title: 'Briefing' },
    { title: 'Guiding' },
    { title: 'Conclusion' },
];

const scenarioData = [
    {
        location: 'St. William Cathedral & Sinking Bell Tower',
        title: 'Arrival in Laoag City Center',
        prompt: 'Your tour group steps out of the bus in front of the Sinking Bell Tower. A tourist asks: "Why is this bell tower located so far away from the main church structure?" How do you respond?',
        image: '/assets/images/Laoag.jpg',
        keywords: ['Earthquake Baroque', 'Augustinian Friars', 'Sandy Foundation', '85 Meters Distance'],
        options: [
            {
                text: 'Explain that Augustinian friars built it 85 meters away due to earthquake precautions and sandy ground foundation conditions.',
                score: 10,
                feedback: 'Excellent response! Accurate historical context regarding earthquake baroque design.',
                isGood: true,
            },
            {
                text: 'Tell them it was accidentally built in the wrong location by Spanish architects.',
                score: -10,
                feedback: 'Incorrect. The placement was deliberate due to soil and structural stability considerations.',
                isGood: false,
            },
        ],
    },
    {
        location: 'Paoay UNESCO World Heritage Church',
        title: 'Guiding at Paoay Church',
        prompt: 'As you approach Paoay Church, guests are amazed by the thick buttresses. What key feature should you highlight in your commentary?',
        image: '/assets/images/Paoay.jpg',
        keywords: ['24 Buttresses', 'UNESCO Site', 'Coral Stone', 'Sugar Cane Mortar'],
        options: [
            {
                text: 'Highlight the 24 massive coral stone buttresses built to withstand severe seismic activity, inscribing it into UNESCO World Heritage list.',
                score: 10,
                feedback: 'Outstanding commentary! Guests are impressed by your heritage knowledge.',
                isGood: true,
            },
            {
                text: 'Focus only on taking photos and skip explaining the architectural history.',
                score: -5,
                feedback: 'Tourists appreciate photo opportunities, but expected historical commentary.',
                isGood: false,
            },
        ],
    },
];

const currentStepData = computed(() => scenarioData[Math.min(currentStepIndex.value, scenarioData.length - 1)]);

const chooseOption = (option, idx) => {
    selectedOptionIndex.value = idx;
    stepAnswered.value = true;
    satisfactionScore.value = Math.min(100, Math.max(0, satisfactionScore.value + option.score));
    feedbackText.value = option.feedback;
    feedbackIsGood.value = option.isGood;
};

const proceedNextStep = () => {
    if (currentStepIndex.value + 1 < scenarioData.length) {
        currentStepIndex.value++;
        stepAnswered.value = false;
        selectedOptionIndex.value = null;
        feedbackText.value = '';
    } else {
        showCompleteModal.value = true;
    }
};
</script>

<style scoped>
.simulation-container {
    background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
    min-height: 100vh;
}

.page-header {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    border-radius: 20px;
    padding: 30px;
    color: white;
    margin: 30px 0;
}

.simulation-steps {
    display: flex;
    justify-content: space-between;
    margin: 25px 0 35px;
}

.step-item {
    text-align: center;
    flex: 1;
    position: relative;
}

.step-item:not(:last-child)::after {
    content: '';
    position: absolute;
    top: 25px;
    right: -50%;
    width: 100%;
    height: 3px;
    background: #dee2e6;
    z-index: 1;
}

.step-item.completed:not(:last-child)::after {
    background: #28a745;
}

.step-number {
    width: 50px;
    height: 50px;
    background: white;
    border: 3px solid #dee2e6;
    color: #6c757d;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.2rem;
    margin: 0 auto 10px;
    position: relative;
    z-index: 2;
}

.step-item.active .step-number {
    border-color: #0a472e;
    background: #0a472e;
    color: white;
}

.step-item.completed .step-number {
    border-color: #28a745;
    background: #28a745;
    color: white;
}

.tourist-question-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px;
    padding: 20px 25px;
    margin-bottom: 20px;
}

.option-btn {
    transition: all 0.3s ease;
}

.option-btn:hover:not(:disabled), .option-btn.selected {
    background-color: rgba(10, 71, 46, 0.08);
    border-color: #0a472e;
    color: #0a472e;
}

.keyword-cloud {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.keyword-tag {
    background: #f8f9fa;
    border-radius: 30px;
    padding: 6px 14px;
    font-size: 0.8rem;
    color: #495057;
    border: 1px solid #dee2e6;
}

.keyword-tag.covered {
    background: #28a745;
    color: white;
    border-color: #28a745;
}
</style>
