<template>
    <EducatorLayout>
        <!-- Dark Green Hero Banner -->
        <div 
            class="card border-0 text-white p-4 p-md-5 mb-4 shadow-sm" 
            style="background: linear-gradient(135deg, #0a472e 0%, #1a5f7a 100%); border-radius: 20px;"
        >
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 class="display-6 fw-bold mb-2 text-white" style="letter-spacing: -0.5px;">
                        <i class="fas fa-certificate me-2 opacity-75"></i>Certificate Editor
                    </h1>
                    <p class="mb-0 text-white fst-italic fs-6 opacity-90">
                        "Design the official completion certificate awarded to students."
                    </p>
                </div>
            </div>
        </div>

        <div class="row g-4">
                <!-- Settings Form -->
                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                            <h5 class="fw-bold mb-0">Configuration</h5>
                        </div>
                        <div class="card-body p-4">
                            <form @submit.prevent="submit">
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark small">University Name</label>
                                    <input type="text" class="form-control bg-light border-0" v-model="form.university_name" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark small">College Name</label>
                                    <input type="text" class="form-control bg-light border-0" v-model="form.college_name" required>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold text-dark small">University Logo</label>
                                        <input type="file" class="form-control form-control-sm bg-light border-0" @change="e => form.university_logo = e.target.files[0]" accept="image/*">
                                        <div v-if="settings.university_logo_path" class="mt-2 text-muted small"><i class="fas fa-check text-success"></i> Uploaded</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold text-dark small">College Logo</label>
                                        <input type="file" class="form-control form-control-sm bg-light border-0" @change="e => form.college_logo = e.target.files[0]" accept="image/*">
                                        <div v-if="settings.college_logo_path" class="mt-2 text-muted small"><i class="fas fa-check text-success"></i> Uploaded</div>
                                    </div>
                                </div>

                                <hr class="my-4 text-muted">

                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark small">Description</label>
                                    <textarea class="form-control bg-light border-0" v-model="form.description" rows="3"></textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold text-dark small">Signer Name</label>
                                        <input type="text" class="form-control bg-light border-0" v-model="form.signer_name" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold text-dark small">Signer Title</label>
                                        <input type="text" class="form-control bg-light border-0" v-model="form.signer_title" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark small">Signature Image (e-Sign)</label>
                                    <input type="file" class="form-control bg-light border-0" @change="e => form.signature_image = e.target.files[0]" accept="image/*">
                                    <div v-if="settings.signature_image_path" class="mt-2 text-muted small"><i class="fas fa-check text-success"></i> Uploaded</div>
                                </div>

                                <hr class="my-4 text-muted">

                                <div class="mb-4">
                                    <label class="form-label fw-bold text-dark small">Custom Background Image (Optional)</label>
                                    <input type="file" class="form-control bg-light border-0" @change="e => form.background_image = e.target.files[0]" accept="image/*">
                                    <div v-if="settings.background_image_path" class="mt-2 text-muted small"><i class="fas fa-check text-success"></i> Uploaded</div>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-success rounded-pill fw-bold" :disabled="form.processing">
                                        <span v-if="form.processing"><i class="fas fa-spinner fa-spin me-2"></i>Saving...</span>
                                        <span v-else><i class="fas fa-save me-2"></i>Save Certificate Settings</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Live Preview Pane -->
                <div class="col-lg-7">
                    <div class="card border-0 shadow-sm rounded-4 bg-light overflow-hidden position-sticky" style="top: 100px;">
                        <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                            <h5 class="fw-bold mb-0"><i class="fas fa-eye text-primary me-2"></i>Live Preview</h5>
                        </div>
                        <div class="card-body p-4 d-flex justify-content-center align-items-center" style="min-height: 500px;">
                            
                            <!-- Certificate Mockup -->
                            <div class="certificate-mockup w-100 position-relative" style="container-type: inline-size;" :style="{
                                backgroundImage: previewBackground ? `url(${previewBackground})` : 'none',
                                backgroundSize: '100% 100%',
                                backgroundPosition: 'center',
                                backgroundRepeat: 'no-repeat',
                                aspectRatio: '297 / 210'
                            }">
                                <div class="position-relative d-flex flex-column justify-content-center" style="width: 100%; height: 100%; background: transparent; padding: 4cqw 6cqw;">
                                    
                                    <div class="d-flex justify-content-center align-items-center" style="gap: 2cqw; margin-bottom: 1cqw;">
                                        <img v-if="previewUniLogo" :src="previewUniLogo" alt="Uni Logo" style="height: 8cqw; width: 8cqw; object-fit: contain;">
                                        <img v-else src="/assets/images/WINLogo.png" alt="Fallback Logo" style="height: 8cqw; width: 8cqw; object-fit: contain; opacity: 0.2;">
                                        
                                        <img v-if="previewColLogo" :src="previewColLogo" alt="Col Logo" style="height: 8cqw; width: 8cqw; object-fit: contain;">
                                        <div v-else class="rounded-circle bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center" style="height: 8cqw; width: 8cqw; border: 0.2cqw dashed #ccc;">
                                            <i class="fas fa-image text-muted opacity-50" style="font-size: 3cqw;"></i>
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <h6 class="text-uppercase text-muted fw-bold" style="font-size: 2cqw; letter-spacing: 0.2cqw; margin-bottom: 0.5cqw;">{{ form.university_name || 'University Name' }}</h6>
                                        <p class="text-muted" style="font-size: 1.6cqw; margin-bottom: 1cqw;">{{ form.college_name || 'College Name' }}</p>
                                        
                                        <h2 class="fw-bold text-dark font-serif" style="font-size: 4.5cqw; margin-top: 1cqw; margin-bottom: 1cqw;">Certificate of Completion</h2>
                                        <p class="text-secondary" style="font-size: 2.2cqw; margin-bottom: 0.5cqw;">This certifies that</p>
                                        <h3 class="fw-bold text-success text-decoration-underline font-serif fst-italic" style="font-size: 4cqw; margin-bottom: 1cqw;">Student Name</h3>
                                        
                                        <p class="text-muted mx-auto lh-base" style="max-width: 85%; font-size: 1.8cqw; margin-bottom: 1cqw;">
                                            {{ form.description || 'has successfully completed the computer-based interactive simulation training for tour guiding across the 21 municipalities of Ilocos Norte.' }}
                                        </p>

                                        <div class="row" style="margin-top: 2cqw; padding-left: 4cqw; padding-right: 4cqw;">
                                            <div class="col-6 text-center">
                                                <div class="signature-container mx-auto position-relative" style="height: 4cqw; width: 32cqw; margin-bottom: 0.5cqw;">
                                                    <img v-if="previewSignature" :src="previewSignature" alt="e-Sign" class="position-absolute bottom-0 start-50 translate-middle-x" style="max-height: 6cqw; max-width: 30cqw; z-index: 10; margin-bottom: -1.5cqw;">
                                                </div>
                                                <hr class="mx-auto mt-0" style="width: 32cqw; border-color: #333; opacity: 1; border-width: 0.2cqw; margin-bottom: 0.5cqw;">
                                                <small class="fw-bold text-dark d-block text-truncate mx-auto" style="font-size: 1.8cqw; max-width: 32cqw;">{{ form.signer_name || 'Signer Name' }}</small>
                                                <small class="text-muted text-truncate mx-auto d-block" style="font-size: 1.5cqw; max-width: 32cqw;">{{ form.signer_title || 'Signer Title' }}</small>
                                            </div>
                                            <div class="col-6 text-center d-flex flex-column justify-content-end align-items-center">
                                                <div style="height: 4cqw;"></div>
                                                <div style="margin-top: 1cqw;" class="text-center">
                                                    <small class="fw-bold text-dark d-block" style="font-size: 1.8cqw;">WIN e-Travel System</small>
                                                    <small class="text-muted" style="font-size: 1.5cqw;">Code: CERT-000-YYYY-XXXX</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
    </EducatorLayout>
