<script setup>
defineProps({
    value: {
        type: String,
    },
    required: {
        type: Boolean,
        default: false,
    }
});
</script>

<template>
    <label class="block text-sm text-gray-700"
           :class="{
            'font-bold': required,
            'font-medium': !required,
            }"
    >
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
        <span v-if="required" class="text-red-300 ml-1">*</span>
    </label>
</template>
