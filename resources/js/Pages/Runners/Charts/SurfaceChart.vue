<script setup>
import { computed } from 'vue'
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

const surfaceChart = computed(() => {
    if (!props.data) {
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
    for (const [key, value] of Object.entries(props.data)) {
        data.labels.push(value.label)
        data.datasets[0].data.push(value.data)
        data.datasets[0].backgroundColor.push(value.color)
    }

    return data
})
</script>

<template>
    <div>
        <Doughnut :data="surfaceChart" :options="options" />
    </div>
</template>

<style scoped>

</style>