<script setup>
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  type: {
    type: String,
    required: true,
  },
  href: {
    type: String,
    required: true,
  },
})

const typeClasses = {
  primary: 'bg-sea-pink-600 hover:bg-sea-pink-700 text-white ',
  secondary: 'bg-white hover:bg-gray-50 text-gray-700 ',
  danger: 'bg-red-600 hover:bg-red-700 text-white ',
  link: 'text-sea-pink-600 hover:text-sea-pink-700 ',
}

const defaultClasses = typeClasses[props.type] ?? 'text-violet-950 hover:text-violet-800'
</script>

<template>
  <Link
    :href="href"
    class="underline"
    :class="defaultClasses"
  >
    <slot />
  </Link>
</template>

<style scoped>

</style>