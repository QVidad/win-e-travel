<template>
    <Head title="Achievements" />
    <StudentLayout>
        <div class="dashboard-container">
            <div>
                <!-- Page Header matching dashboard.html -->
                <div class="welcome-banner">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <h2 class="display-6 fw-bold mb-3">
                                <i class="fas fa-trophy me-2"></i>
                                Achievements & Badges
                            </h2>
                            <p class="mb-0 opacity-90 fs-5">Track your progress and collect badges as you master tour guiding</p>
                        </div>
                        <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                            <div class="d-flex justify-content-lg-end">
                                <div class="text-center">
                                    <div class="overall-progress-circle mb-2 mx-auto" :style="progressCircleStyle">
                                        <span class="progress-percentage fs-4">{{ earnedCount }}/{{ totalBadgesCount }}</span>
                                    </div>
                                    <span class="small fw-bold text-white opacity-75">Badges Earned</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-lg-12">
                        <!-- Certificate Section matching achievements.html -->
                        <div class="certificate-card shadow" :class="{ locked: progressPercentage < 100 }">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <div class="certificate-icon">
                                        <i class="fas fa-scroll"></i>
                                    </div>
                                    <h2 class="display-6 fw-bold mb-3 text-dark">Certificate of Completion</h2>
                                    <p class="fs-5 mb-3 text-dark opacity-90">
                                        Complete all foundation modules and town simulations to earn your certificate!
                                    </p>
                                    <p class="mb-0 fs-6 fw-bold text-dark opacity-75">
                                        <i class="fas fa-tasks me-2"></i>
                                        <span>{{ progressPercentage }}% Complete</span>
                                    </p>
                                </div>
                                <div class="col-md-4 text-md-end mt-4 mt-md-0">
                                    <button v-if="progressPercentage >= 100" @click="showCertificateModal = true" class="btn btn-dark btn-lg fw-bold rounded-pill px-4 shadow-sm">
                                        <i class="fas fa-certificate me-2 text-warning"></i>View Certificate
                                    </button>
                                    <button v-else class="btn btn-secondary btn-lg fw-bold rounded-pill px-4 shadow-sm" disabled>
                                        <i class="fas fa-lock me-2 text-white"></i>Locked
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Category Filter Pills -->
                        <div class="d-flex justify-content-center mb-4">
                            <div class="btn-group rounded-pill p-1 bg-white shadow-sm" role="group">
                                <button
                                    v-for="cat in ['All', 'foundation', 'simulation', 'mastery']"
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
                        <div v-if="selectedCategory === 'All' || selectedCategory === 'foundation'">
                            <h5 class="section-title mt-4">
                                <i class="fas fa-book-open text-warning me-2"></i>
                                Foundation Badges
                            </h5>
                            <div class="badge-grid mb-4">
                                <div v-for="badge in foundationBadges" :key="badge.id" class="badge-item" :class="badge.earned ? 'earned' : 'locked'" style="cursor: pointer;" @click="openBadgeModal(badge)">
                                    <div class="badge-icon shadow-sm">
                                        <i class="fas fa-award"></i>
                                    </div>
                                    <div class="badge-name">{{ badge.title }}</div>
                                    <div class="badge-status" :class="{'text-success': badge.earned}">{{ badge.earned ? 'Earned' : 'Locked' }} <i v-if="badge.earned" class="fas fa-check-circle"></i></div>
                                </div>
                            </div>
                        </div>

                        <!-- Town Badges -->
                        <div v-if="selectedCategory === 'All' || selectedCategory === 'simulation'">
                            <h5 class="section-title mt-4">
                                <i class="fas fa-map-marked-alt text-warning me-2"></i>
                                Town Badges
                            </h5>
                            <div class="badge-grid mb-4">
                                <div v-for="badge in townBadges" :key="badge.id" class="badge-item" :class="badge.earned ? 'earned' : 'locked'" style="cursor: pointer;" @click="openBadgeModal(badge)">
                                    <div class="badge-icon shadow-sm">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div class="badge-name">{{ badge.title }}</div>
                                    <div class="badge-status" :class="{'text-success': badge.earned}">{{ badge.earned ? 'Earned' : 'Locked' }} <i v-if="badge.earned" class="fas fa-check-circle"></i></div>
                                </div>
                            </div>
                        </div>

                        <!-- Special Badges -->
                        <div v-if="selectedCategory === 'All' || selectedCategory === 'mastery'">
                            <h5 class="section-title mt-4">
                                <i class="fas fa-star text-warning me-2"></i>
                                Special Badges
                            </h5>
                            <div class="badge-grid mb-4">
                                <div v-for="badge in specialBadges" :key="badge.id" class="badge-item" :class="badge.earned ? 'earned' : 'locked'" style="cursor: pointer;" @click="openBadgeModal(badge)">
                                    <div class="badge-icon shadow-sm">
                                        <i class="fas fa-trophy"></i>
                                    </div>
                                    <div class="badge-name">{{ badge.title }}</div>
                                    <div class="badge-status" :class="{'text-success': badge.earned}">{{ badge.earned ? 'Earned' : 'Locked' }} <i v-if="badge.earned" class="fas fa-check-circle"></i></div>
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
                    <div class="modal-header text-white" style="background-color: #0d4b38;">
                        <h5 class="modal-title fw-bold"><i class="fas fa-certificate me-2 text-warning"></i>Official Tour Guide Certificate</h5>
                        <button type="button" class="btn-close btn-close-white" @click="showCertificateModal = false"></button>
                    </div>
                    <div class="modal-body text-center p-0 bg-white position-relative d-flex justify-content-center align-items-center" style="min-height: 50vh;">
                        <div id="certificate-print-area" class="mx-auto" style="container-type: inline-size;" :style="{
                            backgroundImage: certificateSettings?.background_image_path ? `url(${certificateSettings.background_image_path})` : 'none',
                            backgroundSize: '100% 100%',
                            backgroundPosition: 'center',
                            backgroundRepeat: 'no-repeat',
                            aspectRatio: '297 / 210',
                            width: '100%',
                            maxWidth: 'calc(70vh * (297 / 210))'
                        }">
                            <!-- Print Overlay to hide elements not meant for PDF -->
                            <div class="position-relative d-flex flex-column justify-content-center h-100" style="background: transparent; padding: 4cqw 6cqw;">
                                
                                <div class="d-flex justify-content-center align-items-center" style="gap: 2cqw; margin-bottom: 1cqw;">
                                    <img v-if="certificateSettings?.university_logo_path" :src="certificateSettings.university_logo_path" alt="Uni Logo" style="height: 8cqw; width: 8cqw; object-fit: contain;">
                                    <img v-else src="/assets/images/WINLogo.png" alt="Fallback Logo" style="height: 8cqw; width: 8cqw; object-fit: contain; opacity: 0.2;">
                                    
                                    <img v-if="certificateSettings?.college_logo_path" :src="certificateSettings.college_logo_path" alt="Col Logo" style="height: 8cqw; width: 8cqw; object-fit: contain;">
                                </div>

                                <div class="text-center">
                                    <h6 class="text-uppercase text-muted fw-bold" style="font-size: 2cqw; letter-spacing: 0.2cqw; margin-bottom: 0.5cqw;">{{ certificateSettings?.university_name || 'Mariano Marcos State University' }}</h6>
                                    <p class="text-muted" style="font-size: 1.6cqw; margin-bottom: 1cqw;">{{ certificateSettings?.college_name || 'College Business, Economics and Accountancy' }}</p>
                                    
                                    <h2 class="fw-bold text-dark font-serif" style="font-size: 4.5cqw; margin-top: 1cqw; margin-bottom: 1cqw;">Certificate of Completion</h2>
                                    <p class="text-secondary" style="font-size: 2.2cqw; margin-bottom: 0.5cqw;">This certifies that</p>
                                    <h3 class="fw-bold text-success text-decoration-underline font-serif fst-italic" style="font-size: 4cqw; margin-bottom: 1cqw;">{{ $page.props.auth.user.name }}</h3>
                                    
                                    <p class="text-muted mx-auto lh-base" style="max-width: 85%; font-size: 1.8cqw; margin-bottom: 1cqw;">
                                        {{ certificateSettings?.description || 'has successfully completed the computer-based interactive simulation training for tour guiding across the 21 municipalities of Ilocos Norte.' }}
                                    </p>

                                    <div class="row" style="margin-top: 2cqw; padding-left: 4cqw; padding-right: 4cqw;">
                                        <div class="col-6 text-center">
                                            <div class="signature-container mx-auto position-relative" style="height: 4cqw; width: 32cqw; margin-bottom: 0.5cqw;">
                                                <img v-if="certificateSettings?.signature_image_path" :src="certificateSettings.signature_image_path" alt="e-Sign" class="position-absolute bottom-0 start-50 translate-middle-x" style="max-height: 6cqw; max-width: 30cqw; z-index: 10; margin-bottom: -1.5cqw;">
                                            </div>
                                            <hr class="mx-auto mt-0" style="width: 32cqw; border-color: #333; opacity: 1; border-width: 0.2cqw; margin-bottom: 0.5cqw;">
                                            <small class="fw-bold text-dark d-block text-truncate mx-auto" style="font-size: 1.8cqw; max-width: 32cqw;">{{ certificateSettings?.signer_name || 'Prof. Maria Santos' }}</small>
                                            <small class="text-muted text-truncate mx-auto d-block" style="font-size: 1.5cqw; max-width: 32cqw;">{{ certificateSettings?.signer_title || 'Lead Instructor' }}</small>
                                        </div>
                                        <div class="col-6 text-center d-flex flex-column justify-content-end align-items-center">
                                            <div style="height: 4cqw;"></div>
                                            <div style="margin-top: 1cqw;" class="text-center">
                                                <small class="fw-bold text-dark d-block" style="font-size: 1.8cqw;">WIN e-Travel System</small>
                                                <small class="text-muted" style="font-size: 1.5cqw;">Code: {{ certificateCode }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary rounded-pill px-4" @click="showCertificateModal = false">Close</button>
                        <button type="button" class="btn btn-success rounded-pill px-4" @click="downloadCertificate">
                            <i class="fas fa-print me-1"></i> Print / Save PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Badge Details Modal -->
        <div v-if="showBadgeModal && selectedBadge" class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
                    <div class="modal-header text-white" :style="{ backgroundColor: selectedBadge.earned ? '#0d4b38' : '#6c757d' }">
                        <h5 class="modal-title fw-bold">
                            <i class="fas fa-medal me-2 text-warning"></i>
                            Badge Details
                        </h5>
                        <button type="button" class="btn-close btn-close-white" @click="showBadgeModal = false"></button>
                    </div>
                    <div class="modal-body text-center p-4">
                        <div class="badge-icon shadow-sm mb-3 mx-auto" :class="selectedBadge.earned ? 'bg-success bg-opacity-10 text-success' : 'bg-secondary bg-opacity-10 text-secondary'" style="width: 80px; height: 80px; font-size: 2.5rem; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                            <i v-if="selectedCategory === 'foundation' || selectedCategory === 'All'" class="fas fa-award"></i>
                            <i v-else-if="selectedCategory === 'simulation'" class="fas fa-map-marker-alt"></i>
                            <i v-else class="fas fa-trophy"></i>
                        </div>
                        <h4 class="fw-bold mb-1">{{ selectedBadge.title }}</h4>
                        <div class="badge-status mb-3 fw-semibold" :class="selectedBadge.earned ? 'text-success' : 'text-muted'">
                            {{ selectedBadge.earned ? 'Earned' : 'Locked' }}
                            <i v-if="selectedBadge.earned" class="fas fa-check-circle"></i>
                            <i v-else class="fas fa-lock"></i>
                        </div>
                        <p class="text-muted mb-0">{{ selectedBadge.description }}</p>
                    </div>
                    <div class="modal-footer bg-light justify-content-center">
                        <button type="button" class="btn btn-secondary rounded-pill px-4" @click="showBadgeModal = false">Close</button>
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
    userStats: Object,
    certificateSettings: Object,
    certificateProgress: Number,
});

