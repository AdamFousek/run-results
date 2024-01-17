<script setup>
import { EditFilled, DeleteFilled } from '@vicons/material'

defineProps({
    results: {
        type: Array,
    }
})
</script>

<template>
    <div class="relative w-full overflow-x-scroll lg:overflow-x-hidden">
        <div class="grid grid-cols-10 gap-2 md:gap-4 border-b min-w-800 text-center">
            <div class="col-span-2 font-bold p-2 text-left">{{ $t('runner.name') }}</div>
            <div class="font-bold p-2">{{ $t('result.position') }}</div>
            <div class="font-bold p-2">{{ $t('result.startingNumber') }}</div>
            <div class="col-span-2 font-bold p-2">{{ $t('result.time') }}</div>
            <div class="font-bold p-2">{{ $t('result.category') }}</div>
            <div class="font-bold p-2">{{ $t('result.categoryPosition') }}</div>
            <div class="font-bold p-2">{{ $t('result.DNF') }}</div>
            <div class="font-bold p-2 text-center">{{ $t('actions') }}</div>
        </div>
        <div v-for="(result, index) in results" :key="result.id" class="grid grid-cols-10 gap-2 md:gap-4 hover:bg-gray-100 min-w-800 text-center"
             :class="{ 'bg-gray-50': index%2 === 0}">
            <div class="col-span-2 font-bold p-2 text-left">{{ result.lastName }} {{ result.name }}</div>
            <div class="p-2">{{ result.position }}</div>
            <div class="p-2">{{ result.startingNumber }}</div>
            <div class="col-span-2 p-2">{{ result.time }}</div>
            <div class="p-2">{{ result.category }}</div>
            <div class="p-2">{{ result.categoryPosition }}</div>
            <div class="p-2">{{ result.DNF ? $t('yes') : $t('no') }}</div>
            <div class="p-2 flex justify-center align-middle gap-2">
                <button class="text-purple-900 hover:text-purple-500">
                    <EditFilled class="w-6" />
                </button>
                <button class="text-red-800 hover:text-red-500">
                    <DeleteFilled class="w-6" />
                </button>
            </div>
        </div>
    </div>
</template>

<style scoped>
.min-w-800 {
    min-width: 800px;
}
</style>