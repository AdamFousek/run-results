<script setup>
import { NIcon } from 'naive-ui'
import { KeyboardArrowDownFilled, KeyboardArrowUpFilled } from '@vicons/material'
import { Link, router, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

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
    <div class="relative">
        <div class="grid grid-cols-5 gap-2 md:gap-4 border-b">
            <div class="font-bold p-3 md:px-4 flex items-center gap-2 cursor-pointer text-violet-900 hover:text-violet-800" @click="changeSort('date', activeSort.isAsc)">
                <div class="flex flex-col">
                    <NIcon v-if="activeSort.name === 'date' && activeSort.isAsc">
                        <KeyboardArrowUpFilled />
                    </NIcon>
                    <NIcon v-if="activeSort.name === 'date' && !activeSort.isAsc">
                        <KeyboardArrowDownFilled />
                    </NIcon>
                </div>
                <span>{{ $t('race.date') }}</span>
            </div>
            <div class="font-bold col-span-2 p-3 md:px-4 flex items-center gap-2 cursor-pointer text-violet-900 hover:text-violet-800" @click="changeSort('name', activeSort.isAsc)">
                <div class="flex flex-col">
                    <NIcon v-if="activeSort.name === 'name' && activeSort.isAsc">
                        <KeyboardArrowUpFilled />
                    </NIcon>
                    <NIcon v-if="activeSort.name === 'name' && !activeSort.isAsc">
                        <KeyboardArrowDownFilled />
                    </NIcon>
                </div>
                <span>{{ $t('race.name') }}</span>
            </div>
            <div class="font-bold p-3 md:px-4 flex justify-center items-center gap-2 cursor-pointer text-violet-900 hover:text-violet-800" @click="changeSort('distance', activeSort.isAsc)">
                <div class="flex flex-col">
                    <NIcon v-if="activeSort.name === 'distance' && activeSort.isAsc">
                        <KeyboardArrowUpFilled />
                    </NIcon>
                    <NIcon v-if="activeSort.name === 'distance' && !activeSort.isAsc">
                        <KeyboardArrowDownFilled />
                    </NIcon>
                </div>
                <span>{{ $t('race.distance') }}</span>
            </div>
            <div class="font-bold p-3 md:px-4 flex justify-center items-center gap-2 cursor-pointer text-violet-900 hover:text-violet-800" @click="changeSort('runnerCount', activeSort.isAsc)">
                <div class="flex flex-col">
                    <NIcon v-if="activeSort.name === 'runnerCount' && activeSort.isAsc">
                        <KeyboardArrowUpFilled />
                    </NIcon>
                    <NIcon v-if="activeSort.name === 'runnerCount' && !activeSort.isAsc">
                        <KeyboardArrowDownFilled />
                    </NIcon>
                </div>
                <span>{{ $t('race.runnersCount') }}</span>
            </div>
        </div>
        <Link v-for="(race, index) in races" :key="race.id" :href="route('admin.results.show', { race: race.id })" class="grid grid-cols-5 gap-2 md:gap-4 hover:bg-gray-100"
             :class="{ 'bg-gray-50': index%2 === 0}">
            <div class="p-3 md:px-4">{{ race.date }}</div>
            <div class="col-span-2 p-3 md:px-4">{{ race.name }} <span v-if="race.vintage > 0"> - {{ race.vintage }}. {{ $t('race.vintage') }}</span></div>
            <div class="p-3 md:px-4 text-center">{{ race.distance }}</div>
            <div class="p-3 md:px-4 text-center">{{ race.resultsCount }}</div>
        </Link>
    </div>
</template>

<style scoped>

</style>