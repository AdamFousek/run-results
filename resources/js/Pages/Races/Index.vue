<script setup>
import { h, ref, watch } from "vue";
import { Head, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AppLayout.vue'
import { NDataTable, NInput, NInputGroup, NSelect } from 'naive-ui';
import { useI18n } from 'vue-i18n'
import Pagination from '@/Components/Pagination.vue'
import InputLabel from '@/Components/InputLabel.vue'

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
  sortOptions: {
    type: Array,
  },
  activeSort: {
    type: String,
  }
})

const search = ref(props.search)
const sort = ref(props.activeSort)

watch(search, (value) => {
  if (value === '' || value.length > 2) {
    searchRaces()
  }
})

watch(sort, (value) => {
  if (value) {
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
      router.get(route('races.show', { race: row.id }))
    }
  };
};
</script>

<template>
  <Head :title="$t('head.races')" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('head.races') }}</h2>
    </template>

    <div class="py-4">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <div class="flex justify-between">
          <div class="my-3 w-1/2">
            <InputLabel for="username" :value="$t('race.search')"/>

            <NInput type="text"
                    class=""
                    v-model:value="search"
                    :placeholder="$t('race.search')"
                    clearable
                    round
            />
          </div>

          <div class="my-3 w-1/6">
            <InputLabel for="username" :value="$t('race.sort')" />

            <NSelect class="w-1/12" round :placeholder="$t('race.sort')" v-model:value="sort" :options="sortOptions" />
          </div>


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