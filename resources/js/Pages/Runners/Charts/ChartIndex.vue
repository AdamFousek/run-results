<script setup>
import DistanceChart from '@/Pages/Runners/Charts/DistanceChart.vue'
import RaceChart from '@/Pages/Runners/Charts/RaceChart.vue'
import CompareByTagChart from '@/Pages/Runners/Charts/CompareByTagChart.vue'

defineProps({
    data: {
        type: Object,
        required: true,
    },
})
</script>

<template>
    <div class="grid md:grid-cols-3 gap-4 md:gap-8">
        <div class="">
            <h2 class="text-center text-lg">{{ $t('runner.chart.distance') }}</h2>
            <DistanceChart v-if="data.distance" :data="data.distance" />
            <div v-else class="text-center text-violet-600">{{ $t('runner.chart.notEnoughData') }}</div>
        </div>
        <div class="">
            <h2 class="text-center text-lg">{{ $t('runner.chart.races') }}</h2>
            <RaceChart v-if="data.races" :data="data.races" />
            <div v-else class="text-center text-violet-600">{{ $t('runner.chart.notEnoughData') }}</div>
        </div>
        <div class="">
            <h2 class="text-center text-lg">{{ $t('runner.chart.surface') }}</h2>
            <DistanceChart v-if="data.surface" :data="data.surface" />
            <div v-else class="text-center text-violet-600">{{ $t('runner.chart.notEnoughData') }}</div>
        </div>
        <div class="md:col-span-3">
            <h2 class="text-center text-lg">{{ $t('runner.chart.compare') }}</h2>
            <CompareByTagChart v-if="data.compare.lenght" :data="data.compare" />
            <div v-else class="text-center text-violet-600">{{ $t('runner.chart.notEnoughData') }}</div>
        </div>
    </div>
</template>

<style scoped>

</style>