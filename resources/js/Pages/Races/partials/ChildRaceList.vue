<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
    races: {
        type: Array,
        required: true,
    },
})
</script>

<template>
    <section class="p-2">
        <h2 class="text-xl mb-2">{{ $t('race.childRaces') }}</h2>
        <Link v-for="race in races" :key="race.id" :href="route('races.show', {race: race.slug})" class="block bg-white overflow-x-auto shadow-sm sm:rounded-lg my-2 md:my-4 p-2 md:px-4 hover:shadow-md">
            <h3 class="font-bold md:text-lg">{{ race.name }} <span v-if="race.vintage">- {{ race.vintage }}. {{ $t('race.vintage') }} </span></h3>
            <div class="text-gray-700">{{ race.date }} / {{ race.distance }}</div>
        </Link>
    </section>
</template>

<style scoped>

</style>