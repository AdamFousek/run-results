<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'
import { NButton, NInput, NCheckbox, NSelect, NInputNumber, NTimePicker, NIcon } from 'naive-ui'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import MyLink from '@/Components/MyLink.vue'
import DeleteRaceForm from '@/Pages/Admin/Races/Partials/DeleteRaceForm.vue'
import MyTrixEditor from '@/Components/MyTrixEditor.vue'
import { RemoveRedEyeOutlined } from '@vicons/material'
import UploadFileForm from '@/Pages/Admin/Races/Partials/UploadFileForm.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import ReloadMeilisearchData from '@/Components/Entity/ReloadMeilisearchData.vue'

const props = defineProps({
    race: {
        type: Object,
        required: true,
    },
    optionsType: {
        type: Array,
        required: true,
    },
    optionsTag: {
        type: Array,
        required: true,
    },
    optionsSurface: {
        type: Array,
        required: true,
    },
    parentRaces: {
        type: Array,
        required: true,
    },
    files: {
        type: Array,
        required: true,
    },
    resultCount: {
        type: Number,
        required: true,
    },
})

const form = useForm({
    parentId: props.race.parentId,
    name: props.race.name,
    slug: props.race.slug,
    description: props.race.descriptionTrix,
    date: props.race.formDate,
    time: props.race.time,
    location: props.race.location,
    distance: props.race.rawDistance,
    surface: props.race.surface,
    type: props.race.type,
    tag: props.race.tag,
    vintage: props.race.vintage,
    region: props.race.region,
    longitude: props.race.longitude,
    latitude: props.race.latitude,
    isParent: !!props.race.isParent,
})


const submit = () => {
    form.post(route('admin.races.update', {race: props.race.id}))
};

const fillValueFromParent = (value) => {
    const selectedRace = props.parentRaces.filter((race) => race.id === value).shift()

    if (selectedRace) {
        form.date = selectedRace.formDate
        form.location = selectedRace.location
        form.distance = selectedRace.distanceRaw
        form.surface = selectedRace.surface
        form.type = selectedRace.type
        form.tag = selectedRace.tag
    }
}
</script>

