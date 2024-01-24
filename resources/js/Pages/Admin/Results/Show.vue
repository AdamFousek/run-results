<script setup>
import { ref } from "vue";
import { Head, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { NButton, NIcon, NModal, NCard } from 'naive-ui';
import { DeleteFilled, DriveFolderUploadFilled, PlusFilled, CloseSharp } from '@vicons/material'
import ResultList from '@/Pages/Admin/Results/Partials/ResultList.vue'
import RaceInfo from '@/Components/Race/RaceInfo.vue'
import CreateForm from '@/Pages/Admin/Results/Partials/ResultForm.vue'

const props = defineProps({
    race: {
        type: Object,
        required: true,
    },
    results: {
        type: Array,
    },
})

const eraseModal = ref(false)
const openModalSingleResult = ref(false)
const uploadResultsModal = ref(false)

const uploadForm = useForm({
    results: null,
})

const openEraseModal = () => {
    eraseModal.value = true
}

const uploadResults = () => {
    uploadResultsModal.value = true
}

const addSingleResult = () => {
    openModalSingleResult.value = true
}

function upload() {
    uploadForm.post(route('admin.results.upload', { race: props.race.id }))
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
                <div class="flex flex-col m-2 md:mx-0 md:flex-row justify-between align-top mb-4">
                    <RaceInfo :race="race" class="bg-white p-4 shadow-sm rounded-xl" />
                    <div class="my-4 flex flex-col justify-start gap-4">
                        <NButton round type="info" @click="uploadResults">
                            <template #icon>
                                <NIcon>
                                    <DriveFolderUploadFilled/>
                                </NIcon>
                            </template>
                            {{ $t('admin.results.upload') }}
                        </NButton>
                        <NButton round type="success" @click="addSingleResult">
                            <template #icon>
                                <NIcon>
                                    <PlusFilled />
                                </NIcon>
                            </template>
                            {{ $t('admin.results.createSingle') }}
                        </NButton>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <ResultList :race="race" :results="results"/>
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
        <NModal v-model:show="openModalSingleResult" size="huge">
            <NCard
                    class="max-w-3xl bg-white overflow-hidden shadow-sm sm:rounded-lg"
                    :title="$t('admin.results.createSingle')"
                    :bordered="false"
                    aria-modal="true"
            >
                <template #header-extra>
                    <div class="w-8 hover:text-gray-500 hover:cursor-pointer" @click="openModalSingleResult = false">
                        <CloseSharp />
                    </div>
                </template>
                <CreateForm :race="race" @submitted="openModalSingleResult = false" />
            </NCard>
        </NModal>
        <NModal v-model:show="uploadResultsModal" size="huge">
            <NCard
                    class="max-w-3xl bg-white overflow-hidden shadow-sm sm:rounded-lg"
                    :title="$t('admin.results.upload')"
                    :bordered="false"
                    aria-modal="true"
            >
                <template #header-extra>
                    <div class="w-8 hover:text-gray-500 hover:cursor-pointer" @click="uploadResultsModal = false">
                        <CloseSharp />
                    </div>
                </template>
                <form @submit.prevent="upload">
                    <div class="mt-3">
                        <input id="files" type="file" @input="uploadForm.results = $event.target.files[0]" />
                        <progress v-if="uploadForm.progress" :value="uploadForm.progress.percentage" max="100">
                            {{ uploadForm.progress.percentage }}%
                        </progress>
                    </div>
                    <div class="mt-3 flex justify-end">
                        <NButton attr-type="submit" :class="{ 'opacity-25': uploadForm.processing }" type="info" round
                                 :disabled="uploadForm.processing">
                            {{ $t('admin.results.upload') }}
                        </NButton>
                    </div>
                </form>
            </NCard>
        </NModal>
    </AdminLayout>
</template>

<style scoped>

</style>