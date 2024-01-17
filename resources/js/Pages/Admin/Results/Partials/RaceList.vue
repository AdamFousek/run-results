<script setup>
import { RemoveRedEyeOutlined } from '@vicons/material'

defineProps({
    races: {
        type: Array,
        required: true,
    },
})
</script>

<template>
    <div class="relative">
        <div class="grid grid-cols-6 gap-2 md:gap-4 border-b">
            <div class="col-span-2 font-bold p-2 md:px-4">{{ $t('race.name') }}</div>
            <div class="font-bold p-2 md:px-4">{{ $t('race.date') }}</div>
            <div class="font-bold p-2 md:px-4">{{ $t('race.location') }}</div>
            <div class="font-bold p-2 md:px-4">{{ $t('race.resultsCount') }}</div>
            <div class="font-bold p-2 md:px-4 text-center">{{ $t('actions') }}</div>
        </div>
        <div v-for="(race, index) in races" :key="race.id" class="grid grid-cols-6 gap-2 md:gap-4 hover:bg-gray-100"
             :class="{ 'bg-gray-50': index%2 === 0}">
            <div class="col-span-2 font-bold p-2 md:px-4">{{ race.name }}</div>
            <div class="p-2 md:px-4">{{ race.date }}</div>
            <div class="p-2 md:px-4">{{ race.location }}</div>
            <div class="p-2 md:px-4">{{ race.resultsCount }}</div>
            <div class="p-2 md:px-4 flex justify-center align-middle">
                <a :href="route('admin.results.show', { race: race.id })" :title="'Zobrazit'" class="inline-block text-purple-900 hover:text-purple-500">
                    <RemoveRedEyeOutlined class="w-7" />
                </a>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>