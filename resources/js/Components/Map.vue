<script setup lang="ts">
import { LMap, LTileLayer, LControl, LMarker } from "@vue-leaflet/vue-leaflet";
import {onMounted, ref} from "vue";

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
        default: 10,
    },
    name: {
        type: String,
        required: true,
    }
})

const mapyApi = import.meta.env.VITE_MAPY_CZ_API_KEY
const defaultZoom = ref(props.zoom)

const tileLayer = ref({
    url: `https://api.mapy.cz/v1/maptiles/basic/256/{z}/{x}/{y}?apikey=${mapyApi}`,
    minZoom: 0,
    maxZoom: 19,
    attribution: '<a href="https://api.mapy.cz/copyright" target="_blank">&copy; Seznam.cz a.s. a další</a>',
})

const zoomIn = () => {
    defaultZoom.value = 12
}

const zoomOut = () => {
    defaultZoom.value = props.zoom
}
</script>

<template>
    <div class="maps-wrapper overflow-hidden border border-indigo-500 hover:border-indigo-800"
         @mouseenter="zoomIn"
         @mouseleave="zoomOut">
        <LMap :id="name"
              :options="{
                    zoomControl: false,
                    dragging: false,
                    boxZoom: false,
                    doubleClickZoom: false,
                    scrollWheelZoom: false,
                }"
              :use-global-leaflet="false"
              v-model:zoom="defaultZoom"
              :center="[x, y]"
              class="maps-wrapper overflow-hidden"
        >
            <LTileLayer
                    :url="tileLayer.url"
                    :min-zoom="tileLayer.minZoom"
                    :max-zoom="tileLayer.maxZoom"
                    :attribution="tileLayer.attribution"
                    layer-type="base"
                    name="mapy.cz"
            />
            <LControl :options="{position: 'bottomleft'}" disable-click-propagation>
                <div>
                    <a href="http://mapy.cz/" target="_blank" aria-label="Link to mapy.cz">
                        <img src="https://api.mapy.cz/img/api/logo.svg" alt="mapy.cz" width="93" height="28" />
                    </a>
                </div>
            </LControl>
            <LMarker :lat-lng="[x, y]">
            </LMarker>
        </LMap>
    </div>
</template>

<style scoped>
.maps-wrapper {
    min-height: 200px;
}
</style>