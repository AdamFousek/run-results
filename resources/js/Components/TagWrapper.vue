<script setup>
import {computed} from "vue";

const props = defineProps({
  color: {
    type: String,
    default: 'default',
  },
});

const classes = computed(() => {
  return {
    'pink': 'text-xs font-semibold inline-block py-1 px-2 rounded-full text-pink-600 bg-pink-200 last:mr-0 mr-1',
    'violet': 'text-xs font-semibold inline-block py-1 px-2 rounded-full text-violet-600 bg-violet-200 last:mr-0 mr-1',
    'blue': 'text-xs font-semibold inline-block py-1 px-2 rounded-full text-blue-600 bg-blue-200 last:mr-0 mr-1',
    'cyan': 'text-xs font-semibold inline-block py-1 px-2 rounded-full text-cyan-600 bg-indigo-200 last:mr-0 mr-1',
    'emerald': 'text-xs font-semibold inline-block py-1 px-2 rounded-full text-emerald-600 bg-emerald-200 last:mr-0 mr-1',
    'green': 'text-xs font-semibold inline-block py-1 px-2 rounded-full text-green-600 bg-green-200 last:mr-0 mr-1',
    'yellow': 'text-xs font-semibold inline-block py-1 px-2 rounded-full text-yellow-600 bg-yellow-200 last:mr-0 mr-1',
    'orange': 'text-xs font-semibold inline-block py-1 px-2 rounded-full text-orange-600 bg-orange-200 last:mr-0 mr-1',
    'red': 'text-xs font-semibold inline-block py-1 px-2 rounded-full text-red-600 bg-red-200 last:mr-0 mr-1',
    'rose': 'text-xs font-semibold inline-block py-1 px-2 rounded-full text-rose-600 bg-rose-200 last:mr-0 mr-1',
    'default': 'text-xs font-semibold inline-block py-1 px-2 rounded-full text-gray-700 bg-gray-200 last:mr-0 mr-1',
  }[props.color];
});
</script>

<template>
  <span :class="[classes]">
    <slot />
  </span>
</template>
