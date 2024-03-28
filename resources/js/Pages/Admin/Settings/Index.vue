<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import axios from "axios";
import {usePage} from "@inertiajs/vue3";
import { NAlert } from 'naive-ui'
import { ref } from 'vue'

const result = ref('')
const message = ref('')

const reloadRunners = () => {
    sendRequest(route('api.settings.reloadRunners'))
}

const reloadRaces = () => {
    sendRequest(route('api.settings.reloadRaces'))
}

const reloadResults = () => {
    sendRequest(route('api.settings.reloadResults'))
}

const sendRequest = (route) => {
    axios.post(route, {
        '_token': usePage().props.auth.token,
    }).then((response) => {
        result.value = response.data.result ? 'success' : 'error'
        message.value = response.data.message
    })
}
</script>

<template>
    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('admin.settings.title') }}</h2>
        </template>
        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <NAlert v-if="message !== ''" :title="message" :type="result" closable @close="message = ''">
                </NAlert>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white p-4 rounded-lg mt-2">
                    <div class="text-lg mb-2">{{ $t('admin.settings.reloadRunners') }}</div>
                    <PrimaryButton @click="reloadRunners" color="blue">{{ $t('admin.settings.refreshDataCta') }}</PrimaryButton>
                </div>
                <div class="bg-white p-4 rounded-lg mt-2">
                    <div class="text-lg mb-2">{{ $t('admin.settings.reloadRaces') }}</div>
                    <PrimaryButton @click="reloadRaces" color="blue">{{ $t('admin.settings.refreshDataCta') }}</PrimaryButton>
                </div>
                <div class="bg-white p-4 rounded-lg mt-2">
                    <div class="text-lg mb-2">{{ $t('admin.settings.reloadResults') }}</div>
                    <PrimaryButton @click="reloadResults" color="blue">{{ $t('admin.settings.refreshDataCta') }}</PrimaryButton>
                </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>

</style>