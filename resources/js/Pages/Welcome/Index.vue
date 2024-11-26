<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AppLayout.vue'
import { ref, watch } from 'vue'
import axios from 'axios'
import MyLink from '@/Components/MyLink.vue'
import RaceItem from '@/Pages/Welcome/Partials/RaceItem.vue'
import ArticleItem from '@/Pages/Welcome/Partials/ArticleItem.vue'

defineProps({
    races: {
        type: Array,
    },
    articles: {
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
    axios.post(route('search'), {
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
                        <div class="flex justify-center flex-wrap">
                            <div class="w-full">
                                <input type="text"
                                       class="rounded-t-lg w-full focus:border-gray-300 py-1 focus:ring-0 shadow-sm"
                                       :class="{'rounded-b-lg': search.length < 3, 'focus:border-purple-300 focus:border-b-gray-300': search.length > 2}"
                                       v-model="search"
                                       @focusin="focused = true"
                                       :placeholder="$t('search')"
                                />
                                <div class="w-full relative">
                                    <div v-show="focused && search.length > 2" class="absolute top-0 right-0 left-0 p-2 rounded-b-lg bg-white outline-2 border-purple-300 border-r border-l border-b">
                                        <div class="grid md:grid-cols-5 gap-4">
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

                        </div>

                        <section id="articles" class="pt-4">
                            <h3 class="text-2xl p-4 md:px-0 text-gray-900">{{ $t('news.articles') }}</h3>
                            <div class="grid space-y-6">
                                <ArticleItem v-for="article in articles" :key="article.id" :article="article" />
                            </div>
                        </section>
                    </div>
                    <div class="md:col-span-1">
                        <h3 class="text-lg pb-2 md:px-0 text-gray-900">{{ $t('news.races') }}</h3>
                        <div class="grid  space-y-6">
                            <RaceItem v-for="race in races" :key="race.id" :race="race" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>
</style>
