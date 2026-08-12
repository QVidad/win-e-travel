<script setup>
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: Boolean,
    status: String,
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});
</script>

<template>
    <section>
        <form @submit.prevent="form.patch(route('profile.update'))">
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
