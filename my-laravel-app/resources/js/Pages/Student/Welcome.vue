<template>
    <StudentLayout>
        <!-- Progress Indicator Bar -->
        <div class="progress-indicator">
            <div class="progress-indicator-bar" :style="{ width: scrollProgress + '%' }"></div>
        </div>

        <!-- Welcome Hero -->
        <section class="welcome-hero text-white">
            <div class="container">
                <div class="row align-items-center py-2">
                    <div class="col-lg-8">
                        <h1 class="display-5 fw-bold mb-3">
                            Welcome, <span>{{ userName }}</span>! 👋
                        </h1>
                        <p class="lead mb-0 opacity-90">
                            Your journey to becoming a certified tour guide starts here. Let's set up your training experience.
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                        <button class="btn btn-gold btn-lg shadow-sm" @click="speakWelcomeMessage">
                            <i class="fas" :class="isSpeaking ? 'fa-square' : 'fa-volume-up'" me-2></i>
                            {{ isSpeaking ? 'Stop Listening' : 'Listen to Welcome' }}
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Avatar Selection Section -->
        <section class="py-5 bg-light border-bottom">
            <div class="container">
                <div class="text-center mb-4">
                    <h3 class="fw-bold mb-2">Choose Your Training Facilitator</h3>
                    <p class="text-muted">Select who will guide you through your training journey</p>
                </div>
                <div class="row justify-content-center g-4">
                    <div class="col-md-5">
                        <div 
                            class="avatar-selection-card d-flex flex-column align-items-center justify-content-center text-center bg-white shadow-sm" 
                            :class="{ 'selected': selectedAvatar === 'male' }" 
                            @click="selectAvatar('male')"
                        >
                            <img src="/assets/images/facilitator-male.jpg" alt="Male Facilitator" class="avatar-image">
                            <h5 class="fw-bold mb-2">
                                <i class="fas fa-male text-primary me-2"></i>Male Facilitator
                            </h5>
                            <p class="text-muted small mb-0">Deep, clear voice guidance</p>
                            <div class="mt-3" style="min-height: 32px;">
                                <i v-if="selectedAvatar === 'male'" class="fas fa-check-circle text-success fa-2x"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div 
                            class="avatar-selection-card d-flex flex-column align-items-center justify-content-center text-center bg-white shadow-sm" 
                            :class="{ 'selected': selectedAvatar === 'female' }" 
                            @click="selectAvatar('female')"
                        >
                            <img src="/assets/images/facilitator-female.jpg" alt="Female Facilitator" class="avatar-image">
                            <h5 class="fw-bold mb-2">
                                <i class="fas fa-female text-danger me-2"></i>Female Facilitator
                            </h5>
                            <p class="text-muted small mb-0">Warm, engaging voice guidance</p>
                            <div class="mt-3" style="min-height: 32px;">
                                <i v-if="selectedAvatar === 'female'" class="fas fa-check-circle text-success fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Welcome Content -->
        <main class="py-5">
            <div class="container">
                <!-- Welcome Message -->
                <div class="row mb-5">
                    <div class="col-lg-8 mx-auto">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-body p-4 p-lg-5">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h3 class="fw-bold mb-0">
                                        <i class="fas fa-info-circle text-success me-2"></i>
                                        Welcome to the Tour Guiding Training App
                                    </h3>
                                    <button class="btn btn-sm btn-outline-mmsu ms-2 flex-shrink-0" @click="speakWelcomeText">
                                        <i class="fas fa-play me-1"></i>Read Aloud
                                    </button>
                                </div>
                                <div>
                                    <p class="lead">Tour guiding is more than just leading people from one place to another — it's about bringing destinations to life, sharing meaningful stories, and ensuring travelers have memorable, safe, and enjoyable experiences.</p>
                                    <p class="mb-0">As a tour guide, you are not only the bridge between visitors and culture, but also an ambassador of your community and its heritage.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Your Role as a Tour Guide -->
                <div class="row mb-5">
                    <div class="col-12">
                        <h3 class="fw-bold mb-4">
                            <i class="fas fa-bullseye text-primary me-2"></i>
                            Your Role as a Tour Guide
                        </h3>
                        <div class="row g-4">
                            <div class="col-md-3">
                                <div class="role-card">
                                    <i class="fas fa-info-circle fa-2x text-success mb-3"></i>
                                    <p class="mb-0">Provide accurate and engaging information about the sites, culture, and history.</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="role-card">
                                    <i class="fas fa-shield-alt fa-2x text-primary mb-3"></i>
                                    <p class="mb-0">Ensure the comfort, safety, and enjoyment of your guests.</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="role-card">
                                    <i class="fas fa-flag fa-2x text-warning mb-3"></i>
                                    <p class="mb-0">Represent your destination with professionalism, integrity, and enthusiasm.</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="role-card">
                                    <i class="fas fa-users-cog fa-2x text-info mb-3"></i>
                                    <p class="mb-0">Adapt to different group needs and create a welcoming atmosphere for everyone.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Learning Outcomes -->
                <div class="row mb-5">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-4">
                                    <h3 class="fw-bold mb-0">
                                        <i class="fas fa-graduation-cap text-warning me-2"></i>
                                        After this training, you will gain:
                                    </h3>
                                    <button class="btn btn-sm btn-outline-mmsu" @click="speakSkillsText">
                                        <i class="fas fa-play me-1"></i>Read Aloud
                                    </button>
                                </div>
                                <div>
                                    <div class="skill-tag"><i class="fas fa-sync-alt me-2 text-success"></i>Adaptability</div>
                                    <div class="skill-tag"><i class="fas fa-comments me-2 text-primary"></i>Communication Skills</div>
                                    <div class="skill-tag"><i class="fas fa-exclamation-triangle me-2 text-warning"></i>Crisis Management</div>
                                    <div class="skill-tag"><i class="fas fa-book me-2 text-info"></i>Cultural & Historical Knowledge</div>
                                    <div class="skill-tag"><i class="fas fa-smile me-2 text-success"></i>Customer Service</div>
                                    <div class="skill-tag"><i class="fas fa-heart me-2 text-danger"></i>Empathy</div>
                                    <div class="skill-tag"><i class="fas fa-balance-scale me-2 text-secondary"></i>Integrity</div>
                                    <div class="skill-tag"><i class="fas fa-briefcase me-2 text-dark"></i>Professionalism</div>
                                    <div class="skill-tag"><i class="fas fa-hand-peace me-2 text-primary"></i>Respect</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- How to Use This App -->
                <div class="row mb-5">
                    <div class="col-12">
                        <h3 class="fw-bold mb-4">
                            <i class="fas fa-compass text-info me-2"></i>
                            How to Use This Training App
                        </h3>
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-body p-4">
                                <div class="step-item d-flex mb-4">
                                    <div class="step-number">1</div>
                                    <div class="flex-grow-1">
                                        <h5 class="fw-bold">Sign In or Create an Account</h5>
                                        <p class="text-muted mb-0">This allows you to track your progress, scores, and unlocked content.</p>
                                    </div>
                                </div>
                                <div class="step-item d-flex mb-4">
                                    <div class="step-number">2</div>
                                    <div class="flex-grow-1">
                                        <h5 class="fw-bold">Select a Training Segment</h5>
                                        <p class="text-muted mb-0">Modules and lessons unlock in sequence. You must pass the current lesson or module before moving on.</p>
                                    </div>
                                </div>
                                <div class="step-item d-flex mb-4">
                                    <div class="step-number">3</div>
                                    <div class="flex-grow-1">
                                        <h5 class="fw-bold">Learn & Practice</h5>
                                        <p class="text-muted mb-0">Watch training videos, read lesson guides, and try interactive activities to build your knowledge, skills, and attitudes.</p>
                                    </div>
                                </div>
                                <div class="step-item d-flex mb-4">
                                    <div class="step-number">4</div>
                                    <div class="flex-grow-1">
                                        <h5 class="fw-bold">Take Quick Checks</h5>
                                        <p class="text-muted mb-2">Short quizzes appear at the end of each lesson.</p>
                                        <div class="requirement-badge">
                                            <i class="fas fa-exclamation-circle me-2"></i>
                                            <strong>Passing requirement: Score 90% or higher</strong> to unlock the next lesson.<br>
                                            <small>You may take Quick Checks without a facilitator. If you score below 90%, review the lesson materials and retake until you pass.</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="step-item d-flex mb-4">
                                    <div class="step-number">5</div>
                                    <div class="flex-grow-1">
                                        <h5 class="fw-bold">Complete the End-of-Module Evaluation</h5>
                                        <p class="text-muted mb-2">A formal 25-item test combining scenario-based and identification questions.</p>
                                        <div class="requirement-badge">
                                            <i class="fas fa-exclamation-circle me-2"></i>
                                            <strong>Passing requirement: Score 90% or higher</strong> to unlock the next module.<br>
                                            <small>Must be completed in the presence of a facilitator. If you score below 90%, review the module content and retake with the facilitator present.</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="step-item d-flex mb-4">
                                    <div class="step-number">6</div>
                                    <div class="flex-grow-1">
                                        <h5 class="fw-bold">Complete Simulations</h5>
                                        <p class="text-muted mb-0">Apply your learning in virtual tour scenarios to demonstrate practical skills and decision-making.</p>
                                    </div>
                                </div>
                                <div class="step-item d-flex mb-4">
                                    <div class="step-number">7</div>
                                    <div class="flex-grow-1">
                                        <h5 class="fw-bold">Advance to the Next Module or Lesson</h5>
                                        <p class="text-muted mb-0">The system automatically unlocks the next section when you meet the 90% passing score for the relevant Quick Check or End-of-Module Evaluation.</p>
                                    </div>
                                </div>
                                <div class="step-item d-flex">
                                    <div class="step-number">8</div>
                                    <div class="flex-grow-1">
                                        <h5 class="fw-bold">Earn Your Certificate</h5>
                                        <p class="text-muted mb-0">Once all modules are completed, download your official training certificate.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Closing Message -->
                <div class="row mb-5">
                    <div class="col-lg-8 mx-auto">
                        <div class="text-center">
                            <div class="mb-4">
                                <i class="fas fa-quote-left fa-3x text-muted opacity-25"></i>
                            </div>
                            <p class="lead fst-italic">
                                Whether you're preparing for your first guided tour or refining years of experience, this app is your step-by-step companion to becoming a confident, knowledgeable, and inspiring tour guide.
                            </p>
                            <div class="mb-4">
                                <i class="fas fa-quote-right fa-3x text-muted opacity-25"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Continue Button -->
                <div class="row">
                    <div class="col-12 text-center">
                        <Link :href="route('dashboard')" class="btn btn-mmsu btn-lg px-5 py-3 shadow">
                            <i class="fas fa-arrow-right me-2"></i>Continue to Dashboard
                        </Link>
                        <p class="text-muted small mt-3 mb-0">
                            <i class="fas fa-info-circle me-1"></i>
                            Your facilitator selection ({{ selectedAvatar === 'female' ? 'Female Facilitator' : 'Male Facilitator' }}) is saved for your session.
                        </p>
                    </div>
                </div>
            </div>
        </main>

        <!-- Floating Speech Control Button -->
        <div class="speech-control">
            <button 
                class="btn btn-speech" 
                :class="{ 'speaking': isSpeaking }" 
                @click="toggleSpeech" 
                :title="isSpeaking ? 'Stop Speech' : 'Text-to-Speech'"
            >
                <i class="fas" :class="isSpeaking ? 'fa-stop' : 'fa-volume-up'"></i>
            </button>
        </div>

        <!-- Footer -->
        <footer class="footer py-4 mt-5">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center">
                        <p class="small opacity-50 mb-0">
                            &copy; <span>{{ currentYear }}</span> Mariano Marcos State University.
                        </p>
                    </div>
                </div>
            </div>
        </footer>
    </StudentLayout>
