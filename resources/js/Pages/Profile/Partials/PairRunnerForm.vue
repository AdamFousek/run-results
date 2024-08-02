<script setup>
import { NButton, NInput, NIcon } from 'naive-ui'
import InputLabel from '@/Components/InputLabel.vue'
import { useForm, usePage } from '@inertiajs/vue3'
import { CloseFilled } from '@vicons/material'
import { reactive, ref } from 'vue'
import axios from 'axios'
import MyLink from '@/Components/MyLink.vue'

defineProps({
  pairRunnerLimit: {
    type: Number,
  },
  runner: {
    type: Object,
  },
});

const user = usePage().props.auth.user;
const alert = usePage().props.flash.alert;
const search = ref('');
const selectedRunner = ref(null);
const error = ref('');

const state = reactive({
  loading: false,
  runners: [],
})

const searchRunner = () => {
  state.loading = true;
  axios.post(route('api.runners.search'), {
    'search': search.value,
    '_token': usePage().props.auth.token,
  }).then((response) => {
    state.runners = response.data.runners
    state.loading = false;
  })
};

const selectRunner = (runner) => {
  selectedRunner.value = runner;
  form.runnerId = runner.id;
}

const resetRunner = () => {
  state.runners = [];
  search.value = '';
  selectedRunner.value = null;
  form.runnerId = 0;
}

const form = useForm({
  runnerId: 0,
  userId: user.id,
  day: 1,
  month: 1,
});

const pairRunner = () => {
  form.post(route('profile.runner.pair'), {
    preserveState: true,
    onError: () => {
      error.value = 'somethingWentWrong';
    },
    onFinish: () => {
      // form.reset('day', 'month');
      // resetRunner();
    },
  });
};
</script>

<template>
  <section>
    <header>
      <h2 class="text-lg font-medium text-gray-900">{{ $t('runnerPair.header') }}</h2>

      <p class="mt-1 text-sm text-gray-600">
        {{ $t('runnerPair.description') }}
      </p>

      <p v-if="!runner" class="mt-1 text-red-800">
        {{ $t('runnerPair.pairLimit') }}
      </p>
      <p v-if="!runner" class="m-0">
        {{ $t('runnerPair.pairLimitCount') }} - {{ pairRunnerLimit }}
      </p>
    </header>

    <template v-if="!runner">
      <div v-if="!user.email_verified_at" class="text-red-800 text-lg my-2">
        {{ $t('profile.runnerPairEmailVerified') }}
      </div>
      <div v-else-if="pairRunnerLimit === 0" class="text-red-800 font-bold">
        {{ $t('runnerPair.pairLimitExceed') }}
      </div>
      <form @submit.prevent="pairRunner" v-else class="mt-6">
        <template v-if="form.runnerId === 0">
          <div>
            <InputLabel for="runnerName" :value="$t('runner.search')"/>

            <NInput
              type="text"
              :input-props="{ id: 'runnerName' }"
              class="mt-1 w-full"
              v-model:value="search"
              :placeholder="$t('runner.search')"
              @keyup="searchRunner"
            />
          </div>

          <div v-if="state.loading" class="relative">
            <div class="mx-auto flex justify-center items-center z-10">
              <div
                class="spinner-border animate-spin inline-block w-8 h-8 border-4 rounded-full text-violet-900 dark:text-emerald-300"
                role="status">
              </div>
              <span class="font-bold ml-2">
                        {{ $t('laoding') }}
                    </span>
            </div>
          </div>
          <div v-else class="relative">
            <ul v-if="state.runners.length"
                class="absolute top-0 w-full z-10 bg-white max-h-32 list-group overflow-y-scroll shadow-md">
              <li v-for="runner in state.runners" :key="runner.id"
                  class="list-group-item p-2 my-1 hover:cursor-pointer hover:bg-violet-50"
                  @click="selectRunner(runner)">
                <div class="flex justify-start gap-4">
                  <p class="text-sm font-medium text-gray-900">{{ runner.last_name }} {{ runner.first_name }}</p>
                  <p class="text-sm text-gray-500">{{ runner.year }}</p>
                </div>
              </li>
            </ul>
            <div v-else-if="search.length" class="flex justify-center">
              <span class="my-2 text-gray-700 text-sm">{{ $t('profile.runnerNotFound') }}</span>
            </div>
          </div>
        </template>
        <template v-else>
          <div class="mt-4 flex justify-between gap-2 items-center">
            <NIcon size="20" class="text-red-800 hover:text-red-500 hover:cursor-pointer" @click="resetRunner()">
              <CloseFilled/>
            </NIcon>
            <div class="flex-1">
              <span class="text-xl">{{ selectedRunner.last_name }} {{ selectedRunner.first_name }}</span> <span
              class="text-sm text-gray-500">{{ selectedRunner.year }}</span>
            </div>
          </div>

          <div class="mt-4 text-red-900">{{ $t('runnerPair.confirmDate') }}</div>
          <div class="mt-2 flex justify-between gap-4">
            <div class="flex-1">
              <InputLabel for="day" :value="$t('runner.day')"/>

              <NInput
                :input-props="{ type: 'number', min: 1, max: 31, id: 'day' }"
                class="mt-1 w-full"
                v-model:value="form.day"
                required
              />
            </div>
            <div class="flex-1">
              <InputLabel for="month" :value="$t('runner.month')"/>

              <NInput
                :input-props="{ type: 'number', min: 1, max: 12, id: 'month' }"
                class="mt-1 w-full"
                v-model:value="form.month"
                required
              />
            </div>
          </div>

          <div class="mt-4 flex items-center gap-4">
            <NButton :loading="form.processing" icon-placement="left" :disabled="form.processing" attr-type="submit"
                     round type="primary">{{ $t('runnerPair.pair') }}
            </NButton>

            <Transition
              enter-active-class="transition ease-in-out"
              enter-from-class="opacity-0"
              leave-active-class="transition ease-in-out"
              leave-to-class="opacity-0"
            >
              <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">{{ $t('runnerPair.requestSent') }}</p>
            </Transition>
          </div>
        </template>
        <span v-if="error.length" class="text-red-800"> {{ error }}</span>
      </form>
    </template>
    <div v-else class="mt-2">
      <span class="text-gray-700 mr-2">
        {{ $t('runnerPair.pairedWith') }}
      </span>
      <MyLink :href="route('runners.show', { runner: runner.id })" class="text-gray-900">{{ runner.last_name }} {{ runner.first_name }}</MyLink>
    </div>
  </section>
</template>

<style scoped>

</style>