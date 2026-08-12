<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <section>
        <form @submit.prevent="updatePassword">
            <div class="mb-3">
                <label for="current_password" class="form-label fw-bold text-dark">Current Password</label>
                <input
                    id="current_password"
                    ref="currentPasswordInput"
                    v-model="form.current_password"
                    type="password"
                    class="form-control rounded-3"
                    autocomplete="current-password"
                    :class="{ 'is-invalid': form.errors.current_password }"
                />
                <div class="invalid-feedback" v-if="form.errors.current_password">
                    {{ form.errors.current_password }}
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label fw-bold text-dark">New Password</label>
                <input
                    id="password"
                    ref="passwordInput"
                    v-model="form.password"
                    type="password"
                    class="form-control rounded-3"
                    autocomplete="new-password"
                    :class="{ 'is-invalid': form.errors.password }"
                />
                <div class="invalid-feedback" v-if="form.errors.password">
                    {{ form.errors.password }}
                </div>
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label fw-bold text-dark">Confirm Password</label>
                <input
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    class="form-control rounded-3"
                    autocomplete="new-password"
                    :class="{ 'is-invalid': form.errors.password_confirmation }"
                />
                <div class="invalid-feedback" v-if="form.errors.password_confirmation">
                    {{ form.errors.password_confirmation }}
                </div>
            </div>

            <div class="d-flex align-items-center gap-3 mt-4">
                <button type="submit" class="btn btn-mmsu px-4 py-2 fw-bold" :disabled="form.processing">
                    Update Password
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
