<script setup>
import { Head, Link, usePage, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AppLayout.vue'
import { ref, watch } from 'vue'
import axios from 'axios'
import MyLink from '@/Components/MyLink.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'

defineProps({
    races: {
        type: Array,
    }
});

const search = ref('')
const runners = ref([])
const searchRace = ref([])
const loading = ref(false)
const focused = ref(false)

const searchRaces = () => {
    loading.value = true
    axios.post(route('api.search'), {
        'search': search.value,
        '_token': usePage().props.auth.token,
    }).then((response) => {
        runners.value = response.data.runners
        searchRace.value = response.data.race
        loading.value = false
    })
};

watch(search, (value) => {
    if (value === '' || value.length > 2) {
        searchRaces()
    }
})

const searchPage = () => {
    router.get(route('search.index', { search: search.value }));
}
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

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid md:grid-cols-4 gap-4">
                    <div class="md:col-span-3">
                        <div class="m-2 flex justify-center flex-wrap gap-4">
                            <div class="flex-1">
                                <input type="text"
                                       class="rounded-t w-full focus:border-gray-300 py-1 focus:ring-0 shadow-sm"
                                       :class="{'rounded-b': search.length < 3, 'focus:border-purple-300 focus:border-b-gray-300': search.length > 2}"
                                       v-model="search"
                                       @focusin="focused = true"
                                       @keyup.enter="searchPage"
                                       :placeholder="$t('search')"
                                />
                                <div class="w-full relative">
                                    <div v-show="focused && search.length > 2" class="absolute top-0 right-0 left-0 p-2 rounded-b-lg bg-white outline-2 border-purple-300 border-r border-l border-b">
                                        <div class="grid md:grid-cols-5 gap-14">
                                            <div class="md:col-span-3">
                                                <h3 class="text-lg border-b mb-2">{{ $t('searchTitles.races') }}</h3>
                                                <div v-if="searchRace.length" class="grid gap-2">
                                                    <MyLink v-for="race in searchRace" :href="route('races.show', { race: race.slug })" class="flex justify-between" :key="race.id">
                                                        <div>
                                                            {{ race.name }}
                                                            <span v-if="race.vintage" class="text-sm">{{ race.vintage }}. {{ $t('race.vintage') }}</span>
                                                        </div>
                                                        <div>{{ race.date }}</div>
                                                    </MyLink>
                                                </div>
                                                <div v-else>{{ $t('noResults') }}</div>
                                            </div>
                                            <div class="md:col-span-2">
                                                <h3 class="text-lg border-b mb-2">{{ $t('searchTitles.runners') }}</h3>
                                                <div v-if="runners.length" class="grid gap-2">
                                                    <MyLink v-for="runner in runners" class="" :href="route('runners.show', {runner: runner.id })" :key="runner.id">
                                                        {{ runner.fullName }} {{ runner.year }}
                                                    </MyLink>
                                                </div>
                                                <div v-else>{{ $t('noResults') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <PrimaryButton @click="searchPage" color="blue">{{ $t('search') }}</PrimaryButton>
                        </div>
                    </div>
                    <div class="md:col-span-1">
                        <div class="text-lg p-2 md:px-0 text-gray-900">{{ $t('news.races') }}</div>
                        <div class="grid gap-4 items-start">
                            <Link :href="route('races.show', { race: race.slug })" v-for="race in races" :key="race.id"
                                  class="p-2 bg-white overflow-hidden shadow-sm hover:shadow-lg sm:rounded-lg">
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
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>
</style>