const selectedCategory = ref('All');
const showCertificateModal = ref(false);
const progressPercentage = computed(() => props.certificateProgress ?? 0);

const foundationBadges = computed(() => props.achievements.filter(a => a.category === 'foundation').map(a => ({ id: a.id, title: a.title, description: a.description, earned: a.is_unlocked })));
const townBadges = computed(() => props.achievements.filter(a => a.category === 'simulation').map(a => ({ id: a.id, title: a.title, description: a.description, earned: a.is_unlocked })));
const specialBadges = computed(() => props.achievements.filter(a => a.category === 'mastery').map(a => ({ id: a.id, title: a.title, description: a.description, earned: a.is_unlocked })));

const selectedBadge = ref(null);
const showBadgeModal = ref(false);

const openBadgeModal = (badge) => {
    selectedBadge.value = badge;
    showBadgeModal.value = true;
};

const earnedCount = computed(() => props.achievements.filter(a => a.is_unlocked).length);
const totalBadgesCount = computed(() => props.achievements.length);

const progressCircleStyle = computed(() => {
    const percentage = Math.round((earnedCount.value / totalBadgesCount.value) * 100) || 0;
    return {
        background: `conic-gradient(#ffc107 ${percentage}%, rgba(255,255,255,0.2) 0)`
    };
});

