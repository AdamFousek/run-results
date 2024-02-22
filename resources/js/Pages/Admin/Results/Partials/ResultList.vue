<script setup>
import { EditFilled, DeleteFilled, CloseSharp } from '@vicons/material'
import { NButton, NCard, NModal } from 'naive-ui'
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import CreateForm from '@/Pages/Admin/Results/Partials/ResultForm.vue'
import MyLink from '@/Components/MyLink.vue'

defineProps({
    race: {
        type: Object,
        required: true,
    },
    results: {
        type: Array,
    }
})

const removeResultModal = ref(false)
const updateResultModal = ref(false)
const selectedResult = ref(null)

const removeResultForm = useForm({});

const removeResult = () => {
    removeResultForm.delete(route('admin.results.destroy', { result: selectedResult.value.id }), {
        preserveScroll: false,
        onSuccess: () => {
            removeResultModal.value = false
            selectedResult.value = null
        },
        onError: (e) => {
            console.log(e)
        },
        onFinish: () => removeResultForm.reset(),
    });
};

const openRemoveResult = (result) => {
    selectedResult.value = result
    removeResultModal.value = true
}

const openUpdateResult = (result) => {
    selectedResult.value = result
    updateResultModal.value = true
}
</script>

<template>
    <div class="relative w-full overflow-x-scroll lg:overflow-x-hidden">
        <div class="grid grid-cols-12 gap-2 md:gap-4 border-b min-w-800 text-center">
            <div class="col-span-2 font-bold p-2 text-left">{{ $t('runner.name') }}</div>
            <div class="font-bold p-2">{{ $t('result.position') }}</div>
            <div class="font-bold p-2">{{ $t('result.startingNumber') }}</div>
            <div class="font-bold p-2">{{ $t('result.time') }}</div>
            <div class="font-bold p-2">{{ $t('result.category') }}</div>
            <div class="font-bold p-2">{{ $t('result.categoryPosition') }}</div>
            <div class="font-bold p-2">{{ $t('result.DNF') }}</div>
            <div class="font-bold p-2">{{ $t('result.DNS') }}</div>
            <div class="col-span-2 font-bold p-2">{{ $t('result.club') }}</div>
            <div class="font-bold p-2 text-center">{{ $t('actions') }}</div>
        </div>
        <div v-for="(result, index) in results" :key="result.id" class="grid grid-cols-12 gap-2 md:gap-4 hover:bg-gray-100 min-w-800 text-center"
             :class="{ 'bg-gray-50': index%2 === 0}">
            <MyLink :href="route('admin.runners.edit', {runner: result.runnerId })" class="col-span-2 font-bold p-2 text-left">{{ result.lastName }} {{ result.name }} <span v-if="result.gender !== ''" class="font-normal text-xs">({{ $t('genders.' + result.gender) }})</span></MyLink>
            <div class="p-2">{{ result.position }}</div>
            <div class="p-2">{{ result.startingNumber }}</div>
            <div class="p-2">{{ result.time }}</div>
            <div class="p-2">{{ result.category }}</div>
            <div class="p-2">{{ result.categoryPosition }}</div>
            <div class="p-2">{{ result.DNF ? $t('yes') : $t('no') }}</div>
            <div class="p-2">{{ result.DNS ? $t('yes') : $t('no') }}</div>
            <div class="col-span-2 p-2">{{ result.club }}</div>
            <div class="p-2 flex justify-center align-middle gap-2">
                <button class="text-purple-900 hover:text-purple-500">
                    <EditFilled class="w-6" @click="openUpdateResult(result)" />
                </button>
                <button class="text-red-800 hover:text-red-500">
                    <DeleteFilled class="w-6" @click="openRemoveResult(result)" />
                </button>
            </div>
        </div>
        <NModal v-model:show="updateResultModal">
            <NCard
                    class="max-w-3xl bg-white overflow-hidden shadow-sm sm:rounded-lg"
                    :title="$t('admin.results.createSingle')"
                    :bordered="false"
                    aria-modal="true"
            >
                <template #header-extra>
                    <div class="w-8 hover:text-gray-500 hover:cursor-pointer" @click="updateResultModal = false">
                        <CloseSharp />
                    </div>
                </template>
                <CreateForm :race="race" :result="selectedResult" @submitted="updateResultModal = false" />
            </NCard>
        </NModal>
        <NModal v-model:show="removeResultModal">
            <div class="bg-white p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    {{ $t('admin.results.deleteConfirmation') }}
                </h2>

                <div class="mt-6 flex justify-end gap-3">
                    <NButton @click="removeResultModal = false" secondary round> {{ $t('cancel') }}</NButton>

                    <NButton @click="removeResult" :class="{ 'opacity-25': removeResultForm.processing }" type="error" round
                             :disabled="removeResultForm.processing">
                        {{ $t('admin.results.delete') }}
                    </NButton>
                </div>
            </div>
        </NModal>
    </div>
</template>

<style scoped>
.min-w-800 {
    min-width: 800px;
}
</style>