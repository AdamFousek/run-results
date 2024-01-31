<script setup>
import { Head } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { NCollapse, NCollapseItem } from 'naive-ui';
import { useI18n } from 'vue-i18n'

const { t } = useI18n();

const props = defineProps({
    page_views: {
        type: Object,
    },
    unique_visitors: {
        type: Object,
    },
})
</script>

<template>
    <Head :title="$t('head.admin.measurement')" />

    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('head.admin.measurement') }}</h2>
        </template>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 gap-4 items-start">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                        <NCollapse>
                            <NCollapseItem
                                    v-for="(pageView, date) of page_views"
                                    :key="date"
                                    :title="date"
                                    :name="date"
                                    accordion
                                    :class="{ 'bg-red-100 p-2': date === new Date() }"
                            >
                                <div v-for="view in pageView" :key="view.view" class="px-3 mb-2 hover:bg-gray-100">
                                    {{ view.page }}: {{ view.views }}
                                </div>
                            </NCollapseItem>
                        </NCollapse>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                        <NCollapse>
                            <NCollapseItem
                                    v-for="(visitors, date) of unique_visitors"
                                    :key="date"
                                    :title="date"
                                    :name="date"
                                    accordion
                                    :class="{ 'bg-red-100 p-2': date === new Date() }"
                            >
                                <div v-for="visitor in visitors" :key="visitor.date" class="px-3 mb-2 hover:bg-gray-100">
                                    {{ visitor.unique_visitors }}
                                </div>
                            </NCollapseItem>
                        </NCollapse>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>

</style>