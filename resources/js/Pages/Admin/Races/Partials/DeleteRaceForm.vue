<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { NButton, NModal } from 'naive-ui';

const props = defineProps({
    race: {
        type: Object,
    },
});

const confirmingRaceDeletion = ref(false);
const formError = ref('');

const form = useForm({
    raceId: props.race.id,
});

const confirmRaceDeletion = () => {
    confirmingRaceDeletion.value = true;
};

const deleteUser = () => {
    form.delete(route('admin.races.destroy', { race: props.race.id }), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => {
            formError.value = 'error'
        },
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingRaceDeletion.value = false;
};
</script>

<template>
    <section class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 md:p-6 space-y-6">
        <header>
            <h2 class="text-lg font-medium text-gray-900">{{ $t('admin.races.deleteRace') }}</h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ $t('admin.races.deleteDescription') }}
            </p>
        </header>

        <NButton @click="confirmRaceDeletion" type="error" round>
            {{ $t('admin.races.delete') }}
        </NButton>

        <NModal v-model:show="confirmingRaceDeletion">
            <div class="bg-white p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    {{ $t('admin.races.deleteConfirmation') }}
                </h2>

                <p class="mt-1 text-sm text-red-500">
                    {{ $t('admin.races.deleteDescription') }}
                </p>

                <div class="mt-6 flex justify-end gap-3">
                    <NButton @click="closeModal" secondary round> {{ $t('cancel') }}</NButton>

                    <NButton @click="deleteUser" :class="{ 'opacity-25': form.processing }" type="error" round
                              :disabled="form.processing">
                        {{ $t('admin.races.delete') }}
                    </NButton>
                </div>
            </div>
        </NModal>
    </section>
</template>
