<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'
import { NButton, NInput, NInputNumber } from 'naive-ui'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import MyLink from '@/Components/MyLink.vue'

const maxYear = new Date().getFullYear();
const minYear = 1900;

const form = useForm({
  first_name: '',
  last_name: '',
  day: null,
  month: null,
  year: 2000,
  city: '',
  club: '',
});

const submit = () => {
  form.post(route('admin.runners.store'))
};
</script>

<template>
  <Head :title="$t('head.admin.runners_create')" />
  <AdminLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('head.admin.runners_create') }}</h2>
    </template>

    <div class="py-4 md:py-12">
      <div class="max-w-xl mx-auto sm:px-3 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 md:p-6">
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
                  :input-props="{ id: 'day' }"
                  :placeholder="$t('runner.day')"
                  :min="0"
                  :max="31"
                  class="mt-1 block w-full"
                  v-model:value="form.day"
                />

                <InputError class="mt-2" :message="form.errors.day"/>
              </div>
              <div class="flex-1">
                <InputLabel for="month" :value="$t('runner.month')"/>

                <NInputNumber
                  :input-props="{ id: 'day' }"
                  :placeholder="$t('runner.month')"
                  :min="0"
                  :max="12"
                  class="mt-1 block w-full"
                  v-model:value="form.month"
                />

                <InputError class="mt-2" :message="form.errors.month"/>
              </div>
              <div class="flex-1">
                <InputLabel for="year" :value="$t('runner.year')"/>

                <NInputNumber
                  :input-props="{ id: 'year'}"
                  :placeholder="$t('runner.year')"
                  :min="minYear"
                  :max="maxYear"
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
                       type="success" round>
                {{ $t('admin.runner.create') }}
              </NButton>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<style scoped>

</style>