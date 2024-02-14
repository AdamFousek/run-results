<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import type Runner from "@/Models/List/Runner";
import route from 'ziggy-js'

defineProps<{
    runners: Runner[],
}>()
</script>

<template>
    <section>
        <div class="grid grid-cols-5 md:grid-cols-6 gap-2 md:gap-4 border-b">
            <div class="font-bold md:col-span-2 p-3 md:px-4">{{ $t('runner.name') }}</div>
            <div class="font-bold p-3 md:px-4">{{ $t('runner.year') }}</div>
            <div class="font-bold p-3 md:px-4">{{ $t('runner.club') }}</div>
            <div class="font-bold p-3 md:px-4">{{ $t('runner.city') }}</div>
            <div class="font-bold p-3 md:px-4 text-center">{{ $t('runner.races') }}</div>
        </div>
        <Link v-for="(runner, index) in runners" :key="runner.id" :href="route('runners.show', { runner: runner.id })" class="grid grid-cols-5 md:grid-cols-6 gap-2 md:gap-4 hover:bg-gray-100"
           :class="{ 'bg-gray-50': index%2 === 0}">
            <div class="md:col-span-2 p-3 md:px-4">{{ runner.lastName }} {{ runner.firstName }}</div>
            <div class="p-3 md:px-4">{{ runner.year === 0 ? '-' : runner.year }}</div>
            <div class="p-3 md:px-4">{{ runner.club }}</div>
            <div class="p-3 md:px-4">{{ runner.city }}</div>
            <div class="p-3 md:px-4 text-center">{{ runner.resultsCount }}</div>
        </Link>
    </section>
</template>

<style scoped>

</style>