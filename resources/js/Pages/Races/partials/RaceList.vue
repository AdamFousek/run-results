<script setup>
import { Link, router, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import { NIcon } from 'naive-ui'
import { KeyboardArrowDownFilled, KeyboardArrowUpFilled } from '@vicons/material'
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

const changeSort = (name, isAsc) => {
    const query = usePage().props.ziggy?.query
    const sort = isAsc ? `${name}:desc` : `${name}:asc`
    router.reload({
        data: {
            ...query,
            ...{
                sort
            }
        },
    })
}
</script>

<template>
    <section>
        <div class="grid grid-cols-8 gap-2 md:gap-4 border-b">
            <SortBlock class="font-bold p-3 md:px-4 flex items-center gap-2" name="date" :is-active="activeSort.name === 'date'" :is-asc="activeSort.isAsc">
                {{ $t('race.date') }}
            </SortBlock>
            <SortBlock class="col-span-2 font-bold p-3 md:px-4 flex items-center gap-2" name="name" :is-active="activeSort.name === 'name'" :is-asc="activeSort.isAsc">
                {{ $t('race.name') }}
            </SortBlock>
            <SortBlock class="col-span-2 font-bold p-3 md:px-4 flex items-center gap-2" name="location" :is-active="activeSort.name === 'location'" :is-asc="activeSort.isAsc">
                {{ $t('race.location') }}
            </SortBlock>
            <SortBlock class="font-bold p-3 md:px-4 flex items-center gap-2" name="distance" :is-active="activeSort.name === 'distance'" :is-asc="activeSort.isAsc">
                {{ $t('race.distance') }}
            </SortBlock>
            <div class="font-bold p-3 md:px-4 flex items-center gap-2">
                {{ $t('race.surface') }}
            </div>
            <SortBlock class="font-bold p-3 md:px-4 flex items-center gap-2" name="runnerCount" :is-active="activeSort.name === 'runnerCount'" :is-asc="activeSort.isAsc">
                {{ $t('race.runnersCount') }}
            </SortBlock>
        </div>
        <Link v-for="(race, index) in races" :key="race.id" :href="route('races.show', { race: race.slug })"
              class="grid grid-cols-8 gap-2 md:gap-4 hover:bg-gray-100"
             :class="{ 'bg-gray-50': index%2 === 0}">
            <div class="p-3 md:px-4">{{ race.date }}</div>
            <div class="col-span-2 p-3 md:px-4">{{ race.name }} <span v-if="race.vintage > 0"> - {{ race.vintage }}. {{ $t('race.vintage') }}</span></div>
            <div class="col-span-2 p-3 md:px-4">{{ race.location }}<span v-if="race.region !== ''">, {{ race.region }}</span></div>
            <div class="p-3 md:px-4">{{ race.distance }}</div>
            <div class="p-3 md:px-4">{{ race.surface }}</div>
            <div class="p-3 md:px-4">{{ race.resultsCount }}</div>
        </Link>
    </section>
</template>

<style scoped>

</style>