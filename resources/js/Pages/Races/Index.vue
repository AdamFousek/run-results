<script setup>
import { onMounted, ref, watch } from "vue";
import { Head, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { NInput, NSelect } from 'naive-ui';
import { useI18n } from 'vue-i18n'
import Pagination from '@/Components/Pagination.vue'
import InputLabel from '@/Components/InputLabel.vue'
import RaceList from '@/Pages/Races/partials/RaceList.vue'

const {t} = useI18n();

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
    sortOptions: {
        type: Array,
    },
    activeSort: {
        type: String,
    }
})

const search = ref(props.search)
const sort = ref(props.activeSort)
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
    router.reload({
        data: {
            query: search.value,
            sort: sort.value,
            page: 1,
        },
        only: ['races', 'paginate'],
        preserveState: true,
    })
}
</script>

<template>
    <Head :title="$t('head.races')"/>

    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('head.races') }}</h2>
        </template>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <div class="m-2 flex justify-between flex-wrap">
                    <div class="md:my-3 w-full md:w-1/2">
                        <InputLabel for="username" :value="$t('race.search')"/>

                        <NInput type="text"
                                class=""
                                v-model:value="search"
                                :placeholder="$t('race.search')"
                                clearable
                                round
                        />
                    </div>

                    <div class="my-3 w-full md:w-1/6">
                        <InputLabel for="username" :value="$t('race.sort')"/>

                        <NSelect v-if="loaded" class="w-1/12" round :placeholder="$t('race.sort')" v-model:value="sort"
                                 :options="sortOptions"/>
                    </div>

                </div>
                <div class="bg-white overflow-x-auto shadow-sm sm:rounded-lg flex">
                    <div class="md:w-full flex-shrink-0">
                        <RaceList :races="races"/>
                        <section v-if="races.length === 0">{{ $t('noResults') }}</section>


                        <Pagination v-if="races.length" :pages="paginate.links" class="my-4"/>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>

</style>