<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { NButton, NInput } from 'naive-ui';
import MyLink from '@/Components/MyLink.vue'
import AppLayout from '@/Layouts/AppLayout.vue'

const form = useForm({
  username: '',
  email: '',
  password: '',
  password_confirmation: '',
});

const submit = () => {
  form.post(route('register'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  });
};
</script>

<template>
  <AppLayout>
    <Head :title="$t('head.register')"/>


    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('auth.register') }}</h2>
    </template>

    <div class="py-4 md:py-12">
      <div class="max-w-xl mx-auto sm:px-3 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 md:p-6">

          <form @submit.prevent="submit">
            <div>
              <InputLabel for="username" :value="$t('auth.username')"/>

              <NInput
                id="username"
                :input-props="{ type: 'text' }"
                :placeholder="$t('auth.username')"
                class="mt-1 w-full"
                v-model:value="form.username"
                required
                autofocus
                autocomplete="username"
              />

              <InputError class="mt-2" :message="form.errors.username"/>
            </div>

            <div class="mt-4">
              <InputLabel for="email" :value="$t('auth.email')"/>

              <NInput
                id="email"
                :input-props="{ type: 'email' }"
                :placeholder="$t('auth.email')"
                class="mt-1 w-full"
                v-model:value="form.email"
                required
                autocomplete="username"
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

            <div class="flex items-center justify-end mt-4 gap-4">
              <MyLink
                :href="route('login')"
              >
                {{ $t('auth.alreadyRegistered') }}
              </MyLink>

              <NButton attr-type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                       type="primary" round>
                {{ $t('auth.register') }}
              </NButton>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
