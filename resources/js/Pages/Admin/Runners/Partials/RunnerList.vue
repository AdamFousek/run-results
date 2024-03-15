<script setup lang="ts">
import {Link} from '@inertiajs/vue3'
import type Runner from "@/Models/List/Runner";
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
})
</script>

<template>
    <section>
        <div class="grid grid-cols-6 md:grid-cols-8 gap-2 md:gap-4 border-b">
            <SortBlock class="font-bold md:col-span-2 p-3 md:px-4 flex items-center gap-2" name="last_name" :is-active="activeSort.name === 'last_name'" :is-asc="activeSort.isAsc">
                {{ $t('runner.name') }}
            </SortBlock>
            <SortBlock class="font-bold p-3 md:px-4 flex items-center justify-center gap-2" name="year" :is-active="activeSort.name === 'year'" :is-asc="activeSort.isAsc">
                {{ $t('runner.year') }}
            </SortBlock>
            <SortBlock class="font-bold md:col-span-2 p-3 md:px-4 flex justify-center items-center gap-2" name="club" :is-active="activeSort.name === 'club'" :is-asc="activeSort.isAsc">
                {{ $t('runner.club') }}
            </SortBlock>
            <SortBlock class="font-bold p-3 md:px-4 flex items-center gap-2" name="city" :is-active="activeSort.name === 'city'" :is-asc="activeSort.isAsc">
                {{ $t('runner.city') }}
            </SortBlock>
            <SortBlock class="font-bold p-3 md:px-4 flex items-center gap-2" name="results_count" :is-active="activeSort.name === 'results_count'" :is-asc="activeSort.isAsc">
                {{ $t('runner.races') }}
            </SortBlock>
            <SortBlock class="font-bold p-3 md:px-4 flex items-center gap-2" name="created_at" :is-active="activeSort.name === 'created_at'" :is-asc="activeSort.isAsc">
                {{ $t('admin.runner.createdAt') }}
            </SortBlock>
        </div>
        <Link v-for="(runner, index) in runners" :key="runner.id" :href="route('admin.runners.edit', { runner: runner.id })" class="grid grid-cols-6 md:grid-cols-8 gap-2 md:gap-4 hover:bg-gray-100"
              :class="{ 'bg-gray-50': index%2 === 0}">
            <div class="md:col-span-2 p-3 md:px-4">{{ runner.lastName }} {{ runner.firstName }}</div>
            <div class="p-3 md:px-4 text-center">{{ runner.year === 0 ? '-' : runner.year }}</div>
            <div class="p-3 md:col-span-2 md:px-4 text-center">{{ runner.club }}</div>
            <div class="p-3 md:px-4">{{ runner.city }}</div>
            <div class="p-3 md:px-4 text-center">{{ runner.resultsCount }}</div>
            <div class="p-3 md:px-4 text-center">{{ runner.createdAt }}</div>
        </Link>
    </section>
</template>

<style scoped>

</style>