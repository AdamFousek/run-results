<script setup>
import {Link} from "@inertiajs/vue3";

defineProps({
    race: {
        type: Object,
        required: true,
    }
})
</script>

<template>
    <Link :href="route('races.show', { race: race.slug })"
          class="p-2 bg-white overflow-hidden shadow-sm hover:shadow-lg sm:rounded-lg">
        <h3 class="font-bold text-lg">{{ race.name }}</h3>
        <div class="flex justify-between">
            <div class="text-gray-600">{{ race.date }}</div>
            <div class="text-gray-600">{{ race.location }}</div>
        </div>
        <div v-if="race.region !== ''" class="flex justify-end">
            <div class="text-gray-600">{{ race.region }}</div>
        </div>
        <div class="flex justify-between">
            <div class="text-gray-600">{{ $t('race.runners') }}</div>
            <div class="text-gray-600">{{ race.resultsCount }}</div>
        </div>
    </Link>
</template>

<style scoped>

</style>