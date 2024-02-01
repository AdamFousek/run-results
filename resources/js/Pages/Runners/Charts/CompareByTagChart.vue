<script setup>
import { computed } from 'vue'
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend
} from 'chart.js'
import { Line } from 'vue-chartjs'

const options = {
    "responsive": true,
    "hoverMode": "index",
    "stacked": false,
    "title": null,
    "scales": {
        "yAxes": [{
            "type": "time",
            "display": true,
            "position": "bottom",
            "id": "xAxeTime",
            "scaleLabel": {
                "display": true,
                "labelString": "Temps",
                "fontColor": "black"
            },
            "time": {
                "unit": "minute",
                // "parser": "moment.ISO_8601", -> remove this line
                "tooltipFormat": "ll"
            }
        }],
        "xAxes": [{
            "type": "linear",
            "display": true,
            "position": "left",
            "id": "yAxeTemperature",
            "scaleLabel": {
                "display": true,
                "labelString": "Température",
                "fontColor": "red"
            }
        }]
    }
}

const compareChart = computed(() => {
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
        <Line :data="compareChart" :options="options" />
    </div>
</template>

<style scoped>

</style>