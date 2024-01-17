<script setup>
import { ref, watch } from "vue";
import { Head, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { NButton, NIcon, NInput } from 'naive-ui';
import { useI18n } from 'vue-i18n'
import Pagination from '@/Components/Pagination.vue'
import { PlusSharp } from '@vicons/material'
import RaceList from '@/Pages/Admin/Results/Partials/RaceList.vue'

const { t } = useI18n();

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
            page: 1,
        },
        only: ['races', 'paginate'],
        preserveState: true,
    })
}
</script>

<template>
    <Head :title="$t('head.admin.results')" />

    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('head.admin.results') }}</h2>
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
                    <NButton round type="success" @click="router.get(route('admin.results.create'))">
                        <template #icon>
                            <NIcon>
                                <PlusSharp />
                            </NIcon>
                        </template>
                        {{ $t('admin.results.create') }}
                    </NButton>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <RaceList :races="races" />

                    <Pagination v-if="races.length" :pages="paginate.links" class="my-4" />
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>

</style>