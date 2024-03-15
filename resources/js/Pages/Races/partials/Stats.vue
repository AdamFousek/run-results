<script setup lang="ts">
import MyLink from "@/Components/MyLink.vue";

const props = withDefaults(defineProps<{
    raceSlug?: string,
    stats: {
        fastestTime: {
            runnerId: Number,
            name: String,
            time: String,
            year: Number,
        },
        fastestMan: {
            runnerId: Number,
            name: String,
            time: String,
            year: Number,
        },
        fastestWoman: {
            runnerId: Number,
            name: String,
            time: String,
            year: Number,
        },
        averageTime: {
            time: String,
        },
    },
}>(),
{
    raceSlug: '',
});

</script>

<template>
    <section class="bg-white text-sm col-span-1 md:col-span-3 lg:col-span-1 p-4 shadow-sm rounded-xl self-start">
        <h2 class="text-xl">{{ $t('race.stats.title') }}</h2>
        <div v-if="stats.fastestTime" class="flex flex-col my-2 gap-2 justify-start">
            <div class="font-bold">{{ $t('race.stats.fastestTime') }}:</div>
            <div class="flex justify-between">
                <MyLink :href="route('runners.show', { runner: stats.fastestTime.runnerId })">{{ stats.fastestTime.name }}</MyLink>
                <span>{{ stats.fastestTime.time }} ({{ stats.fastestTime.year }})</span>
            </div>
        </div>
        <div v-if="stats.fastestMan" class="flex flex-col my-2 gap-2 justify-start">
            <div class="font-bold">{{ $t('race.stats.fastestMan') }}:</div>
            <div class="flex justify-between">
                <MyLink :href="route('runners.show', { runner: stats.fastestMan.runnerId })">{{ stats.fastestMan.name }}</MyLink>
                <span>{{ stats.fastestMan.time }} ({{ stats.fastestMan.year }})</span>
            </div>
        </div>
        <div v-if="stats.fastestWoman" class="flex flex-col my-2 gap-2 justify-start">
            <div class="font-bold">{{ $t('race.stats.fastestWoman') }}:</div>
            <div class="flex justify-between">
                <MyLink :href="route('runners.show', { runner: stats.fastestWoman.runnerId })">{{ stats.fastestWoman.name }}</MyLink>
                <span>{{ stats.fastestWoman.time }} ({{ stats.fastestWoman.year }})</span>
            </div>
        </div>
        <div v-if="stats.averageTime" class="flex flex-col my-2 gap-2 justify-start">
            <div class="font-bold">{{ $t('race.stats.averageTime') }}:</div>
            <div class="">{{ stats.averageTime.time }}</div>
        </div>
        <div v-if="raceSlug !== ''" class="flex justify-end">
            <MyLink :href="route('races.stats', { race: raceSlug })" class="text-base">
                {{ $t('race.stats.showAll') }}
            </MyLink>
        </div>
    </section>
</template>

<style scoped>

</style>