// ============================================
// ACHIEVEMENTS DATA
// ============================================

// Foundation Badges
const foundationBadges = [
    { id: 1, name: 'Module 1', description: 'Tour Preparation', icon: 'clipboard-list', earned: true },
    { id: 2, name: 'Module 2', description: 'Tour Briefings', icon: 'users', earned: false },
    { id: 3, name: 'Module 3', description: 'Information Delivery', icon: 'comments', earned: false },
    { id: 4, name: 'Module 4', description: 'Conclude the Tour', icon: 'flag-checkered', earned: false }
];

// Town Badges (simplified for demo - first 6 towns)
const townBadges = [
    { id: 1, name: 'Laoag', description: 'Capital City', icon: 'city', earned: true },
    { id: 2, name: 'Paoay', description: 'UNESCO Heritage', icon: 'church', earned: true },
    { id: 3, name: 'Pagudpud', description: 'Northern Paradise', icon: 'umbrella-beach', earned: false },
    { id: 4, name: 'Batac', description: 'Great Leaders', icon: 'landmark', earned: false },
    { id: 5, name: 'San Nicolas', description: 'Pottery Capital', icon: 'cube', earned: false },
    { id: 6, name: 'Sarrat', description: 'Marcos Birthplace', icon: 'home', earned: false }
];

// Special Badges
const specialBadges = [
    { id: 1, name: 'Quick Learner', description: 'Complete 3 modules in one day', icon: 'bolt', earned: false },
    { id: 2, name: 'Perfect Score', description: '100% on any simulation', icon: 'crown', earned: false },
    { id: 3, name: 'Early Bird', description: 'Complete training before noon', icon: 'sun', earned: true },
    { id: 4, name: 'Streak Master', description: '3 perfect quick checks', icon: 'fire', earned: false }
];

// Statistics
let stats = {
    keywordsMastered: 15,
    simulationsPassed: 1,
    perfectScores: 0,
    timeSpent: '45m'
};

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    loadNavUserName();
    loadProgress();
    renderBadges();
    renderCertificate();
    updateStats();
    updateTotalBadges();
});

// Load user name
function loadNavUserName() {
    const userName = localStorage.getItem('userName') || 'Trainee';
    document.getElementById('navUserName').textContent = userName;
}

// Load progress from localStorage
function loadProgress() {
    // Check foundation progress
    const foundationProgress = localStorage.getItem('foundationProgress');
    if (foundationProgress) {
        const progress = JSON.parse(foundationProgress);
        // Update foundation badges based on progress
        if (progress[1]?.lessonsCompleted) {
            foundationBadges[0].earned = true;
        }
    }
    
    // Check simulation progress
    const simulationProgress = localStorage.getItem('simulationProgress');
    if (simulationProgress) {
        const progress = JSON.parse(simulationProgress);
        progress.forEach((sim, index) => {
            if (index < townBadges.length && sim.completed) {
                townBadges[index].earned = true;
            }
        });
    }
    
    // Update stats based on progress
    const completedSims = townBadges.filter(b => b.earned).length;
    stats.simulationsPassed = completedSims;
    stats.keywordsMastered = completedSims * 15; // Rough estimate
}

// Render all badges
function renderBadges() {
    renderBadgeGroup('foundationBadges', foundationBadges);
    renderBadgeGroup('townBadges', townBadges);
    renderBadgeGroup('specialBadges', specialBadges);
}

// Render a group of badges
function renderBadgeGroup(containerId, badges) {
    const container = document.getElementById(containerId);
    
    container.innerHTML = badges.map(badge => `
        <div class="badge-item ${badge.earned ? 'earned' : 'locked'}" 
             onclick="showBadgeInfo('${badge.name}', '${badge.description}', ${badge.earned})">
            <div class="badge-icon">
                <i class="fas fa-${badge.icon}"></i>
            </div>
            <div class="badge-name">${badge.name}</div>
            <div class="badge-status">
                ${badge.earned ? '<i class="fas fa-check-circle me-1"></i>Earned' : '<i class="fas fa-lock me-1"></i>Locked'}
            </div>
        </div>
    `).join('');
}

