<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'
import { NButton, NInput, NDatePicker, NSelect } from 'naive-ui'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import MyLink from '@/Components/MyLink.vue'
import MyTrixEditor from '@/Components/MyTrixEditor.vue'
import FormWrapper from '@/Components/Form/FormWrapper.vue'
import FormSection from '@/Components/Form/FormSection.vue'

const props = defineProps({
    tags: {
        type: Array,
        required: true,
    },
    keywords: {
        type: Array,
        required: true,
    }
})

const form = useForm({
    title: '',
    content: '',
    publishedAt: Date.now(),
    tags: [],
    metaDescription: '',
    keywords: [],
});

const submit = () => {
    form.post(route('admin.articles.store'))
};

</script>

<template>
    <Head :title="$t('head.admin.article_create')"/>
    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('head.admin.article_create') }}</h2>
        </template>

        <div class="py-4 md:py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 md:p-6">
                    <FormWrapper @submit.prevent="submit">
                        <template #header>{{ $t('admin.articles.create') }}</template>

                        <template #description>{{ $t('admin.articles.create_description') }}</template>

                        <FormSection>
                            <template #header>{{ $t('admin.articles.form.section_basic_header') }}</template>

                            <template #description>{{ $t('admin.articles.form.section_basic_description') }}</template>

                            <div class="flex flex-col md:flex-row justify-between gap-4">
                                <div class="flex-1 md:mb-2">
                                    <InputLabel for="title" :value="$t('article.title')" required/>

                                    <NInput
                                            :input-props="{ type: 'text', id: 'title', autofocus: true }"
                                            :placeholder="$t('article.title')"
                                            class="mt-1 w-full"
                                            v-model:value="form.title"
                                            required
                                    />

                                    <InputError class="mt-2" :message="form.errors.title"/>
                                </div>
                            </div>

                            <div>
                                <InputLabel for="description" :value="$t('article.content')" required/>

                                <MyTrixEditor v-model="form.content" name="content"></MyTrixEditor>

                                <InputError class="mt-2" :message="form.errors.content"/>
                            </div>

                            <div class="flex">
                                <div>
                                    <InputLabel for="date" :value="$t('article.publishedAt')" />
                                    <p class="text-small text-gray-500">
                                        {{ $t('admin.articles.publishedAtDescription') }}
                                    </p>

                                    <NDatePicker
                                            :input-props="{ id: 'date' }"
                                            type="datetime"
                                            clearable
                                            :placeholder="''"
                                            class="mt-1 w-full"
                                            v-model:value="form.publishedAt"
                                    />

                                    <InputError class="mt-2" :message="form.errors.publishedAt"/>
                                </div>
                            </div>

                            <div>
                                <InputLabel for="tags" :value="$t('article.tags')"/>

                                <NSelect v-model:value="form.tags"
                                         :input-props="{ id: 'tags' }"
                                         filterable
                                         tag
                                         clearable
                                         multiple
                                         label-field="name"
                                         value-field="name"
                                         :options="tags"
                                         class="mt-1 w-full"/>

                                <InputError class="mt-2" :message="form.errors.content"/>
                            </div>
                        </FormSection>

                        <FormSection>
                            <template #header>{{ $t('admin.articles.form.section_meta_header') }}</template>
                            <template #description>{{ $t('admin.articles.form.section_meta_description') }}</template>

                            <div>
                                <InputLabel for="metaDescription" :value="$t('article.meta.description')"/>

                                <NInput
                                        :input-props="{ type: 'text', id: 'metaDescription', autofocus: true }"
                                        :placeholder="$t('article.meta.description')"
                                        class="mt-1 w-full"
                                        v-model:value="form.metaDescription"
                                        required
                                />

                                <InputError class="mt-2" :message="form.errors.content"/>
                            </div>

                            <div>
                                <InputLabel for="keywords" :value="$t('article.meta.keywords')"/>

                                <NSelect v-model:value="form.keywords"
                                         :input-props="{ id: 'keywords' }"
                                         filterable
                                         tag
                                         clearable
                                         multiple
                                         label-field="name"
                                         value-field="name"
                                         :options="keywords"
                                         class="mt-1 w-full"/>

                                <InputError class="mt-2" :message="form.errors.content"/>
                            </div>
                        </FormSection>

                        <template #cancel>
                            <MyLink :href="route('admin.articles.index')" type="default" round>
                                {{ $t('admin.articles.back') }}
                            </MyLink>
                        </template>
                        <template #submit>
                            <NButton attr-type="submit" :class="{ 'opacity-25': form.processing }"
                                     :disabled="form.processing"
                                     type="success" round>
                                {{ $t('admin.articles.create') }}
                            </NButton>
                        </template>
                    </FormWrapper>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>

</style>