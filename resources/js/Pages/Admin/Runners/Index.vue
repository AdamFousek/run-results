<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { NButton, NInput, NIcon } from 'naive-ui'
import { PlusSharp } from '@vicons/material'
import { computed, ref, watch } from 'vue'
import RunnerList from '@/Pages/Admin/Runners/Partials/RunnerList.vue'
import MeilisearchPagination from '@/Components/MeilisearchPagination.vue'

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
    activeSort: {
        type: String,
    },
})

const search = ref(props.search)
const searching = ref(false)

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
        preserveState: false,
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
    <Head :title="$t('head.admin.runners')"/>
    <AdminLayout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('head.admin.runners') }}</h2>
                <NButton round type="warning" @click="router.get(route('admin.runners.duplicity'))">
                    {{ $t('admin.runner.duplicity') }}
                </NButton>
            </div>
        </template>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <div class="flex justify-between items-center gap-4">
                    <NInput type="text"
                            class="my-4 w-full md:w-7/12 mx-auto"
                            v-model:value="search"
                            :placeholder="$t('runner.search')"
                            clearable
                            round
                    />

                    <NButton round type="success" @click="router.get(route('admin.runners.create'))">
                        <template #icon>
                            <NIcon>
                                <PlusSharp/>
                            </NIcon>
                        </template>
                        {{ $t('admin.runner.create') }}
                    </NButton>
                </div>

                <div class="bg-white overflow-x-auto shadow-sm sm:rounded-lg flex">
                    <div class="md:w-full flex-shrink-0">
                        <RunnerList :runners="runners" :sort="activeSort" />
                        <section class="p-4 text-center" v-if="runners.length === 0">{{ $t('noResults') }}</section>
                        <div class="flex justify-end px-4 border-t border-gray-200 p-4">{{ pagination }}</div>
                    </div>
                </div>
                <MeilisearchPagination v-if="runners.length && !searching" :page="paginate.page"
                                       :per-page="paginate.limit" :total="paginate.total" :on-page="paginate.onPage"
                                       class="my-4"/>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>

</style>