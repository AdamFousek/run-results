<script setup>
import { onMounted, ref } from 'vue'

const props = defineProps(['modelValue', 'name']);

const emits = defineEmits(['update:modelValue']);

const input = ref(null);
const editor = ref();

onMounted(() => {
    if (editor.value) {
        editor.value.value = props.modelValue;
    }
});

const trixChange = (event) => {
    emits('update:modelValue', event.target.value)
}
</script>

<template>
    <div>
        <input
                :id="`trix-input-${name}`"
                type="hidden"
                :value="modelValue"
                ref="input"
        >
        <trix-editor @trix-change="trixChange" :inputId="`trix-input-${name}`" class="trix-content big" ref="editor"></trix-editor>
    </div>
</template>
