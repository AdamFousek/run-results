<script setup>
import { Link } from '@inertiajs/vue3'
import SortBlock from '@/Components/SortBlock.vue'
import { computed } from 'vue'

const props = defineProps({
    articles: {
        type: Array,
        required: true,
    },
    sort: {
        type: String,
        required: false,
    }
})

console.log(props.articles);

const activeSort = computed(() => {
    const sort = props.sort.split(':')
    return {
        name: sort[0],
        isAsc: sort[1] === 'asc',
    }
});
</script>

<template>
    <section>
        <div class="grid grid-cols-5 gap-2 md:gap-4 border-b">
            <SortBlock class="font-bold p-3 md:px-4 flex items-center gap-2" name="title" :is-active="activeSort.name === 'title'" :is-asc="activeSort.isAsc">
                {{ $t('article.title') }}
            </SortBlock>
            <SortBlock class="font-bold p-3 md:px-4 flex items-center gap-2" name="title" :is-active="activeSort.name === 'slug'" :is-asc="activeSort.isAsc">
                {{ $t('article.slug') }}
            </SortBlock>
            <SortBlock class="font-bold p-3 md:px-4 flex items-center gap-2" name="published_at" :is-active="activeSort.name === 'published_at'" :is-asc="activeSort.isAsc">
                {{ $t('article.publishedAt') }}
            </SortBlock>
            <SortBlock class="font-bold p-3 md:px-4 flex items-center gap-2" name="created_at" :is-active="activeSort.name === 'created_at'" :is-asc="activeSort.isAsc">
                {{ $t('article.createdAt') }}
            </SortBlock>
            <SortBlock class="font-bold p-3 md:px-4 flex items-center gap-2" name="author" :is-active="activeSort.name === 'author'" :is-asc="activeSort.isAsc">
                {{ $t('article.author') }}
            </SortBlock>
        </div>
        <Link v-for="(article, index) in articles" :key="article.id" :href="route('admin.articles.edit', { article: article.id })"
              class="grid grid-cols-5 gap-2 items-center md:gap-4 hover:bg-gray-100"
              :class="{ 'bg-gray-50': index%2 === 0}">
            <div class="p-3 md:px-4">{{ article.title }}</div>
            <div class="p-3 md:px-4">{{ article.slug }}</div>
            <div class="p-3 md:px-4">{{ article.publishedAt }}</div>
            <div class="p-3 md:px-4">{{ article.createdAt }}</div>
            <div class="p-3 md:px-4">{{ article.author }}</div>
        </Link>
    </section>
</template>

<style scoped>

</style>