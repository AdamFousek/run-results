<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';
import { NButton, NInput, NModal } from 'naive-ui';

const props = defineProps({
  runner: {
    type: Object,
  },
});

const confirmingUserDeletion = ref(false);
const formError = ref('');

const form = useForm({
  runnerId: props.runner.id,
});

const confirmUserDeletion = () => {
  confirmingUserDeletion.value = true;
};

const deleteUser = () => {
  form.delete(route('admin.runners.destroy', { runner: props.runner.id }), {
    preserveScroll: true,
    onSuccess: () => closeModal(),
    onError: () => {
      formError.value = 'error'
    },
    onFinish: () => form.reset(),
  });
};

const closeModal = () => {
  confirmingUserDeletion.value = false;
};
</script>

<template>
  <section class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 md:p-6 space-y-6">
    <header>
      <h2 class="text-lg font-medium text-gray-900">{{ $t('admin.runner.deleteRunner') }}</h2>

      <p class="mt-1 text-sm text-gray-600">
        {{ $t('admin.runner.deleteDescription') }}
      </p>
    </header>

    <n-button @click="confirmUserDeletion" type="error" round>
      {{ $t('admin.runner.delete') }}
    </n-button>

    <NModal v-model:show="confirmingUserDeletion">
      <div class="bg-white p-6">
        <h2 class="text-lg font-medium text-gray-900">
          {{ $t('admin.runner.deleteConfirmation') }}
        </h2>

        <p class="mt-1 text-sm text-red-500">
          {{ $t('admin.runner.deleteDescription') }}
        </p>

        <div class="mt-6 flex justify-end gap-3">
          <n-button @click="closeModal" secondary round> {{ $t('cancel') }}</n-button>

          <n-button @click="deleteUser" :class="{ 'opacity-25': form.processing }" type="error" round
                    :disabled="form.processing">
            {{ $t('profile.deleteAccount') }}
          </n-button>
        </div>
      </div>
    </NModal>
  </section>
</template>
