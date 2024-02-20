<script setup lang="ts">
import {NInput, NModal} from "naive-ui";
import {useForm, usePage} from "@inertiajs/vue3";
import {reactive, ref} from "vue";
import route from 'ziggy-js'
import PrimaryButton from "@/Components/PrimaryButton.vue";
import axios from "axios";
import InputLabel from "@/Components/InputLabel.vue";

const props = defineProps<{
    runnerId: Number,
    runnerCount: Number,
}>();

const mergeRunnerModal = ref(false);
const search = ref('');
const selectedRunner = ref(null);

const mergeForm = useForm({
    runnerId: null,
})

const state = reactive({
    loading: false,
    runners: [],
})


const searchRunner = () => {
    state.loading = true;
    axios.post(route('api.admin.runners.search', { runner: props.runnerId }), {
        'search': search.value,
        '_token': usePage().props.auth.token,
    }).then((response) => {
        state.runners = response.data.runners
        state.loading = false;
    })
};

const selectRunner = (runner) => {
    selectedRunner.value = runner
    mergeForm.runnerId = runner.id;
}

const mergeRunner = () => {
    mergeForm.post(route('admin.runners.merge', {runner: props.runnerId}), {
        preserveScroll: true,
        onSuccess: () => { mergeRunnerModal.value = false },
        onFinish: () => mergeForm.reset(),
    });
};
</script>

<template>
    <section class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 md:p-6 space-y-6">
        <header>
            <h2 class="text-lg font-medium text-gray-900">{{ $t('admin.runner.mergeRunner') }}</h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ $t('admin.runner.mergeDescription') }}
            </p>
        </header>

        <PrimaryButton @click="mergeRunnerModal = true" color="yellow" rounded>
            {{ $t('admin.runner.mergeRunner') }}
        </PrimaryButton>

        <NModal v-model:show="mergeRunnerModal">
            <div class="bg-white p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    {{ $t('admin.runner.mergeConfirmation') }}
                </h2>

                <p class="my-1 text-sm text-red-500">
                    {{ $t('admin.runner.mergeDescription') }}
                </p>

                <p class="my-1">
                    {{ $t('admin.runner.mergeRunnerCount', runnerCount) }}
                </p>

                <div>
                    <InputLabel for="runnerName" :value="$t('runner.search')"/>

                    <NInput
                            type="text"
                            :input-props="{ id: 'runnerName' }"
                            class="mt-1 block w-full"
                            v-model:value="search"
                            :placeholder="$t('runner.search')"
                            @keyup="searchRunner"
                    />
                </div>

                <template v-if="selectedRunner === null">
                    <div v-if="state.loading" class="relative">
                        <div class="mx-auto flex justify-center items-center z-10">
                            <div
                                    class="spinner-border animate-spin inline-block w-8 h-8 border-4 rounded-full text-violet-900 dark:text-emerald-300"
                                    role="status">
                            </div>
                            <span class="font-bold ml-2">
                        {{ $t('laoding') }}
                    </span>
                        </div>
                    </div>
                    <div v-else class="relative">
                        <ul v-if="state.runners.length"
                            class="absolute top-0 w-full z-10 bg-white max-h-32 list-group overflow-y-scroll shadow-md">
                            <li v-for="runner in state.runners" :key="runner.id"
                                class="list-group-item p-2 my-1 hover:cursor-pointer hover:bg-violet-50"
                                @click="selectRunner(runner)">
                                <div class="flex justify-start gap-4">
                                    <p class="text-sm font-medium text-gray-900">{{ runner.lastName }} {{ runner.firstName }}</p>
                                    <p class="text-sm text-gray-500">{{ runner.year }}</p>
                                    <p class="text-sm text-gray-500">{{ $t('admin.runner.resultCount') }} - {{ runner.resultsCount }}</p>
                                </div>
                            </li>
                        </ul>
                        <div v-else-if="search.length" class="flex justify-center">
                            <span class="my-2 text-gray-700 text-sm">{{ $t('profile.runnerNotFound') }}</span>
                        </div>
                    </div>
                </template>
                <div v-else class="my-2 flex justify-between items-center">
                    <div>
                        <div>{{ $t('admin.runner.selectedRunner') }}:</div>
                        <div class="font-bold">{{ selectedRunner.lastName }} {{ selectedRunner.firstName }} {{ selectedRunner.year }} {{ $t('admin.runner.resultCount') }} - {{ selectedRunner.resultsCount }}</div>
                    </div>
                    <div>
                        <PrimaryButton @click="selectedRunner = null" size="small" color="red" outline>
                            {{ $t('admin.runner.changeRunner') }}
                        </PrimaryButton>
                    </div>
                </div>

                <div class="mt-6 flex justify-between gap-3">
                    <PrimaryButton @click="mergeRunnerModal = false" color="blue" rounded outline> {{ $t('cancel') }}</PrimaryButton>

                    <PrimaryButton @click="mergeRunner" :class="{ 'opacity-25': mergeForm.processing }" color="yellow" rounded
                              :disabled="mergeForm.processing">
                        {{ $t('admin.runner.mergeRunner') }}
                    </PrimaryButton>
                </div>
            </div>
        </NModal>
    </section>
</template>

<style scoped>

</style>