<script setup>
import { computed, ref } from 'vue'
import { Chart as ChartJS, ArcElement, Tooltip, Legend } from 'chart.js'
import { Doughnut } from 'vue-chartjs'

const props = defineProps({
    data: {
        type: Object,
        required: true,
    },
})

ChartJS.register(ArcElement, Tooltip, Legend)

const options = {
    responsive: true,
    maintainAspectRatio: false
}

const racesChart = computed(() => {
    if (!props.data.races) {
        return null
    }

    const data = {
        labels: [],
        datasets: [
            {
                backgroundColor: [],
                data: [],
            },
        ],
    }
    for (const [key, value] of Object.entries(props.data.races)) {
        data.labels.push(value.label)
        data.datasets[0].data.push(value.data)
        data.datasets[0].backgroundColor.push(value.color)
    }

    return data
})


const distanceChart = computed(() => {
    if (!props.data.distance) {
        return null
    }

    const data = {
        labels: [],
        datasets: [
            {
                backgroundColor: [],
                data: [],
            },
        ],
    }
    for (const [key, value] of Object.entries(props.data.distance)) {
        data.labels.push(value.label)
        data.datasets[0].data.push(value.data)
        data.datasets[0].backgroundColor.push(value.color)
    }

    return data
})
</script>

<template>
    <div class="grid md:grid-cols-2 gap-4">
        <div class="">
            <h2 class="text-center text-lg">{{ $t('runner.chart.distance') }}</h2>
            <div v-if="distanceChart">
                <Doughnut :data="distanceChart" :options="options" />
            </div>
            <div v-else>{{ $t('runner.chart.notEnoughData') }}</div>
        </div>
        <div class="">
            <h2 class="text-center text-lg">{{ $t('runner.chart.races') }}</h2>
            <div v-if="racesChart">
                <Doughnut :data="racesChart" :options="options" />
            </div>
            <div v-else>{{ $t('runner.chart.notEnoughData') }}</div>
        </div>
    </div>
</template>

<style scoped>

</style>