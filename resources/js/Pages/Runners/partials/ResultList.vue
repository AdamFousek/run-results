<script setup>
import { Link } from '@inertiajs/vue3'
import { NIcon } from 'naive-ui'
import { EmojiEventsOutlined } from '@vicons/material'
import confetti from 'https://cdn.skypack.dev/canvas-confetti';

defineProps({
    runner: {
        type: Object,
        required: true,
    },
    results: {
        type: Array,
        required: true,
    },
})

const startConfetti = () => {
    confetti()
}
</script>

<template>
    <section>
        <div class="grid grid-cols-11 gap-2 md:gap-4 border-b">
            <div class="font-bold p-3 md:px-4">{{ $t('race.date') }}</div>
            <div class="col-span-3 font-bold p-3 md:px-4">{{ $t('race.name') }}</div>
            <div class="col-span-2 font-bold p-3 md:px-4">{{ $t('race.location') }}</div>
            <div class="font-bold p-3 md:px-4">{{ $t('race.distance') }}</div>
            <div class="font-bold p-3 md:px-4">{{ $t('result.time') }}</div>
            <div class="font-bold p-3 md:px-4">{{ $t('result.position') }}</div>
            <div class="col-span-2 font-bold p-3 md:px-4">{{ $t('result.categoryPosition') }}</div>
        </div>
        <Link v-for="(result, index) in results" :key="result.id" :href="route('races.show', { race: result.raceSlug, runnerId: runner.id })" class="grid grid-cols-11 gap-2 md:gap-4 hover:bg-gray-100"
              :class="{
                'bg-gray-50': index%2 === 0,
                }">
            <div class="p-3 md:px-4 text-center">{{ result.date }}</div>
            <div class="col-span-3 p-3 md:px-4">{{ result.name }}</div>
            <div class="col-span-2 p-3 md:px-4">{{ result.location }}</div>
            <div class="p-3 md:px-4">{{ result.distance }}</div>
            <div class="p-3 md:px-4">{{ result.time }}</div>
            <div class="p-3 md:px-4 text-center flex justify-center items-center gap-2">
                <NIcon v-if="result.position < 4"
                       :class="{
                            'text-2xl text-gold-500': result.position === 1,
                            'text-xl text-silver-500': result.position === 2,
                            'text-lg text-bronze-500': result.position === 3,
                       }"
                       @mouseenter="startConfetti"
                >
                    <EmojiEventsOutlined />
                </NIcon>
                <span>{{ result.position }}</span>
            </div>
            <div class="col-span-2 p-3 md:px-4">{{ result.category_position }} ({{ result.category }})</div>
        </Link>
    </section>
</template>

<style scoped>

</style>