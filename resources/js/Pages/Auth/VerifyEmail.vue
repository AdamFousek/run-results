<script setup>
import { computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { NButton } from 'naive-ui';
import MyLink from '@/Components/MyLink.vue'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
  status: {
    type: String,
  },
});

const form = useForm({});

const submit = () => {
  form.post(route('verification.send'));
};

const verificationLinkSent = computed(() => props.status === 'verification-link-sent');
</script>

<template>
  <AppLayout>
    <Head :title="$t('head.verifyEmail')"/>

    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('head.verifyEmail') }}</h2>
    </template>

    <div class="py-4 md:py-12">
      <div class="max-w-xl mx-auto sm:px-3 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 md:p-6">

          <div class="mb-4 text-sm text-gray-600">
            {{ $t('auth.verifyEmailDescription') }}
          </div>

          <div class="mb-4 font-medium text-sm text-green-600" v-if="verificationLinkSent">
            {{ $t('auth.verifyLinkSent') }}
          </div>

          <form @submit.prevent="submit">
            <div class="mt-4 flex items-center justify-between">
              <NButton attr-type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                       type="primary" round>
                {{ $t('auth.resendVerifyEmail') }}
              </NButton>

              <MyLink :href="route('logout')" method="post">
                {{ $t('auth.logout') }}
              </MyLink>
            </div>
          </form>

        </div>
      </div>
    </div>
  </AppLayout>
</template>
