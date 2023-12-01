<script setup>
import { h, ref, watch } from "vue";
import { Head, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AppLayout.vue'
import { NDataTable, NInput, NInputGroup, NButton } from 'naive-ui';
import { useI18n } from 'vue-i18n'
import Pagination from '@/Components/Pagination.vue'

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
      router.get(route('races.show', { race: row.id }))
    }
  };
};
</script>

<template>
  <Head :title="$t('head.runners')" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('head.runners') }}</h2>
    </template>

    <div class="py-4">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <div class="my-4 w-full md:w-7/12 mx-auto">
          <NInput type="text"
                  class="mx-4"
                  v-model:value="search"
                  :placeholder="$t('race.search')"
                  clearable
                  round
          />
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <NDataTable
            :columns="columns"
            :data="races"
            :pagination="false"
            :bordered="false"
            :row-props="rowProps"
          />

          <Pagination v-if="races.length" :pages="paginate.links" class="my-4" />
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>

</style>