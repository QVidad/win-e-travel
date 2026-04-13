// Global variables
let selectedAvatar = localStorage.getItem('selectedAvatar') || null;
let isSpeaking = false;
let speechSynthesis = window.speechSynthesis;
let currentUtterance = null;
let userName = localStorage.getItem('userName') || 'Trainee';
let availableVoices = [];
let selectedVoice = null;

// Initialize page on load
document.addEventListener('DOMContentLoaded', function() {
    // Set current year in footer
    document.querySelector('#copyright-year').textContent = new Date().getFullYear();
    
    // Display user name
    document.getElementById('userDisplayName').textContent = userName;
    document.getElementById('welcomeName').textContent = userName;
    
    // Load available voices
    loadVoices();
    
    // Chrome loads voices asynchronously
    if (speechSynthesis.onvoiceschanged !== undefined) {
        speechSynthesis.onvoiceschanged = loadVoices;
    }
    
    // Restore avatar selection if previously selected
    if (selectedAvatar) {
        selectAvatar(selectedAvatar, false);
    }
    
    // Initialize progress indicator
    initProgressIndicator();
});

// Load and categorize available voices
function loadVoices() {
    availableVoices = speechSynthesis.getVoices();
    console.log('Available voices:', availableVoices.length);
    
    // Log all voices for debugging
    availableVoices.forEach((voice, index) => {
        console.log(`${index}: ${voice.name} (${voice.lang}) - ${voice.gender || 'no gender specified'}`);
    });
    
    // If avatar is already selected, update voice
    if (selectedAvatar) {
        setVoiceByGender(selectedAvatar);
    }
}

// Set voice based on gender preference
function setVoiceByGender(gender) {
    if (availableVoices.length === 0) {
        console.warn('No voices available yet');
        return;
    }
    
    let preferredVoice = null;
    
    if (gender === 'male') {
        // Try to find a male-sounding voice
        preferredVoice = availableVoices.find(voice => 
            voice.name.toLowerCase().includes('male') ||
            voice.name.toLowerCase().includes('daniel') ||
            voice.name.toLowerCase().includes('david') ||
            voice.name.toLowerCase().includes('james') ||
            voice.name.toLowerCase().includes('tom') ||
            voice.name.toLowerCase().includes('google uk english male') ||
            voice.name.toLowerCase().includes('microsoft david') ||
            voice.name.toLowerCase().includes('microsoft mark')
        );
    } else {
        // Try to find a female-sounding voice
        preferredVoice = availableVoices.find(voice => 
            voice.name.toLowerCase().includes('female') ||
            voice.name.toLowerCase().includes('samantha') ||
            voice.name.toLowerCase().includes('victoria') ||
            voice.name.toLowerCase().includes('karen') ||
            voice.name.toLowerCase().includes('google uk english female') ||
            voice.name.toLowerCase().includes('microsoft zira') ||
            voice.name.toLowerCase().includes('microsoft hazel') ||
            voice.name.toLowerCase().includes('google us english')
        );
    }
    
    // Fallback: Pick first English voice that matches gender pattern
    if (!preferredVoice) {
        preferredVoice = availableVoices.find(voice => 
            voice.lang.startsWith('en') && 
            ((gender === 'male' && !voice.name.toLowerCase().includes('female')) ||
             (gender === 'female' && !voice.name.toLowerCase().includes('male')))
        );
    }
    
    // Final fallback: Use default voice
    if (!preferredVoice && availableVoices.length > 0) {
        preferredVoice = availableVoices[0];
        console.warn('No gender-specific voice found, using default');
    }
    
    selectedVoice = preferredVoice;
    
    if (selectedVoice) {
        console.log(`Selected ${gender} voice: ${selectedVoice.name}`);
        showNotification(`Voice set to: ${selectedVoice.name}`, 'info');
        
        // Test the voice with a short phrase
        setTimeout(() => {
            speakText(`Hello ${userName}, I will be your ${gender} facilitator for this training.`);
        }, 500);
    }
}