<template>
    <Head :title="$t('head.admin.races_update')"/>
    <AdminLayout>
        <template #header>
            <div class="flex justify-between items-center gap-4">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $t('head.admin.races_update') }}
                </h2>
                <PrimaryButton :href="route('races.show', { race: race.slug })" link color="blue" outline rounded class="flex items-center gap-3">
                    <NIcon>
                        <RemoveRedEyeOutlined/>
                    </NIcon>
                    <span class="hidden md:block">{{ $t('admin.results.showRace') }}</span>
                </PrimaryButton>
            </div>
        </template>

        <div class="py-4 md:py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div class="col-span-1 md:col-span-3 bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 md:p-6">
                        <form @submit.prevent="submit">
                            <div class="flex flex-col md:flex-row justify-between gap-4">
                                <div class="flex-1 md:mb-2">
                                    <InputLabel for="name" :value="$t('race.name')"/>

                                    <NInput
                                            :input-props="{ type: 'text', id: 'name', autofocus: true }"
                                            :placeholder="$t('race.name')"
                                            class="mt-1 w-full"
                                            v-model:value="form.name"
                                            required
                                    />

                                    <InputError class="mt-2" :message="form.errors.name"/>
                                </div>
                                <div class="flex-1 md:mb-2">
                                    <InputLabel for="slug" :value="$t('race.slug')"/>

                                    <NInput
                                            :input-props="{ type: 'text', id: 'slug' }"
                                            :placeholder="$t('race.slug')"
                                            class="mt-1 w-full"
                                            v-model:value="form.slug"
                                            required
                                    />

                                    <InputError class="mt-2" :message="form.errors.name"/>
                                </div>
                            </div>
                            <div class="flex justify-between gap-4">
                                <div class="md:mb-2">
                                    <InputLabel for="isParent" :value="$t('race.isParent')"/>

                                    <NCheckbox
                                            :input-props="{ type: 'text', id: 'isParent'}"
                                            :placeholder="$t('race.isParent')"
                                            class="mt-1 w-full"
                                            v-model:checked="form.isParent"
                                            required
                                    />

                                    <InputError class="mt-2" :message="form.errors.isParent"/>
                                </div>
                                <div v-if="!form.isParent" class="flex-1 md:mb-2">
                                    <InputLabel for="parent" :value="$t('race.parent')"/>

                                    <NSelect
                                            v-model:value="form.parentId"
                                            :input-props="{ id: 'type' }"
                                            filterable
                                            clearable
                                            :options="parentRaces"
                                            label-field="name"
                                            value-field="id"
                                            class="mt-1 w-full"
                                            @update:value="fillValueFromParent"
                                    />

                                    <InputError class="mt-2" :message="form.errors.parentId"/>
                                </div>
                            </div>

                            <div class="mt-4">
                                <InputLabel for="description" :value="$t('race.description')"/>

                                <MyTrixEditor v-model="form.description" name="description"></MyTrixEditor>

                                <InputError class="mt-2" :message="form.errors.description"/>
                            </div>

                            <div class="mt-4 flex justify-between gap-4 w-full">
                                <div class="flex-1">
                                    <InputLabel for="vintage" :value="$t('race.vintage')"/>

                                    <NInputNumber
                                            :input-props="{ type: 'number', id: 'vintage' }"
                                            :placeholder="$t('race.vintage')"
                                            class="mt-1 w-full"
                                            v-model:value="form.vintage"
                                    />

                                    <InputError class="mt-2" :message="form.errors.vintage"/>
                                </div>
                                <div class="flex-1">
                                    <InputLabel for="date" :value="$t('race.date')"/>

                                    <NInput
                                            :input-props="{ type: 'date', id: 'date' }"
                                            :placeholder="''"
                                            class="mt-1 w-full"
                                            v-model:value="form.date"
                                    />

                                    <InputError class="mt-2" :message="form.errors.date"/>
                                </div>
                                <div class="flex-1">
                                    <InputLabel for="time" :value="$t('race.time')"/>

                                    <NTimePicker
                                            :input-props="{ id: 'time' }"
                                            :placeholder="''"
                                            format="HH:mm"
                                            value-format="HH:mm"
                                            class="mt-1 w-full"
                                            v-model:formatted-value="form.time"
                                    />

                                    <InputError class="mt-2" :message="form.errors.time"/>
                                </div>
                            </div>

                            <div class="mt-4 flex justify-between gap-4 w-full">
                                <div class="flex-1">
                                    <InputLabel for="location" :value="$t('race.location')"/>

                                    <NInput
                                            :input-props="{ type: 'text', id: 'location' }"
                                            :placeholder="$t('race.location')"
                                            class="mt-1 w-full"
                                            v-model:value="form.location"
                                    />

                                    <InputError class="mt-2" :message="form.errors.location"/>
                                </div>
                                <div class="flex-1">
                                    <InputLabel for="location" :value="$t('race.region')"/>

                                    <NInput
                                            :input-props="{ type: 'text', id: 'region' }"
                                            :placeholder="$t('race.region')"
                                            class="mt-1 w-full"
                                            v-model:value="form.region"
                                    />

                                    <InputError class="mt-2" :message="form.errors.region"/>
                                </div>
                            </div>

                            <div class="mt-4 flex justify-between gap-4 w-full">
                                <div class="flex-1">
                                    <InputLabel for="location" :value="$t('race.latitude')"/>

                                    <NInputNumber
                                            :input-props="{ id: 'latitude' }"
                                            :placeholder="$t('race.latitude')"
                                            class="mt-1 w-full"
                                            v-model:value="form.latitude"
                                    />

                                    <InputError class="mt-2" :message="form.errors.latitude"/>
                                </div>
                                <div class="flex-1">
                                    <InputLabel for="location" :value="$t('race.longitude')"/>

                                    <NInputNumber
                                            :input-props="{ id: 'longitude' }"
                                            :placeholder="$t('race.longitude')"
                                            class="mt-1 w-full"
                                            v-model:value="form.longitude"
                                    />

                                    <InputError class="mt-2" :message="form.errors.longitude"/>
                                </div>
                            </div>

                            <div class="mt-4">
                                <InputLabel for="distance" :value="$t('race.distance') + ' (m)'"/>

                                <NInputNumber
                                        :input-props="{ type: 'number', id: 'distance' }"
                                        :placeholder="$t('race.distance')"
                                        class="mt-1 w-full"
                                        v-model:value="form.distance"
                                />

                                <InputError class="mt-2" :message="form.errors.distance"/>
                            </div>

                            <div class="mt-4">
                                <InputLabel for="surface" :value="$t('race.surface')"/>

                                <NSelect v-model:value="form.surface" :input-props="{ id: 'surface' }" filterable tag
                                         clearable
                                         :options="optionsSurface" class="mt-1 w-full"/>

                                <InputError class="mt-2" :message="form.errors.surface"/>
                            </div>

                            <div class="mt-4">
                                <InputLabel for="type" :value="$t('race.type')"/>

                                <NSelect v-model:value="form.type" :input-props="{ id: 'type' }" filterable tag
                                         clearable
                                         :options="optionsType" class="mt-1 w-full"/>

                                <InputError class="mt-2" :message="form.errors.type"/>
                            </div>

                            <div class="mt-4">
                                <InputLabel for="type" :value="$t('race.tag')"/>

                                <NSelect v-model:value="form.tag" :input-props="{ id: 'tag' }" filterable tag
                                         clearable
                                         :options="optionsTag" class="mt-1 w-full"/>

                                <InputError class="mt-2" :message="form.errors.tag"/>
                            </div>

                            <div class="flex items-center justify-between mt-4 gap-4">
                                <MyLink :href="route('admin.races.index')" type="default">
                                    {{ $t('admin.runner.back') }}
                                </MyLink>
                                <NButton attr-type="submit" :class="{ 'opacity-25': form.processing }"
                                         :disabled="form.processing"
                                         type="info" round>
                                    {{ $t('admin.races.update') }}
                                </NButton>
                            </div>
                        </form>
                    </div>
                    <div class="col-span-1 md:col-span-2">
                        <div class="grid gap-4">
                            <ReloadMeilisearchData :entity-id="race.id" entity="Race" />
                            <section class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 md:p-6 space-y-6">
                                <div class="flex justify-between items-center mb-2">
                                    <h2 class="text-lg font-medium text-gray-900">{{ $t('admin.races.results') }} - {{ resultCount }}</h2>
                                    <PrimaryButton :href="route('admin.results.show', {race: race.id})" link color="blue" rounded outline>
                                        {{ $t('admin.races.showResults') }}
                                    </PrimaryButton>
                                </div>
                            </section>
                            <UploadFileForm :race="race" :files="files" />
                            <DeleteRaceForm :race="race"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>

</style>