</template>

<script setup>
import { computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import EducatorLayout from '@/Layouts/EducatorLayout.vue';

const props = defineProps({
    settings: {
        type: Object,
        default: () => ({})
    }
});

const form = useForm({
    university_name: props.settings.university_name || 'Mariano Marcos State University',
    college_name: props.settings.college_name || 'College of Business, Economics and Accountancy',
    signer_name: props.settings.signer_name || '',
    signer_title: props.settings.signer_title || '',
    description: props.settings.description || 'has successfully completed the computer-based interactive simulation training for tour guiding across the 21 municipalities of Ilocos Norte.',
    university_logo: null,
    college_logo: null,
    signature_image: null,
    background_image: null,
});

// Create object URLs for live preview of uploaded files
const previewUniLogo = computed(() => {
    if (form.university_logo) return URL.createObjectURL(form.university_logo);
    return props.settings.university_logo_path;
});

const previewColLogo = computed(() => {
    if (form.college_logo) return URL.createObjectURL(form.college_logo);
    return props.settings.college_logo_path;
});

const previewSignature = computed(() => {
    if (form.signature_image) return URL.createObjectURL(form.signature_image);
    return props.settings.signature_image_path;
});

const previewBackground = computed(() => {
    if (form.background_image) return URL.createObjectURL(form.background_image);
    return props.settings.background_image_path;
});

const submit = () => {
    form.post(route('educator.certificate.update'), {
        preserveScroll: true,
    });
};
</script>

<style scoped>
.font-serif {
    font-family: "Playfair Display", Georgia, serif;
}
.letter-spacing-2 {
    letter-spacing: 2px;
}
.certificate-mockup {
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    border-radius: 1rem;
    overflow: hidden;
    padding: 15px;
    background-color: #fff;
}
</style>
