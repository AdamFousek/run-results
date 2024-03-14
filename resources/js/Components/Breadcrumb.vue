<script setup lang="ts">
import MyLink from "@/Components/MyLink.vue";

const props = defineProps<{
    breadcrumb: {
        name: string,
        link?: string,
    }[],
}>()
</script>

<template>
    <div class="flex justify-start gap-2">
        <div v-for="(bc, index) in breadcrumb" :key="bc.name" class="flex justify-start gap-2 items-center">
            <MyLink v-if="bc.link" :href="bc.link">{{ bc.name }}</MyLink>
            <span v-else>{{ bc.name }}</span>
            <span v-if="index < breadcrumb.length-1">|</span>
        </div>
    </div>
</template>

<style scoped>

</style>