<script setup>
import { computed, onMounted, ref, watch } from "vue";
import { Head, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { NInput } from 'naive-ui';
import RaceList from '@/Pages/Races/partials/RaceList.vue'
import MeilisearchPagination from '@/Components/MeilisearchPagination.vue'

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
const sort = ref(props.activeSort)
const searching = ref(false)
const loaded = ref(false)

watch(search, (value) => {
    if (value === '' || value.length > 2) {
        searchRaces()
    }
})

watch(sort, (value) => {
    if (value) {
        searchRaces()
    }
})

onMounted(() => {
    loaded.value = true
})

const searchRaces = () => {
    searching.value = true
    router.reload({
        data: {
            query: search.value,
            page: 1,
        },
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
    <Head :title="$t('head.races')"/>

    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('head.races') }}</h2>
        </template>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <div class="m-2 flex justify-center flex-wrap">
                    <div class="md:my-3 w-full md:w-1/2">
                        <NInput type="text"
                                class=""
                                v-model:value="search"
                                :placeholder="$t('race.search')"
                                clearable
                                round
                        />
                    </div>
                </div>
                <div class="bg-white overflow-x-auto shadow-sm sm:rounded-lg flex">
                    <div class="md:w-full flex-shrink-0">
                        <RaceList :races="races" :sort="activeSort" />
                        <section class="p-4 text-center" v-if="races.length === 0">{{ $t('noResults') }}</section>
                        <div class="flex justify-end px-4 border-t border-gray-200 p-4">{{ pagination }}</div>
                    </div>
                </div>
                <MeilisearchPagination v-if="races.length && !searching" :page="paginate.page" :per-page="paginate.limit" :total="paginate.total" :on-page="paginate.onPage" class="my-4"/>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>

</style>