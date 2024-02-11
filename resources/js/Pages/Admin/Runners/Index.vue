<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { NButton, NInput, NIcon } from 'naive-ui'
import Pagination from '@/Components/Pagination.vue'
import { PlusSharp } from '@vicons/material'
import { useI18n } from 'vue-i18n'
import { computed, ref, watch } from 'vue'
import RunnerList from '@/Pages/Admin/Runners/Partials/RunnerList.vue'

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

const searchRunners = (options) => {
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
    <Head :title="$t('head.admin.runners')"/>
    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('head.admin.runners') }}</h2>
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

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="md:w-full flex-shrink-0">
                        <RunnerList :runners="runners" @sort-runners="searchRunners"/>
                        <section class="p-4 text-center" v-if="runners.length === 0">{{ $t('noResults') }}</section>
                        <div class="flex justify-end px-4">{{ pagination }}</div>

                        <Pagination v-if="runners.length" :pages="paginate.links" class="my-4"/>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>

</style>