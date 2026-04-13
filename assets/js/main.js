// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Set current year in footer
    document.querySelector('#copyright-year span').textContent = new Date().getFullYear();
    
    // Initialize municipalities data
    loadMunicipalities();
    
    // Initialize smooth scrolling for anchor links
    initSmoothScroll();
    
    // Initialize form handlers
    initFormHandlers();
    
    // Initialize tooltips and popovers if Bootstrap is available
    initBootstrapComponents();
});

// Municipality data with real image paths
const municipalitiesData = [
    {
        name: 'Laoag City',
        description: 'Capital of Ilocos Norte',
        attractions: 'Sinking Bell Tower, St. William\'s Cathedral, La Paz Sand Dunes, Museo Ilocos Norte',
        modules: 5,
        badge: 'Capital City',
        badgeClass: 'bg-warning text-dark',
        icon: 'fa-crown',
        imagePath: 'assets/images/Laoag.jpg',
        imageAlt: 'Laoag City - Sinking Bell Tower and Cathedral'
    },
    {
        name: 'Paoay',
        description: '17km from Laoag',
        attractions: 'Paoay Church (St. Augustine), Malacañang of the North, Paoay Lake, Herencia Café',
        modules: 4,
        badge: 'UNESCO Heritage',
        badgeClass: 'bg-info text-white',
        icon: 'fa-unesco',
        imagePath: 'assets/images/Paoay.jpg',
        imageAlt: 'Paoay Church - UNESCO World Heritage Site'
    },
    {
        name: 'Pagudpud',
        description: 'Northernmost tip',
        attractions: 'Saud Beach, Blue Lagoon, Bangui Windmills, Patapat Viaduct, Kabigan Falls',
        modules: 5,
        badge: 'Boracay of the North',
        badgeClass: 'bg-primary text-white',
        icon: 'fa-umbrella-beach',
        imagePath: 'assets/images/Pagudpud.jpg',
        imageAlt: 'Pagudpud Beach - Saud Beach and Windmills'
    }
];

