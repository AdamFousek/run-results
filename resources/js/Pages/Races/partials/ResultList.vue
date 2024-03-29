<script setup>
import { Link } from '@inertiajs/vue3'
import { NIcon } from 'naive-ui'
import { EmojiEventsOutlined } from '@vicons/material'

defineProps({
    selectedRunner: {
        type: Number,
        required: false,
    },
    results: {
        type: Array,
        required: true,
    },
})
</script>

<template>
    <section>
        <div class="grid grid-cols-9 gap-2 md:gap-4 border-b">
            <div class="font-bold p-3 md:px-4">{{ $t('result.position') }}</div>
            <div class="col-span-2 font-bold p-3 md:px-4">{{ $t('runner.name') }}</div>
            <div class="font-bold p-3 md:px-4">{{ $t('result.time') }}</div>
            <div class="font-bold p-3 md:px-4">{{ $t('result.category') }}</div>
            <div class="font-bold p-3 md:px-4">{{ $t('result.categoryPosition') }}</div>
            <div class="col-span-2 font-bold p-3 md:px-4">{{ $t('runner.club') }}</div>
            <div class="font-bold p-3 md:px-4">{{ $t('result.startingNumber') }}</div>
        </div>
        <Link v-for="(result, index) in results" :key="result.id"
              :href="route('runners.show', { runner: result.runner.id })"
              class="grid grid-cols-9 gap-2 md:gap-4 hover:bg-gray-100"
              :class="{
                  'bg-gray-50': index%2 === 0,
                  'bg-indigo-100 hover:bg-indigo-200': selectedRunner === result.runner.id,
                  'text-red-800': result.runner.gender === 'F',
              }"
        >
            <div class="p-3 md:px-4 flex flex-start items-center gap-2">
                <span>{{ result.position }}</span>
                <NIcon v-if="result.position > 0 && result.position < 4"
                       :class="{
                            'text-2xl text-gold-500': result.position === 1,
                            'text-xl text-silver-500': result.position === 2,
                            'text-lg text-bronze-500': result.position === 3,
                       }"
                >
                    <EmojiEventsOutlined />
                </NIcon>
            </div>
            <div class="col-span-2 p-3 md:px-4 flex flex-start items-center">{{ result.runner.lastName }} {{ result.runner.firstName }} ({{ result.runner.year }})</div>
            <div v-if="result.DNF" class="p-3 md:px-4 flex flex-start items-center">{{ $t('result.DNF') }}</div>
            <div v-else-if="result.DNS" class="p-3 md:px-4 flex flex-start items-center">{{ $t('result.DNS') }}</div>
            <div v-else class="p-3 md:px-4 flex flex-start items-center">{{ result.time }}</div>
            <div class="p-3 md:px-4 flex flex-start items-center">{{ result.category }}</div>
            <div class="p-3 md:px-4 flex flex-start items-center gap-2">
                <span>{{ result.categoryPosition }}</span>
                <NIcon v-if="result.categoryPosition > 0 && result.categoryPosition < 4"
                       :class="{
                            'text-base text-gold-500': result.categoryPosition === 1,
                            'text-base text-silver-500': result.categoryPosition === 2,
                            'text-base text-bronze-500': result.categoryPosition === 3,
                       }"
                >
                    <EmojiEventsOutlined />
                </NIcon>
            </div>
            <div class="col-span-2 p-3 md:px-4 flex flex-start items-center">{{ result.club }}</div>
            <div class="p-3 md:px-4 flex flex-start items-center">{{ result.startingNumber }}</div>
        </Link>
    </section>
</template>

<style scoped>

</style>