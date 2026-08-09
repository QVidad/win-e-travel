<template>
    <div>
        <!-- Navigation Header -->
        <nav class="navbar navbar-expand-lg bg-white shadow-sm py-3 sticky-top">
            <div class="container">
                <Link :href="route('home')" class="navbar-brand d-flex align-items-center">
                    <img src="/assets/images/WINLogo.png" alt="WIN Logo" style="height: 40px; width: 40px; margin-right: 8px;">
                    <span class="fw-bold" style="color: var(--mmsu-green);">WIN e-Travel Training</span>
                </Link>
                <div class="d-flex align-items-center ms-auto gap-3">
                    <a href="#features" class="nav-link text-secondary"><i class="fas fa-star me-1"></i>Features</a>
                    <a href="#municipalities" class="nav-link text-secondary"><i class="fas fa-map-marker-alt me-1"></i>Municipalities</a>
                    <a href="#flow" class="nav-link text-secondary"><i class="fas fa-route me-1"></i>Learning Path</a>
                    <button type="button" class="btn btn-outline-mmsu rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#loginModal" @click="openLoginModal">
                        <i class="fas fa-sign-in-alt me-1"></i> Sign In
                    </button>
                    <button type="button" class="btn btn-mmsu rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#registerModal" @click="openRegisterModal">
                        <i class="fas fa-user-plus me-1"></i> Start Training
                    </button>
                </div>
            </div>
        </nav>

        <main>
            <!-- Hero Section -->
            <section class="position-relative overflow-hidden text-white" style="background: linear-gradient(135deg, #0a472e 0%, #1a5f7a 100%); min-height: 90vh;">
                <!-- Decorative Background Pattern -->
                <div class="position-absolute top-0 start-0 w-100 h-100 pointer-events-none" style="z-index: 1;">
                    <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <pattern id="hero-map-grid" width="110" height="110" patternUnits="userSpaceOnUse">
                                <!-- Softer, Larger Intersecting Grid Lines -->
                                <path d="M 0 55 L 110 55 M 55 0 L 55 110" stroke="#ffffff" stroke-width="0.75" opacity="0.08" />
                                <!-- Subtle Center Intersection Dot -->
                                <circle cx="55" cy="55" r="2" fill="#ffffff" opacity="0.2" />
                            </pattern>
                        </defs>
                        <rect width="100%" height="100%" fill="url(#hero-map-grid)" />
                    </svg>
                </div>

                <div class="container position-relative" style="z-index: 2; padding-top: 80px; padding-bottom: 80px;">
                    <div class="row align-items-center min-vh-75">
                        <div class="col-lg-6 text-white mb-5 mb-lg-0">
                            <div class="d-inline-flex align-items-center bg-white bg-opacity-10 text-white px-4 py-2 rounded-pill mb-4 backdrop-blur">
                                <i class="fas fa-vr-cardboard me-2"></i>
                                <span>{{ hero?.content?.badge || 'Interactive Computer-Based Simulation' }}</span>
                            </div>
                            <h1 class="display-4 fw-bold mb-4">
                                Master Tour Guiding.
                                <br>
                                <span class="text-warning">Explore Ilocos Norte.</span>
                            </h1>
                            <p class="lead mb-4 opacity-90">
                                {{ hero?.subtitle || 'WIN e-Travel Training bridges the gap between classroom theory and real-world tour guiding. Practice your delivery, explore 21 municipalities, and develop the skills needed to become a confident, industry-ready tour guide.' }}
                            </p>
                            <div class="d-flex flex-wrap gap-3">
                                <a href="#" class="btn btn-gold btn-lg" data-bs-toggle="modal" data-bs-target="#registerModal" @click.prevent="openRegisterModal">
                                    <i class="fas fa-play-circle me-2"></i>Begin Training
                                </a>
                                <a href="#flow" class="btn btn-outline-light btn-lg">
                                    <i class="fas fa-route me-2"></i>View Learning Path
                                </a>
                            </div>

                            <!-- Key Stats -->
                            <div class="row mt-5">
                                <div class="col-3">
                                    <h3 class="text-warning display-6 fw-bold mb-0">21</h3>
                                    <p class="small opacity-75 mb-0">Municipalities</p>
                                </div>
                                <div class="col-3">
                                    <h3 class="text-warning display-6 fw-bold mb-0">4</h3>
                                    <p class="small opacity-75 mb-0">Core Skills</p>
                                </div>
                                <div class="col-3">
                                    <h3 class="text-warning display-6 fw-bold mb-0">360°</h3>
                                    <p class="small opacity-75 mb-0">Virtual Tours</p>
                                </div>
                                <div class="col-3">
                                    <h3 class="text-warning display-6 fw-bold mb-0">Speech</h3>
                                    <p class="small opacity-75 mb-0">Enabled Practice</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 text-center">
                            <div class="position-relative">
                                <div class="bg-white bg-opacity-10 rounded-4 p-4 backdrop-blur shadow-lg">
                                    <img src="/assets/images/INBackground.jpg" 
                                         alt="WIN e-Travel Training Interface" 
                                         class="img-fluid rounded-3 shadow-lg"
                                         style="max-height: 420px; width: 100%; object-fit: cover;">
                                </div>
                                <div class="position-absolute top-0 start-0 bg-warning rounded-3 p-3 shadow" style="margin-left: -15px; margin-top: 30px;">
                                    <i class="fas fa-microphone-alt text-dark fa-lg"></i>
                                </div>
                                <div class="position-absolute bottom-0 end-0 bg-success rounded-3 p-3 shadow" style="margin-right: -15px; margin-bottom: 40px;">
                                    <i class="fas fa-trophy text-white fa-lg"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="position-absolute bottom-0 start-0 w-100">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 100" fill="#f5f7fa">
                        <path d="M0,64L80,69.3C160,75,320,85,480,80C640,75,800,53,960,48C1120,43,1280,53,1360,58.7L1440,64L1440,100L1360,100C1280,100,1120,100,960,100C800,100,640,100,480,100C320,100,160,100,80,100L0,100Z"/>
                    </svg>
                </div>
            </section>

            <!-- Core Features Section -->
            <section id="features" class="py-5">
                <div class="container py-4">
                    <div class="text-center mb-5">
                        <span class="badge-mmsu mb-3 d-inline-block">
                            <i class="fas fa-star me-1"></i> Research-Based Training
                        </span>
                        <h2 class="display-6 fw-bold mb-3">{{ features?.title || 'Developed with Tourism Experts' }}</h2>
                        <p class="text-muted col-lg-8 mx-auto">
                            {{ features?.subtitle || 'Our training modules are designed based on insights from DOT-accredited tour guides, tourism educators, and BSTM students across Ilocos Norte.' }}
                        </p>
                    </div>

                    <div class="row g-4">
                        <!-- Tour Preparation -->
                        <div class="col-lg-3 col-md-6">
                            <div class="card h-100 border-0 shadow-sm hover-lift">
                                <div class="card-body text-center p-4">
                                    <div class="feature-icon bg-success bg-opacity-10 text-success mx-auto">
                                        <i class="fas fa-clipboard-list"></i>
                                    </div>
                                    <h4 class="h5 fw-bold mt-3">Tour Preparation</h4>
                                    <p class="small text-muted">
                                        Master logistics, destination research, and itinerary planning through interactive modules.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Tour Briefing -->
                        <div class="col-lg-3 col-md-6">
                            <div class="card h-100 border-0 shadow-sm hover-lift">
                                <div class="card-body text-center p-4">
                                    <div class="feature-icon bg-primary bg-opacity-10 text-primary mx-auto">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <h4 class="h5 fw-bold mt-3">Tour Briefing</h4>
                                    <p class="small text-muted">
                                        Practice delivering engaging briefings with timing, humor, and guest engagement techniques.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Destination Storytelling -->
                        <div class="col-lg-3 col-md-6">
                            <div class="card h-100 border-0 shadow-sm hover-lift">
                                <div class="card-body text-center p-4">
                                    <div class="feature-icon bg-warning bg-opacity-10 text-warning mx-auto">
                                        <i class="fas fa-landmark"></i>
                                    </div>
                                    <h4 class="h5 fw-bold mt-3">Destination Storytelling</h4>
                                    <p class="small text-muted">
                                        Learn to deliver compelling narratives about Ilocos Norte's rich cultural heritage.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Voice & Delivery Training -->
                        <div class="col-lg-3 col-md-6">
                            <div class="card h-100 border-0 shadow-sm hover-lift">
                                <div class="card-body text-center p-4">
                                    <div class="feature-icon bg-info bg-opacity-10 text-info mx-auto">
                                        <i class="fas fa-microphone-alt"></i>
                                    </div>
                                    <h4 class="h5 fw-bold mt-3">Voice & Delivery</h4>
                                    <p class="small text-muted">
                                        Receive real-time AI feedback on voice modulation, pacing, and posture during simulations.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Learning Flow Section -->
            <section id="flow" class="py-5 bg-light">
                <div class="container py-4">
                    <div class="text-center mb-5">
                        <span class="badge-mmsu mb-3 d-inline-block">
                            <i class="fas fa-sitemap me-1"></i> Structured Learning
                        </span>
                        <h2 class="display-6 fw-bold mb-3">Your Training Journey</h2>
                        <p class="text-muted col-lg-8 mx-auto">
                            Follow our research-validated framework designed to build tour guiding competence step by step.
                        </p>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-lg-10">
                            <div class="timeline-steps">
                                <div class="row g-4">
                                    <div class="col-md-3 mb-4">
                                        <div class="card border-0 shadow-sm h-100">
                                            <div class="card-body text-center p-4">
                                                <div class="display-1 fw-bold text-success bg-success bg-opacity-10 rounded-circle d-inline-flex justify-content-center align-items-center mb-3" style="width: 70px; height: 70px; font-size: 28px;">1</div>
                                                <h5 class="fw-bold">Orientation</h5>
                                                <p class="small text-muted mb-0">Tour guide roles, ethics, and system navigation</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-4">
                                        <div class="card border-0 shadow-sm h-100">
                                            <div class="card-body text-center p-4">
                                                <div class="display-1 fw-bold text-primary bg-primary bg-opacity-10 rounded-circle d-inline-flex justify-content-center align-items-center mb-3" style="width: 70px; height: 70px; font-size: 28px;">2</div>
                                                <h5 class="fw-bold">Tour Preparation</h5>
                                                <p class="small text-muted mb-0">Research, itinerary planning, logistics</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-4">
                                        <div class="card border-0 shadow-sm h-100">
                                            <div class="card-body text-center p-4">
                                                <div class="display-1 fw-bold text-warning bg-warning bg-opacity-10 rounded-circle d-inline-flex justify-content-center align-items-center mb-3" style="width: 70px; height: 70px; font-size: 28px;">3</div>
                                                <h5 class="fw-bold">Tour Briefing</h5>
                                                <p class="small text-muted mb-0">Scriptwriting, voice practice, guest engagement</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-4">
                                        <div class="card border-0 shadow-sm h-100">
                                            <div class="card-body text-center p-4">
                                                <div class="display-1 fw-bold text-info bg-info bg-opacity-10 rounded-circle d-inline-flex justify-content-center align-items-center mb-3" style="width: 70px; height: 70px; font-size: 28px;">4</div>
                                                <h5 class="fw-bold">Storytelling</h5>
                                                <p class="small text-muted mb-0">Destination narratives, cultural context</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-4 justify-content-center">
                                    <div class="col-md-3 mb-4">
                                        <div class="card border-0 shadow-sm h-100">
                                            <div class="card-body text-center p-4">
                                                <div class="display-1 fw-bold text-danger bg-danger bg-opacity-10 rounded-circle d-inline-flex justify-content-center align-items-center mb-3" style="width: 70px; height: 70px; font-size: 28px;">5</div>
                                                <h5 class="fw-bold">Guest Scenarios</h5>
                                                <p class="small text-muted mb-0">Handling questions, different guest types</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-4">
                                        <div class="card border-0 shadow-sm h-100">
                                            <div class="card-body text-center p-4">
                                                <div class="display-1 fw-bold text-secondary bg-secondary bg-opacity-10 rounded-circle d-inline-flex justify-content-center align-items-center mb-3" style="width: 70px; height: 70px; font-size: 28px;">6</div>
                                                <h5 class="fw-bold">Crisis Management</h5>
                                                <p class="small text-muted mb-0">Emergency protocols, problem-solving</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-4">
                                        <div class="card border-0 shadow-sm h-100">
                                            <div class="card-body text-center p-4">
                                                <div class="display-1 fw-bold text-success bg-success bg-opacity-10 rounded-circle d-inline-flex justify-content-center align-items-center mb-3" style="width: 70px; height: 70px; font-size: 28px;">7</div>
                                                <h5 class="fw-bold">Final Tour</h5>
                                                <p class="small text-muted mb-0">Complete simulation with voice assessment</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-4">
                                        <div class="card border-0 shadow-sm h-100">
                                            <div class="card-body text-center p-4">
                                                <div class="display-1 fw-bold text-success bg-success bg-opacity-10 rounded-circle d-inline-flex justify-content-center align-items-center mb-3" style="width: 70px; height: 70px; font-size: 28px;">8</div>
                                                <h5 class="fw-bold">Reflection</h5>
                                                <p class="small text-muted mb-0">Feedback, badges, and certification</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Municipalities Preview Section -->
            <section id="municipalities" class="py-5">
                <div class="container py-4">
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                        <div>
                            <h2 class="display-6 fw-bold mb-2">Explore Ilocos Norte</h2>
                            <p class="text-muted mb-0">21 municipalities and cities waiting to be discovered</p>
                        </div>
                        <Link v-if="$page.props.auth?.user" :href="route('towns.index')" class="btn btn-outline-mmsu mt-3 mt-sm-0">
                            View All Municipalities <i class="fas fa-arrow-right ms-2"></i>
                        </Link>
                        <button v-else @click="openRegisterModal" class="btn btn-outline-mmsu mt-3 mt-sm-0">
                            View All Municipalities <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>

                    <div class="row g-4" id="municipalitiesContainer">
                        <div v-for="(town, idx) in displayedTowns" :key="town.id || idx" class="col-md-4">
                            <div class="card border-0 shadow-sm municipality-card h-100 hover-lift overflow-hidden">
                                <div class="position-relative">
                                    <img :src="town.hero_image || town.imagePath || '/assets/images/INBackground.jpg'" 
                                         class="card-img-top" 
                                         :alt="town.name" 
                                         style="height: 200px; object-fit: cover;">
                                    <span class="position-absolute top-0 end-0 badge m-3 px-3 py-2" :class="town.badgeClass || 'bg-success text-white'">
                                        <i class="fas" :class="town.icon || 'fa-map-pin'"></i> {{ town.badge || town.region || 'Municipality' }}
                                    </span>
                                </div>
                                <div class="card-body p-4 d-flex flex-column">
                                    <h5 class="card-title fw-bold">{{ town.name }}</h5>
                                    <p class="card-text small text-muted mb-2">
                                        <i class="fas fa-map-pin me-1 text-success"></i> {{ town.description || town.tagline }}
                                    </p>
                                    <p class="card-text small text-secondary flex-grow-1">
                                        {{ town.attractions || town.cultural_highlights }}
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                                        <span class="badge bg-success bg-opacity-10 text-success">
                                            <i class="fas fa-check-circle me-1"></i>{{ town.modules || 4 }} Modules
                                        </span>
                                        <Link v-if="$page.props.auth?.user" :href="route('towns.show', town.slug || town.id)" class="btn btn-sm btn-outline-mmsu rounded-pill px-3">
                                            Explore <i class="fas fa-arrow-right ms-1"></i>
                                        </Link>
                                        <button v-else @click="openRegisterModal" class="btn btn-sm btn-outline-mmsu rounded-pill px-3">
                                            Explore <i class="fas fa-arrow-right ms-1"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Gamification Features -->
            <section class="py-5 bg-light">
                <div class="container py-4">
                    <div class="row align-items-center">
                        <div class="col-lg-6 mb-4 mb-lg-0">
                            <span class="badge-mmsu mb-3 d-inline-block">
                                <i class="fas fa-gamepad me-1"></i> Gamified Learning
                            </span>
                            <h2 class="display-6 fw-bold mb-4">Earn Badges. Track Progress. Master Skills.</h2>
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-medal fa-2x text-warning me-3"></i>
                                        <div>
                                            <h6 class="fw-bold mb-1">Achievement Badges</h6>
                                            <p class="small text-muted mb-0">Unlock recognition for each completed municipality</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-chart-line fa-2x text-success me-3"></i>
                                        <div>
                                            <h6 class="fw-bold mb-1">Progress Dashboard</h6>
                                            <p class="small text-muted mb-0">Visualize your skill development over time</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-trophy fa-2x text-primary me-3"></i>
                                        <div>
                                            <h6 class="fw-bold mb-1">Leaderboard</h6>
                                            <p class="small text-muted mb-0">Compare performance with fellow trainees</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-certificate fa-2x text-info me-3"></i>
                                        <div>
                                            <h6 class="fw-bold mb-1">Micro-Credentials</h6>
                                            <p class="small text-muted mb-0">Earn certificates recognized by tourism partners</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 text-center">
                            <img src="/assets/images/badges.jpg" 
                                 alt="Gamification Features" 
                                 class="img-fluid rounded-4 shadow">
                        </div>
                    </div>
                </div>
            </section>

            <!-- CTA Section -->
            <section class="py-5" style="background: linear-gradient(135deg, #0a472e 0%, #1a5f7a 100%);">
                <div class="container py-4">
                    <div class="row align-items-center">
                        <div class="col-lg-8 text-white">
                            <h2 class="display-6 fw-bold mb-3">Ready to Become a Certified Virtual Tour Guide?</h2>
                            <p class="lead opacity-90 mb-0">
                                Join the WIN e-Travel Training program and develop the skills needed for a successful career in Ilocos Norte tourism.
                            </p>
                        </div>
                        <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                            <button v-if="!$page.props.auth?.user" @click="openRegisterModal" class="btn btn-gold btn-lg px-5">
                                <i class="fas fa-play me-2"></i>Start Training Now
                            </button>
                            <Link v-else :href="route('dashboard')" class="btn btn-gold btn-lg px-5">
                                <i class="fas fa-tachometer-alt me-2"></i>Go to Dashboard
                            </Link>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <!-- Footer -->
        <footer class="footer py-5 mt-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 mb-4">
                        <h5 class="text-white mb-3">
                            <i class="fas fa-map-signs me-2 text-warning"></i>WIN e-Travel Training
                        </h5>
                        <p class="small opacity-75">
                            An interactive computer-based simulation training for tour guiding. Bridging classroom theory and real-world practice in Ilocos Norte tourism.
                        </p>
                        <p class="small mt-3 text-warning">
                            <i class="fas fa-leaf me-1"></i> 
                            Cultivating Minds, Transforming Futures
                        </p>
                    </div>
                    <div class="col-lg-3 mb-4">
                        <h6 class="text-white mb-3">Quick Links</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2"><Link :href="route('home')"><i class="fas fa-chevron-right me-2 small"></i>Home</Link></li>
                            <template v-if="!$page.props.auth?.user">
                                <li class="mb-2"><a href="#" @click.prevent="openLoginModal"><i class="fas fa-chevron-right me-2 small"></i>Trainee Login</a></li>
                                <li class="mb-2"><a href="#" @click.prevent="openRegisterModal"><i class="fas fa-chevron-right me-2 small"></i>Register</a></li>
                            </template>
                            <template v-else>
                                <li class="mb-2"><Link :href="route('dashboard')"><i class="fas fa-chevron-right me-2 small"></i>Dashboard</Link></li>
                            </template>
                            <li class="mb-2"><a href="#features"><i class="fas fa-chevron-right me-2 small"></i>About the Project</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-5 mb-4">
                        <h6 class="text-white mb-3">Contact MMSU</h6>
                        <p class="small opacity-75 mb-2">
                            <i class="fas fa-map-marker-alt me-2"></i>City of Batac, Ilocos Norte, Philippines
                        </p>
                        <p class="small opacity-75 mb-2">
                            <i class="fas fa-phone me-2"></i>(63) 77-600-0459
                        </p>
                        <p class="small opacity-75 mb-2">
                            <i class="fas fa-envelope me-2"></i>op@mmsu.edu.ph
                        </p>
                        <div class="mt-3">
                            <a href="#" class="text-white me-3"><i class="fab fa-facebook fa-lg"></i></a>
                            <a href="#" class="text-white me-3"><i class="fab fa-twitter fa-lg"></i></a>
                            <a href="#" class="text-white"><i class="fab fa-youtube fa-lg"></i></a>
                        </div>
                    </div>
                </div>
                <hr class="bg-secondary opacity-25 mt-4">
                <div class="row">
                    <div class="col-12 text-center">
                        <p class="small opacity-50 mb-0">
                            &copy; <span>{{ currentYear }}</span> Mariano Marcos State University. Developed by CCIS Research Team.
                        </p>
                    </div>
                </div>
            </div>
        </footer>

        <!-- Modal Backdrop -->
        <div v-if="showLoginModal || showRegisterModal" class="modal-backdrop fade show" @click="closeModals"></div>

        <!-- Login Modal -->
        <div class="modal fade" :class="{ 'show d-block': showLoginModal }" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" :aria-hidden="!showLoginModal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold" id="loginModalLabel">
                            <i class="fas fa-sign-in-alt me-2" style="color: var(--mmsu-green);"></i>Sign In
                        </h5>
                        <button type="button" class="btn-close" @click="closeModals" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <form @submit.prevent="submitLogin">
                            <div class="mb-3">
                                <label for="loginEmail" class="form-label fw-semibold">Email address</label>
                                <input type="email" class="form-control rounded-3" id="loginEmail" v-model="loginForm.email" placeholder="Enter email" required>
                                <div v-if="loginForm.errors.email" class="text-danger small mt-1">{{ loginForm.errors.email }}</div>
                            </div>
                            <div class="mb-3">
                                <label for="loginPassword" class="form-label fw-semibold">Password</label>
                                <input type="password" class="form-control rounded-3" id="loginPassword" v-model="loginForm.password" placeholder="Password" required>
                                <div v-if="loginForm.errors.password" class="text-danger small mt-1">{{ loginForm.errors.password }}</div>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" v-model="loginForm.remember">
                                <label class="form-check-label small text-muted" for="remember">Remember me</label>
                            </div>
                            <button type="submit" class="btn btn-mmsu w-100 py-2 fw-bold" :disabled="loginForm.processing">
                                <span v-if="loginForm.processing" class="spinner-border spinner-border-sm me-2" role="status"></span>
                                Sign In
                            </button>
                        </form>
                    </div>
                    <div class="modal-footer border-0 bg-light rounded-bottom-4 justify-content-center">
                        <p class="mb-0 small">Don't have an account? <a href="#" class="fw-bold" style="color: var(--mmsu-green);" @click.prevent="switchToRegister">Register here</a></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Register Modal -->
        <div class="modal fade" :class="{ 'show d-block': showRegisterModal }" tabindex="-1" role="dialog" aria-labelledby="registerModalLabel" :aria-hidden="!showRegisterModal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold" id="registerModalLabel">
                            <i class="fas fa-user-plus me-2" style="color: var(--mmsu-green);"></i>Start Training
                        </h5>
                        <button type="button" class="btn-close" @click="closeModals" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <form @submit.prevent="submitRegister">
                            <div class="mb-3">
                                <label for="regName" class="form-label fw-semibold">Full Name</label>
                                <input type="text" class="form-control rounded-3" id="regName" v-model="registerForm.name" placeholder="Enter full name" required>
                                <div v-if="registerForm.errors.name" class="text-danger small mt-1">{{ registerForm.errors.name }}</div>
                            </div>
                            <div class="mb-3">
                                <label for="regEmail" class="form-label fw-semibold">Email address</label>
                                <input type="email" class="form-control rounded-3" id="regEmail" v-model="registerForm.email" placeholder="Enter email" required>
                                <div v-if="registerForm.errors.email" class="text-danger small mt-1">{{ registerForm.errors.email }}</div>
                            </div>
                            <div class="mb-3">
                                <label for="regPassword" class="form-label fw-semibold">Password</label>
                                <input type="password" class="form-control rounded-3" id="regPassword" v-model="registerForm.password" placeholder="Password" required>
                                <div v-if="registerForm.errors.password" class="text-danger small mt-1">{{ registerForm.errors.password }}</div>
                            </div>
                            <div class="mb-3">
                                <label for="regConfirmPassword" class="form-label fw-semibold">Confirm Password</label>
                                <input type="password" class="form-control rounded-3" id="regConfirmPassword" v-model="registerForm.password_confirmation" placeholder="Confirm password" required>
                            </div>
                            <button type="submit" class="btn btn-mmsu w-100 py-2 fw-bold" :disabled="registerForm.processing">
                                <span v-if="registerForm.processing" class="spinner-border spinner-border-sm me-2" role="status"></span>
                                Register & Start Training
                            </button>
                        </form>
                    </div>
                    <div class="modal-footer border-0 bg-light rounded-bottom-4 justify-content-center">
                        <p class="mb-0 small">Already have an account? <a href="#" class="fw-bold" style="color: var(--mmsu-green);" @click.prevent="switchToLogin">Sign In</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    hero: Object,
    features: Object,
    featuredTowns: Array,
    towns: Array,
    municipalities: Array,
});

