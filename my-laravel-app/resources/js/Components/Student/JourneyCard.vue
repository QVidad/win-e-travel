<template>
    <div class="col-lg-4">
        <div :class="['journey-stage-card', customClass, { locked: isLocked }]" :id="cardId">
            <LockedBadge v-if="isLocked" />
            
            <div class="stage-icon">
                <i :class="icon"></i>
            </div>
            
            <h4 class="fw-bold mb-2">{{ title }}</h4>
            <p class="text-muted small mb-3">{{ subtitle }}</p>

            <div class="progress-bar-custom mb-3">
                <div class="progress-fill" :style="{ width: percentComplete + '%' }"></div>
            </div>
            
            <div class="d-flex justify-content-between mb-3">
                <span class="small text-muted">
                    <span>{{ completed }}</span>/{{ total }} {{ completedLabel }}
                </span>
                <span class="small fw-bold text-success">{{ percentComplete }}%</span>
            </div>

            <div class="chapter-progress mb-3">
                <slot name="dots"></slot>
            </div>

            <div v-if="isLocked" class="text-secondary small mt-2">
                <i class="fas fa-lock me-1"></i>{{ lockedMessage }}
            </div>
            <Link v-else :href="routeUrl" class="btn btn-journey w-100" :style="{ backgroundColor: btnColor, color: 'white' }">
                <i class="fas fa-arrow-right me-2"></i>{{ btnText }}
            </Link>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import LockedBadge from '@/Components/UI/LockedBadge.vue';

const props = defineProps({
    customClass: String,
    cardId: String,
    icon: String,
    title: String,
    subtitle: String,
    completed: Number,
    total: Number,
    completedLabel: {
        type: String,
        default: 'Completed'
    },
    isLocked: Boolean,
    lockedMessage: String,
    routeUrl: String,
    btnColor: String,
    btnText: String,
});

const percentComplete = computed(() => {
    if (!props.total) return 0;
    return Math.round((props.completed / props.total) * 100);
});
</script>