</template>

<script setup>
import StudentLayout from '@/Layouts/StudentLayout.vue';
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const userName = computed(() => page.props.auth?.user?.name || 'Trainee');
const currentYear = new Date().getFullYear();

// Facilitator selection persisted to localStorage
const selectedAvatar = ref('male');

onMounted(() => {
    const saved = localStorage.getItem('selectedAvatar');
    if (saved) {
        selectedAvatar.value = saved;
    } else {
        localStorage.setItem('selectedAvatar', 'male');
    }
    window.addEventListener('scroll', handleScroll);

    if ('speechSynthesis' in window) {
        window.speechSynthesis.onvoiceschanged = () => {
            window.speechSynthesis.getVoices();
        };
    }
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
    stopSpeech();
});

const selectAvatar = (gender) => {
    selectedAvatar.value = gender;
    localStorage.setItem('selectedAvatar', gender);
};

// Scroll progress bar
const scrollProgress = ref(0);
const handleScroll = () => {
    const totalHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
    if (totalHeight > 0) {
        scrollProgress.value = (window.scrollY / totalHeight) * 100;
    }
};

// Web Speech API Text-to-Speech
const isSpeaking = ref(false);

const stopSpeech = () => {
    if ('speechSynthesis' in window) {
        window.speechSynthesis.cancel();
        isSpeaking.value = false;
    }
};