// Render certificate section
function renderCertificate() {
    const totalBadges = foundationBadges.length + townBadges.length;
    const earnedBadges = foundationBadges.filter(b => b.earned).length + townBadges.filter(b => b.earned).length;
    const percentage = Math.round((earnedBadges / totalBadges) * 100);
    
    const card = document.getElementById('certificateCard');
    const btn = document.getElementById('certificateBtn');
    const desc = document.getElementById('certificateDescription');
    const progress = document.getElementById('certificateProgress');
    
    progress.textContent = `${percentage}% Complete`;
    
    // Certificate unlocks when all foundation + town badges are earned
    const allEarned = foundationBadges.every(b => b.earned) && townBadges.every(b => b.earned);
    
    if (allEarned) {
        card.classList.remove('locked');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-download me-2"></i>Download Certificate';
        btn.className = 'btn btn-warning btn-lg';
        btn.onclick = () => downloadCertificate();
        desc.textContent = 'Congratulations! You\'ve earned your tour guide certificate!';
    } else {
        card.classList.add('locked');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-lock me-2"></i>Locked';
        btn.className = 'btn btn-secondary btn-lg';
        desc.textContent = 'Complete all foundation modules and town simulations to earn your certificate!';
    }
}

// Update statistics display
function updateStats() {
    document.getElementById('keywordsMastered').textContent = stats.keywordsMastered;
    document.getElementById('simulationsPassed').textContent = stats.simulationsPassed;
    document.getElementById('perfectScores').textContent = stats.perfectScores;
    document.getElementById('timeSpent').textContent = stats.timeSpent;
}

// Update total badges count
function updateTotalBadges() {
    const total = foundationBadges.length + townBadges.length + specialBadges.length;
    const earned = foundationBadges.filter(b => b.earned).length + 
                   townBadges.filter(b => b.earned).length + 
                   specialBadges.filter(b => b.earned).length;
    document.getElementById('totalBadgesEarned').textContent = earned;
}

// Show badge info modal
function showBadgeInfo(name, description, earned) {
    // Simple alert for demo - can be upgraded to modal
    if (earned) {
        alert(`🏆 ${name}\n\n${description}\n\nStatus: Earned!`);
    } else {
        alert(`🔒 ${name}\n\n${description}\n\nStatus: Locked - Keep training to unlock!`);
    }
}

// Download certificate
function downloadCertificate() {
    const userName = localStorage.getItem('userName') || 'Trainee';
    const date = new Date().toLocaleDateString('en-US', { 
        year: 'numeric', month: 'long', day: 'numeric' 
    });
    
    // Create certificate HTML for download
    const certificateHtml = `
        <!DOCTYPE html>
        <html>
        <head>
            <title>Certificate - WIN e-Travel</title>
            <style>
                body {
                    font-family: 'Georgia', serif;
                    text-align: center;
                    padding: 50px;
                    background: linear-gradient(135deg, #0a472e 0%, #1a5f7a 100%);
                    color: white;
                }
                .certificate {
                    border: 10px solid #ffd700;
                    padding: 50px;
                    max-width: 800px;
                    margin: 0 auto;
                    background: rgba(255,255,255,0.1);
                }
                h1 { font-size: 48px; color: #ffd700; }
                h2 { font-size: 32px; }
                .name { font-size: 36px; font-weight: bold; margin: 30px 0; }
                .date { margin-top: 50px; }
            </style>
        </head>
        <body>
            <div class="certificate">
                <h1>🏆 WIN e-Travel Training</h1>
                <h2>Certificate of Completion</h2>
                <p>This certifies that</p>
                <div class="name">${userName}</div>
                <p>has successfully completed the Virtual Tour Guide Training Program</p>
                <p>demonstrating mastery of tour preparation, briefing, information delivery,<br>
                and virtual simulations across Ilocos Norte municipalities.</p>
                <div class="date">
                    <p>Awarded on ${date}</p>
                    <p>Mariano Marcos State University</p>
                </div>
            </div>
        </body>
        </html>
    `;
    
    // Create download
    const blob = new Blob([certificateHtml], { type: 'text/html' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `WIN_Certificate_${userName}.html`;
    a.click();
    
    alert('🎉 Certificate downloaded! Open the file to view your certificate.');
}