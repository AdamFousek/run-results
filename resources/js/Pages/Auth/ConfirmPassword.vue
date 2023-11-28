<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { NButton, NInput } from 'naive-ui';
import AppLayout from '@/Layouts/AppLayout.vue'

const form = useForm({
  password: '',
});

const submit = () => {
  form.post(route('password.confirm'), {
    onFinish: () => form.reset(),
  });
};
</script>

<template>
  <AppLayout>
    <Head :title="$t('head.confirmPassword')"/>

    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('auth.confirmPassword') }}</h2>
    </template>

    <div class="py-4 md:py-12">
      <div class="max-w-xl mx-auto sm:px-3 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 md:p-6">

          <div class="mb-4 text-sm text-gray-600">
            {{ $t('auth.secureApp') }}
          </div>

          <form @submit.prevent="submit">
            <div>
              <InputLabel for="password" :value="$t('auth.password')"/>
              <NInput
                id="password"
                :input-props="{ type: 'password' }"
                :placeholder="$t('auth.password')"
                class="mt-1 block w-full"
                v-model="form.password"
                required
                autocomplete="current-password"
                autofocus
              />
              <InputError class="mt-2" :message="form.errors.password"/>
            </div>

            <div class="flex justify-end mt-4">
              <NButton attr-type="submit" class="ms-4" :class="{ 'opacity-25': form.processing }"
                       :disabled="form.processing" type="primary" round>
                {{ $t('auth.confirm') }}
              </NButton>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
