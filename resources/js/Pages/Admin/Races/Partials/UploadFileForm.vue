<script setup lang="ts">

import PrimaryButton from "@/Components/PrimaryButton.vue";
</script>

<template>
    <section class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 md:p-6 space-y-6">
        <header>
            <h2 class="text-lg font-medium text-gray-900">{{ $t('admin.races.uploadedFiles') }}</h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ $t('admin.races.uploadedFilesDescription') }}
            </p>

            <PrimaryButton class="mt-3">
                {{ $t('admin.races.uploadFile') }}
            </PrimaryButton>
        </header>
    </section>
</template>

<style scoped>

</style>