// Load municipalities into the page
function loadMunicipalities() {
    const container = document.getElementById('municipalitiesContainer');
    if (!container) return;
    
    container.innerHTML = municipalitiesData.map(municipality => `
        <div class="col-md-4">
            <div class="card border-0 shadow-sm municipality-card">
                <div class="position-relative">
                    <img src="${municipality.imagePath}" 
                         class="card-img-top" 
                         alt="${municipality.imageAlt}" 
                         style="height: 200px; object-fit: cover;"
                         onerror="this.src='https://placehold.co/600x400/1a5f7a/ffffff?text=${encodeURIComponent(municipality.name)}'">
                    <span class="position-absolute top-0 end-0 badge ${municipality.badgeClass} m-3 px-3 py-2">
                        <i class="fas ${municipality.icon} me-1"></i>${municipality.badge}
                    </span>
                </div>
                <div class="card-body">
                    <h5 class="card-title fw-bold">${municipality.name}</h5>
                    <p class="card-text small text-muted mb-2">
                        <i class="fas fa-map-pin me-1 text-success"></i> ${municipality.description}
                    </p>
                    <p class="card-text small">
                        ${municipality.attractions}
                    </p>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="badge bg-success bg-opacity-10 text-success">
                            <i class="fas fa-check-circle me-1"></i>${municipality.modules} Modules
                        </span>
                        <span class="badge bg-info bg-opacity-10 text-info">
                            <i class="fas fa-microphone me-1"></i>Voice Practice
                        </span>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
}

// Initialize smooth scrolling
function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href !== '#') {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });
}

// Initialize form handlers
function initFormHandlers() {
    // Update login form handler
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            if (email && password) {
                // Extract name from email for demo
                const userName = email.split('@')[0];
                localStorage.setItem('userName', userName);
                localStorage.setItem('userEmail', email);
                
                showNotification('Login successful! Redirecting...', 'success');
                
                setTimeout(() => {
                    bootstrap.Modal.getInstance(document.getElementById('loginModal')).hide();
                    // Check if first time login
                    const onboardingComplete = localStorage.getItem('onboardingComplete');
                    if (onboardingComplete) {
                        window.location.href = 'dashboard.html';
                    } else {
                        window.location.href = 'welcome.html';
                    }
                }, 1000);
            } else {
                showNotification('Please fill in all fields', 'warning');
            }
        });
    }

    // Update register form handler
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const name = document.getElementById('regName').value;
            const email = document.getElementById('regEmail').value;
            const password = document.getElementById('regPassword').value;
            const confirmPassword = document.getElementById('regConfirmPassword').value;
            
            if (name && email && password && confirmPassword) {
                if (password !== confirmPassword) {
                    showNotification('Passwords do not match', 'danger');
                    return;
                }
                
                // Save user info
                localStorage.setItem('userName', name);
                localStorage.setItem('userEmail', email);
                localStorage.setItem('onboardingComplete', 'false');
                
                showNotification('Registration successful! Redirecting to welcome page...', 'success');
                
                setTimeout(() => {
                    bootstrap.Modal.getInstance(document.getElementById('registerModal')).hide();
                    window.location.href = 'welcome.html';
                }, 1500);
            } else {
                showNotification('Please fill in all fields', 'warning');
            }
        });
    }
    
    // View all municipalities handler
    const viewAllBtn = document.getElementById('viewAllMunicipalities');
    if (viewAllBtn) {
        viewAllBtn.addEventListener('click', function(e) {
            e.preventDefault();
            showNotification('Loading all 21 municipalities... (Demo mode)', 'info');
            // In a real app, this would navigate to a municipalities page
        });
    }
}

// Initialize Bootstrap components
function initBootstrapComponents() {
    // Enable all tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Enable all popovers
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function(popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
}

// Show notification toast
function showNotification(message, type = 'info') {
    // Create toast container if it doesn't exist
    let toastContainer = document.querySelector('.toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
        document.body.appendChild(toastContainer);
    }
    
    // Create toast element
    const toastId = 'toast-' + Date.now();
    const bgClass = type === 'success' ? 'bg-success' : 
                   type === 'danger' ? 'bg-danger' : 
                   type === 'warning' ? 'bg-warning' : 'bg-info';
    
    const toastHtml = `
        <div id="${toastId}" class="toast align-items-center text-white ${bgClass} border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;
    
    toastContainer.insertAdjacentHTML('beforeend', toastHtml);
    
    // Initialize and show toast
    const toastElement = document.getElementById(toastId);
    const toast = new bootstrap.Toast(toastElement, {
        animation: true,
        autohide: true,
        delay: 3000
    });
    toast.show();
    
    // Remove toast after it's hidden
    toastElement.addEventListener('hidden.bs.toast', function() {
        this.remove();
    });
}

// Handle modal switching
document.addEventListener('click', function(e) {
    // Switch from login to register
    if (e.target.matches('[data-bs-target="#registerModal"]') && e.target.closest('#loginModal')) {
        const loginModal = bootstrap.Modal.getInstance(document.getElementById('loginModal'));
        loginModal.hide();
        setTimeout(() => {
            const registerModal = new bootstrap.Modal(document.getElementById('registerModal'));
            registerModal.show();
        }, 300);
    }
    
    // Switch from register to login
    if (e.target.matches('[data-bs-target="#loginModal"]') && e.target.closest('#registerModal')) {
        const registerModal = bootstrap.Modal.getInstance(document.getElementById('registerModal'));
        registerModal.hide();
        setTimeout(() => {
            const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
            loginModal.show();
        }, 300);
    }
});

// Add active class to nav items on scroll
window.addEventListener('scroll', function() {
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
    
    let current = '';
    sections.forEach(section => {
        const sectionTop = section.offsetTop;
        const sectionHeight = section.clientHeight;
        if (pageYOffset >= sectionTop - 100) {
            current = section.getAttribute('id');
        }
    });
    
    navLinks.forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('href') === `#${current}`) {
            link.classList.add('active');
        }
    });
});