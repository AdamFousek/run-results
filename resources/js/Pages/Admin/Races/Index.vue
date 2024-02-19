<script setup>
import { computed, ref, watch } from "vue";
import { Head, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { NButton, NIcon, NInput } from 'naive-ui';
import { PlusSharp } from '@vicons/material'
import MeilisearchPagination from '@/Components/MeilisearchPagination.vue'
import AdminRaceList from '@/Pages/Admin/Races/Partials/AdminRaceList.vue'

const props = defineProps({
    races: {
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
        searchRaces()
    }
})

const searchRaces = () => {
    searching.value = true
    router.reload({
        data: {
            query: search.value,
            page: 1,
        },
        preserveState: true,
        onFinish() {
            searching.value = false
        }
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
    <Head :title="$t('head.admin.races')"/>

    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('head.admin.races') }}</h2>
        </template>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <div class="flex justify-between items-center gap-4">
                    <NInput type="text"
                            class="my-4"
                            v-model:value="search"
                            :placeholder="$t('race.search')"
                            clearable
                            round
                    />
                    <NButton round type="success" @click="router.get(route('admin.races.create'))">
                        <template #icon>
                            <NIcon>
                                <PlusSharp/>
                            </NIcon>
                        </template>
                        {{ $t('admin.races.create') }}
                    </NButton>
                </div>
                <div class="bg-white overflow-x-auto shadow-sm sm:rounded-lg flex">
                    <div class="md:w-full flex-shrink-0">
                        <AdminRaceList :races="races" :sort="activeSort"/>
                        <section class="p-4 text-center" v-if="races.length === 0">{{ $t('noResults') }}</section>
                        <div class="flex justify-end px-4 border-t border-gray-200 p-4">{{ pagination }}</div>
                    </div>
                </div>
                <MeilisearchPagination v-if="races.length && !searching" :page="paginate.page"
                                       :per-page="paginate.limit" :total="paginate.total" :on-page="paginate.onPage"
                                       class="my-4"/>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>

</style>