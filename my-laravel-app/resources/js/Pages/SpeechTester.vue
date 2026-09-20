<template>
    <div class="container py-5">
        <h2 class="fw-bold mb-2">Speech Recognition AI Trainer (Sandbox)</h2>
        <p class="text-muted mb-5">Test how the browser interprets your pronunciation and map its "mistakes" to the correct keywords.</p>

        <div class="row g-4">
            <!-- Educator Side: Define Keywords and Aliases -->
            <div class="col-md-6">
                <div class="card shadow-sm border-0 h-100 rounded-4">
                    <div class="card-header bg-primary text-white fw-bold py-3 rounded-top-4 border-0">
                        1. Educator: Add Keywords & Train AI
                    </div>
                    <div class="card-body p-4 bg-light">
                        <div class="input-group mb-4 shadow-sm rounded-pill overflow-hidden">
                            <input v-model="newKeyword" @keyup.enter="addKeyword" type="text" class="form-control border-0 px-4 py-3" placeholder="Type a keyword (e.g. Pamulinawen)" />
                            <button @click="addKeyword" class="btn btn-primary px-4 fw-bold">Add Keyword</button>
                        </div>

                        <div v-for="(kw, idx) in keywords" :key="idx" class="card mb-3 border-0 shadow-sm rounded-3">
                            <div class="card-body p-4">
                                <h5 class="fw-bold text-dark mb-3">Target: <span class="text-primary">{{ kw.word }}</span></h5>
                                
                                <div class="mb-3 p-3 bg-light rounded-3 border">
                                    <label class="form-label text-muted small fw-bold mb-2 d-block">Recorded Aliases (What the browser heard):</label>
                                    <div class="d-flex flex-wrap gap-2">
                                        <span v-for="(alias, aIdx) in kw.aliases" :key="aIdx" class="badge bg-secondary px-3 py-2 fs-6 rounded-pill">
                                            {{ alias }}
                                            <i @click="removeAlias(idx, aIdx)" class="fas fa-times ms-2" style="cursor: pointer;"></i>
                                        </span>
                                        <span v-if="kw.aliases.length === 0" class="text-muted small fst-italic">No aliases yet. Click below to record one.</span>
                                    </div>
                                </div>

                                <button @click="startRecordingAlias(idx)" class="btn rounded-pill w-100 fw-bold shadow-sm" :class="activeRecordingIdx === idx ? 'btn-danger' : 'btn-outline-primary'">
                                    <i class="fas" :class="activeRecordingIdx === idx ? 'fa-spinner fa-spin me-2' : 'fa-microphone me-2'"></i>
                                    {{ activeRecordingIdx === idx ? 'Listening... Speak the word now' : 'Test Pronunciation (Record Alias)' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Student Side: Test Recognition -->
            <div class="col-md-6">
                <div class="card shadow-sm border-0 h-100 rounded-4">
                    <div class="card-header bg-success text-white fw-bold py-3 rounded-top-4 border-0">
                        2. Student: Simulate Tour Commentary
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <label class="form-label fw-bold">Select Language Engine:</label>
                            <select v-model="speechLang" class="form-select rounded-pill px-4 shadow-sm border-0 bg-light">
                                <option value="en-PH">English (Philippines) - Recommended</option>
                                <option value="en-US">English (United States)</option>
                                <option value="fil-PH">Filipino (Philippines)</option>
                            </select>
                        </div>

                        <div class="text-center mb-4">
                            <button @click="toggleSimulation" class="btn btn-lg rounded-pill px-5 shadow fw-bold w-100 py-3" :class="isSimulating ? 'btn-danger' : 'btn-success'">
                                <i class="fas me-2" :class="isSimulating ? 'fa-stop-circle' : 'fa-play-circle'"></i>
                                {{ isSimulating ? 'Stop Commentary' : 'Start Commentary Simulation' }}
                            </button>
                        </div>

                        <div class="bg-dark text-white p-4 rounded-4 mb-4 shadow-inner" style="min-height: 150px; font-size: 1.1rem; line-height: 1.6;">
                            <span v-if="simulationTranscript">{{ simulationTranscript }}</span>
                            <span v-else class="text-white-50 fst-italic"><i class="fas fa-microphone-alt me-2"></i>Live transcript will appear here...</span>
                        </div>

                        <div class="p-4 bg-light rounded-4 border">
                            <h5 class="fw-bold mb-3"><i class="fas fa-check-double text-success me-2"></i>Validation Status:</h5>
                            <div class="d-flex flex-wrap gap-2">
                                <span v-for="(kw, idx) in keywords" :key="'val-'+idx" class="badge fs-6 px-3 py-2 rounded-pill shadow-sm transition-all" :class="isMatched(kw) ? 'bg-success text-white border border-success' : 'bg-white text-secondary border'">
                                    <i class="fas me-2" :class="isMatched(kw) ? 'fa-check-circle' : 'fa-clock text-muted'"></i>
                                    {{ kw.word }}
                                </span>
                            </div>
                            <p class="text-muted small mt-3 mb-0 fst-italic">Watch these turn green instantly if the exact word OR any of its aliases are detected in the transcript.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onUnmounted } from 'vue';
import StudentLayout from '@/Layouts/StudentLayout.vue';

defineOptions({ layout: StudentLayout });

const newKeyword = ref('');
const keywords = ref([
    { word: 'Laoag City', aliases: [] },
    { word: 'Pamulinawen', aliases: [] },
    { word: 'Aurora Park', aliases: [] },
]);

const speechLang = ref('en-PH');
let recognition = null;

// Alias Recording State
const activeRecordingIdx = ref(null);

// Simulation State
const isSimulating = ref(false);
const simulationTranscript = ref('');
const matchedWords = ref([]);
let simulationRec = null;

const initSpeech = (continuous = false) => {
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    if (!SpeechRecognition) {
        alert("Speech Recognition is not supported in this browser. Please use Chrome.");
        return null;
    }
    const rec = new SpeechRecognition();
    rec.continuous = continuous;
    rec.interimResults = true;
    rec.lang = speechLang.value;
    return rec;
};

// --- Educator Alias Logic ---
const addKeyword = () => {
    if (newKeyword.value.trim()) {
        keywords.value.push({ word: newKeyword.value.trim(), aliases: [] });
        newKeyword.value = '';
    }
};

const removeAlias = (kwIdx, aliasIdx) => {
    keywords.value[kwIdx].aliases.splice(aliasIdx, 1);
};

const startRecordingAlias = (idx) => {
    if (activeRecordingIdx.value !== null) return;
    
    recognition = initSpeech(false);
    if (!recognition) return;

    activeRecordingIdx.value = idx;
    
    recognition.onresult = (e) => {
        if (e.results[0].isFinal) {
            const transcript = Array.from(e.results).map(r => r[0].transcript).join('');
            const alias = transcript.toLowerCase().trim();
            // Prevent empty or exact matches
            if (alias && alias !== keywords.value[idx].word.toLowerCase() && !keywords.value[idx].aliases.includes(alias)) {
                keywords.value[idx].aliases.push(alias);
            }
        }
    };
    
    recognition.onend = () => {
        activeRecordingIdx.value = null;
        recognition = null;
    };
    
    try {
        recognition.start();
    } catch (e) {
        console.error("Error starting speech recognition", e);
        activeRecordingIdx.value = null;
    }
};

// --- Student Simulation Logic ---
const toggleSimulation = () => {
    if (isSimulating.value) {
        if (simulationRec) {
            simulationRec.stop();
        }
        isSimulating.value = false;
    } else {
        simulationTranscript.value = '';
        matchedWords.value = [];
        
        simulationRec = initSpeech(true);
        if (!simulationRec) return;
        
        simulationRec.onstart = () => {
            isSimulating.value = true;
        };

        simulationRec.onresult = (e) => {
            const current = Array.from(e.results).map(r => r[0].transcript).join('');
            simulationTranscript.value = current;
            validateTranscript(current);
        };
        
        simulationRec.onend = () => {
            isSimulating.value = false;
        };
        
        try {
            simulationRec.start();
        } catch (e) {
            console.error("Error starting simulation", e);
            isSimulating.value = false;
        }
    }
};

const validateTranscript = (text) => {
    const cleanText = text.toLowerCase().replace(/[.,/#!$%^&*;:{}=\-_`~()]/g,"");
    
    keywords.value.forEach(kw => {
        if (matchedWords.value.includes(kw.word)) return; // already matched
        
        const cleanWord = kw.word.toLowerCase().replace(/[.,/#!$%^&*;:{}=\-_`~()]/g,"");
        
        // 1. Check exact word
        if (cleanText.includes(cleanWord)) {
            matchedWords.value.push(kw.word);
            return;
        }
        
        // 2. Check aliases
        for (const alias of kw.aliases) {
            const cleanAlias = alias.toLowerCase().replace(/[.,/#!$%^&*;:{}=\-_`~()]/g,"");
            if (cleanText.includes(cleanAlias)) {
                matchedWords.value.push(kw.word);
                return;
            }
        }
    });
};

const isMatched = (kw) => {
    return matchedWords.value.includes(kw.word);
};

onUnmounted(() => {
    if (recognition) recognition.stop();
    if (simulationRec) simulationRec.stop();
});
</script>
