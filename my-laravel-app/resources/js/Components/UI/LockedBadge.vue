<template>
    <span 
        class="badge rounded-pill fw-bold locked-badge-custom"
        :class="{ 'position-absolute': absolute }"
        :style="badgeStyle"
    >
        Locked <i class="fas fa-lock ms-1"></i>
    </span>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    absolute: {
        type: Boolean,
        default: true
    },
    top: {
        type: String,
        default: '15px'
    },
    right: {
        type: String,
        default: '15px'
    }
});

const badgeStyle = computed(() => {
    let style = {
        background: 'rgba(108, 117, 125, 0.85)',
        color: 'white',
        padding: '6px 14px',
        fontSize: '0.85rem',
        zIndex: 2,
    };
    
    if (props.absolute) {
        style.top = props.top;
        style.right = props.right;
    }
    
    return style;
});
</script>

<style scoped>
.locked-badge-custom {
    backdrop-filter: blur(4px);
}
</style>
