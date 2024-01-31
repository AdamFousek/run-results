<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AppLayout.vue'

defineProps({
    races: {
        type: Array,
    }
});
</script>

<template>
    <Head :title="$t('welcome')"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('welcome') }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-2 md:p-6 text-gray-900">{{ $t('news.races') }}</div>
                <div class="grid md:grid-cols-5 gap-4 items-start">
                    <Link :href="route('races.show', { race: race.slug })" v-for="race in races" :key="race.id"
                          class="p-4 bg-white overflow-hidden shadow-sm hover:shadow-lg sm:rounded-lg">
                        <h3 class="font-bold text-lg">{{ race.name }}</h3>
                        <div class="flex justify-between">
                            <div class="text-gray-600">{{ race.date }}</div>
                            <div class="text-gray-600">{{ race.location }}</div>
                        </div>
                    </Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>
</style>
