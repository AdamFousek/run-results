<script setup>
import { Head, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AppLayout.vue'
import { NInput } from 'naive-ui'
import { ref, watch } from 'vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import MyLink from '@/Components/MyLink.vue'

const props = defineProps({
    races: {
        type: Array,
    },
    runners: {
        type: Array,
    },
    search: {
        type: String,
        default: ''
    }
})

const searchPhrase = ref(props.search)

const searchPage = () => {
    router.reload({
        data: {
            search: searchPhrase.value,
        },
    });
}

let searchTimeout;

watch(searchPhrase, (value) => {
    if (searchTimeout) {
        clearTimeout(searchTimeout);
    }
    if (value === '' || value.length > 2) {
        searchTimeout = setTimeout(() => {
            searchPage()
        }, 250)
    }
})
</script>

<template>
    <Head>
        <title>{{ $t('search') }}</title>
        <meta name="description" :content="$t('search') + ' ' + search">
        <meta name="robots" content="noindex, nofollow">
    </Head>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('search') }} {{ search }}</h2>
        </template>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex gap-4 px-4 py-4 md:px-0">
                    <NInput type="text"
                            v-model:value="searchPhrase"
                            @keyup.enter="searchPage"
                            :placeholder="$t('search')"
                            clearable
                    />
                    <PrimaryButton @click="searchPage" color="blue">{{ $t('search') }}</PrimaryButton>
                </div>
                <div v-if="searchPhrase.length === 0" class="bg-white flex justify-center p-4">
                    <p>{{ $t('searchPage.fillSearchField')}}</p>
                </div>
                <div v-else-if="races.length === 0 && runners.length === 0" class="bg-white flex justify-center p-4">
                    <p>{{ $t('noResults')}}</p>
                </div>
                <div v-else class="grid md:grid-cols-5 gap-4">
                    <div class="bg-white md:col-span-3 p-4 rounded-lg self-start">
                        <h2 class="text-lg">{{ $t('searchTitles.races') }}</h2>
                        <div v-for="race in races" class="flex py-1">
                            <MyLink :href="route('races.show', { race: race.slug })" class="">
                                {{ race.name }} <span v-if="race.vintage">- {{ race.vintage }} {{ $t('race.vintage') }}</span><span v-if="race.date"> - {{ race.date }}</span>
                            </MyLink>
                        </div>
                    </div>
                    <div class="bg-white md:col-span-2 p-4 rounded-lg self-start">
                        <h2 class="text-lg">{{ $t('searchTitles.runners') }}</h2>
                        <div v-for="runner in runners" class="flex">
                            <MyLink :href="route('runners.show', { runner: runner.id })" class="">{{ runner.fullName }} {{ runner.year }}</MyLink>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>
</style>
