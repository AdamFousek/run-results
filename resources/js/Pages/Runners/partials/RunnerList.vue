<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
    runners: {
        type: Array,
        required: true,
    },
})
</script>

<template>
    <section>
        <div class="grid grid-cols-4 md:grid-cols-5 gap-2 md:gap-4 border-b">
            <div class="font-bold md:col-span-2 p-3 md:px-4">{{ $t('runner.name') }}</div>
            <div class="font-bold p-3 md:px-4">{{ $t('runner.year') }}</div>
            <div class="font-bold p-3 md:px-4">{{ $t('runner.club') }}</div>
            <div class="font-bold p-3 md:px-4">{{ $t('runner.city') }}</div>
        </div>
        <Link v-for="(runner, index) in runners" :key="runner.id" :href="route('runners.show', { runner: runner.id })" class="grid grid-cols-4 md:grid-cols-5 gap-2 md:gap-4 hover:bg-gray-100"
           :class="{ 'bg-gray-50': index%2 === 0}">
            <div class="md:col-span-2 p-3 md:px-4">{{ runner.last_name }} {{ runner.first_name }}</div>
            <div class="p-3 md:px-4">{{ runner.year === 0 ? '-' : runner.year }}</div>
            <div class="p-3 md:px-4">{{ runner.club }}</div>
            <div class="p-3 md:px-4">{{ runner.city }}</div>
        </Link>
    </section>
</template>

<style scoped>

</style>