<script setup lang="ts">
import {Link} from '@inertiajs/vue3'
import type Runner from "@/Models/List/Runner";
import route from 'ziggy-js'
import { computed } from 'vue'
import SortBlock from "@/Components/SortBlock.vue";

const props = defineProps<{
    runners: Runner[],
    sort: String,
}>()


const activeSort = computed(() => {
    const sort = props.sort.split(':')
    return {
        name: sort[0],
        isAsc: sort[1] === 'asc',
    }
});
</script>

<template>
    <section>
        <div class="grid grid-cols-5 md:grid-cols-7 gap-2 md:gap-4 border-b">
            <SortBlock class="flex justify-start items-center md:col-span-2 font-bold p-3 md:px-4" name="lastName" :is-active="activeSort.name === 'lastName'" :is-asc="activeSort.isAsc">
                {{ $t('runner.name') }}
            </SortBlock>
            <SortBlock class="flex justify-start items-center font-bold p-3 md:px-4" name="year" :is-active="activeSort.name === 'year'" :is-asc="activeSort.isAsc">
                {{ $t('runner.year') }}
            </SortBlock>
            <SortBlock class="flex justify-start items-center md:col-span-2 font-bold p-3 md:px-4" name="club" :is-active="activeSort.name === 'club'" :is-asc="activeSort.isAsc">
                {{ $t('runner.club') }}
            </SortBlock>
            <SortBlock class="flex justify-start items-center font-bold p-3 md:px-4" name="city" :is-active="activeSort.name === 'city'" :is-asc="activeSort.isAsc">
                {{ $t('runner.city') }}
            </SortBlock>
            <SortBlock class="flex justify-start items-center font-bold p-3 md:px-4" name="resultsCount" :is-active="activeSort.name === 'resultsCount'" :is-asc="activeSort.isAsc">
                {{ $t('runner.races') }}
            </SortBlock>
        </div>
        <Link v-for="(runner, index) in runners" :key="runner.id" :href="route('runners.show', { runner: runner.id })" class="grid grid-cols-5 md:grid-cols-7 gap-2 md:gap-4 hover:bg-gray-100"
           :class="{ 'bg-gray-50': index%2 === 0}">
            <div class="md:col-span-2 p-3 md:px-4">{{ runner.lastName }} {{ runner.firstName }}</div>
            <div class="p-3 md:px-4">{{ runner.year === 0 ? '-' : runner.year }}</div>
            <div class="md:col-span-2 p-3 md:px-4">{{ runner.club }}</div>
            <div class="p-3 md:px-4">{{ runner.city }}</div>
            <div class="p-3 md:px-4 text-center">{{ runner.resultsCount }}</div>
        </Link>
    </section>
</template>

<style scoped>

</style>