const currentYear = new Date().getFullYear();

// Modal state management
const showLoginModal = ref(false);
const showRegisterModal = ref(false);

const openLoginModal = () => {
    showRegisterModal.value = false;
    showLoginModal.value = true;
};

const openRegisterModal = () => {
    showLoginModal.value = false;
    showRegisterModal.value = true;
};

const closeModals = () => {
    showLoginModal.value = false;
    showRegisterModal.value = false;
};

const switchToRegister = () => {
    openRegisterModal();
};

const switchToLogin = () => {
    openLoginModal();
};

// Inertia Forms
const loginForm = useForm({
    email: '',
    password: '',
    remember: false,
});

const registerForm = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submitLogin = () => {
    loginForm.post(route('login'), {
        onSuccess: () => {
            const modalEl = document.getElementById('loginModal');
            if (modalEl && window.bootstrap?.Modal) {
                const modal = window.bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();
            }
            closeModals();
            loginForm.reset('password');
        },
    });
};

const submitRegister = () => {
    registerForm.post(route('register'), {
        onSuccess: () => {
            const modalEl = document.getElementById('registerModal');
            if (modalEl && window.bootstrap?.Modal) {
                const modal = window.bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();
            }
            closeModals();
            registerForm.reset('password', 'password_confirmation');
        },
    });
};

