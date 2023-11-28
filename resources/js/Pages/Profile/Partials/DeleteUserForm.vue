<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';
import { NButton, NInput, NModal } from 'naive-ui';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);
const formError = ref('');

const form = useForm({
  password: '',
});

const confirmUserDeletion = () => {
  confirmingUserDeletion.value = true;

  nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
  form.delete(route('profile.destroy'), {
    preserveScroll: true,
    onSuccess: () => closeModal(),
    onError: () => {
      passwordInput.value.focus()
      formError.value = 'error'
    },
    onFinish: () => form.reset(),
  });
};

const closeModal = () => {
  confirmingUserDeletion.value = false;

  form.reset();
};
</script>

<template>
  <section class="space-y-6">
    <header>
      <h2 class="text-lg font-medium text-gray-900">{{ $t('profile.deleteAccount') }}</h2>

      <p class="mt-1 text-sm text-gray-600">
        {{ $t('profile.deleteDescription') }}
      </p>
    </header>

    <n-button @click="confirmUserDeletion" type="error" round>
      {{ $t('profile.deleteAccount') }}
    </n-button>

    <NModal v-model:show="confirmingUserDeletion">
      <div class="bg-white p-6">
        <h2 class="text-lg font-medium text-gray-900">
          {{ $t('profile.delete.confirmation') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
          {{ $t('profile.deleteDescription') }}
        </p>

        <div class="mt-6">
          <InputLabel for="password" value="Password" class="sr-only"/>

          <n-input
            id="password"
            ref="passwordInput"
            v-model:value="form.password"
            type="password"
            :placeholder="$t('profile.password')"
            :status="formError"
            @input="formError = ''"
            @keyup.enter="deleteUser"
          />

          <InputError :message="form.errors.password" class="mt-2"/>
        </div>

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
