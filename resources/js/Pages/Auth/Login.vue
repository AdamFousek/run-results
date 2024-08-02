<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { NButton, NInput, NCheckbox } from 'naive-ui';
import MyLink from '@/Components/MyLink.vue'
import AppLayout from '@/Layouts/AppLayout.vue'

defineProps({
  canResetPassword: {
    type: Boolean,
  },
  status: {
    type: String,
  },
});

const form = useForm({
  email: '',
  password: '',
  remember: false,
});

const submit = () => {
  form.post(route('login'), {
    onFinish: () => form.reset('password'),
  });
};
</script>

<template>
  <AppLayout>
    <Head :title="$t('head.login')"/>

    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('auth.login') }}</h2>
    </template>

    <div class="py-4 md:py-12">
      <div class="max-w-xl mx-auto sm:px-3 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 md:p-6">

          <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
          </div>

          <form @submit.prevent="submit">
            <div>
              <InputLabel for="username" :value="$t('auth.username')"/>

              <NInput
                id="username"
                class="mt-1 w-full"
                v-model:value="form.email"
                required
                autofocus
                autocomplete="username"
                :placeholder="$t('auth.username')"
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
                autocomplete="password"
              />

              <InputError class="mt-2" :message="form.errors.password"/>
            </div>

            <div class="block mt-4">
              <label class="flex items-center">
                <NCheckbox v-model:checked="form.remember">
                  {{ $t('auth.rememberMe') }}
                </NCheckbox>
              </label>
            </div>

            <div class="flex items-center justify-end mt-4 gap-4">
              <MyLink
                v-if="canResetPassword"
                :href="route('password.request')"
              >
                {{ $t('auth.forgotPassword') }}
              </MyLink>

              <NButton attr-type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" type="primary" round>
                {{ $t('auth.login') }}
              </NButton>
            </div>
          </form>

        </div>
      </div>
    </div>


  </AppLayout>
</template>
