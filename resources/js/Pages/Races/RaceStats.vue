<script setup>
import { Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AppLayout.vue'
import { NIcon } from 'naive-ui'
import { ArrowBackOutlined } from '@vicons/material'
import Stats from '@/Pages/Races/partials/Stats.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TopRunners from '@/Pages/Races/partials/TopRunners.vue'
import Breadcrumb from '@/Components/Breadcrumb.vue'

const props = defineProps({
    race: {
        type: Object,
        required: true,
    },
    head: {
        type: Object,
        required: true,
    },
    stats: {
        type: Object,
    },
    topMen: {
        type: Array,
    },
    topWomen: {
        type: Array,
    },
    topParticipant: {
        type: Array,
    },
    breadcrumb: {
        type: Array,
    },
})
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
                    <span>&nbsp;-&nbsp;{{ $t('race.stats.title') }}</span>
                </h1>
                <div class="">
                    <PrimaryButton :href="route('races.show', { race: race.slug })" link color="blue" outline rounded class="flex items-center gap-3">
                        <NIcon>
                            <ArrowBackOutlined />
                        </NIcon>
                        <span class="hidden md:block">{{ $t('back') }}</span>
                    </PrimaryButton>
                </div>
            </div>
        </template>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-2">
            <Breadcrumb :breadcrumb="breadcrumb" />
        </div>
        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 justify-between gap-4 flex-wrap p-2">
                    <div class="md:col-span-3 grid md:grid-cols-2 gap-4">
                        <TopRunners :race-slug="race.slug" :runners="topMen" :title="$t('race.stats.topMen')" :is-top-men="true" />
                        <TopRunners :race-slug="race.slug" :runners="topWomen" :title="$t('race.stats.topWomen')" :is-top-women="true" />
                        <TopRunners :race-slug="race.slug" :runners="topParticipant" :title="$t('race.stats.topParticipant')" :is-participiant="true" />
                    </div>
                    <Stats v-if="stats" :stats="stats" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>