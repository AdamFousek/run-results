<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {CloseSharp, DeleteFilled, LockOpenFilled, LockOutlined} from "@vicons/material";
import {NCard, NCheckbox, NIcon, NInput, NModal, NPopover, NPopconfirm} from "naive-ui";
import {ref} from "vue";
import {router, useForm} from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import MyLink from "@/Components/MyLink.vue";

const props = defineProps({
    race: {
        type: Object,
        required: true,
    },
    files: {
        type: Array
    },
});

const uploadFileModal = ref(false);

const uploadFileForm = useForm({
    name: '',
    file: null,
    isPublic: true,
});

const openUploadModal = () => {
    uploadFileModal.value = true;
};

const closeModal = () => {
    uploadFileModal.value = false;
};

const upload = () => {
    uploadFileForm.post(route('admin.races.fileUpload', {race: props.race.id}), {
        onSuccess: () => {
            closeModal()
            uploadFileForm.reset()
            router.reload()
        },
    })
}

const togglePublicity = (id) => {
    router.post(route('admin.uploadedFiles.togglePublicity', {uploadedFiles: id}))
    router.reload()
}

const removeFile = (id) => {
    router.delete(route('admin.uploadedFiles.destroy', {uploadedFiles: id}))
    router.reload()
}
</script>

<template>
    <section class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 md:p-6 space-y-6">
        <header>
            <div class="flex justify-between items-center mb-2">
                <h2 class="text-lg font-medium text-gray-900">{{ $t('admin.races.uploadedFiles') }}</h2>
                <PrimaryButton @click="openUploadModal" color="blue" rounded>
                    {{ $t('admin.races.uploadFile') }}
                </PrimaryButton>
            </div>


            <p class="mt-1 text-sm text-gray-600">
                {{ $t('admin.races.uploadedFilesDescription') }}
            </p>

            <div class="mt-3 grid gap-2">
                <div v-for="file in files" :key="file.name" class="flex justify-between items-center">
                    <MyLink :href="file.file_path" download external>{{ file.name }}</MyLink>
                    <div class="flex gap-4 text-2xl items-center">
                        <NPopover v-if="file.is_public" trigger="hover">
                            <template #trigger>
                                <NIcon class="text-indigo-500 hover:text-indigo-800 cursor-pointer" @click="togglePublicity(file.id)"> <LockOpenFilled /> </NIcon>
                            </template>
                            <span>{{ $t('admin.file.isPublic') }}</span>
                        </NPopover>
                        <NPopover v-else trigger="hover">
                            <template #trigger>
                                <NIcon class="text-indigo-500 hover:text-indigo-800 cursor-pointer" @click="togglePublicity(file.id)"> <LockOutlined /> </NIcon>
                            </template>
                            <span>{{ $t('admin.file.isNotPublic') }}</span>
                        </NPopover>
                        <NPopconfirm
                                @positive-click="removeFile(file.id)"
                                @negative-click="() => {}"
                                :show-icon="false"
                                :positive-text="$t('admin.file.delete')"
                                :negative-text="$t('admin.file.cancel')"
                                :positive-button-props="{ type: 'error', round: true }"
                                :negative-button-props="{ type: 'success', round: true }"
                        >
                            <template #trigger>
                                <NIcon class="text-red-600 hover:text-red-800 cursor-pointer">
                                    <DeleteFilled/>
                                </NIcon>
                            </template>
                            {{ $t('admin.file.deleteConfirmation') }}
                        </NPopconfirm>

                    </div>
                </div>
            </div>

            <NModal v-model:show="uploadFileModal">
                <NCard
                        class="max-w-3xl bg-white overflow-hidden shadow-sm sm:rounded-lg"
                        :title="$t('admin.races.uploadFile')"
                        :bordered="false"
                        aria-modal="true"
                >
                    <template #header-extra>
                        <div class="w-8 hover:text-gray-500 hover:cursor-pointer" @click="closeModal">
                            <CloseSharp/>
                        </div>
                    </template>
                    <form @submit.prevent="upload">
                        <div class="mt-3">
                            <InputLabel for="name" :value="$t('admin.file.name')"/>

                            <NInput
                                    :input-props="{ type: 'text', id: 'name', autofocus: true }"
                                    :placeholder="$t('admin.file.name')"
                                    class="mt-1 block w-full"
                                    v-model:value="uploadFileForm.name"
                                    required
                            />

                            <InputError class="mt-2" :message="uploadFileForm.errors.name"/>
                        </div>
                        <div class="flex">
                            <div class="mt-3">
                                <input id="files" type="file" @input="uploadFileForm.file = $event.target.files[0]"/>
                                <progress v-if="uploadFileForm.progress" :value="uploadFileForm.progress.percentage" max="100">
                                    {{ uploadFileForm.progress.percentage }}%
                                </progress>
                            </div>
                            <div class="flex gap-2 mt-3 items-center">
                                <NCheckbox
                                        :input-props="{ id: 'isPublic'}"
                                        :placeholder="$t('admin.file.isPublic')"
                                        class="mt-1 block w-full"
                                        v-model:checked="uploadFileForm.isPublic"
                                        required>
                                    <InputLabel for="isParent" :value="$t('admin.file.isPublic')" class="flex-shrink-0"/>
                                </NCheckbox>

                                <InputError class="mt-2" :message="uploadFileForm.errors.isPublic"/>
                            </div>
                        </div>
                        <div class="text-red-800">{{ uploadFileForm.errors.file }}</div>
                        <div class="mt-3 flex justify-end">
                            <PrimaryButton type="submit" color="blue" rounded
                                     :disabled="uploadFileForm.processing">
                                {{ $t('admin.races.uploadFile') }}
                            </PrimaryButton>
                        </div>
                    </form>
                </NCard>
            </NModal>
        </header>
    </section>
</template>

<style scoped>

</style>