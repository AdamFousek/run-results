<script setup>
import { Head, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AppLayout.vue'
import { NInput } from 'naive-ui'
import { ref, watch } from 'vue'
import { useI18n } from 'vue-i18n'
import Pagination from '@/Components/Pagination.vue'
import ChildRaceList from '@/Pages/Races/partials/ChildRaceList.vue'
import RaceInfo from '@/Components/Race/RaceInfo.vue'
import ResultList from '@/Pages/Races/partials/ResultList.vue'

const {t} = useI18n();


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
})

const search = ref(props.search)

watch(search, (value) => {
    if (value !== '') {
        searchRaces(value)
    } else {
        searchRaces('')
    }
})

const searchRaces = (searchTerm) => {
    router.reload({
        data: {
            query: searchTerm,
        },
        only: ['results'],
        preserveState: true,
    })
}
</script>

<template>
    <Head>
        <title>{{ head.title }}</title>
        <meta name="description" :content="head.description">
    </Head>

    <AuthenticatedLayout>
        <template #header>
            <h1 class="font-semibold text-xl md:text-2xl text-gray-800 leading-tight">{{ race.name }}</h1>
        </template>
        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <div class="grid grid-cols-1 md:grid-cols-4 justify-between gap-4 flex-wrap p-2">
                    <div class="bg-white col-span-1 md:col-span-3 p-4 shadow-sm rounded-xl self-start trix-content"
                         v-html="race.description">
                    </div>
                    <RaceInfo :race="race" class="bg-white p-4 shadow-sm rounded-xl" />
                </div>

                <section v-if="!race.isParent">
                    <div class="my-4 w-8/12 md:w-7/12 mx-auto">
                        <NInput type="text"
                                v-model:value="search"
                                :placeholder="$t('runner.search')"
                                clearable
                                round
                        />
                    </div>
                    <div class="bg-white overflow-x-auto shadow-sm sm:rounded-lg flex">
                        <div class="md:w-full flex-shrink-0">
                            <ResultList :results="results" :selected-runner="selectedRunner" />
                            <section class="p-4 text-center" v-if="results.length === 0">{{ $t('noResults') }}</section>

                            <Pagination v-if="results.length" :pages="paginate.links" class="my-4"/>
                        </div>
                    </div>
                </section>
                <ChildRaceList v-if="childRaces.length > 0" :races="childRaces" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>