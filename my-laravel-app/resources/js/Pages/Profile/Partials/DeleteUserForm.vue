<script setup>
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;
    nextTick(() => passwordInput.value?.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value?.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;
    form.clearErrors();
    form.reset();
};
</script>

<template>
    <section>
        <p class="text-muted small mb-4">
            Before deleting your account, please download any data or information that you wish to retain.
        </p>

        <button class="btn btn-danger px-4 py-2 fw-bold" @click="confirmUserDeletion">
            Delete Account
        </button>

        <!-- Bootstrap Modal Simulation -->
        <div v-if="confirmingUserDeletion" class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5); z-index: 1055;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <div class="modal-header border-bottom-0 pb-0">
                        <h5 class="modal-title fw-bold text-dark">Confirm Account Deletion</h5>
                        <button type="button" class="btn-close" @click="closeModal"></button>
                    </div>
                    
                    <div class="modal-body py-4">
                        <p class="text-muted small mb-4">
                            Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.
                        </p>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold text-dark sr-only">Password</label>
                            <input
                                id="password"
                                ref="passwordInput"
                                v-model="form.password"
                                type="password"
                                class="form-control rounded-3"
                                placeholder="Enter your password"
                                @keyup.enter="deleteUser"
                                :class="{ 'is-invalid': form.errors.password }"
                            />
                            <div class="invalid-feedback" v-if="form.errors.password">
                                {{ form.errors.password }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal-footer border-top-0 pt-0 gap-2">
                        <button type="button" class="btn btn-light fw-medium px-4" @click="closeModal">
                            Cancel
                        </button>
                        <button 
                            type="button" 
                            class="btn btn-danger fw-bold px-4" 
                            :disabled="form.processing"
                            @click="deleteUser"
                        >
                            <span v-if="form.processing" class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                            Delete Account
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