// Avatar selection
function selectAvatar(gender, saveToStorage = true) {
    // Remove selected class from all cards
    document.querySelectorAll('.avatar-selection-card').forEach(card => {
        card.classList.remove('selected');
    });
    
    // Hide all check icons
    document.getElementById('maleCheck').style.display = 'none';
    document.getElementById('femaleCheck').style.display = 'none';
    
    // Add selected class and show check for chosen avatar
    if (gender === 'male') {
        document.getElementById('maleAvatar').classList.add('selected');
        document.getElementById('maleCheck').style.display = 'inline-block';
        selectedAvatar = 'male';
    } else {
        document.getElementById('femaleAvatar').classList.add('selected');
        document.getElementById('femaleCheck').style.display = 'inline-block';
        selectedAvatar = 'female';
    }
    
    // Set the appropriate voice
    setVoiceByGender(gender);
    
    // Save to localStorage if requested
    if (saveToStorage) {
        localStorage.setItem('selectedAvatar', selectedAvatar);
        showNotification(`Great! Your ${gender} facilitator will guide you through the training.`, 'success');
    }
}

// Text-to-Speech Functions
function speakText(text) {
    // Cancel any ongoing speech
    if (speechSynthesis.speaking) {
        speechSynthesis.cancel();
    }
    
    const utterance = new SpeechSynthesisUtterance(text);
    
    // Use selected voice if available
    if (selectedVoice) {
        utterance.voice = selectedVoice;
        console.log('Using voice:', selectedVoice.name);
    } else if (availableVoices.length > 0) {
        // Fallback based on preference
        const preference = localStorage.getItem('selectedAvatar');
        if (preference === 'male') {
            utterance.voice = availableVoices.find(v => v.name.includes('Male')) || availableVoices[0];
        } else {
            utterance.voice = availableVoices.find(v => v.name.includes('Female')) || availableVoices[0];
        }
    }
    
    // Configure speech parameters
    utterance.rate = 0.9;
    utterance.pitch = 1;
    utterance.volume = 1;
    
    // Event handlers
    utterance.onstart = function() {
        isSpeaking = true;
        updateSpeechButton();
    };
    
    utterance.onend = function() {
        isSpeaking = false;
        updateSpeechButton();
        currentUtterance = null;
    };
    
    utterance.onerror = function(event) {
        console.error('Speech error:', event);
        isSpeaking = false;
        updateSpeechButton();
        currentUtterance = null;
    };
    
    currentUtterance = utterance;
    speechSynthesis.speak(utterance);
}

// Speak welcome message
function speakWelcomeMessage() {
    const welcomeText = `Welcome to the Tour Guiding Training App, ${userName}! ` +
        `Tour guiding is more than just leading people from one place to another — it's about bringing destinations to life, sharing meaningful stories, and ensuring travelers have memorable, safe, and enjoyable experiences. ` +
        `As a tour guide, you are not only the bridge between visitors and culture, but also an ambassador of your community and its heritage.`;
    
    speakText(welcomeText);
}

// Speak specific section
function speakSection(sectionId) {
    const section = document.getElementById(sectionId);
    if (section) {
        const text = section.textContent || section.innerText;
        speakText(text);
    }
}

// Toggle speech
function toggleSpeech() {
    if (isSpeaking) {
        speechSynthesis.cancel();
        isSpeaking = false;
    } else {
        // Speak the main welcome content
        speakWelcomeMessage();
    }
    updateSpeechButton();
}

// Update speech button appearance
function updateSpeechButton() {
    const btn = document.getElementById('speechToggle');
    const icon = btn.querySelector('i');
    
    if (isSpeaking) {
        btn.classList.add('speaking');
        icon.classList.remove('fa-volume-up');
        icon.classList.add('fa-volume-mute');
        btn.title = 'Stop Speaking';
    } else {
        btn.classList.remove('speaking');
        icon.classList.remove('fa-volume-mute');
        icon.classList.add('fa-volume-up');
        btn.title = 'Text-to-Speech';
    }
}

// Initialize progress indicator
function initProgressIndicator() {
    window.addEventListener('scroll', function() {
        const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
        const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        const scrolled = (winScroll / height) * 100;
        document.getElementById('pageProgress').style.width = scrolled + '%';
    });
}

// Continue to dashboard
function continueToDashboard() {
    if (!selectedAvatar) {
        showNotification('Please select a facilitator to continue', 'warning');
        // Highlight avatar selection section
        document.querySelector('.avatar-selection-card')?.scrollIntoView({ 
            behavior: 'smooth', 
            block: 'center' 
        });
        return;
    }
    
    // Save that user has completed onboarding
    localStorage.setItem('onboardingComplete', 'true');
    
    // Show loading state
    const btn = document.getElementById('continueBtn');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Loading...';
    btn.disabled = true;
    
    // Simulate loading and redirect
    setTimeout(() => {
        window.location.href = 'dashboard.html';
    }, 1000);
}

