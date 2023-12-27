<script setup>
import { h, ref, watch } from "vue";
import { Head, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { NButton, NDataTable, NIcon, NInput, NSelect } from 'naive-ui';
import { useI18n } from 'vue-i18n'
import Pagination from '@/Components/Pagination.vue'
import InputLabel from '@/Components/InputLabel.vue'
import { PlusSharp } from '@vicons/material'

const { t } = useI18n();

const props = defineProps({
  races: {
    type: Array,
  },
  paginate: {
    type: Object,
    required: true,
  },
  search: {
    type: String,
    required: false,
    default: '',
  },
})

const search = ref(props.search)

watch(search, (value) => {
  if (value === '' || value.length > 2) {
    searchRaces()
  }
})

const searchRaces = () => {
  router.reload({
    data: {
      query: search.value,
      sort: sort.value,
      page: 1,
    },
    only: ['races', 'paginate'],
    preserveState: true,
  })
}

const columns = [
  {
    title: t('race.name'),
    key: 'name',
  },
  {
    title: t('race.date'),
    key: 'date',
  },
  {
    title: t('race.location'),
    key: 'location',
  },
  {
    title: t('race.distance'),
    key: 'distance',
  },
]

const rowProps = (row) => {
  return {
    style: "cursor: pointer;",
    onClick: () => {
      router.get(route('admin.race.edit', { race: row.id }))
    }
  };
};
</script>

<template>
  <Head :title="$t('head.admin.races')" />

  <AdminLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('head.admin.races') }}</h2>
    </template>

    <div class="py-4">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <div class="flex justify-between items-center gap-4">
          <NInput type="text"
                  class="my-4"
                  v-model:value="search"
                  :placeholder="$t('race.search')"
                  clearable
                  round
          />
          <NButton round type="success" @click="router.get(route('admin.races.create'))">
            <template #icon>
              <NIcon>
                <PlusSharp />
              </NIcon>
            </template>
            {{ $t('admin.races.create') }}
          </NButton>
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <NDataTable
            :columns="columns"
            :data="races"
            :pagination="false"
            :bordered="false"
            :row-props="rowProps"
          >
            <template #empty>{{ $t('noResults') }}</template>
          </NDataTable>

          <Pagination v-if="races.length" :pages="paginate.links" class="my-4" />
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<style scoped>

</style>