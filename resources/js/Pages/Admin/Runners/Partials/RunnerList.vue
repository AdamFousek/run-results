<script setup lang="ts">
import {Link, router, usePage} from '@inertiajs/vue3'
import type Runner from "@/Models/List/Runner";
import route from 'ziggy-js'
import {KeyboardArrowDownFilled, KeyboardArrowUpFilled} from "@vicons/material";
import {NIcon} from "naive-ui";
import { computed } from 'vue'

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

const query = computed(() => {
    return (usePage().props.ziggy as any).query
})

const changeSort = (name: string, isAsc: boolean) => {
    const sort = isAsc ? `${name}:desc` : `${name}:asc`
    router.reload({
        data: {
            ...query.value,
            ...{
                sort
            }
        },
    })
}
</script>

<template>
    <section>
        <div class="grid grid-cols-5 md:grid-cols-7 gap-2 md:gap-4 border-b">
            <div class="font-bold md:col-span-2 p-3 md:px-4 flex items-center gap-2 cursor-pointer text-violet-900 hover:text-violet-800" @click="changeSort('lastName', activeSort.isAsc)">
                <div class="flex flex-col">
                    <NIcon v-if="activeSort.name === 'lastName' && activeSort.isAsc">
                        <KeyboardArrowUpFilled />
                    </NIcon>
                    <NIcon v-if="activeSort.name === 'lastName' && !activeSort.isAsc">
                        <KeyboardArrowDownFilled />
                    </NIcon>
                </div>
                <span>{{ $t('runner.name') }}</span>
            </div>
            <div class="font-bold p-3 md:px-4 flex items-center gap-2 cursor-pointer text-violet-900 hover:text-violet-800" @click="changeSort('year', activeSort.isAsc)">
                <div class="flex flex-col">
                    <NIcon v-if="activeSort.name === 'year' && activeSort.isAsc">
                        <KeyboardArrowUpFilled />
                    </NIcon>
                    <NIcon v-if="activeSort.name === 'year' && !activeSort.isAsc">
                        <KeyboardArrowDownFilled />
                    </NIcon>
                </div>
                <span>{{ $t('runner.year') }}</span>
            </div>
            <div class="font-bold md:col-span-2 p-3 md:px-4 flex justify-center items-center gap-2 cursor-pointer text-violet-900 hover:text-violet-800" @click="changeSort('club', activeSort.isAsc)">
                <div class="flex flex-col">
                    <NIcon v-if="activeSort.name === 'club' && activeSort.isAsc">
                        <KeyboardArrowUpFilled />
                    </NIcon>
                    <NIcon v-if="activeSort.name === 'club' && !activeSort.isAsc">
                        <KeyboardArrowDownFilled />
                    </NIcon>
                </div>
                <span>{{ $t('runner.club') }}</span>
            </div>
            <div class="font-bold p-3 md:px-4 flex items-center gap-2 cursor-pointer text-violet-900 hover:text-violet-800" @click="changeSort('city', activeSort.isAsc)">
                <div class="flex flex-col">
                    <NIcon v-if="activeSort.name === 'city' && activeSort.isAsc">
                        <KeyboardArrowUpFilled />
                    </NIcon>
                    <NIcon v-if="activeSort.name === 'city' && !activeSort.isAsc">
                        <KeyboardArrowDownFilled />
                    </NIcon>
                </div>
                <span>{{ $t('runner.city') }}</span>
            </div>
            <div class="font-bold p-3 md:px-4 text-center flex items-center gap-2 cursor-pointer text-violet-900 hover:text-violet-800" @click="changeSort('resultsCount', activeSort.isAsc)">
                <div class="flex flex-col">
                    <NIcon v-if="activeSort.name === 'resultsCount' && activeSort.isAsc">
                        <KeyboardArrowUpFilled />
                    </NIcon>
                    <NIcon v-if="activeSort.name === 'resultsCount' && !activeSort.isAsc">
                        <KeyboardArrowDownFilled />
                    </NIcon>
                </div>
                <span>{{ $t('runner.races') }}</span>
            </div>
        </div>
        <Link v-for="(runner, index) in runners" :key="runner.id" :href="route('admin.runners.edit', { runner: runner.id })" class="grid grid-cols-5 md:grid-cols-7 gap-2 md:gap-4 hover:bg-gray-100"
              :class="{ 'bg-gray-50': index%2 === 0}">
            <div class="md:col-span-2 p-3 md:px-4">{{ runner.lastName }} {{ runner.firstName }}</div>
            <div class="p-3 md:px-4 text-center">{{ runner.year === 0 ? '-' : runner.year }}</div>
            <div class="p-3 md:col-span-2 md:px-4 text-center">{{ runner.club }}</div>
            <div class="p-3 md:px-4">{{ runner.city }}</div>
            <div class="p-3 md:px-4 text-center">{{ runner.resultsCount }}</div>
        </Link>
    </section>
</template>

<style scoped>

</style>