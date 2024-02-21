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
        <div class="grid grid-cols-10 md:grid-cols-11 gap-2 md:gap-4 border-b">
            <div class="font-bold p-3 md:px-4">{{ $t('result.position') }}</div>
            <div class="col-span-2 md:col-span-3 font-bold p-3 md:px-4">{{ $t('runner.name') }}</div>
            <div class="col-span-2 font-bold p-3 md:px-4">{{ $t('result.time') }}</div>
            <div class="font-bold p-3 md:px-4">{{ $t('result.category') }}</div>
            <div class="font-bold p-3 md:px-4">{{ $t('result.categoryPosition') }}</div>
            <div class="col-span-2 font-bold p-3 md:px-4">{{ $t('runner.club') }}</div>
            <div class="font-bold p-3 md:px-4">{{ $t('result.startingNumber') }}</div>
        </div>
        <Link v-for="(result, index) in results" :key="result.id"
              :href="route('runners.show', { runner: result.runner_id })"
              class="grid grid-cols-10 md:grid-cols-11 gap-2 md:gap-4 hover:bg-gray-100"
              :class="{
                  'bg-gray-50': index%2 === 0,
                  'bg-indigo-100 hover:bg-indigo-200': selectedRunner === result.runner_id,
                  'text-red-800': result.gender === 'F',
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
            <div class="col-span-2 md:col-span-3 p-3 md:px-4 flex flex-start items-center">{{ result.last_name }} {{ result.first_name }} ({{ result.year }})</div>
            <div v-if="result.DNF" class="col-span-2 p-3 md:px-4 flex flex-start items-center">{{ $t('result.DNF') }}</div>
            <div v-else-if="result.DNS" class="col-span-2 p-3 md:px-4 flex flex-start items-center">{{ $t('result.DNS') }}</div>
            <div v-else class="col-span-2 p-3 md:px-4 flex flex-start items-center">{{ result.time }}</div>
            <div class="p-3 md:px-4 flex flex-start items-center">{{ result.category }}</div>
            <div class="p-3 md:px-4 flex flex-start items-center gap-2">
                <span>{{ result.category_position }}</span>
                <NIcon v-if="result.category_position > 0 && result.category_position < 4"
                       :class="{
                            'text-base text-gold-500': result.category_position === 1,
                            'text-base text-silver-500': result.category_position === 2,
                            'text-base text-bronze-500': result.category_position === 3,
                       }"
                >
                    <EmojiEventsOutlined />
                </NIcon>
            </div>
            <div class="col-span-2 p-3 md:px-4 flex flex-start items-center">{{ result.club }}</div>
            <div class="p-3 md:px-4 flex flex-start items-center">{{ result.starting_number }}</div>
        </Link>
    </section>
</template>

<style scoped>

</style>