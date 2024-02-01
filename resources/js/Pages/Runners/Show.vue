<script setup>
import { Head, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AppLayout.vue'
import { NInput, NTabs, NTabPane } from 'naive-ui'
import { computed, ref, watch } from 'vue'
import { useI18n } from 'vue-i18n'
import ResultList from '@/Pages/Runners/partials/ResultList.vue'
import useIsMobile from '@/Comp/useIsMobile.js'
import ChartIndex from '@/Pages/Runners/Charts/ChartIndex.vue'

const {t} = useI18n();


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
    }
})

const search = ref(props.search)

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

const justifyContent = computed(() => {
    const {isMobile} = useIsMobile()
    if (isMobile.value) {
        return 'space-evenly'
    }

    return 'start'
})
</script>

<template>
    <Head :title="runner.last_name + ' ' + runner.first_name"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ runner.last_name }} {{
                    runner.first_name
                }}</h2>
        </template>
        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <NTabs :default-value="$t('runner.tabRaces')" :justify-content="justifyContent" type="line">
                    <NTabPane :name="$t('runner.tabRaces')" :tab="$t('runner.tabRaces')">
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
                                <section v-if="results.length === 0" class="p-4 text-center">{{
                                        $t('noResults')
                                    }}
                                </section>
                            </div>
                        </div>
                    </NTabPane>
                    <NTabPane :name="$t('runner.tabCharts')" :tab="$t('runner.tabCharts')">
                        <ChartIndex :data="chartData" />
                    </NTabPane>
                </NTabs>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>