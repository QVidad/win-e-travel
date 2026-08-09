<template>
    <StudentLayout>
        <div class="achievements-container py-4">
            <div class="container">
                <!-- Page Header matching achievements.html -->
                <div class="page-header">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <h2 class="fw-bold mb-2">
                                <i class="fas fa-trophy me-2"></i>
                                Achievements & Badges
                            </h2>
                            <p class="mb-0 opacity-90">Track your progress and collect badges as you master tour guiding</p>
                        </div>
                        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                            <div class="d-inline-block bg-white bg-opacity-25 rounded-4 px-4 py-2 fw-bold">
                                <span id="totalBadgesEarned">{{ earnedCount }}</span>/{{ totalBadgesCount }} Badges Earned
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-lg-8">
                        <!-- Certificate Section matching achievements.html -->
                        <div class="certificate-card" :class="{ locked: progressPercentage < 100 }">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <div class="certificate-icon">
                                        <i class="fas fa-scroll"></i>
                                    </div>
                                    <h4 class="fw-bold mb-2">Certificate of Completion</h4>
                                    <p class="mb-2 opacity-90">
                                        Complete all foundation modules and town simulations to earn your certificate!
                                    </p>
                                    <p class="mb-0 small opacity-75">
                                        <i class="fas fa-tasks me-1"></i>
                                        <span>{{ progressPercentage }}% Complete</span>
                                    </p>
                                </div>
                                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                    <button @click="showCertificateModal = true" class="btn btn-warning btn-lg fw-bold rounded-pill px-4 shadow">
                                        <i class="fas fa-certificate me-2"></i>View Certificate
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Category Filter Pills -->
                        <div class="d-flex justify-content-center mb-4">
                            <div class="btn-group rounded-pill p-1 bg-white shadow-sm" role="group">
                                <button
                                    v-for="cat in ['All', 'foundation', 'exploration', 'simulation', 'mastery']"
                                    :key="cat"
                                    type="button"
                                    class="btn btn-sm text-capitalize px-4 rounded-pill"
                                    :class="selectedCategory === cat ? 'btn-success fw-bold' : 'btn-light text-dark'"
                                    @click="selectedCategory = cat"
                                >
                                    {{ cat }}
                                </button>
                            </div>
                        </div>

                        <!-- Foundation Badges -->
                        <h5 class="section-title mt-4">
                            <i class="fas fa-book-open text-warning me-2"></i>
                            Foundation Badges
                        </h5>
                        <div class="badge-grid mb-4">
                            <div v-for="badge in foundationBadges" :key="badge.id" class="badge-item earned">
                                <div class="badge-icon shadow-sm">
                                    <i class="fas fa-award"></i>
                                </div>
                                <div class="badge-name">{{ badge.title }}</div>
                                <div class="badge-status text-success">Earned <i class="fas fa-check-circle"></i></div>
                            </div>
                        </div>

                        <!-- Town Badges -->
                        <h5 class="section-title mt-4">
                            <i class="fas fa-map-marked-alt text-warning me-2"></i>
                            Town Badges
                        </h5>
                        <div class="badge-grid mb-4">
                            <div v-for="badge in townBadges" :key="badge.id" class="badge-item" :class="badge.earned ? 'earned' : 'locked'">
                                <div class="badge-icon shadow-sm">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div class="badge-name">{{ badge.title }}</div>
                                <div class="badge-status">{{ badge.earned ? 'Earned' : 'Locked' }}</div>
                            </div>
                        </div>

                        <!-- Special Badges -->
                        <h5 class="section-title mt-4">
                            <i class="fas fa-star text-warning me-2"></i>
                            Special Badges
                        </h5>
                        <div class="badge-grid mb-4">
                            <div v-for="badge in specialBadges" :key="badge.id" class="badge-item" :class="badge.earned ? 'earned' : 'locked'">
                                <div class="badge-icon shadow-sm">
                                    <i class="fas fa-trophy"></i>
                                </div>
                                <div class="badge-name">{{ badge.title }}</div>
                                <div class="badge-status">{{ badge.earned ? 'Earned' : 'Locked' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics Card Column matching achievements.html -->
                    <div class="col-lg-4">
                        <div class="stats-card">
                            <h5 class="fw-bold mb-3 text-dark">
                                <i class="fas fa-chart-bar me-2" style="color: var(--mmsu-green);"></i>
                                Statistics
                            </h5>

                            <div class="stat-item py-3 border-bottom d-flex align-items-center">
                                <div class="stat-icon me-3">
                                    <i class="fas fa-key"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-value">15</div>
                                    <div class="stat-label">Keywords Mastered</div>
                                </div>
                            </div>

                            <div class="stat-item py-3 border-bottom d-flex align-items-center">
                                <div class="stat-icon me-3">
                                    <i class="fas fa-microphone-alt"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-value">2</div>
                                    <div class="stat-label">Simulations Passed</div>
                                </div>
                            </div>

                            <div class="stat-item py-3 border-bottom d-flex align-items-center">
                                <div class="stat-icon me-3">
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-value">1</div>
                                    <div class="stat-label">Perfect Scores</div>
                                </div>
                            </div>

                            <div class="stat-item py-3 d-flex align-items-center">
                                <div class="stat-icon me-3">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-value">45m</div>
                                    <div class="stat-label">Time Spent Training</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Official Certificate Modal -->
        <div v-if="showCertificateModal" class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.7);">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title fw-bold"><i class="fas fa-certificate me-2"></i>Official Tour Guide Certificate</h5>
                        <button type="button" class="btn-close btn-close-white" @click="showCertificateModal = false"></button>
                    </div>
                    <div class="modal-body text-center p-5 bg-white">
                        <div class="border border-4 border-warning p-4 rounded-4 position-relative" style="background: #faf8f5;">
                            <img src="/assets/images/WINLogo.png" alt="MMSU Logo" style="width: 70px;" class="mb-3">
                            <h6 class="text-uppercase text-muted letter-spacing-2">Mariano Marcos State University</h6>
                            <h2 class="fw-bold text-dark font-serif my-3">Certificate of Completion</h2>
                            <p class="lead text-secondary">This certifies that</p>
                            <h3 class="fw-bold text-success text-decoration-underline mb-3">{{ $page.props.auth.user.name }}</h3>
                            <p class="text-muted max-w-xl mx-auto">has successfully completed the computer-based interactive simulation training for tour guiding across the 21 municipalities of Ilocos Norte.</p>

                            <div class="row mt-5 pt-3">
                                <div class="col-6 text-center">
                                    <hr class="w-50 mx-auto mb-1">
                                    <small class="fw-bold text-dark d-block">Prof. Maria Santos</small>
                                    <small class="text-muted">Lead Instructor</small>
                                </div>
                                <div class="col-6 text-center">
                                    <hr class="w-50 mx-auto mb-1">
                                    <small class="fw-bold text-dark d-block">WIN e-Travel System</small>
                                    <small class="text-muted">Digital Verification Code: WIN-2026-8891</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary rounded-pill px-4" @click="showCertificateModal = false">Close</button>
                        <button type="button" class="btn btn-success rounded-pill px-4" @click="downloadCertificate">
                            <i class="fas fa-download me-1"></i> Download PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </StudentLayout>
</template>

<script setup>
import StudentLayout from '@/Layouts/StudentLayout.vue';
import { ref, computed } from 'vue';

const props = defineProps({
    achievements: Array,
    stats: Object,
});

const selectedCategory = ref('All');
const showCertificateModal = ref(false);
const progressPercentage = ref(45);

const earnedCount = computed(() => 4);
const totalBadgesCount = computed(() => 29);

const foundationBadges = [
    { id: 1, title: 'Orientation Master', earned: true },
    { id: 2, title: 'Ethics Scholar', earned: true },
    { id: 3, title: 'Speech Delivery', earned: true },
];

const townBadges = [
    { id: 4, title: 'Laoag Navigator', earned: true },
    { id: 5, title: 'Paoay Expert', earned: false },
    { id: 6, title: 'Pagudpud Guide', earned: false },
];

const specialBadges = [
    { id: 7, title: 'Simulation Pro', earned: true },
    { id: 8, title: 'Master Tour Guide', earned: false },
];

const downloadCertificate = () => {
    alert('Digital Certificate PDF download initiated!');
};
</script>

<style scoped>
.achievements-container {
    background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
    min-height: 100vh;
}

.page-header {
    background: linear-gradient(135deg, #ffd700 0%, #ff8c00 100%);
    border-radius: 20px;
    padding: 30px;
    color: white;
    margin: 30px 0;
}

.certificate-card {
    background: linear-gradient(135deg, #0a472e 0%, #1a5f7a 100%);
    border-radius: 20px;
    padding: 30px;
    color: white;
    margin-bottom: 30px;
    position: relative;
    overflow: hidden;
}

.certificate-icon {
    font-size: 50px;
    color: #ffd700;
    margin-bottom: 10px;
}

.badge-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
    gap: 20px;
}

.badge-item {
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.badge-item:hover {
    transform: translateY(-5px);
}

.badge-icon {
    width: 75px;
    height: 75px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 8px;
    font-size: 32px;
    transition: all 0.3s ease;
}

.badge-item.earned .badge-icon {
    background: linear-gradient(135deg, #ffd700 0%, #ff8c00 100%);
    color: white;
    box-shadow: 0 8px 20px rgba(255, 215, 0, 0.3);
}

.badge-item.locked .badge-icon {
    background: #e9ecef;
    color: #adb5bd;
}

.badge-name {
    font-size: 0.75rem;
    font-weight: 600;
    margin-bottom: 2px;
}

.badge-status {
    font-size: 0.65rem;
}

.stats-card {
    background: white;
    border-radius: 20px;
    padding: 25px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
}

.stat-icon {
    width: 45px;
    height: 45px;
    border-radius: 15px;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: var(--mmsu-green);
}

.stat-value {
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--mmsu-green);
}

.stat-label {
    font-size: 0.8rem;
    color: #6c757d;
}

.section-title {
    font-weight: 700;
    margin-bottom: 15px;
    color: #333;
}
</style>
