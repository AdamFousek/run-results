<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { NButton, NDataTable, NInput, NIcon } from 'naive-ui'
import Pagination from '@/Components/Pagination.vue'
import { PlusSharp } from '@vicons/material'
import { useI18n } from 'vue-i18n'
import { h, ref, watch } from 'vue'

const { t } = useI18n();

const props = defineProps({
  runners: {
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
    searchRunners()
  }
})

const searchRunners = () => {
  router.reload({
    data: {
      query: search.value,
      page: 1,
    },
    only: ['runners', 'paginate'],
    preserveState: true,
  })
}

const columns = [
  {
    title: t('runner.name'),
    key: 'name',
    render(row) {
      return h('div', { innerHTML: row.last_name + ' ' + row.first_name });
    }
  },
  {
    title: t('runner.year'),
    key: 'year',
  },
  {
    title: t('runner.club'),
    key: 'club',
  },
  {
    title: t('runner.city'),
    key: 'city',
  },
]

const rowProps = (row) => {
  return {
    style: "cursor: pointer;",
    onClick: () => {
      router.get(route('runners.show', { runner: row.id }))
    }
  };
};

</script>

<template>
  <Head :title="$t('head.admin.runners')" />
  <AdminLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('head.admin.runners') }}</h2>
    </template>

    <div class="py-4">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <div class="flex justify-between items-center gap-4">
          <NInput type="text"
                  class="my-4 w-full md:w-7/12 mx-auto"
                  v-model:value="search"
                  :placeholder="$t('runner.search')"
                  clearable
                  round
          />

          <NButton round type="success" @click="router.get(route('admin.runners.create'))">
            <template #icon>
              <NIcon>
                <PlusSharp />
              </NIcon>
            </template>
            {{ $t('admin.runner.create') }}
          </NButton>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <NDataTable
            :columns="columns"
            :data="runners"
            :pagination="false"
            :bordered="false"
            :row-props="rowProps"
          >
            <template #empty>{{ $t('noResults') }}</template>
          </NDataTable>

          <Pagination v-if="runners.length" :pages="paginate.links" class="my-4" />
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<style scoped>

</style>