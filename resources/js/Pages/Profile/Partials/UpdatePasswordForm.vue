<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { NButton, NInput } from 'naive-ui';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);
const errors = ref({
  currentPassword: '',
  password: '',
  passwordConfirmation: '',
});

const form = useForm({
  current_password: '',
  password: '',
  password_confirmation: '',
});

const updatePassword = () => {
  form.put(route('password.update'), {
    preserveScroll: true,
    onSuccess: () => form.reset(),
    onError: () => {
      if (form.errors.password) {
        form.reset('password', 'password_confirmation');
        passwordInput.value.focus();
        errors.value.password = 'error'
      }
      if (form.errors.current_password) {
        form.reset('current_password');
        currentPasswordInput.value.focus();
        errors.value.currentPassword = 'error'
      }
    },
  });
};
</script>

<template>
  <section>
    <header>
      <h2 class="text-lg font-medium text-gray-900">{{ $t('profile.updatePassword') }}</h2>

      <p class="mt-1 text-sm text-gray-600">
        {{ $t('profile.updatePasswordDescription') }}
      </p>
    </header>

    <form @submit.prevent="updatePassword" class="mt-6 space-y-6">
      <div>
        <InputLabel for="current_password" :value="$t('profile.currentPassword')"/>

        <NInput
          :input-props="{ id: 'current_password', autocomplete: 'current-password' }"
          ref="currentPasswordInput"
          v-model:value="form.current_password"
          type="password"
          class="mt-1 block w-full"
          :status="errors.currentPassword"
          :placeholder="$t('profile.currentPassword')"
          @input="errors.currentPassword = ''"
        />

        <InputError :message="form.errors.current_password" class="mt-2"/>
      </div>

      <div>
        <InputLabel for="password" :value="$t('profile.newPassword')"/>

        <NInput
          :input-props="{ id: 'password', autocomplete: 'new-password' }"
          ref="passwordInput"
          v-model:value="form.password"
          type="password"
          class="mt-1 block w-full"
          :status="errors.password"
          :placeholder="$t('profile.newPassword')"
          @input="errors.password = ''"
        />

        <InputError :message="form.errors.password" class="mt-2"/>
      </div>

      <div>
        <InputLabel for="password_confirmation" :value="$t('profile.confirmPassword')"/>

        <NInput
          :input-props="{ id: 'password_confirmation' }"
          v-model:value="form.password_confirmation"
          type="password"
          class="mt-1 block w-full"
          autocomplete="new-password"
          :status="errors.passwordConfirmation"
          :placeholder="$t('profile.confirmPassword')"
          @input="errors.passwordConfirmation = ''"
        />

        <InputError :message="form.errors.password_confirmation" class="mt-2"/>
      </div>

      <div class="flex items-center gap-4">
        <NButton attr-type="submit" :disabled="form.processing" round type="primary">{{ $t('profile.save') }}</NButton>

        <Transition
          enter-active-class="transition ease-in-out"
          enter-from-class="opacity-0"
          leave-active-class="transition ease-in-out"
          leave-to-class="opacity-0"
        >
          <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">{{ $t('profile.saved') }}</p>
        </Transition>
      </div>
    </form>
  </section>
</template>
