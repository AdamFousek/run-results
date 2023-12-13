<script setup>
import { h, ref, watch } from "vue";
import { Head, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { NDataTable, NInput, NSelect } from 'naive-ui';
import { useI18n } from 'vue-i18n'
import Pagination from '@/Components/Pagination.vue'
import InputLabel from '@/Components/InputLabel.vue'

const { t } = useI18n();

const props = defineProps({
  users: {
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
    searchUser()
  }
})

const searchUser = () => {
  router.reload({
    data: {
      query: search.value,
      page: 1,
    },
    only: ['users', 'paginate'],
    preserveState: true,
  })
}

const columns = [
  {
    title: t('user.username'),
    key: 'username',
  },
  {
    title: t('user.email'),
    key: 'email',
  },
  {
    title: t('user.runner.firstName'),
    key: 'runner.first_name',
  },
  {
    title: t('user.runner.lastName'),
    key: 'runner.last_name',
  },
]

const rowProps = (row) => {
  return {
    style: "cursor: pointer;",
    onClick: () => {
      router.get(route('admin.users.edit', { user: row.id }))
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

        <div class="flex justify-between">
          <div class="my-3 w-1/2">
            <InputLabel for="username" :value="$t('user.search')"/>

            <NInput type="text"
                    class=""
                    v-model:value="search"
                    :placeholder="$t('user.search')"
                    clearable
                    round
            />
          </div>
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <NDataTable
            :columns="columns"
            :data="users"
            :pagination="false"
            :bordered="false"
            :row-props="rowProps"
          >
            <template #empty>{{ $t('noResults') }}</template>
          </NDataTable>

          <Pagination v-if="users.length" :pages="paginate.links" class="my-4" />
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<style scoped>

</style>