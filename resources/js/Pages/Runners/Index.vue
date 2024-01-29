<script setup>
import { ref, watch } from "vue";
import { Head, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { NInput } from 'naive-ui';
import { useI18n } from 'vue-i18n'
import Pagination from '@/Components/Pagination.vue'
import RunnerList from '@/Pages/Runners/partials/RunnerList.vue'

const {t} = useI18n();

const props = defineProps({
    runners: {
        type: Array,
    },
    paginate: {
        type: Object,
        required: true,
    },
    search: {
        type: String,
        required: false,
        default: '',
    },
})

const search = ref(props.search)

watch(search, (value) => {
    if (value === '' || value.length > 2) {
        searchRunners()
    }
})

const searchRunners = () => {
    router.reload({
        data: {
            query: search.value,
            page: 1,
        },
        only: ['runners', 'paginate'],
        preserveState: true,
    })
}
</script>

<template>
    <Head :title="$t('head.runners')"/>

    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('head.runners') }}</h2>
        </template>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <div class="my-4 w-10/12 md:w-7/12 mx-auto">
                    <NInput type="text"
                            class="md:mx-4"
                            v-model:value="search"
                            :placeholder="$t('runner.search')"
                            clearable
                            round
                    />
                </div>
                <div class="bg-white overflow-x-auto shadow-sm sm:rounded-lg flex">
                    <div class="md:w-full flex-shrink-0">
                        <RunnerList :runners="runners" />
                        <section class="p-4 text-center" v-if="runners.length === 0">{{ $t('noResults') }}</section>

                        <Pagination v-if="runners.length" :pages="paginate.links" class="my-4"/>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>

</style>