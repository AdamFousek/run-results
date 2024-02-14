<script setup lang="ts">
import {computed, type PropType, ref, watch} from "vue";
import { Head, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { NInput } from 'naive-ui';
import RunnerList from '../Runners/partials/RunnerList.vue'
import MeilisearchPagination from '@/Components/MeilisearchPagination.vue'
import type Runner from "@/Models/List/Runner";

const props = defineProps({
    runners: {
        type: Array as PropType<Runner[]>,
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

const pagination = computed(() => {
    if (props.paginate.total < props.paginate.limit) {
        return `${props.paginate.total} / ${props.paginate.total}`
    }

    return `${props.paginate.limit} / ${props.paginate.total}`
})
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
                        <div class="flex justify-end px-4 border-t border-gray-200 p-4">{{ pagination }}</div>
                    </div>
                </div>
                <MeilisearchPagination v-if="runners.length" :page="paginate.page" :per-page="paginate.limit" :total="paginate.total" :on-page="paginate.onPage" class="my-4"/>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>

</style>