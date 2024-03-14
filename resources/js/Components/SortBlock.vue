<script setup lang="ts">
import {KeyboardArrowDownFilled, KeyboardArrowUpFilled} from "@vicons/material";
import {NIcon} from "naive-ui";
import {router, usePage} from "@inertiajs/vue3";

const props = withDefaults(defineProps<{
        name: string,
        isActive: boolean,
        isAsc?: boolean,
    }>(),
    {
        isAsc: false,
    });

const changeSort = () => {
    const query = (usePage().props.ziggy as any).query
    const sort = props.isAsc ? `${props.name}:desc` : `${props.name}:asc`
    router.reload({
        data: {
            ...query,
            ...{
                sort
            }
        },
    })
}
</script>

<template>
    <div class="cursor-pointer text-violet-900 hover:text-violet-800" @click="changeSort()">
        <div class="flex flex-col">
            <NIcon v-if="isActive && isAsc">
                <KeyboardArrowUpFilled />
            </NIcon>
            <NIcon v-if="isActive && !isAsc">
                <KeyboardArrowDownFilled />
            </NIcon>
        </div>
        <slot />
    </div>
</template>

<style scoped>

</style>