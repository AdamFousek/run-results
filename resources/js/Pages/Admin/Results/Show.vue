<script setup>
import { computed, onBeforeUnmount, ref } from "vue";
import { Head, useForm, router, usePage } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { NButton, NIcon, NModal, NCard, NBadge } from 'naive-ui';
import { DeleteFilled, DriveFolderUploadFilled, PlusFilled, CloseSharp, RemoveRedEyeOutlined } from '@vicons/material'
import ResultList from '@/Pages/Admin/Results/Partials/ResultList.vue'
import RaceInfo from '@/Components/Race/RaceInfo.vue'
import CreateForm from '@/Pages/Admin/Results/Partials/ResultForm.vue'
import MyLink from '@/Components/MyLink.vue'
import UploadsList from '@/Pages/Admin/Results/Partials/UploadsList.vue'
import axios from 'axios'
import ReloadMeilisearchData from '@/Components/Entity/ReloadMeilisearchData.vue'

const props = defineProps({
    race: {
        type: Object,
        required: true,
    },
    results: {
        type: Array,
    },
    uploads: {
        type: Array,
    },
})

const eraseModal = ref(false)
const openModalSingleResult = ref(false)
const uploadResultsModal = ref(false)
const uploadsLogModal = ref(false)

const uploadForm = useForm({
    results: null,
})

const deleteForm = useForm({})
const loading = ref(false)

const openEraseModal = () => {
    eraseModal.value = true
}

const uploadResults = () => {
    uploadResultsModal.value = true
}

const addSingleResult = () => {
    openModalSingleResult.value = true
}

let interval = null;

function upload() {
    uploadForm.post(route('admin.results.upload', {race: props.race.id}), {
        onSuccess: () => {
            uploadForm.reset()
            uploadResultsModal.value = false
            interval = setInterval(reloadPage, 2000)
        },
    })
}

const reloadPage = () => {
    console.log(props.uploads)
    loading.value = true
    router.reload({
        only: ['results', 'uploads'],
        onFinish() {
            loading.value = false
        }
    })
}

onBeforeUnmount(() => {
    clearInterval(interval)
})

const openUploadsLogModal = () => {
    uploadsLogModal.value = true
}

const deleteResults = () => {
    deleteForm.delete(route('admin.results.destroyAll', { race: props.race.id }), {
        onSuccess: () => {
            eraseModal.value = false
        },
    })
}

const failedRows = computed(() => {
    return loading.value ? 0 : props.uploads[0]?.failed_rows
})
</script>

<template>
    <Head :title="$t('head.admin.results')"/>

    <AdminLayout>
        <template #header>
            <div class="flex justify-between items-center gap-4">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $t('head.admin.results') }} {{ race.name }}
                </h2>
                <MyLink :href="route('races.show', { race: race.slug })" type="button" class="flex items-center gap-3">
                    <NIcon>
                        <RemoveRedEyeOutlined/>
                    </NIcon>
                    <span class="hidden md:block">{{ $t('admin.results.showRace') }}</span>
                </MyLink>
            </div>
        </template>

        <div class="md:py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex flex-col m-2 md:mx-0 md:flex-row justify-between align-top mb-4">
                    <RaceInfo :race="race" class="bg-white p-4 self-start shadow-sm rounded-xl"/>
                    <div class="my-4 flex flex-col justify-start gap-4">
                        <ReloadMeilisearchData class="self-start" :entity-id="race.id" entity="Race" />
                        <NButton class="self-end" round type="info" @click="uploadResults">
                            <template #icon>
                                <NIcon>
                                    <DriveFolderUploadFilled/>
                                </NIcon>
                            </template>
                            {{ $t('admin.results.upload') }}
                        </NButton>
                        <NButton class="self-end" round type="success" @click="addSingleResult">
                            <template #icon>
                                <NIcon>
                                    <PlusFilled/>
                                </NIcon>
                            </template>
                            {{ $t('admin.results.createSingle') }}
                        </NButton>
                        <NBadge class="self-end" :value="failedRows">
                            <NButton v-if="uploads.length" round type="warning" @click="openUploadsLogModal">
                                <template #icon>
                                    <NIcon>
                                        <RemoveRedEyeOutlined/>
                                    </NIcon>
                                </template>
                                {{ $t('admin.results.showResultsLog') }}
                            </NButton>
                        </NBadge>
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
        <NModal v-model:show="eraseModal" size="huge">
            <NCard
                    class="max-w-3xl bg-white overflow-hidden shadow-sm sm:rounded-lg"
                    :title="$t('admin.results.removeAllResults')"
                    :bordered="false"
                    aria-modal="true"
            >
                <template #header-extra>
                    <div class="w-8 hover:text-gray-500 hover:cursor-pointer" @click="eraseModal = false">
                        <CloseSharp/>
                    </div>
                </template>
                <NButton attr-type="submit" :class="{ 'opacity-25': deleteForm.processing }" @click="deleteResults" type="error" round
                         :disabled="deleteForm.processing">
                    {{ $t('admin.results.deleteAll') }}
                </NButton>
            </NCard>
        </NModal>
        <NModal v-model:show="openModalSingleResult" size="huge">
            <NCard
                    class="max-w-3xl bg-white overflow-hidden shadow-sm sm:rounded-lg"
                    :title="$t('admin.results.createSingle')"
                    :bordered="false"
                    aria-modal="true"
            >
                <template #header-extra>
                    <div class="w-8 hover:text-gray-500 hover:cursor-pointer" @click="openModalSingleResult = false">
                        <CloseSharp/>
                    </div>
                </template>
                <CreateForm :race="race" @submitted="openModalSingleResult = false"/>
            </NCard>
        </NModal>
        <NModal v-model:show="uploadsLogModal" size="huge">
            <NCard
                    class="max-w-3xl bg-white overflow-hidden shadow-sm sm:rounded-lg"
                    :title="$t('admin.results.uploads')"
                    :bordered="false"
                    aria-modal="true"
            >
                <template #header-extra>
                    <div class="w-8 hover:text-gray-500 hover:cursor-pointer" @click="uploadsLogModal = false">
                        <CloseSharp/>
                    </div>
                </template>
                <UploadsList :uploads="uploads"/>
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
                        <CloseSharp/>
                    </div>
                </template>
                <div class="text-red-800">{{ $t('admin.results.uploadWillRemoveAllResults') }}</div>
                <form @submit.prevent="upload">
                    <div class="mt-3">
                        <input id="files" type="file" @input="uploadForm.results = $event.target.files[0]"/>
                        <progress v-if="uploadForm.progress" :value="uploadForm.progress.percentage" max="100">
                            {{ uploadForm.progress.percentage }}%
                        </progress>
                    </div>
                    <div class="text-red-800">{{ uploadForm.errors.results }}</div>
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