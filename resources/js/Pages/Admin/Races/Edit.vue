<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'
import { NButton, NInput } from 'naive-ui'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import MyLink from '@/Components/MyLink.vue'
import { ref } from 'vue'

const props = defineProps({
  race: {
    type: Object,
    required: true,
  },
})

const form = useForm({
  parentId: props.race.parentId,
  name: props.race.name,
  description: props.race.description,
  date: props.race.date,
  location: props.race.location,
  distance: props.race.distance,
  surface: props.race.surface,
  type: props.race.type,
  isParent: props.race.isParent,
});

const description = ref(props.race.description);

const submit = () => {
  form.post(route('admin.races.update'))
};

document.addEventListener('trix-change', (event) => {
  description.value = event.target.value;
})
</script>

<template>
  <Head :title="$t('head.admin.races_update')" />
  <AdminLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('head.admin.races_update') }}</h2>
    </template>

    <div class="py-4 md:py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 md:p-6">
          <form @submit.prevent="submit">
            <div>
              <InputLabel for="name" :value="$t('race.name')"/>

              <NInput
                :input-props="{ type: 'text', id: 'name', autofocus: true }"
                :placeholder="$t('race.name')"
                class="mt-1 block w-full"
                v-model:value="form.name"
                required
              />

              <InputError class="mt-2" :message="form.errors.name"/>
            </div>

            <div class="mt-4">
              <InputLabel for="description" :value="$t('race.description')"/>

              <trix-editor v-model="description" :inputId="'description'" class="trix-content"></trix-editor>

              <InputError class="mt-2" :message="form.errors.description"/>
            </div>

            <div class="mt-4 flex justify-between gap-4 w-full">
              <div class="flex-1">
                <InputLabel for="date" :value="$t('race.date')"/>

                <NInput
                  :input-props="{ type: 'date', id: 'date' }"
                  :placeholder="''"
                  class="mt-1 block w-full"
                  v-model:value="form.date"
                />

                <InputError class="mt-2" :message="form.errors.date"/>
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