// Show notification toast
function showNotification(message, type = 'info') {
    // Create toast container if it doesn't exist
    let toastContainer = document.querySelector('.toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
        toastContainer.style.zIndex = '9999';
        document.body.appendChild(toastContainer);
    }
    
    // Create toast element
    const toastId = 'toast-' + Date.now();
    const bgClass = type === 'success' ? 'bg-success' : 
                   type === 'danger' ? 'bg-danger' : 
                   type === 'warning' ? 'bg-warning' : 'bg-info';
    
    const iconMap = {
        'success': 'check-circle',
        'danger': 'exclamation-circle',
        'warning': 'exclamation-triangle',
        'info': 'info-circle'
    };
    
    const toastHtml = `
        <div id="${toastId}" class="toast align-items-center text-white ${bgClass} border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-${iconMap[type]} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;
    
    toastContainer.insertAdjacentHTML('beforeend', toastHtml);
    
    // Initialize and show toast
    const toastElement = document.getElementById(toastId);
    const toast = new bootstrap.Toast(toastElement, {
        animation: true,
        autohide: true,
        delay: type === 'success' ? 3000 : 5000
    });
    toast.show();
    
    // Remove toast after it's hidden
    toastElement.addEventListener('hidden.bs.toast', function() {
        this.remove();
    });
}

// Handle page unload - stop speech
window.addEventListener('beforeunload', function() {
    if (speechSynthesis.speaking) {
        speechSynthesis.cancel();
    }
});

// Debug function - can be called from console
function testVoices() {
    console.log('=== Available Voices ===');
    availableVoices.forEach((voice, i) => {
        console.log(`${i}: ${voice.name} (${voice.lang})`);
    });
    
    // Test male voice
    console.log('\n=== Testing Male Voice ===');
    const maleVoice = availableVoices.find(v => v.name.toLowerCase().includes('male'));
    if (maleVoice) {
        const utterance = new SpeechSynthesisUtterance('This is a male voice test.');
        utterance.voice = maleVoice;
        speechSynthesis.speak(utterance);
    } else {
        console.log('No male voice found');
    }
    
    // Test female voice
    setTimeout(() => {
        console.log('\n=== Testing Female Voice ===');
        const femaleVoice = availableVoices.find(v => v.name.toLowerCase().includes('female'));
        if (femaleVoice) {
            const utterance = new SpeechSynthesisUtterance('This is a female voice test.');
            utterance.voice = femaleVoice;
            speechSynthesis.speak(utterance);
        } else {
            console.log('No female voice found');
        }
    }, 3000);
}

// Check browser compatibility
function checkBrowserCompatibility() {
    const isChrome = /Chrome/.test(navigator.userAgent) && /Google Inc/.test(navigator.vendor);
    const isEdge = /Edg/.test(navigator.userAgent);
    const isSafari = /Safari/.test(navigator.userAgent) && !isChrome && !isEdge;
    const isFirefox = /Firefox/.test(navigator.userAgent);
    
    // Check if speech synthesis is supported
    if (!window.speechSynthesis) {
        showNotification('Text-to-speech is not supported in your browser. Please use Chrome for full features.', 'danger');
        document.getElementById('speechToggle').style.display = 'none';
        return false;
    }
    
    // Show notice for non-Chrome/Edge browsers
    if (!isChrome && !isEdge) {
        document.getElementById('browserNotice').style.display = 'block';
        
        let browserName = 'your browser';
        if (isSafari) browserName = 'Safari';
        if (isFirefox) browserName = 'Firefox';
        
        console.warn(`Using ${browserName}. Some voice features may be limited. Chrome is recommended.`);
        showNotification(`You're using ${browserName}. For best voice quality, we recommend Chrome.`, 'warning');
    } else {
        console.log('✅ Using compatible browser for speech features');
    }
    
    // Check available voices
    setTimeout(() => {
        const voices = speechSynthesis.getVoices();
        const englishVoices = voices.filter(v => v.lang.startsWith('en'));
        console.log(`Found ${englishVoices.length} English voices`);
        
        if (englishVoices.length < 2 && (isChrome || isEdge)) {
            console.warn('Limited English voices detected. Check system language settings.');
        }
    }, 500);
    
    return true;
}