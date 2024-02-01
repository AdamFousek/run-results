<script setup>
import DistanceChart from '@/Pages/Runners/Charts/DistanceChart.vue'
import RaceChart from '@/Pages/Runners/Charts/RaceChart.vue'

defineProps({
    data: {
        type: Object,
        required: true,
    },
})
</script>

<template>
    <div class="grid md:grid-cols-2 gap-4 md:gap-8">
        <div class="">
            <h2 class="text-center text-lg">{{ $t('runner.chart.distance') }}</h2>
            <DistanceChart v-if="data.distance" :data="data.distance" />
            <div v-else>{{ $t('runner.chart.notEnoughData') }}</div>
        </div>
        <div class="">
            <h2 class="text-center text-lg">{{ $t('runner.chart.races') }}</h2>
            <RaceChart v-if="data.races" :data="data.races" />
            <div v-else>{{ $t('runner.chart.notEnoughData') }}</div>
        </div>
        <div class="">
            <h2 class="text-center text-lg">{{ $t('runner.chart.surface') }}</h2>
            <DistanceChart v-if="data.surface" :data="data.surface" />
            <div v-else>{{ $t('runner.chart.notEnoughData') }}</div>
        </div>
    </div>
</template>

<style scoped>

</style>