<script setup>
import { Head, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { NInput } from 'naive-ui'
import { ref, watch } from 'vue'
import ResultList from '@/Pages/Runners/partials/ResultList.vue'
import ChartIndex from '@/Pages/Runners/Charts/ChartIndex.vue'
import Pagination from '@/Components/Pagination.vue'
import { useI18n } from 'vue-i18n'

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
})

const search = ref(props.search)
const unmounted = ref(false)
const selectedTab = ref(t('runner.tabRaces'))

watch(search, (value) => {
    if (value === '' || value.length > 2) {
        searchRaces()
    }
})

const searchRaces = () => {
    router.reload({
        data: {
            query: search.value,
        },
        only: ['results'],
        preserveState: true,
    })
}

const selectTab = (tab) => {
    selectedTab.value = tab
}
</script>

<template>
    <Head :title="runner.last_name + ' ' + runner.first_name"/>

    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ runner.last_name }} {{ runner.first_name }}
            </h2>
        </template>
        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-center md:justify-start gap-4 mb-4">
                    <div class="p-4"
                         :class="{
                            'border-b-2 border-violet-600': selectedTab === $t('runner.tabRaces'),
                         }"
                         @click="selectTab($t('runner.tabRaces'))">{{ $t('runner.tabRaces') }}</div>
                    <div class="p-4"
                         :class="{
                            'border-b-2 border-violet-600': selectedTab === $t('runner.tabCharts'),
                         }"
                         @click="selectTab($t('runner.tabCharts'))">{{ $t('runner.tabCharts') }}</div>
                </div>
                <div v-show="selectedTab === $t('runner.tabRaces')" class="">
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
                            <ResultList :results="results" :runner="runner"/>
                            <section v-if="results.length === 0" class="p-4 text-center">
                                {{ $t('noResults') }}
                            </section>
                        </div>
                    </div>
                    <Pagination v-if="results.length" :pages="paginate.links" class="my-4" />
                </div>
                <div v-show="selectedTab === $t('runner.tabCharts')">
                    <ChartIndex :data="chartData" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>

</style>