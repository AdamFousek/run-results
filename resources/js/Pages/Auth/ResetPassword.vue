<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { NButton, NInput } from 'naive-ui';
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
  email: {
    type: String,
    required: true,
  },
  token: {
    type: String,
    required: true,
  },
});

const form = useForm({
  token: props.token,
  email: props.email,
  password: '',
  password_confirmation: '',
});

const submit = () => {
  form.post(route('password.store'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  });
};
</script>

<template>
  <AppLayout>
    <Head :title="$t('head.resetPassword')"/>

    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('auth.confirmPassword') }}</h2>
    </template>

    <div class="py-4 md:py-12">
      <div class="max-w-xl mx-auto sm:px-3 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 md:p-6">

          <form @submit.prevent="submit">
            <div>
              <InputLabel for="email" :value="$t('auth.email')"/>

              <NInput
                id="email"
                :input-props="{ type: 'email' }"
                :placeholder="$t('auth.email')"
                class="mt-1 w-full"
                v-model:value="form.email"
                required
                autofocus
                autocomplete="email"
              />

              <InputError class="mt-2" :message="form.errors.email"/>
            </div>

            <div class="mt-4">
              <InputLabel for="password" :value="$t('auth.password')"/>

              <NInput
                id="password"
                :input-props="{ type: 'password' }"
                :placeholder="$t('auth.password')"
                class="mt-1 w-full"
                v-model:value="form.password"
                required
                autocomplete="new-password"
              />

              <InputError class="mt-2" :message="form.errors.password"/>
            </div>

            <div class="mt-4">
              <InputLabel for="password_confirmation" :value="$t('auth.confirmPassword')"/>

              <NInput
                id="password_confirmation"
                :input-props="{ type: 'password' }"
                :placeholder="$t('auth.confirmPassword')"
                class="mt-1 w-full"
                v-model:value="form.password_confirmation"
                required
                autocomplete="new-password"
              />

              <InputError class="mt-2" :message="form.errors.password_confirmation"/>
            </div>

            <div class="flex items-center justify-end mt-4">
              <NButton attr-type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                       type="primary" round>
                {{ $t('auth.resetPassword') }}
              </NButton>
            </div>
          </form>

        </div>
      </div>
    </div>
  </AppLayout>
</template>
