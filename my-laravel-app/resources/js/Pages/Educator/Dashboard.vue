<template>
    <div class="d-flex flex-column min-vh-100 bg-light">
        <!-- Educator Header -->
        <nav class="navbar navbar-expand-lg bg-success text-white shadow-sm py-3">
            <div class="container">
                <Link :href="route('home')" class="navbar-brand text-white fw-bold d-flex align-items-center">
                    <img src="/assets/images/WINLogo.png" alt="WIN Logo" style="height: 35px;" class="me-2">
                    WIN e-Travel CMS — Educator Panel
                </Link>
                <div class="ms-auto d-flex align-items-center gap-3">
                    <span class="badge bg-warning text-dark"><i class="fas fa-chalkboard-teacher me-1"></i> Educator CMS</span>
                    <Link :href="route('dashboard')" class="btn btn-sm btn-outline-light rounded-pill">
                        <i class="fas fa-eye me-1"></i> Student View
                    </Link>
                    <Link :href="route('logout')" method="post" as="button" class="btn btn-sm btn-light text-danger rounded-pill">
                        Log Out
                    </Link>
                </div>
            </div>
        </nav>

        <!-- Main CMS Body -->
        <div class="container py-5 flex-grow-1">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-dark mb-0">Course Content & Media Management</h2>
                    <p class="text-muted">Manage 21 municipalities, edit historical commentaries, toggle visibility, and upload instructional media.</p>
                </div>
            </div>

            <!-- Notification Banner -->
            <div v-if="$page.props.flash && $page.props.flash.success" class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ $page.props.flash.success }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>

            <!-- Educator CMS Tabs -->
            <ul class="nav nav-pills mb-4 bg-white p-2 rounded-4 shadow-sm gap-2" role="tablist">
                <li class="nav-item">
                    <button class="nav-link" :class="{ active: activeTab === 'towns' }" @click="activeTab = 'towns'">
                        <i class="fas fa-map-marked-alt me-1"></i> Municipalities ({{ towns.length }})
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" :class="{ active: activeTab === 'media' }" @click="activeTab = 'media'">
                        <i class="fas fa-photo-video me-1"></i> Upload & Media Library
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" :class="{ active: activeTab === 'sections' }" @click="activeTab = 'sections'">
                        <i class="fas fa-file-alt me-1"></i> Page Content Sections
                    </button>
                </li>
            </ul>

            <!-- TAB 1: Towns Management -->
            <div v-if="activeTab === 'towns'">
                <div class="row g-4">
                    <div v-for="town in towns" :key="town.id" class="col-md-6">
                        <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-bold mb-0 text-dark">{{ town.name }}</h5>
                                <span class="badge" :class="town.status === 'published' ? 'bg-success' : 'bg-secondary'">
                                    {{ town.status }}
                                </span>
                            </div>

                            <form @submit.prevent="updateTown(town)">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Subtitle / Tagline</label>
                                    <input v-model="town.title" type="text" class="form-control form-control-sm">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Description</label>
                                    <textarea v-model="town.description" rows="3" class="form-control form-control-sm"></textarea>
                                </div>

                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <label class="form-label small fw-bold">Difficulty</label>
                                        <select v-model="town.difficulty_level" class="form-select form-select-sm">
                                            <option value="Beginner">Beginner</option>
                                            <option value="Intermediate">Intermediate</option>
                                            <option value="Advanced">Advanced</option>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small fw-bold">Visibility Status</label>
                                        <select v-model="town.status" class="form-select form-select-sm">
                                            <option value="published">Published</option>
                                            <option value="draft">Draft</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-sm btn-success rounded-pill px-4">
                                        <i class="fas fa-save me-1"></i> Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 2: Upload & Media Library -->
            <div v-if="activeTab === 'media'">
                <div class="row g-4">
                    <!-- Upload Form Card -->
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                            <h5 class="fw-bold mb-3 text-dark"><i class="fas fa-cloud-upload-alt text-success me-2"></i>Upload Media Asset</h5>
                            <form @submit.prevent="uploadMedia">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Asset Title</label>
                                    <input v-model="mediaForm.title" type="text" class="form-control form-control-sm" required placeholder="e.g. Bangui Windmills High Res">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Select File (Image / Video)</label>
                                    <input @change="handleFileChange" type="file" class="form-control form-control-sm" required>
                                </div>
                                <button type="submit" class="btn btn-success w-100 rounded-pill fw-bold" :disabled="mediaForm.processing">
                                    <i class="fas fa-upload me-1"></i> Upload Asset
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Media Assets Grid -->
                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                            <h5 class="fw-bold mb-3 text-dark"><i class="fas fa-folder-open text-warning me-2"></i>Uploaded Media Assets</h5>
                            <div class="row g-3">
                                <div v-for="asset in mediaAssets" :key="asset.id" class="col-md-4 col-6">
                                    <div class="border rounded-3 p-2 text-center bg-light">
                                        <img v-if="asset.file_type === 'image'" :src="asset.file_path" :alt="asset.title" class="img-fluid rounded mb-2" style="height: 100px; width: 100%; object-fit: cover;">
                                        <i v-else class="fas fa-video display-6 text-primary my-3"></i>
                                        <small class="fw-bold d-block text-truncate">{{ asset.title }}</small>
                                        <small class="text-muted d-block" style="font-size: 0.75rem;">{{ asset.file_path }}</small>
                                    </div>
                                </div>

                                <div v-if="mediaAssets.length === 0" class="col-12 text-center py-4 text-muted">
                                    No media assets uploaded yet.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 3: Content Sections -->
            <div v-if="activeTab === 'sections'">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                    <h5 class="fw-bold mb-3 text-dark"><i class="fas fa-edit text-primary me-2"></i>Editable Content Sections</h5>
                    <div v-for="sec in contentSections" :key="sec.id" class="border-bottom py-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <span class="badge bg-primary me-2">{{ sec.page_key }}</span>
                                <strong>{{ sec.section_key }}</strong>
                            </div>
                        </div>
                        <form @submit.prevent="updateSection(sec)">
                            <div class="row g-3">
                                <div class="col-md-5">
                                    <input v-model="sec.title" type="text" class="form-control form-control-sm" placeholder="Title">
                                </div>
                                <div class="col-md-5">
                                    <input v-model="sec.subtitle" type="text" class="form-control form-control-sm" placeholder="Subtitle">
                                </div>
                                <div class="col-md-2 text-end">
                                    <button type="submit" class="btn btn-sm btn-outline-success rounded-pill px-3">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';

const props = defineProps({
    towns: Array,
    mediaAssets: Array,
    contentSections: Array,
});

const activeTab = ref('towns');

const updateTown = (town) => {
    router.put(route('educator.towns.update', town.id), {
        name: town.name,
        title: town.title,
        description: town.description,
        status: town.status,
        difficulty_level: town.difficulty_level,
    }, {
        preserveScroll: true,
    });
};

const mediaForm = useForm({
    title: '',
    file: null,
});

const handleFileChange = (e) => {
    mediaForm.file = e.target.files[0];
};

const uploadMedia = () => {
    mediaForm.post(route('educator.media.store'), {
        onSuccess: () => {
            mediaForm.reset();
        },
    });
};

const updateSection = (sec) => {
    router.put(route('educator.content.update', sec.id), {
        title: sec.title,
        subtitle: sec.subtitle,
        is_visible: sec.is_visible,
    }, {
        preserveScroll: true,
    });
};
</script>

<style scoped>
.nav-pills .nav-link {
    color: #495057;
    border-radius: 30px;
    padding: 8px 20px;
    font-weight: 500;
}
.nav-pills .nav-link.active {
    background-color: #0a472e;
    color: #ffffff;
}
</style>
