<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'
import { NButton, NInput, NInputNumber } from 'naive-ui'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import MyLink from '@/Components/MyLink.vue'
import DeleteRunnerForm from '@/Pages/Admin/Runners/Partials/DeleteRunnerForm.vue'

const maxYear = new Date().getFullYear();
const minYear = 1900;

const props = defineProps({
  runner: {
    type: Object,
  },
});

const form = useForm({
  first_name: props.runner.first_name,
  last_name: props.runner.last_name,
  day: props.runner.day,
  month: props.runner.month,
  year: props.runner.year,
  city: props.runner.city,
  club: props.runner.club,
});

const submit = () => {
  form.post(route('admin.runners.update', {runner: props.runner.id}), {
    only: ['runner', 'flash'],
    onSuccess: () => {
      console.log(props.runner.day);
      form.day = props.runner.day ?? null;
      form.month = props.runner.month ?? null;
    },
  });
};
</script>

<template>
  <Head :title="$t('head.admin.runners_create')"/>
  <AdminLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('head.admin.runners_create') }}</h2>
    </template>

    <div class="py-4 md:py-12">
      <div class="max-w-7xl mx-auto sm:px-3 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div class="col-span-1 md:col-span-3 bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 md:p-6">
            <form @submit.prevent="submit">
              <div>
                <InputLabel for="first_name" :value="$t('runner.firstName')"/>

                <NInput
                  :input-props="{ type: 'text', id: 'first_name', autofocus: true }"
                  :placeholder="$t('runner.firstName')"
                  class="mt-1 block w-full"
                  v-model:value="form.first_name"
                  required
                />

                <InputError class="mt-2" :message="form.errors.first_name"/>
              </div>

              <div class="mt-4">
                <InputLabel for="last_name" :value="$t('runner.lastName')"/>

                <NInput
                  :input-props="{ type: 'text', id: 'last_name' }"
                  :placeholder="$t('runner.lastName')"
                  class="mt-1 block w-full"
                  v-model:value="form.last_name"
                  required
                />

                <InputError class="mt-2" :message="form.errors.last_name"/>
              </div>

              <div class="mt-4 flex justify-between gap-4 w-full">
                <div class="flex-1">
                  <InputLabel for="day" :value="$t('runner.day')"/>

                  <NInputNumber
                    :input-props="{ type: 'number', id: 'day', min: 0, max: 31 }"
                    :placeholder="$t('runner.day')"
                    class="mt-1 block w-full"
                    v-model:value="form.day"
                  />

                  <InputError class="mt-2" :message="form.errors.day"/>
                </div>
                <div class="flex-1">
                  <InputLabel for="month" :value="$t('runner.month')"/>

                  <NInputNumber
                    :input-props="{ type: 'number', id: 'day', min: 0, max: 12 }"
                    :placeholder="$t('runner.month')"
                    class="mt-1 block w-full"
                    v-model:value="form.month"
                  />

                  <InputError class="mt-2" :message="form.errors.month"/>
                </div>
                <div class="flex-1">
                  <InputLabel for="year" :value="$t('runner.year')"/>

                  <NInputNumber
                    :input-props="{ type: 'number', id: 'year', min: minYear, max: maxYear }"
                    :placeholder="$t('runner.year')"
                    class="mt-1 block w-full"
                    v-model:value="form.year"
                    required
                  />

                  <InputError class="mt-2" :message="form.errors.year"/>
                </div>
              </div>

              <div class="mt-4">
                <InputLabel for="city" :value="$t('runner.city')"/>

                <NInput
                  :input-props="{ type: 'text', id: 'city' }"
                  :placeholder="$t('runner.city')"
                  class="mt-1 block w-full"
                  v-model:value="form.city"
                />

                <InputError class="mt-2" :message="form.errors.city"/>
              </div>

              <div class="mt-4">
                <InputLabel for="club" :value="$t('runner.club')"/>

                <NInput
                  :input-props="{ type: 'text', id: 'club' }"
                  :placeholder="$t('runner.club')"
                  class="mt-1 block w-full"
                  v-model:value="form.club"
                />

                <InputError class="mt-2" :message="form.errors.club"/>
              </div>

              <div class="flex items-center justify-between mt-4 gap-4">
                <MyLink :href="route('admin.runners.index')" type="default" round>{{ $t('admin.runner.back') }}</MyLink>
                <NButton attr-type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                         type="info" round>
                  {{ $t('admin.runner.update') }}
                </NButton>
              </div>
            </form>
          </div>
          <div class="col-span-1">
            <DeleteRunnerForm :runner="runner" />
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<style scoped>

</style>