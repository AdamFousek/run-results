<script setup lang="ts">
import PrimaryButton from "@/Components/PrimaryButton.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
</script>

<template>
    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('admin.settings.title') }}</h2>
        </template>
        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white p-4 rounded-lg">
                    <div class="text-lg">{{ $t('admin.settings.refreshData') }}</div>
                    <PrimaryButton>{{ $t('admin.settings.refreshDataCta') }}</PrimaryButton> - zatím nefunguje
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>

</style>