<script setup>
import {Link} from "@inertiajs/vue3";

const props = defineProps({
  pages: {
    type: Array,
    required: true,
  }
})
</script>

<template>
  <nav class="flex justify-center items-center p-4" aria-label="Page navigation example">
    <ul class="inline-flex flex-wrap justify-center -space-x-px text-sm w-full overflow-x-auto">
      <li class="mb-2" v-for="(link, index ) in pages" :key="index">
        <Link v-if="link.active"
              href="#"
              aria-current="page"
              class="flex items-center justify-center px-3 h-8 text-violet-950 border border-violet-950 bg-violet-200 hover:bg-violet-50 hover:text-violet-800"
              :class="{
                'rounded-l-md': index === 0,
                'rounded-r-md': index === pages.length-1,
              }"
        >
            {{ link.label }}
        </Link>
        <Link v-else-if="link.link"
              :href="link.link"
              class="flex items-center justify-center px-3 h-8 leading-tight text-violet-950 border border-violet-950 bg-violet-50 hover:bg-violet-100 hover:text-violet-800"
              :class="{
                'rounded-l-md': index === 0,
                'rounded-r-md': index === pages.length-1,
              }"
        >
          {{ link.label }}
        </Link>
        <span v-else class="flex items-center justify-center px-3 h-8 text-gray-500 bg-white border border-gray-300" v-html="link.label" />
      </li>
    </ul>
  </nav>
</template>

<style scoped>

</style>