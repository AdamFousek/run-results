<script setup>
defineProps({
    races: {
        type: Array,
        required: true,
    },
})
</script>

<template>
    <section>
        <div class="grid grid-cols-6 gap-2 md:gap-4 border-b">
            <div class="font-bold col-span-2 p-3 md:px-4">{{ $t('race.name') }}</div>
            <div class="font-bold p-3 md:px-4">{{ $t('race.date') }}</div>
            <div class="font-bold p-3 md:px-4">{{ $t('race.location') }}</div>
            <div class="font-bold p-3 md:px-4">{{ $t('race.distance') }}</div>
            <div class="font-bold p-3 md:px-4">{{ $t('race.surface') }}</div>
        </div>
        <a v-for="(race, index) in races" :key="race.id" :href="route('races.show', { race: race.slug })" class="grid grid-cols-6 gap-2 md:gap-4 hover:bg-gray-100"
             :class="{ 'bg-gray-50': index%2 === 0}">
            <div class="col-span-2 p-3 md:px-4">{{ race.name }}</div>
            <div class="p-3 md:px-4">{{ race.date }}</div>
            <div class="p-3 md:px-4">{{ race.location }}</div>
            <div class="p-3 md:px-4">{{ race.distance }}</div>
            <div class="p-3 md:px-4">{{ race.surface }}</div>
        </a>
    </section>
</template>

<style scoped>

</style>