<script setup lang="ts">
</script>

<template>
    <form>
        <div class="mb-8 pb-4">
            <h3 v-if="$slots.header" class="text-xl">
                <slot name="header" />
            </h3>
            <p v-if="$slots.description" class="text-gray-500 text-sm w-1/2">
                <slot name="description" />
            </p>
        </div>
        <div class="space-y-6">
            <slot />
        </div>
        <div class="my-2 text-xs">
            <span class="text-red-300 mr-1">*</span>
            <span class="text-gray-500">{{ $t('requiredDescription') }}</span>
        </div>
        <div class="flex items-center justify-between mt-4 gap-4">
            <slot name="cancel" />
            <slot name="submit" />
        </div>
    </form>
</template>

<style scoped>

</style>