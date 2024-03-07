<script setup lang="ts">
import route from 'ziggy-js'
import MyLink from "@/Components/MyLink.vue";

const props = withDefaults(defineProps<{
    raceSlug?: string,
    title: string,
    runners: {
        runnerId: number,
        name: string,
        time: string,
        year: number,
        participiantCount: number,
    }[],
    isTopWomen?: boolean,
    isTopMen?: boolean,
    isParticipiant?: boolean,
}>(), {
    isTopWomen: false,
    isTopMen: false,
    isParticipiant: false,
});
</script>

<template>
    <section class="bg-white p-4 shadow-sm rounded-xl self-start">
        <h2 class="text-xl mb-2">{{ title }}</h2>
        <div class="flex justify-between gap-4 font-bold mb-2">
            <div class="">{{ $t('runner.name') }}</div>
            <div v-if="!isParticipiant"class="flex justify-end gap-4">{{ $t('result.time') }} ({{ $t('result.yearOfRun') }})</div>
            <div v-else class="flex justify-end gap-4">{{ $t('result.countParticipiant') }}</div>
        </div>
        <div v-for="runner in runners" class="flex justify-between gap-4 mb-1" :key="runner.name">
            <MyLink :href="route('runners.show', { runner: runner.runnerId })">{{ runner.name }}</MyLink>
            <div v-if="!isParticipiant" class="flex justify-end gap-4"><span>{{ runner.time }}</span><span v-if="runner.year">({{ runner.year }})</span></div>
            <div v-else class="flex justify-end gap-4">{{ runner.participiantCount }}</div>
        </div>
        <div class="flex justify-center">
            <MyLink v-if="isTopMen" :href="route('races.stats.topMen', { race: raceSlug })" type="linkSecondary">{{ $t('showMore') }}</MyLink>
            <MyLink v-if="isTopWomen" :href="route('races.stats.topWomen', { race: raceSlug })" type="linkSecondary">{{ $t('showMore') }}</MyLink>
            <MyLink v-if="isParticipiant" :href="route('races.stats.topParticipant', { race: raceSlug })" type="linkSecondary">{{ $t('showMore') }}</MyLink>
        </div>
    </section>
</template>

<style scoped>

</style>