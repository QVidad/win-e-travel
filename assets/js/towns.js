// ============================================
// TOWN DATA - Ilocos Norte Municipalities
// ============================================

const towns = [
    {
        id: 1,
        name: 'Laoag City',
        description: 'Capital of Ilocos Norte • Sunshine City',
        image: 'assets/images/laoag.jpg',
        badge: 'Completed',
        badgeClass: 'bg-success text-white',
        status: 'completed',
        progress: 100,
        pageUrl: 'town-laoag.html',
        attractions: ['Sinking Bell Tower', 'St. William\'s Cathedral', 'Aurora Park', 'Museo Ilocos Norte']
    },
    {
        id: 2,
        name: 'Paoay',
        description: 'UNESCO World Heritage Site • 20km from Laoag',
        image: 'assets/images/paoay.jpg',
        badge: 'Available',
        badgeClass: 'bg-primary text-white',
        status: 'available',
        progress: 0,
        pageUrl: 'town-paoay.html',
        attractions: ['Paoay Church', 'Malacañang of the North', 'Paoay Lake', 'Sand Dunes']
    },
    {
        id: 3,
        name: 'Pagudpud',
        description: 'Northernmost tip • Boracay of the North',
        image: 'assets/images/pagudpud.jpg',
        badge: 'Locked',
        badgeClass: 'bg-secondary text-white',
        status: 'locked',
        progress: 0,
        pageUrl: 'town-pagudpud.html',
        attractions: ['Saud Beach', 'Blue Lagoon', 'Bantay Abot', 'Patapat Viaduct']
    },
    {
        id: 4,
        name: 'Batac',
        description: 'Home of Great Leaders • Marcos Museum',
        badge: 'Coming Soon',
        badgeClass: 'bg-light text-dark',
        status: 'coming-soon',
        progress: 0,
        attractions: []
    },
    {
        id: 5,
        name: 'San Nicolas',
        description: 'Pottery Capital • Damili Festival',
        badge: 'Coming Soon',
        badgeClass: 'bg-light text-dark',
        status: 'coming-soon',
        progress: 0,
        attractions: []
    },
    {
        id: 6,
        name: 'Sarrat',
        description: 'Birthplace of Ferdinand Marcos',
        badge: 'Coming Soon',
        badgeClass: 'bg-light text-dark',
        status: 'coming-soon',
        progress: 0,
        attractions: []
    },
    {
        id: 7,
        name: 'Dingras',
        description: 'Rice Granary of Ilocos Norte',
        badge: 'Coming Soon',
        badgeClass: 'bg-light text-dark',
        status: 'coming-soon',
        progress: 0,
        attractions: []
    },
    {
        id: 8,
        name: 'Bacarra',
        description: 'Home of Balikbayans • Domeless Bell Tower',
        badge: 'Coming Soon',
        badgeClass: 'bg-light text-dark',
        status: 'coming-soon',
        progress: 0,
        attractions: []
    },
    {
        id: 9,
        name: 'Badoc',
        description: 'Juan Luna Shrine • La Virgen Milagrosa',
        badge: 'Coming Soon',
        badgeClass: 'bg-light text-dark',
        status: 'coming-soon',
        progress: 0,
        attractions: []
    },
    {
        id: 10,
        name: 'Pinili',
        description: 'Garlic Capital • Inabel Weaving',
        badge: 'Coming Soon',
        badgeClass: 'bg-light text-dark',
        status: 'coming-soon',
        progress: 0,
        attractions: []
    },
    {
        id: 11,
        name: 'Currimao',
        description: 'Pangil Rock Formation • Seawall Lights',
        badge: 'Coming Soon',
        badgeClass: 'bg-light text-dark',
        status: 'coming-soon',
        progress: 0,
        attractions: []
    },
    {
        id: 12,
        name: 'Bangui',
        description: 'Bangui Wind Farm • First in Southeast Asia',
        badge: 'Coming Soon',
        badgeClass: 'bg-light text-dark',
        status: 'coming-soon',
        progress: 0,
        attractions: []
    }
];

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    loadNavUserName();
    loadTownProgress();
    renderTownGrid();
    updateOverallProgress();
});