// Default Municipalities fallback data if controller sends empty prop
const defaultMunicipalities = [
    {
        name: 'Laoag City',
        description: 'Capital of Ilocos Norte',
        attractions: 'Sinking Bell Tower, St. William\'s Cathedral, La Paz Sand Dunes, Museo Ilocos Norte',
        modules: 5,
        badge: 'Capital City',
        badgeClass: 'bg-warning text-dark',
        icon: 'fa-crown',
        imagePath: '/assets/images/Laoag.jpg',
    },
    {
        name: 'Paoay',
        description: '17km from Laoag',
        attractions: 'Paoay Church (St. Augustine), Malacañang of the North, Paoay Lake, Herencia Café',
        modules: 4,
        badge: 'UNESCO Heritage',
        badgeClass: 'bg-info text-white',
        icon: 'fa-landmark',
        imagePath: '/assets/images/Paoay.jpg',
    },
    {
        name: 'Pagudpud',
        description: 'Northernmost tip',
        attractions: 'Saud Beach, Blue Lagoon, Bangui Windmills, Patapat Viaduct, Kabigan Falls',
        modules: 5,
        badge: 'Boracay of the North',
        badgeClass: 'bg-primary text-white',
        icon: 'fa-umbrella-beach',
        imagePath: '/assets/images/Pagudpud.jpg',
    }
];

const displayedTowns = computed(() => {
    const list = props.featuredTowns || props.towns || props.municipalities;
    if (list && list.length > 0) {
        return list;
    }
    return defaultMunicipalities;
});
</script>

<style scoped>
.hover-lift {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.hover-lift:hover {
    transform: translateY(-6px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.12) !important;
}
.modal.show {
    background-color: rgba(0, 0, 0, 0.5);
}
</style>
