<script setup>
import { useForm, usePage } from '@inertiajs/vue3'
import { NButton, NInput, NCheckbox, NSelect, NInputNumber } from 'naive-ui'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import { onMounted, ref, toRef } from 'vue'
import axios from 'axios'

const props = defineProps({
    race: {
        type: Object,
        required: true,
    },
    result: {
        type: Object,
        required: false,
    },
})

const time = ref(null)
const availableRunners = ref([])

const emits = defineEmits(['submitted'])

const form = useForm({
    raceId: props.race.id,
    runnerId: props.result?.runnerId ?? null,
    position: props.result?.position ?? 0,
    startingNumber: props.result?.startingNumber ?? 0,
    time: props.result?.time ?? null,
    category: props.result?.category ?? '',
    categoryPosition: props.result?.categoryPosition ?? 0,
    DNF: props.result?.DNF ?? false,
    DNS: props.result?.DNS ?? false,
});

const submit = () => {
    if (props.result) {
        form.post(route('admin.results.update', { result: props.result.id }))
    } else {
        form.post(route('admin.results.store'))
    }
    emits('submitted')
};

onMounted(() => {
    axios.post(route('api.results.availableRunners'), {
        'raceId': props.race.id,
        '_token': usePage().props.auth.token,
    }).then((response) => {
        availableRunners.value = response.data.runners
    })
})

</script>

<template>
    <form @submit.prevent="submit" class="grid grid-cols-2 gap-4">
        <div>
            <InputLabel for="runner" :value="$t('result.runner')"/>

            <NSelect
                    v-model:value="form.runnerId"
                    :input-props="{ id: 'runner' }"
                    filterable
                    clearable
                    :options="availableRunners"
                    label-field="label"
                    value-field="value"
                    class="mt-1 block w-full"
                    required
            />

            <InputError class="mt-2" :message="form.errors.runnerId"/>
        </div>
        <div>
            <InputLabel for="startingNumber" :value="$t('result.startingNumber')"/>

            <NInputNumber
                    :input-props="{ id: 'startingNumber' }"
                    :placeholder="$t('result.startingNumber')"
                    class="mt-1 block w-full"
                    v-model:value="form.startingNumber"
                    required
            />

            <InputError class="mt-2" :message="form.errors.startingNumber"/>
        </div>
        <div>
            <InputLabel for="position" :value="$t('result.position')"/>

            <NInputNumber
                    :input-props="{ id: 'position' }"
                    :placeholder="$t('result.position')"
                    class="mt-1 block w-full"
                    v-model:value="form.position"
                    required
            />

            <InputError class="mt-2" :message="form.errors.position"/>
        </div>
        <div>
            <InputLabel for="time" :value="$t('result.time')"/>

            <NInput
                    :input-props="{ type: 'text', id: 'time' }"
                    :placeholder="$t('result.time')"
                    class="mt-1 block w-full"
                    v-model:value="form.time"
                    required
            />

            <InputError class="mt-2" :message="form.errors.time"/>
        </div>
        <div>
            <InputLabel for="category" :value="$t('result.category')"/>

            <NInput
                    :input-props="{ type: 'text', id: 'category' }"
                    :placeholder="$t('result.category')"
                    class="mt-1 block w-full"
                    v-model:value="form.category"
                    required
            />

            <InputError class="mt-2" :message="form.errors.category"/>
        </div>
        <div>
            <InputLabel for="categoryPosition" :value="$t('result.categoryPosition')"/>

            <NInputNumber
                    :input-props="{  id: 'categoryPosition' }"
                    :placeholder="$t('result.categoryPosition')"
                    class="mt-1 block w-full"
                    v-model:value="form.categoryPosition"
                    required
            />

            <InputError class="mt-2" :message="form.errors.categoryPosition"/>
        </div>
        <div>
            <InputLabel for="isParent" :value="$t('result.DNF')"/>

            <NCheckbox
                    :input-props="{ id: 'DNF'}"
                    :placeholder="$t('result.DNF')"
                    class="mt-1 block w-full"
                    v-model:checked="form.DNF"
            />

            <InputError class="mt-2" :message="form.errors.DNF"/>
        </div>
        <div>
            <InputLabel for="isParent" :value="$t('result.DNS')"/>

            <NCheckbox
                    :input-props="{ id: 'DNS'}"
                    :placeholder="$t('result.DNS')"
                    class="mt-1 block w-full"
                    v-model:checked="form.DNS"
            />

            <InputError class="mt-2" :message="form.errors.DNS"/>
        </div>

        <div class="col-span-2 flex justify-end self-end mt-4 gap-4">
            <NButton attr-type="submit" :class="{ 'opacity-25': form.processing }"
                     :disabled="form.processing"
                     type="success" round>
                {{ $t('admin.results.createSingle') }}
            </NButton>
        </div>
    </form>
</template>

<style scoped>

</style>
