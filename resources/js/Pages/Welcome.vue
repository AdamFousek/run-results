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
    <Head>
        <title>{{ $t('welcome') }}</title>
        <meta name="description" :content="$t('webDescription')">
    </Head>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('welcome') }}</h2>
        </template>

        <div class="py-4 md:py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="text-lg p-2 md:px-0 md:py-6 text-gray-900">{{ $t('news.races') }}</div>
                <div class="grid md:grid-cols-5 gap-4 items-start">
                    <Link :href="route('races.show', { race: race.slug })" v-for="race in races" :key="race.id"
                          class="p-4 bg-white overflow-hidden shadow-sm hover:shadow-lg sm:rounded-lg">
                        <h3 class="font-bold text-lg">{{ race.name }}</h3>
                        <div class="flex justify-between">
                            <div class="text-gray-600">{{ race.date }}</div>
                            <div class="text-gray-600">{{ race.location }}</div>
                        </div>
                        <div v-if="race.region !== ''" class="flex justify-end">
                            <div class="text-gray-600">{{ race.region }}</div>
                        </div>
                        <div class="flex justify-between">
                            <div class="text-gray-600">{{ $t('race.runners') }}</div>
                            <div class="text-gray-600">{{ race.resultsCount }}</div>
                        </div>
                    </Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>
</style>