// Render town grid
function renderTownGrid() {
    const grid = document.getElementById('townGrid');
    
    grid.innerHTML = towns.map(town => {
        const statusClass = town.status;
        const imageUrl = town.image || `https://placehold.co/600x400/1a5f7a/ffffff?text=${encodeURIComponent(town.name)}`;
        
        const cardContent = `
            <div class="town-image" style="background-image: url('${imageUrl}')">
                <span class="town-badge ${town.badgeClass}">
                    ${town.badge}
                    ${town.status === 'completed' ? ' <i class="fas fa-check-circle ms-1"></i>' : ''}
                    ${town.status === 'locked' ? ' <i class="fas fa-lock ms-1"></i>' : ''}
                </span>
            </div>
            <div class="town-content">
                <h5 class="fw-bold mb-1">${town.name}</h5>
                <p class="text-muted small mb-2">${town.description}</p>
                ${town.status !== 'coming-soon' ? `
                    <div class="progress-bar-custom">
                        <div class="progress-fill bg-success" style="width: ${town.progress}%; height: 6px; border-radius: 10px;"></div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="small text-muted">${town.progress}% Complete</span>
                        ${town.status === 'completed' ? 
                            '<i class="fas fa-check-circle text-success"></i>' : 
                            '<i class="fas fa-arrow-right text-muted"></i>'}
                    </div>
                ` : `
                    <p class="small text-muted mb-2"><i class="fas fa-clock me-1"></i>Coming Soon</p>
                `}
                ${town.attractions.length > 0 ? `
                    <div class="mt-2">
                        ${town.attractions.slice(0, 2).map(attr => 
                            `<span class="attraction-tag">${attr}</span>`
                        ).join('')}
                        ${town.attractions.length > 2 ? 
                            `<span class="attraction-tag">+${town.attractions.length - 2} more</span>` : ''}
                    </div>
                ` : ''}
            </div>
        `;
        
        if (town.status === 'coming-soon' || town.status === 'locked') {
            return `<div class="town-card ${statusClass}">${cardContent}</div>`;
        } else {
            return `<a href="${town.pageUrl}" class="town-card ${statusClass}">${cardContent}</a>`;
        }
    }).join('');
}

// Update overall progress
function updateOverallProgress() {
    const completed = towns.filter(t => t.status === 'completed').length;
    const total = towns.filter(t => t.status !== 'coming-soon').length;
    const percentage = (completed / total) * 100;
    
    document.getElementById('townsCompleted').textContent = completed;
    document.getElementById('overallTownsProgress').style.width = percentage + '%';
}

// Load progress from localStorage
function loadTownProgress() {
    const saved = localStorage.getItem('townsProgress');
    if (saved) {
        const progress = JSON.parse(saved);
        progress.forEach(p => {
            const town = towns.find(t => t.id === p.id);
            if (town) {
                town.status = p.status;
                town.progress = p.progress;
                if (town.status === 'completed') {
                    town.badge = 'Completed';
                    town.badgeClass = 'bg-success text-white';
                }
            }
        });
    }
    
    // Ensure correct initial state for demo
    const laoag = towns.find(t => t.id === 1);
    if (laoag) {
        laoag.status = 'completed';
        laoag.progress = 100;
        laoag.badge = 'Completed';
        laoag.badgeClass = 'bg-success text-white';
    }
    
    const paoay = towns.find(t => t.id === 2);
    if (paoay) {
        paoay.status = 'available';
        paoay.badge = 'Available';
        paoay.badgeClass = 'bg-primary text-white';
    }
    
    const pagudpud = towns.find(t => t.id === 3);
    if (pagudpud) {
        pagudpud.status = 'locked';
        pagudpud.badge = 'Locked';
        pagudpud.badgeClass = 'bg-secondary text-white';
    }
}

// Load user name
function loadNavUserName() {
    const userName = localStorage.getItem('userName') || 'Trainee';
    document.getElementById('navUserName').textContent = userName;
}