<script setup lang="ts">
import {Link, usePage} from '@inertiajs/vue3'
import route from 'ziggy-js'

const props = defineProps({
    page: {
        type: Number,
        required: true,
    },
    total: {
        type: Number,
        required: true,
    },
    perPage: {
        type: Number,
        required: true,
    },
    onPage: {
        type: Number,
        required: true,
    },
})

const startPage = props.page > 3 ? props.page - 2 : 1
const lastPage = Math.ceil(props.total / props.perPage)
const endPage = startPage + 4 < lastPage ? startPage + 4 : lastPage
const query = (usePage().props.ziggy as any).query;
const current = (usePage().props.ziggy as any).current ?? '#';
</script>

<template>
    <nav class="flex px-4 md:justify-center" aria-label="Page navigation example">
        <ul class="flex items-center -space-x-px h-8 text-sm">
            <li>
                <span v-if="page === 1" class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-gray-100 border border-e-0 border-gray-300 rounded-s-lg cursor-not-allowed">
                    <span class="sr-only">{{ $t('Previous') }}</span>
                    <svg class="w-2.5 h-2.5 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                         fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M5 1 1 5l4 4"/>
                    </svg>
                </span>
                <Link v-else :href="route(current, {
                    _query: {
                        ...query,
                        page: page - 1,
                    }
                })"
                      class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700">
                    <span class="sr-only">{{ $t('Previous') }}</span>
                    <svg class="w-2.5 h-2.5 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                         fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M5 1 1 5l4 4"/>
                    </svg>
                </Link>
            </li>
            <template v-if="startPage > 1">
                <li>
                    <Link :href="route(current, {
                    _query: {
                        ...query,
                        page: 1,
                    }
                })" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white hover:bg-violet-100 border border-gray-300 hover:text-gray-700">
                        1
                    </Link>
                </li>
                <li v-if="startPage-1 > 1">
                    <span class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:text-gray-700 cursor-not-allowed">...</span>
                </li>
            </template>
            <template v-for="index in lastPage" :key="index">
            <li v-if="index <= endPage && index >= startPage">
                <Link :href="route(current, {
                    _query: {
                        ...query,
                        page: index,
                    }
                })"
                   class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 border border-gray-300  hover:text-gray-700"
                     :class="{
                          'bg-violet-200': index === page,
                          'bg-white hover:bg-violet-100': index !== page,
                          'text-gray-700': index === page,
                     }"
                >
                    {{ index }}
                </Link>
            </li>
            </template>
            <template v-if="endPage < lastPage">
                <li v-if="endPage+1 < lastPage">
                    <span class="flex items-center justify-center px-3 h-8 leading-tight bg-white text-gray-500 border border-gray-300 hover:text-gray-700 cursor-not-allowed">...</span>
                </li>
                <li>
                    <Link :href="route(current, {
                        _query: {
                            ...query,
                            page: lastPage,
                        }
                    })" class="flex items-center justify-center px-3 h-8 leading-tight bg-white hover:bg-violet-100 text-gray-500 border border-gray-300 hover:text-gray-700">
                        {{ lastPage }}
                    </Link>
                </li>
            </template>
            <li>
                <span v-if="lastPage === page" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-gray-100 border border-gray-300 rounded-e-lg cursor-not-allowed">
                    <span class="sr-only">{{ $t('Next') }}</span>
                    <svg class="w-2.5 h-2.5 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                         fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="m1 9 4-4-4-4"/>
                    </svg>
                </span>
                <Link v-else :href="route(current, {
                    _query: {
                        ...query,
                        page: page + 1,
                    }
                })"
                      class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700">
                    <span class="sr-only">{{ $t('Next') }}</span>
                    <svg class="w-2.5 h-2.5 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                </Link>
            </li>
        </ul>
    </nav>
</template>

<style scoped>

</style>