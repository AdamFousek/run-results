<script setup lang="ts">
import route from 'ziggy-js'
import MyLink from "@/Components/MyLink.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {computed} from "vue";

const props = withDefaults(defineProps<{
    raceSlug?: string,
    title: string,
    runners: {
        position: number,
        runnerId: number,
        name: string,
        time: string,
        year: number,
        participantCount: number,
    }[],
    isTopWomen?: boolean,
    isTopMen?: boolean,
    isParticipiant?: boolean,
}>(), {
    isTopWomen: false,
    isTopMen: false,
    isParticipiant: false,
});

const showMoreLink = computed(() => {
    if (props.isTopMen) {
        return route('races.stats.topMen', { race: props.raceSlug })
    }

    if (props.isTopWomen) {
        return route('races.stats.topWomen', { race: props.raceSlug })
    }

    return route('races.stats.topParticipant', { race: props.raceSlug })
})
</script>

<template>
    <section class="bg-white p-4 shadow-sm rounded-xl self-start">
        <h2 class="text-xl mb-2">{{ title }}</h2>
        <div class="grid grid-cols-6 justify-between gap-4 font-bold mb-2">
            <div class="col-span-1">{{ $t('result.position') }}</div>
            <div class="col-span-3">{{ $t('runner.name') }}</div>
            <div v-if="!isParticipiant" class="col-span-2 flex justify-end gap-2"><span>{{ $t('result.time') }}</span><span>({{ $t('result.yearOfRun') }})</span></div>
            <div v-else class="col-span-2 text-right">{{ $t('result.countParticipiant') }}</div>
        </div>
        <div v-for="(runner, index) in runners" class="grid grid-cols-6 justify-between gap-4  mb-1" :key="runner.name">
            <div v-if="!isParticipiant" class="col-span-1">{{ runner.position }}.</div>
            <div v-else class="col-span-1">{{ ++index }}.</div>
            <MyLink :href="route('runners.show', { runner: runner.runnerId })" class="col-span-3">{{ runner.name }}</MyLink>
            <div v-if="!isParticipiant" class="col-span-2 flex justify-end gap-2"><span>{{ runner.time }}</span><span v-if="runner.year">({{ runner.year }})</span></div>
            <div v-else class="col-span-2 text-right">{{ runner.participantCount }}</div>
        </div>
        <div v-if="!isParticipiant" class="flex justify-center mt-4">
            <PrimaryButton :href="showMoreLink" link color="blue" rounded class="flex items-center gap-3">
                <span>{{ $t('showAll') }}</span>
            </PrimaryButton>
        </div>
    </section>
</template>

<style scoped>

</style>