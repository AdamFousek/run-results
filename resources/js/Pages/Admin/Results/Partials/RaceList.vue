<script setup>
import { NIcon } from 'naive-ui'
import { KeyboardArrowDownFilled, KeyboardArrowUpFilled } from '@vicons/material'
import { Link, router, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import SortBlock from '@/Components/SortBlock.vue'

const props = defineProps({
    races: {
        type: Array,
        required: true,
    },
    sort: {
        type: String,
        required: false,
    }
})

const activeSort = computed(() => {
    const sort = props.sort.split(':')
    return {
        name: sort[0],
        isAsc: sort[1] === 'asc',
    }
});
</script>

<template>
    <div class="relative">
        <div class="grid grid-cols-6 gap-2 md:gap-4 border-b">
            <SortBlock class="font-bold p-3 md:px-4 flex items-center gap-2" name="date" :is-active="activeSort.name === 'date'" :is-asc="activeSort.isAsc">
                {{ $t('race.date') }}
            </SortBlock>
            <SortBlock class="font-bold col-span-2 p-3 md:px-4 flex items-center gap-2" name="name" :is-active="activeSort.name === 'name'" :is-asc="activeSort.isAsc">
                {{ $t('race.name') }}
            </SortBlock>
            <SortBlock class="font-bold p-3 md:px-4 flex justify-center items-center gap-2" name="distance" :is-active="activeSort.name === 'distance'" :is-asc="activeSort.isAsc">
                {{ $t('race.distance') }}
            </SortBlock>
            <SortBlock class="font-bold p-3 md:px-4 flex justify-center items-center gap-2" name="results_count" :is-active="activeSort.name === 'results_count'" :is-asc="activeSort.isAsc">
                {{ $t('race.runnersCount') }}
            </SortBlock>
            <SortBlock class="font-bold p-3 md:px-4 flex justify-center items-center gap-2" name="created_at" :is-active="activeSort.name === 'created_at'" :is-asc="activeSort.isAsc">
                {{ $t('admin.races.createdAt') }}
            </SortBlock>
        </div>
        <Link v-for="(race, index) in races" :key="race.id" :href="route('admin.results.show', { race: race.id })" class="grid grid-cols-6 gap-2 md:gap-4 hover:bg-gray-100"
             :class="{ 'bg-gray-50': index%2 === 0}">
            <div class="p-3 md:px-4">{{ race.date }}</div>
            <div class="col-span-2 p-3 md:px-4">{{ race.name }} <span v-if="race.vintage > 0"> - {{ race.vintage }}. {{ $t('race.vintage') }}</span></div>
            <div class="p-3 md:px-4 text-center">{{ race.distance }}</div>
            <div class="p-3 md:px-4 text-center">{{ race.resultsCount }}</div>
            <div class="p-3 md:px-4 text-center">{{ race.createdAt }}</div>
        </Link>
    </div>
</template>

<style scoped>

</style>