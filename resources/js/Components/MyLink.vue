<script setup>
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    color: {
        type: String,
        required: false,
        default: 'primary',
    },
    type: {
        type: String,
        required: false,
        default: 'link',
    },
    external: {
        type: Boolean,
        required: false,
        default: false,
    },
    withoutClasses: {
        type: Boolean,
        required: false,
        default: false,
    },
})

const colorClasses = {
    primary: 'text-violet-950 hover:text-violet-800',
    secondary: 'bg-white hover:bg-gray-50 text-gray-700',
    danger: 'bg-red-600 hover:bg-red-700',
    link: 'text-indigo-800 hover:text-indigo-950',
    linkSecondary: 'text-rose-600 hover:text-rose-800',
}

const typeClasses = {
    button: 'px-4 py-1 rounded-full border border-violet-950 hover:bg-violet-950 hover:text-white',
    link: 'underline',
}

const defaultTypeClasses = typeClasses[props.color]

const defaultClasses = colorClasses[props.type] ?? 'text-violet-950 hover:text-violet-800'

const classes = defaultClasses + ' ' + defaultTypeClasses
</script>

<template>
    <Link
            v-if="!external"
            class="cursor-pointer"
            :class="withoutClasses ? '' : classes"
    >
        <slot/>
    </Link>
    <a v-else
       class="cursor-pointer"
       :class="withoutClasses ? '' : classes">
        <slot />
    </a>
</template>

<style scoped>

</style>