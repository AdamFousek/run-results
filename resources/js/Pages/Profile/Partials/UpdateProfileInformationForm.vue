<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { NButton, NInput } from 'naive-ui';

defineProps({
  mustVerifyEmail: {
    type: Boolean,
  },
  status: {
    type: String,
  },
});

const user = usePage().props.auth.user;

const form = useForm({
  username: user.username,
  email: user.email,
});
</script>

<template>
  <section>
    <header>
      <h2 class="text-lg font-medium text-gray-900">{{ $t('profile.information') }}</h2>

      <p class="mt-1 text-sm text-gray-600">
        {{ $t('profile.informationDescription') }}
      </p>
    </header>

    <form @submit.prevent="form.patch(route('profile.update'))" class="mt-6 space-y-6">
      <div>
        <InputLabel for="name" :value="$t('auth.username')"/>

        <NInput
          :input-props="{ id: 'name', autocomplete: 'name' }"
          type="text"
          class="mt-1 block w-full"
          v-model:value="form.username"
          disabled
        />

        <InputError class="mt-2" :message="form.errors.name"/>
      </div>

      <div>
        <InputLabel for="email" :value="$t('auth.email')"/>

        <NInput
          :input-props="{ type: 'email', id: 'email', autocomplete: 'email' }"
          class="mt-1 block w-full"
          v-model:value="form.email"
          required
        />

        <InputError class="mt-2" :message="form.errors.email"/>
      </div>

      <div v-if="mustVerifyEmail && user.email_verified_at === null">
        <p class="text-sm mt-2 text-gray-800">
          {{ $t('profile.emailVerification') }}
          <Link
            :href="route('verification.send')"
            method="post"
            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none"
          >
            {{ $t('profile.emailVerificationLink') }}
          </Link>
        </p>

        <div
          v-show="status === 'verification-link-sent'"
          class="mt-2 font-medium text-sm text-green-600"
        >
          {{ $t('profile.emailVerificationLinkSent') }}
        </div>
      </div>

      <div class="flex items-center gap-4">
        <NButton :disabled="form.processing" attr-type="submit" round type="primary">{{ $t('profile.save') }}</NButton>

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
