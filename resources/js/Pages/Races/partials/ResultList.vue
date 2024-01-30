<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
    results: {
        type: Array,
        required: true,
    },
})
</script>

<template>
    <section>
        <div class="grid grid-cols-12 gap-2 md:gap-4 border-b">
            <div class="font-bold p-3 md:px-4">{{ $t('result.position') }}</div>
            <div class="font-bold p-3 md:px-4">{{ $t('result.startingNumber') }}</div>
            <div class="col-span-3 font-bold p-3 md:px-4">{{ $t('runner.name') }}</div>
            <div class="col-span-2 font-bold p-3 md:px-4">{{ $t('result.time') }}</div>
            <div class="font-bold p-3 md:px-4">{{ $t('result.category') }}</div>
            <div class="font-bold p-3 md:px-4">{{ $t('result.categoryPosition') }}</div>
            <div class="col-span-3 font-bold p-3 md:px-4">{{ $t('runner.club') }}</div>
        </div>
        <Link v-for="(result, index) in results" :key="result.id" :href="route('runners.show', { runner: result.runner_id })" class="grid grid-cols-12 gap-2 md:gap-4 hover:bg-gray-100"
           :class="{ 'bg-gray-50': index%2 === 0}">
            <div class="p-3 md:px-4 text-center">{{ result.position }}</div>
            <div class="p-3 md:px-4 text-center">{{ result.starting_number }}</div>
            <div class="col-span-3 p-3 md:px-4">{{ result.last_name }} {{ result.first_name }} ({{ result.year }})</div>
            <div v-if="result.DNF" class="col-span-2 p-3 md:px-4">{{ $t('result.DNF') }}</div>
            <div v-else-if="result.DNS" class="col-span-2 p-3 md:px-4">{{ $t('result.DNS') }}</div>
            <div v-else class="col-span-2 p-3 md:px-4">{{ result.time }}</div>
            <div class="p-3 md:px-4">{{ result.category }}</div>
            <div class="p-3 md:px-4 text-center">{{ result.category_position }}</div>
            <div class="col-span-3 p-3 md:px-4">{{ result.club }}</div>
        </Link>
    </section>
</template>

<style scoped>

</style>