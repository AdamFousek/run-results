<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import route from 'ziggy-js'

const props = defineProps({
    runners: {
        type: Array,
    },
})

</script>

<template>
    <Head :title="$t('head.admin.runnersDuplicity')"/>
    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('head.admin.runnersDuplicity') }}</h2>
        </template>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <div class="bg-white overflow-x-auto shadow-sm sm:rounded-lg flex">
                    <div class="md:w-full flex-shrink-0">
                        <section>
                            <div class="grid grid-cols-2 gap-2 md:gap-4 border-b">
                                <div class="font-bold p-3 md:px-4">
                                    {{ $t('runner.name') }}
                                </div>
                                <div class="font-bold p-3 md:px-4">
                                    {{ $t('runner.year') }}
                                </div>
                            </div>
                            <Link v-for="(runner, index) in runners" :key="runner.id" :href="route('admin.runners.edit', { runner: runner.id })" class="grid grid-cols-2 gap-2 md:gap-4 hover:bg-gray-100"
                                  :class="{ 'bg-gray-50': index%2 === 0}">
                                <div class="p-3 md:px-4">{{ runner.lastName }} {{ runner.firstName }}</div>
                                <div class="p-3 md:px-4">{{ runner.year === 0 ? '-' : runner.year }}</div>
                            </Link>
                        </section>
                        <section class="p-4 text-center" v-if="runners.length === 0">{{ $t('noResults') }}</section>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>

</style>