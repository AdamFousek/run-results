<script setup lang="ts">
import { Link } from '@inertiajs/vue3'

defineProps({
    runners: {
        type: Array,
        required: true,
    },
    sort: {
        type: String
    }
})

const emits = defineEmits(['sortRunners'])
</script>

<template>
    <section>
        <div class="grid grid-cols-5 md:grid-cols-6 gap-2 md:gap-4 border-b">
            <div class="font-bold md:col-span-2 p-3 md:px-4 cursor-pointer"
                 :class="{ 'text-violet-800': sort === 'name' }"
                 @click="$emit('sortRunners', { sort: 'name' })">
                {{ $t('runner.name') }}
            </div>
            <div class="font-bold p-3 md:px-4 cursor-pointer"
                 :class="{ 'text-violet-800': sort === 'year' }"
                 @click="$emit('sortRunners', { sort: 'year' })">
                {{ $t('runner.year') }}
            </div>
            <div class="font-bold p-3 md:px-4 cursor-pointer"
                 :class="{ 'text-violet-800': sort === 'club' }"
                 @click="$emit('sortRunners', { sort: 'club' })">
                {{ $t('runner.club') }}
            </div>
            <div class="font-bold p-3 md:px-4 cursor-pointer"
                 :class="{ 'text-violet-800': sort === 'city' }"
                 @click="$emit('sortRunners', { sort: 'city' })">
                {{ $t('runner.city') }}
            </div>
            <div class="text-center font-bold p-3 md:px-4 cursor-pointer"
                 :class="{ 'text-violet-800': sort === 'results_count' }"
                 @click="$emit('sortRunners', { sort: 'results_count' })">
                {{ $t('runner.races') }}
            </div>
        </div>
        <Link v-for="(runner, index) in runners" :key="runner.id" :href="route('admin.runners.edit', { runner: runner.id })"
              class="grid grid-cols-5 md:grid-cols-6 gap-2 md:gap-4 hover:bg-gray-100"
              :class="{ 'bg-gray-50': index%2 === 0}">
            <div class="md:col-span-2 p-3 md:px-4">{{ runner.last_name }} {{ runner.first_name }}</div>
            <div class="p-3 md:px-4">{{ runner.year === 0 ? '-' : runner.year }}</div>
            <div class="p-3 md:px-4">{{ runner.club }}</div>
            <div class="p-3 md:px-4">{{ runner.city }}</div>
            <div class="text-center p-3 md:px-4">{{ runner.results_count }}</div>
        </Link>
    </section>
</template>

<style scoped>

</style>