const speakText = (text) => {
    if (!('speechSynthesis' in window)) return;
    stopSpeech();

    if (!text) return;

    const utterance = new SpeechSynthesisUtterance(text);
    const voices = window.speechSynthesis.getVoices();

    if (selectedAvatar.value === 'female') {
        // Find a female voice (Chrome/Edge/Safari support names like Zira, Samantha, Victoria, Google UK English Female, etc.)
        const femaleVoice = voices.find(v => 
            v.lang.startsWith('en') && 
            (v.name.toLowerCase().includes('female') || 
             v.name.toLowerCase().includes('zira') || 
             v.name.toLowerCase().includes('samantha') || 
             v.name.toLowerCase().includes('victoria') ||
             v.name.toLowerCase().includes('google us english'))
        );
        if (femaleVoice) utterance.voice = femaleVoice;
        utterance.pitch = 1.3; // Higher pitch for female voice
    } else {
        // Find a male voice (David, Mark, Guy, Google US English Male, etc.)
        const maleVoice = voices.find(v => 
            v.lang.startsWith('en') && 
            (v.name.toLowerCase().includes('male') || 
             v.name.toLowerCase().includes('david') || 
             v.name.toLowerCase().includes('mark') ||
             v.name.toLowerCase().includes('guy'))
        );
        if (maleVoice) utterance.voice = maleVoice;
        utterance.pitch = 0.85; // Deeper pitch for male voice
    }

    utterance.rate = 0.95;
    utterance.onstart = () => { isSpeaking.value = true; };
    utterance.onend = () => { isSpeaking.value = false; };
    utterance.onerror = () => { isSpeaking.value = false; };

    window.speechSynthesis.speak(utterance);
};

