<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AppLayout.vue";
import {Head} from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";

defineProps({
    returnLink: {
        type: String,
        required: false,
        default: '/'
    }
});
</script>

<template>
    <Head :title="$t('errors.401.title')" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('errors.401.title') }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 flex justify-center items-center flex-col gap-4">
                    <div>{{ $t('errors.401.description') }}</div>
                    <PrimaryButton :href="returnLink" link>{{ $t('back') }}</PrimaryButton>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>