<script setup>
import { NButton } from 'naive-ui'
import { Link } from '@inertiajs/vue3'

defineProps({
    race: {
        type: Object,
        required: true,
    },
})
</script>

<template>
    <section>
        <h2 class="text-xl">{{ $t('race.information') }}</h2>
        <div v-if="race.date" class="flex my-2 gap-2 justify-start">
            <div class="font-bold">{{ $t('race.date') }}:</div>
            <div class="">{{ race.date }}</div>
        </div>
        <div v-if="race.time" class="flex my-2 gap-2 justify-start">
            <div class="font-bold">{{ $t('race.time') }}:</div>
            <div class="">{{ race.time }}</div>
        </div>
        <div v-if="race.distance" class="flex my-2 gap-2 justify-start">
            <div class="font-bold">{{ $t('race.distance') }}:</div>
            <div class="">{{ race.distance }}</div>
        </div>
        <div v-if="race.surface" class="flex my-2 gap-2 justify-start">
            <div class="font-bold">{{ $t('race.surface') }}:</div>
            <div class="">{{ race.surface }}</div>
        </div>
        <div v-if="!race.isParent" class="flex my-2 gap-2 justify-start">
            <div class="font-bold">{{ $t('race.runners') }}:</div>
            <div class="">{{ race.runners }}</div>
        </div>
        <div v-if="race.isParent" class="flex my-2 gap-2 justify-start">
            <div class="font-bold">{{ $t('race.races') }}:</div>
            <div class="">{{ race.races }}</div>
        </div>
        <div v-if="race.parentName" class="my-2">
            <div class="font-bold">{{ $t('race.parentRace') }}:</div>
            <Link :href="route('races.show', { race: race.parentSlug })">
                <NButton size="small" secondary round type="info">
                    {{ race.parentName }}
                </NButton>
            </Link>
        </div>
    </section>
</template>

<style scoped>

</style>