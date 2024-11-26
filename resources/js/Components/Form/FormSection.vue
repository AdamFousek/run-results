<script setup lang="ts">

</script>

<template>
    <div class="grid grid-cols-3 gap-4 border-b pb-6">
        <div class="col-1">
            <h3 v-if="$slots.header" class="text-xl">
                <slot name="header" />
            </h3>
            <p v-if="$slots.description" class="text-gray-500 text-sm">
                <slot name="description" />
            </p>
        </div>
        <div class="border-l col-span-2 space-y-6 pl-4">
            <slot />
        </div>
    </div>
</template>

<style scoped>

</style>