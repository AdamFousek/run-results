<script setup lang="ts">
import L from 'leaflet/dist/leaflet.js'
import {onMounted} from "vue";

const props = defineProps({
    x: {
        type: Number,
        required: true,
    },
    y: {
        type: Number,
        required: true,
    },
    zoom: {
        type: Number,
        required: false,
        default: 14,
    },
    name: {
        type: String,
        required: true,
    }
})

const mapyApi = import.meta.env.VITE_MAPY_CZ_API_KEY

onMounted(() => {
    const map = L.map(props.name, {
        zoomControl: false,
        dragging: false,
    }).setView([props.x, props.y], props.zoom);

    L.tileLayer(`https://api.mapy.cz/v1/maptiles/basic/256/{z}/{x}/{y}?apikey=${mapyApi}`, {
        minZoom: 0,
        maxZoom: 19,
        attribution: '<a href="https://api.mapy.cz/copyright" target="_blank">&copy; Seznam.cz a.s. a další</a>',
    }).addTo(map);

    const LogoControl = L.Control.extend({
        options: {
            position: 'bottomleft',
        },

        onAdd: function (map) {
            const container = L.DomUtil.create('div');
            const link = L.DomUtil.create('a', '', container);

            link.setAttribute('href', 'http://mapy.cz/');
            link.setAttribute('target', '_blank');
            link.innerHTML = '<img src="https://api.mapy.cz/img/api/logo.svg" />';
            L.DomEvent.disableClickPropagation(link);

            return container;
        },
    });

    L.marker([props.x, props.y]).addTo(map);

    new LogoControl().addTo(map);
})

</script>

<template>
    <div :id="name" class="maps-wrapper overflow-hidden"></div>
</template>

<style scoped>
.maps-wrapper {
    min-height: 200px;
}
</style>