<script setup>
const props = defineProps({
  runner: {
    type: Object,
    required: true,
  },
})

console.log(props.runner);
</script>

<template>
  <h2>Detail {{ runner.first_name }} {{ runner.last_name }}</h2>
</template>

<style scoped>

</style>