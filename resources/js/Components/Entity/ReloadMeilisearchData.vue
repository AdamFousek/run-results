<script setup>
import axios from 'axios'
import {usePage} from '@inertiajs/vue3'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import {NAlert} from 'naive-ui'
import { ref } from 'vue'

const props = defineProps({
    entity: {
        type: String,
        required: true,
    },
    entityId: {
        type: Number,
        required: true,
    },
})

const meilisearchResult = ref('')
const meilisearchResultMessage = ref('')

const reloadMeilisearch = () => {
    axios.post(route('api.meilisearch.reloadEntity'), {
        'entity': props.entity,
        'entityId': props.entityId,
        '_token': usePage().props.auth.token,
    }).then((response) => {
        meilisearchResult.value = response.data.result ? 'success' : 'error'
        meilisearchResultMessage.value = response.data.message
    })
}
</script>

<template>
    <section class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 md:p-6 space-y-6">
        <div class="flex justify-between gap-4 items-center mb-2">
            <h2 class="text-lg font-medium text-gray-900">{{ $t('admin.races.reloadData') }}</h2>
            <PrimaryButton @click="reloadMeilisearch" color="yellow" rounded>
                {{ $t('admin.races.reloadData') }}
            </PrimaryButton>
        </div>
        <NAlert v-if="meilisearchResultMessage !== ''" :title="meilisearchResultMessage" :type="meilisearchResult" closable @close="meilisearchResultMessage = ''">
        </NAlert>
    </section>
</template>

<style scoped>

</style>