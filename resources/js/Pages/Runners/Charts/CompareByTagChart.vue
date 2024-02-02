<script setup>
import { ref } from 'vue'
import { CategoryScale, Chart as ChartJS, Legend, LinearScale, LineElement, PointElement, TimeScale, Title, Tooltip } from 'chart.js'
import { Line } from 'vue-chartjs'
import { NButton } from 'naive-ui'

ChartJS.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend,
    TimeScale
)

const props = defineProps({
    data: {
        type: Object,
        required: true,
    }
})

const formatTime = (value) => {
    let seconds = parseInt((value / 1000) % 60);
    let minutes = parseInt((value / (1000 * 60)) % 60);
    const hours = parseInt((value / (1000 * 60 * 60)) % 24);
    if (seconds < 10) {
        seconds = `0${seconds}`
    }
    if (minutes < 10) {
        minutes = `0${minutes}`
    }

    return `${hours}:${minutes}:${seconds}`
}

const options = {
    responsive: true,
    hoverMode: "index",
    stacked: false,
    scales: {
        y: {
            display: true,
            type: 'linear',
            ticks: {
                callback: function(val) {
                    return formatTime(val)
                },
            }
        },
    },
    plugins: {
        tooltip: {
            callbacks: {
                label: function(context) {
                    const label = context.dataset.label || ''
                    const time = formatTime(context.parsed.y)

                    return `${label}: ${time}`;
                }
            }
        }
    }
}

const compareChart = ref(null)

const setData = (value) => {
    if (!value) {
        return null
    }

    console.log(value);
    const datasets = []
    for (const dataset of value) {
        datasets.push({
            label: dataset.label,
            data: dataset.data,
            backgroundColor: dataset.color,
            borderColor: dataset.color,
        })
    }

    compareChart.value = {
        datasets: datasets,
    }
}
</script>

<template>
    <div class="w-full max-h-screen mb-4">
        <div class="flex justify-start gap-4 m-2">
            <NButton v-for="(value, tag) of data" :key="tag" @click="setData(value)" type="info" secondary round>
                {{ tag }}
            </NButton>
        </div>
        <Line v-if="compareChart" :data="compareChart" :options="options" />
    </div>
</template>

<style scoped>

</style>