import { usePage, Head } from '@inertiajs/vue3';
const page = usePage();

const certificateCode = computed(() => {
    const userId = page.props.auth.user.id.toString().padStart(3, '0');
    const year = new Date().getFullYear();
    const hash = Math.random().toString(36).substring(2, 6).toUpperCase();
    return `CERT-${userId}-${year}-${hash}`;
});

const downloadCertificate = () => {
    // Use outerHTML to capture the background image and container-type styles on the main div
    const printContent = document.getElementById('certificate-print-area').outerHTML;
    const originalContent = document.body.innerHTML;
    
    // Add print styles dynamically
    const printStyles = `
        <style>
            @page {
                size: A4 landscape;
                margin: 0;
            }
            @media print {
                body {
                    margin: 0;
                    padding: 0;
                    -webkit-print-color-adjust: exact !important;
                    print-color-adjust: exact !important;
                    background-color: white;
                }
                #print-section {
                    width: 297mm;
                    height: 210mm;
                    margin: 0;
                    padding: 0;
                    display: block;
                }
                #certificate-print-area {
                    max-width: none !important;
                    max-height: none !important;
                    width: 297mm !important;
                    height: 210mm !important;
                    margin: 0 !important;
                }
            }
        </style>
    `;

    document.body.innerHTML = printStyles + '<div id="print-section">' + printContent + '</div>';
    window.print();
    document.body.innerHTML = originalContent;
    location.reload(); // Reload to restore Vue bindings after modifying DOM
};
</script>

<style scoped>
.achievements-container {
    min-height: 100vh;
}

.welcome-banner {
    background: linear-gradient(135deg, #e65100 0%, #ff8f00 100%);
    border-radius: 20px;
    padding: 40px;
    color: white;
    margin-bottom: 30px;
    box-shadow: 0 10px 30px rgba(230, 81, 0, 0.2);
}

.certificate-card {
    background: linear-gradient(135deg, #ffd700 0%, #ff8c00 100%);
    border-radius: 20px;
    padding: 30px;
    color: white;
    margin-bottom: 30px;
    position: relative;
    overflow: hidden;
}

.certificate-icon {
    font-size: 50px;
    color: #0a472e;
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
.overall-progress-circle {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: conic-gradient(#ffc107 0deg 0deg, rgba(255,255,255,0.2) 0deg 360deg);
    position: relative;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.overall-progress-circle::before {
    content: '';
    position: absolute;
    width: 80px;
    height: 80px;
    background-color: #ff8f00; /* matching banner */
    border-radius: 50%;
}

.progress-percentage {
    position: relative;
    font-weight: 800;
    color: white;
}
</style>
