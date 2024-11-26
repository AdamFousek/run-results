<script setup>
import {Link} from "@inertiajs/vue3";
import TagWrapper from '@/Components/TagWrapper.vue'

defineProps({
    article: {
        type: Object,
        required: true,
    }
})

</script>

<template>
    <Link :href="route('articles.show', { article: article.slug })"
          class="p-6 bg-white overflow-hidden shadow-sm hover:shadow-lg sm:rounded-lg">
        <h4 class="font-bold text-xl">{{ article.title }}</h4>
        <div v-if="article.tags.length" class="mb-4 mt-2">
            <TagWrapper v-for="tag in article.tags" :key="tag" class="self-end">
                {{ tag }}
            </TagWrapper>
        </div>
        <div class="text-gray-800">{{ article.content }}</div>
        <div class="flex justify-end">
            <div class="text-gray-600">{{ article.publishedAt }}</div>
        </div>
    </Link>
</template>

<style scoped>

</style>