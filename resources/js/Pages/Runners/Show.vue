<script setup>
import { Head, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AppLayout.vue'
import { NDataTable, NInput } from 'naive-ui'
import { ref, watch } from 'vue'
import { useI18n } from 'vue-i18n'
import ResultList from '@/Pages/Runners/partials/ResultList.vue'

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

                <div class="my-4 w-full md:w-7/12 mx-auto">
                    <NInput type="text"
                            class="mx-4"
                            v-model:value="search"
                            :placeholder="$t('race.search')"
                            clearable
                            round
                    />
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <ResultList :results="results"/>
                    <section v-if="results.length === 0" class="p-4 text-center">{{ $t('noResults') }}</section>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>