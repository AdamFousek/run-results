<script setup>
import { NCollapse, NCollapseItem } from 'naive-ui';

defineProps({
    uploads: {
        type: Array,
    },
})
</script>

<template>
    <NCollapse>
        <NCollapseItem
                v-for="(upload, index) in uploads"
                :key="upload.id"
                :title="upload.created_at"
                :name="upload.id"
                accordion
                :class="{ 'bg-red-100 p-2': index === 0 && upload.failed_rows }"
        >
            <div v-for="row in upload.rows" class="grid grid-cols-6">
                <div class="">{{ $t('admin.results.rowNumber') }}: {{ row.row_number }}</div>
                <div class="col-span-3">{{ row.data }}</div>
                <div class="col-span-2">{{ row.error }}</div>
            </div>
            <template #header-extra>
                <span v-if="index === 0 && upload.failed_rows > 0">{{ $t('admin.results.failedRows') }}: {{ upload.failed_rows }} / {{ upload.total_rows }}</span>
                <span v-else>{{ $t('admin.results.processedRows') }}: {{ upload.processed_rows }} / {{ upload.total_rows }}</span>
            </template>
        </NCollapseItem>
    </NCollapse>
</template>

<style scoped>

</style>