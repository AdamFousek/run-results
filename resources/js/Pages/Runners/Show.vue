<script setup>
import { Head, router, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { NInput } from 'naive-ui'
import { ref, watch, defineAsyncComponent } from 'vue'
import ResultList from '@/Pages/Runners/partials/ResultList.vue'
import Pagination from '@/Components/Pagination.vue'
import { useI18n } from 'vue-i18n'
import MeilisearchPagination from '@/Components/MeilisearchPagination.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'

const { t } = useI18n();

const props = defineProps({
    runner: {
        type: Object,
        required: true,
    },
    results: {
        type: Array,
    },
    search: {
        type: String,
        required: false,
        default: '',
    },
    chartData: {
        type: Object,
    },
    paginate: {
        type: Object,
        required: true,
    },
    sort: {
        type: String,
        required: false,
    },
    head: {
        type: Object,
        required: true,
    },
})

const search = ref(props.search)
const searching = ref(false)
const selectedTab = ref(t('runner.tabRaces'))

const ChartIndex = defineAsyncComponent(() => import('@/Pages/Runners/Charts/ChartIndex.vue'))

watch(search, (value) => {
    if (value === '' || value.length > 2) {
        searchRaces()
    }
})

const searchRaces = () => {
    searching.value = true
    router.reload({
        data: {
            query: search.value,
        },
        only: ['results', 'paginate', 'ziggy', 'sort'],
        preserveState: true,
        onFinish() {
            searching.value = false
        },
    })
}

const selectTab = (tab) => {
    selectedTab.value = tab
}

const isAdmin = usePage().props?.auth?.isAdmin ?? false
</script>

<template>
    <Head>
        <title>{{ head.title }}</title>
        <meta name="description" :content="head.description">
        <link rel="canonical" :href="head.canonical">
    </Head>

    <AppLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ runner.last_name }} {{ runner.first_name }} - {{ runner.year }}
                </h1>
                <PrimaryButton v-if="isAdmin" :href="route('admin.runners.edit', {runner: runner.id})" link rounded outline color="blue">{{ $t('admin.races.showRace') }}</PrimaryButton>
            </div>
        </template>
        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-center md:justify-start gap-4 mb-4">
                    <div class="p-4 cursor-pointer hover:border-b-2 border-gray-300"
                         :class="{
                            'border-b-2 border-violet-600': selectedTab === $t('runner.tabRaces'),
                         }"
                         @click="selectTab($t('runner.tabRaces'))">{{ $t('runner.tabRaces') }}</div>
                    <div class="p-4 cursor-pointer hover:border-b-2 border-gray-300"
                         :class="{
                            'border-b-2 border-violet-600': selectedTab === $t('runner.tabCharts'),
                         }"
                         @click="selectTab($t('runner.tabCharts'))">{{ $t('runner.tabCharts') }}</div>
                </div>
                <div v-if="selectedTab === $t('runner.tabRaces')" class="">
                    <div class="my-2 md:my-4 w-7/12 mx-auto">
                        <NInput type="text"
                                class="mx-4"
                                v-model:value="search"
                                :placeholder="$t('race.search')"
                                clearable
                                round
                        />
                    </div>

                    <div class="bg-white overflow-x-auto shadow-sm sm:rounded-lg flex">
                        <div class="md:w-full flex-shrink-0">
                            <ResultList :results="results" :sort="sort" />
                            <section v-if="results.length === 0" class="p-4 text-center">
                                {{ $t('noResults') }}
                            </section>
                        </div>
                    </div>
                    <MeilisearchPagination v-if="!searching && results.length" :page="paginate.page" :per-page="paginate.limit" :total="paginate.total" :on-page="paginate.onPage" :ulr-params="{runner: runner.id}" class="my-4"/>
                </div>
                <div v-if="selectedTab === $t('runner.tabCharts')">
                    <ChartIndex :data="chartData" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>

</style>