const toggleSpeech = () => {
    if (isSpeaking.value) {
        stopSpeech();
    } else {
        speakWelcomeMessage();
    }
};

const speakWelcomeMessage = () => {
    const text = `Welcome, ${userName.value}! Your journey to becoming a certified tour guide starts here. Choose your training facilitator and let's set up your training experience.`;
    speakText(text);
};

const speakWelcomeText = () => {
    const text = "Welcome to the Tour Guiding Training App. Tour guiding is more than just leading people from one place to another — it's about bringing destinations to life, sharing meaningful stories, and ensuring travelers have memorable, safe, and enjoyable experiences. As a tour guide, you are not only the bridge between visitors and culture, but also an ambassador of your community and its heritage.";
    speakText(text);
};

const speakSkillsText = () => {
    const text = "After this training, you will gain: Adaptability, Communication Skills, Crisis Management, Cultural and Historical Knowledge, Customer Service, Empathy, Integrity, Professionalism, and Respect.";
    speakText(text);
};
</script>

<style scoped>
.welcome-hero {
    background: linear-gradient(135deg, #0a472e 0%, #1a5f7a 100%);
    padding: 60px 0;
}

.avatar-selection-card {
    cursor: pointer;
    transition: all 0.3s ease;
    border: 3px solid transparent;
    border-radius: 20px;
    padding: 20px;
}

.avatar-selection-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.1);
}

.avatar-selection-card.selected {
    border-color: var(--mmsu-gold, #d4a017);
    background-color: rgba(212, 160, 23, 0.05) !important;
}

.avatar-image {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    margin: 0 auto 15px auto;
    display: block;
    border: 4px solid var(--mmsu-gold, #d4a017);
}

.step-number {
    width: 35px;
    height: 35px;
    background-color: var(--mmsu-green);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin-right: 15px;
    flex-shrink: 0;
}

.requirement-badge {
    background-color: #fff3cd;
    color: #856404;
    border-left: 4px solid #ffc107;
    padding: 15px;
    border-radius: 10px;
}

.skill-tag {
    background-color: #f8f9fa;
    border-radius: 30px;
    padding: 8px 16px;
    margin: 5px;
    display: inline-block;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.skill-tag:hover {
    background-color: var(--mmsu-green);
    color: white;
    transform: scale(1.05);
}

.role-card {
    background: white;
    border-radius: 15px;
    padding: 20px;
    height: 100%;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}

.role-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.12);
}

.speech-control {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 1000;
}

.btn-speech {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background-color: var(--mmsu-green);
    color: white;
    border: none;
    box-shadow: 0 4px 15px rgba(10, 71, 46, 0.3);
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-speech:hover {
    transform: scale(1.1);
    background-color: var(--mmsu-gold);
}

.btn-speech.speaking {
    background-color: #dc3545;
    animation: pulse 1.5s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

.progress-indicator {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background-color: #e9ecef;
    z-index: 1050;
}

.progress-indicator-bar {
    height: 100%;
    background: linear-gradient(90deg, var(--mmsu-green), var(--mmsu-gold));
    transition: width 0.1s ease;
}
</style>
