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
    href: {
        type: String,
        required: true,
    },
})

const colorClasses = {
    primary: 'text-violet-950 hover:text-violet-800',
    secondary: 'bg-white hover:bg-gray-50 text-gray-700',
    danger: 'bg-red-600 hover:bg-red-700',
    link: 'text-sea-pink-600 hover:text-sea-pink-700 ',
}

const typeClasses = {
    button: 'px-4 py-1 rounded-full border border-violet-950 hover:bg-violet-950 hover:text-white',
    link: 'underline',
}

const defaultTypeClasses = typeClasses[props.type] ?? 'text-violet-950 hover:text-violet-800'

const defaultClasses = colorClasses[props.color] ?? 'text-violet-950 hover:text-violet-800'
</script>

<template>
    <Link
            :href="href"
            class=""
            :class="defaultClasses + ' ' + defaultTypeClasses"
    >
        <slot/>
    </Link>
</template>

<style scoped>

</style>