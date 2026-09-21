<script setup>
import { ref } from 'vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: Boolean,
    status: String,
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
    avatar: null,
});

const imagePreview = ref(null);

const handleAvatarChange = (e) => {
    const file = e.target.files[0];
    if (!file) return;

    form.clearErrors('avatar');

    const reader = new FileReader();
    reader.onload = (event) => {
        const img = new Image();
        img.onload = () => {
            const canvas = document.createElement('canvas');
            const MAX_WIDTH = 800;
            const MAX_HEIGHT = 800;
            let width = img.width;
            let height = img.height;

            if (width > height) {
                if (width > MAX_WIDTH) {
                    height *= MAX_WIDTH / width;
                    width = MAX_WIDTH;
                }
            } else {
                if (height > MAX_HEIGHT) {
                    width *= MAX_HEIGHT / height;
                    height = MAX_HEIGHT;
                }
            }

            canvas.width = width;
            canvas.height = height;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0, width, height);

            // Compress to JPEG with 0.8 quality
            const dataUrl = canvas.toDataURL('image/jpeg', 0.8);
            imagePreview.value = dataUrl;

            // Convert to Blob and assign to form
            fetch(dataUrl)
                .then(res => res.blob())
                .then(blob => {
                    const compressedFile = new File([blob], file.name.replace(/\.[^/.]+$/, "") + ".jpg", {
                        type: 'image/jpeg',
                        lastModified: Date.now()
                    });
                    form.avatar = compressedFile;
                });
        };
        img.src = event.target.result;
    };
    reader.readAsDataURL(file);
};
</script>

<template>
    <section>
        <form @submit.prevent="form.post(route('profile.update'), { preserveScroll: true, forceFormData: true })">
            
            <!-- Avatar Upload Area -->
            <div class="mb-4 text-center">
                <div class="mb-3 position-relative d-inline-block">
                    <img 
                        v-if="user.avatar || imagePreview"
                        :src="imagePreview || user.avatar" 
                        alt="Profile Preview" 
                        class="rounded-circle object-fit-cover shadow-sm border border-3 border-white"
                        style="width: 120px; height: 120px; background-color: #e2e8f0;"
                    >
                    <div 
                        v-else 
                        class="rounded-circle bg-success text-white d-inline-flex align-items-center justify-content-center shadow-sm border border-3 border-white" 
                        style="width: 120px; height: 120px; font-size: 3rem;"
                    >
                        <i class="fas fa-user"></i>
                    </div>
                </div>
                <div>
                    <label for="avatar" class="btn btn-outline-secondary btn-sm rounded-pill fw-bold px-4 py-2 hover-lift">
                        <i class="fas fa-camera me-2"></i> Change Profile Picture
                    </label>
                    <input
                        id="avatar"
                        type="file"
                        class="d-none"
                        accept="image/*"
                        @change="handleAvatarChange"
                    />
                    <div class="invalid-feedback d-block mt-2" v-if="form.errors.avatar">
                        {{ form.errors.avatar }}
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="name" class="form-label fw-bold text-dark">Name</label>
                <input
                    id="name"
                    type="text"
                    class="form-control rounded-3"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                    :class="{ 'is-invalid': form.errors.name }"
                />
                <div class="invalid-feedback" v-if="form.errors.name">
                    {{ form.errors.name }}
                </div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label fw-bold text-dark">Email Address</label>
                <input
                    id="email"
                    type="email"
                    class="form-control rounded-3"
                    v-model="form.email"
                    required
                    autocomplete="username"
                    :class="{ 'is-invalid': form.errors.email }"
                />
                <div class="invalid-feedback" v-if="form.errors.email">
                    {{ form.errors.email }}
                </div>
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null" class="mb-3">
                <p class="mt-2 text-muted small">
                    Your email address is unverified.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="btn btn-link p-0 m-0 align-baseline text-decoration-none"
                    >
                        Click here to re-send the verification email.
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-2 text-success small fw-medium"
                >
                    A new verification link has been sent to your email address.
                </div>
            </div>

            <div class="d-flex align-items-center gap-3 mt-4">
                <button type="submit" class="btn btn-mmsu px-4 py-2 fw-bold" :disabled="form.processing">
                    Save Changes
                </button>

                <Transition
                    enter-active-class="transition"
                    enter-from-class="opacity-0"
                    leave-active-class="transition"
                    leave-to-class="opacity-0"
                >
                    <span
                        v-if="form.recentlySuccessful"
                        class="text-success small fw-bold"
                    >
                        <i class="fas fa-check-circle me-1"></i> Saved successfully.
                    </span>
                </Transition>
            </div>
        </form>
    </section>
</template>
