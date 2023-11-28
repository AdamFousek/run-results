<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { NButton, NInput } from 'naive-ui';
import AppLayout from '@/Layouts/AppLayout.vue'

defineProps({
  status: {
    type: String,
  },
});

const form = useForm({
  email: '',
});

const submit = () => {
  form.post(route('password.email'));
};
</script>

<template>
  <AppLayout>
    <Head :title="$t('head.forgotPassword')"/>

    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('auth.login') }}</h2>
    </template>

    <div class="py-4 md:py-12">
      <div class="max-w-xl mx-auto sm:px-3 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 md:p-6">
          <div class="mb-4 text-sm text-gray-600">
            {{ $t('auth.forgotPasswordDesc') }}
          </div>

          <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
          </div>

          <form @submit.prevent="submit">
            <div>
              <InputLabel for="email" :value="$t('auth.email')"/>

              <NInput
                id="email"
                :input-props="{ type: 'email' }"
                :placeholder="$t('auth.email')"
                class="mt-1 block w-full"
                v-model:value="form.email"
                required
                autofocus
                autocomplete="username"
              />

              <InputError class="mt-2" :message="form.errors.email"/>
            </div>

            <div class="flex items-center justify-end mt-4">
              <NButton attr-type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                       type="primary" round>
                {{ $t('auth.sendEmailResetLink') }}
              </NButton>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
