<script setup>
import { Head, router, usePage } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AppLayout.vue'
import { NIcon, NInput, NCheckbox, NButton } from 'naive-ui'
import { ref, onMounted, watch } from 'vue'
import RaceInfo from '@/Components/Race/RaceInfo.vue'
import ResultList from '@/Pages/Races/partials/ResultList.vue'
import { CloudDownloadOutlined, MapOutlined } from '@vicons/material'
import MyLink from '@/Components/MyLink.vue'
import Stats from '@/Pages/Races/partials/Stats.vue'
import MeilisearchPagination from '@/Components/MeilisearchPagination.vue'
import Map from '@/Components/Map.vue'
import ChildRaceList from '@/Pages/Races/partials/ChildRaceList.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'

const props = defineProps({
    race: {
        type: Object,
        required: true,
    },
    results: {
        type: Array,
    },
    childRaces: {
        type: Array,
    },
    search: {
        type: String,
        required: false,
        default: '',
    },
    paginate: {
        type: Object,
        required: false,
    },
    head: {
        type: Object,
        required: true,
    },
    selectedRunner: {
        type: Number,
        required: false,
    },
    files: {
        type: Array,
    },
    filter: {
        type: Object,
    },
    stats: {
        type: Object,
    },
    topMen: {
        type: Array,
    },
    topWomen: {
        type: Array,
    },
    topParticipant: {
        type: Array,
    },
    categories: {
        type: Array,
    },
})

const search = ref(props.search)
const searching = ref(false)
const loading = ref(true)
const showFemale = ref(props.filter?.showFemale)
const showMale = ref(props.filter?.showMale)
const filterCategories = ref(props.filter?.categories)

watch([showFemale, showMale], () => {
    searchRaces()
})

onMounted(() => {
    loading.value = false
})

const searchRaces = () => {
    searching.value = true
    const data = {
        query: search.value,
        showFemale: showFemale.value,
        showMale: showMale.value,
        filterCategories: filterCategories.value,
    }

    router.replace(route(route().current(), { race: props.race.slug }),{
        data: {
            ...data,
        },
        only: ['results', 'paginate', 'filter', 'ziggy'],
        preserveState: true,
        replace: true,
        preserveScroll: true,
        onFinish() {
            searching.value = false
        }
    })
}

const selectCategory = (category) => {
    if (props.filter?.categories.includes(category)) {
        filterCategories.value = filterCategories.value.filter((c) => c !== category)
    } else {
        filterCategories.value.push(category)
    }

    searchRaces()
}

const isAdmin = usePage().props?.auth?.isAdmin ?? false
</script>

<template>
    <Head>
        <title>{{ head.title }}</title>
        <meta name="description" :content="head.description">
        <link rel="canonical" :href="head.canonical">
    </Head>

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h1 class="font-semibold text-lg md:text-2xl text-gray-800 leading-tight">
                    <span v-if="race.date">{{ race.date }}&nbsp;-&nbsp;</span>
                    <span v-if="race.vintage">{{ race.vintage }}.&nbsp;{{ $t('race.vintage')}}&nbsp;-&nbsp;</span>
                    <span>{{ race.name }}</span>
                </h1>
                <PrimaryButton v-if="isAdmin" :href="route('admin.races.edit', {race: race.id})" link rounded outline color="blue">{{ $t('admin.races.showRace') }}</PrimaryButton>
            </div>
        </template>
        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 justify-between gap-4 flex-wrap p-2">
                    <Stats v-if="stats" :stats="stats" :race-slug="race.slug" :showMore="true"  />
                    <RaceInfo :race="race" class="bg-white p-4 shadow-sm rounded-xl self-start" />
                    <div class="bg-white col-span-1 p-4 shadow-sm rounded-xl self-start">
                        <h2 class="text-xl mb-2">{{ $t('race.location') }}</h2>
                        <div class="">{{ race.location }}<span v-if="race.region">,&nbsp;{{ race.region }}</span></div>
                        <MyLink :href="`http://www.mapy.cz/?query=${race.latitude},${race.longitude}`" v-if="race.latitude || race.longitude" class="flex items-center gap-2 text-xs">
                            <NIcon>
                                <MapOutlined />
                            </NIcon>
                            {{ race.latitude }}, {{ race.longitude }}
                        </MyLink>
                        <div v-if="race.latitude || race.longitude" class="w-full mt-2">
                            <MyLink :href="`http://www.mapy.cz/?query=${race.latitude},${race.longitude}`" target="_blank" external>
                                <Map v-if="!loading" :name="race.name" :x="race.latitude" :y="race.longitude" />
                            </MyLink>
                        </div>
                    </div>
                    <div v-if="files.length" class="bg-white p-4 shadow-sm rounded-xl self-start">
                        <h2 class="text-xl mb-2">{{ $t('race.filesToDownload') }}</h2>
                        <div v-for="file in files" :key="file.name" class="mb-2">
                            <MyLink :href="file.url" download external class="text-base flex justify-between items-center">
                                {{ file.name }}
                                <NIcon class="text-2xl">
                                    <CloudDownloadOutlined />
                                </NIcon>
                            </MyLink>
                        </div>
                    </div>
                    <div v-if="!race.isParent && race.description.length" class="bg-white col-span-1 md:col-span-3 lg:col-span-1 p-4 shadow-sm rounded-xl self-start">
                        <h2 class="text-xl mb-2">{{ $t('race.description') }}</h2>
                        <div class="trix-content" v-html="race.description"></div>
                    </div>
                </div>


                <section v-if="!race.isParent">
                    <div class="my-4 grid grid-cols-1 md:grid-cols-6 gap-4 px-4">
                        <div class="flex-shrink-0 md:col-span-3">
                            <NInput type="text"
                                    v-model:value="search"
                                    :placeholder="$t('runner.search')"
                                    clearable
                                    round
                                    @input="searchRaces"
                            />
                        </div>
                        <div class="md:col-span-3 flex items-center justify-center md:justify-end">
                            <NCheckbox v-model:checked="showMale" @input="searchRaces">
                                {{ $t('result.filter.onlyMale') }}
                            </NCheckbox>
                            <NCheckbox v-model:checked="showFemale" @input="searchRaces">
                                {{ $t('result.filter.onlyFemale') }}
                            </NCheckbox>
                        </div>
                        <div class="md:col-span-6">
                            <div class="font-bold">{{ $t('result.filter.byCategory') }}</div>
                            <div class="flex justify-start gap-4 overflow-x-auto pb-2">
                                <NButton
                                    v-for="category in categories"
                                    :key="category"
                                    :type="filter?.categories.includes(category) ? 'success' : 'info'"
                                    @click="selectCategory(category)"
                                    secondary
                                    rounded>
                                    {{ category }}
                                </NButton>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white overflow-x-auto shadow-sm sm:rounded-lg flex">
                        <div class="md:w-full flex-shrink-0">
                            <ResultList :results="results" :selected-runner="selectedRunner" />
                            <section class="p-4 text-center" v-if="results.length === 0">{{ $t('noResults') }}</section>
                        </div>
                    </div>
                    <MeilisearchPagination v-if="!searching && results.length" :page="paginate.page" :per-page="paginate.limit" :total="paginate.total" :on-page="paginate.onPage" :ulr-params="{race: race.slug}" class="my-4"/>
                </section>
                <ChildRaceList v-if="childRaces.length > 0" :races="childRaces" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>