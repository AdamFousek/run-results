<script setup>
import { Head, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AppLayout.vue'
import { NIcon, NInput } from 'naive-ui'
import { ArrowBackOutlined } from '@vicons/material'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import route from 'ziggy-js'
import MyLink from '@/Components/MyLink.vue'
import { ref } from 'vue'
import MeilisearchPagination from '@/Components/MeilisearchPagination.vue'

const props = defineProps({
    race: {
        type: Object,
        required: true,
    },
    head: {
        type: Object,
        required: true,
    },
    title: {
        type: String,
        required: true,
    },
    runners: {
        type: Array,
    },
    breadcrumb: {
        type: Array,
    },
    isParticipiant: {
        type: Boolean,
        required: false,
        default: false,
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

const search = ref(props.search);
const searching = ref(false);

const searchRaces = () => {
    searching.value = true
    const data = {
        query: search.value,
    }

    router.replace(route(route().current(), { race: props.race.slug }),{
        data: {
            ...data,
        },
        only: ['runners', 'paginate', 'search'],
        preserveState: true,
        replace: true,
        preserveScroll: true,
        onFinish() {
            searching.value = false
        }
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
            <div class="flex justify-between">
                <h1 class="font-semibold text-lg md:text-2xl text-gray-800 leading-tight">
                    <span v-if="race.date">{{ race.date }}&nbsp;-&nbsp;</span>
                    <span v-if="race.vintage">{{ race.vintage }}.&nbsp;{{ $t('race.vintage')}}&nbsp;-&nbsp;</span>
                    <span>{{ race.name }}</span>
                    <span>&nbsp;-&nbsp;{{ title }}</span>
                </h1>
                <div class="">
                    <PrimaryButton :href="route('races.stats', { race: race.slug })" link color="blue" outline rounded class="flex items-center gap-3">
                        <NIcon>
                            <ArrowBackOutlined />
                        </NIcon>
                        <span class="hidden md:block">{{ $t('back') }}</span>
                    </PrimaryButton>
                </div>
            </div>
        </template>
        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-start mb-4">
                    <NInput type="text"
                            v-model:value="search"
                            :placeholder="$t('runner.search')"
                            clearable
                            round
                            @input="searchRaces"
                    />
                </div>
                <div v-if="!searching" class="grid grid-cols-1 justify-between flex-wrap bg-white rounded-lg shadow-2xl">
                    <div class="grid grid-cols-6 justify-between gap-4 border-b p-4 font-bold">
                        <div class="col-span-4 flex">
                            <span class="min-w-16">{{ $t('result.position') }}</span>
                            <span>{{ $t('runner.name') }}</span>
                        </div>
                        <div v-if="!isParticipiant" class="col-span-2 flex justify-end gap-2">{{ $t('result.time') }} ({{ $t('result.yearOfRun') }})</div>
                        <div v-else class="col-span-2 text-right">{{ $t('result.countParticipiant') }}</div>
                    </div>
                    <div v-for="(runner, index) in runners"
                         class="grid grid-cols-6 justify-between gap-4 px-4 py-2 hover:bg-gray-100"
                         :key="runner.name"
                         :class="{
                              'bg-gray-50': index%2 === 0,
                          }"
                    >
                        <div class="col-span-4 flex">
                            <span class="min-w-16">{{ runner.position }}</span>
                            <MyLink :href="route('runners.show', { runner: runner.runnerId })">{{ runner.name }} ({{ runner.runnerYear }})</MyLink>
                        </div>
                        <div v-if="!isParticipiant" class="col-span-2 flex justify-end gap-2"><span>{{ runner.time }}</span><span v-if="runner.year">({{ runner.year }})</span></div>
                        <div v-else class="col-span-2 text-right">{{ runner.participiantCount }}</div>
                    </div>
                    <MeilisearchPagination v-if="!searching && runners.length" :page="paginate.page" :per-page="paginate.limit" :total="paginate.total" :on-page="paginate.onPage" :ulr-params="{race: race.slug}" class="my-4"/>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>