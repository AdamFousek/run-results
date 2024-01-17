<script setup>
import { ref, watch } from "vue";
import { Head, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { NButton, NIcon, NInput } from 'naive-ui';
import { DeleteFilled, DriveFolderUploadFilled } from '@vicons/material'
import ResultList from '@/Pages/Admin/Results/Partials/ResultList.vue'
import RaceInfo from '@/Components/Race/RaceInfo.vue'

defineProps({
    race: {
        type: Object,
        required: true,
    },
    results: {
        type: Array,
    },
})

const eraseModal = ref(false)

const openEraseModal = () => {
    eraseModal.value = true
}
</script>

<template>
    <Head :title="$t('head.admin.results')"/>

    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('head.admin.results') }} {{
                    race.name
                }}</h2>
        </template>

        <div class="md:py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-between align-top mb-4">
                    <RaceInfo :race="race" class="bg-white p-4 shadow-sm rounded-xl" />
                    <div class="my-4 flex justify-end items-start gap-4">
                        <NButton round type="success" @click="uploadResults">
                            <template #icon>
                                <NIcon>
                                    <DriveFolderUploadFilled/>
                                </NIcon>
                            </template>
                            {{ $t('admin.results.upload') }}
                        </NButton>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <ResultList :results="results"/>
                </div>

                <div class="my-4 flex justify-end items-center gap-4">
                    <NButton round type="error" @click="openEraseModal">
                        <template #icon>
                            <NIcon>
                                <DeleteFilled/>
                            </NIcon>
                        </template>
                        {{ $t('admin.results.erase') }}
                    </NButton>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>

</style>