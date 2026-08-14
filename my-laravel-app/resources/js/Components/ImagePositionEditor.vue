<template>
    <div v-if="show" class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.7); z-index: 1055;">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header bg-light border-bottom p-4">
                    <h5 class="modal-title fw-bold text-dark">
                        <i class="fas fa-crop-alt text-primary me-2"></i> Adjust Image Framing
                    </h5>
                    <button type="button" class="btn-close" @click="close"></button>
                </div>
                
                <div class="modal-body p-4 bg-white">
                    <p class="text-muted small mb-3">
                        Drag the image up or down, or use the slider below to adjust which part of the image is shown in the banner.
                    </p>

                    <!-- Draggable Image Container -->
                    <div 
                        class="position-relative overflow-hidden rounded border mb-4 bg-light cursor-move mx-auto" 
                        :style="{ 
                            height: '250px', 
                            width: '100%', 
                            maxWidth: targetType === 'lesson' ? '500px' : '100%',
                            touchAction: 'none' 
                        }"
                        @mousedown="startDrag"
                        @mousemove="onDrag"
                        @mouseup="endDrag"
                        @mouseleave="endDrag"
                        @touchstart.prevent="startDragTouch"
                        @touchmove.prevent="onDragTouch"
                        @touchend="endDrag"
                    >
                        <div 
                            class="w-100 h-100"
                            :style="{
                                backgroundImage: `url(${imageUrl})`,
                                backgroundSize: 'cover',
                                backgroundPosition: `center ${positionY}%`,
                                backgroundRepeat: 'no-repeat'
                            }"
                        ></div>

                        <!-- Drag overlay hint -->
                        <div class="position-absolute top-50 start-50 translate-middle pointer-events-none opacity-50 bg-dark text-white rounded-pill px-3 py-2 d-flex align-items-center gap-2" style="pointer-events: none;">
                            <i class="fas fa-arrows-alt-v"></i> Drag to adjust
                        </div>
                    </div>

                    <!-- Range Slider -->
                    <div class="d-flex align-items-center gap-3 px-2">
                        <span class="text-muted small fw-bold">Top</span>
                        <input 
                            type="range" 
                            class="form-range flex-grow-1" 
                            min="0" 
                            max="100" 
                            v-model="positionY"
                        >
                        <span class="text-muted small fw-bold">Bottom</span>
                    </div>
                </div>

                <div class="modal-footer bg-light border-top p-3 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-light fw-bold px-4" @click="close">Cancel</button>
                    <button type="button" class="btn btn-primary fw-bold px-4" @click="save">
                        <i class="fas fa-check me-2"></i> Apply Position
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue';

const props = defineProps({
    show: Boolean,
    imageUrl: String,
    initialPosition: {
        type: String,
        default: 'center 50%'
    },
    targetType: {
        type: String,
        default: 'module'
    }
});

const emit = defineEmits(['close', 'save']);

// We only extract the Y percentage from 'center X%'
const positionY = ref(50);
const isDragging = ref(false);
const startY = ref(0);
const startPositionY = ref(0);

// Parse the initial position (e.g. "center 45%")
const parseInitialPosition = () => {
    if (!props.initialPosition) return 50;
    
    // Quick fallbacks for words
    if (props.initialPosition === 'top') return 0;
    if (props.initialPosition === 'center') return 50;
    if (props.initialPosition === 'bottom') return 100;
    
    // Try to parse 'center 30%'
    const match = props.initialPosition.match(/(\d+)%/);
    if (match && match[1]) {
        return parseInt(match[1]);
    }
    
    return 50; // Default fallback
};

watch(() => props.show, (newVal) => {
    if (newVal) {
        positionY.value = parseInitialPosition();
    }
});

onMounted(() => {
    positionY.value = parseInitialPosition();
});

// Drag Logic for Mouse
const startDrag = (e) => {
    isDragging.value = true;
    startY.value = e.clientY;
    startPositionY.value = Number(positionY.value);
};

const onDrag = (e) => {
    if (!isDragging.value) return;
    
    const deltaY = e.clientY - startY.value;
    const sensitivity = 0.3; // Adjust this if dragging feels too fast or slow
    let newY = startPositionY.value - (deltaY * sensitivity);
    
    if (newY < 0) newY = 0;
    if (newY > 100) newY = 100;
    
    positionY.value = newY;
};

const endDrag = () => {
    isDragging.value = false;
};

// Drag Logic for Touch
const startDragTouch = (e) => {
    isDragging.value = true;
    startY.value = e.touches[0].clientY;
    startPositionY.value = Number(positionY.value);
};

const onDragTouch = (e) => {
    if (!isDragging.value) return;
    const deltaY = e.touches[0].clientY - startY.value;
    const sensitivity = 0.4;
    let newY = startPositionY.value - (deltaY * sensitivity);
    
    if (newY < 0) newY = 0;
    if (newY > 100) newY = 100;
    
    positionY.value = newY;
};

const close = () => {
    emit('close');
};

const save = () => {
    emit('save', `center ${Math.round(positionY.value)}%`);
};
</script>

<style scoped>
.cursor-move {
    cursor: grab;
}
.cursor-move:active {
    cursor: grabbing;
}
</style>
