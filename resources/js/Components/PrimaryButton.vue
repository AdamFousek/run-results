<script setup>
import {computed} from "vue";
import MyLink from '@/Components/MyLink.vue'


const props = defineProps({
    type: {
        type: String,
        default: 'button',
    },
    color: {
        type: String,
        default: 'default',
    },
    size: {
        type: String,
        default: 'default',
    },
    rounded: {
        type: Boolean,
        default: false,
    },
    outline: {
        type: Boolean,
        default: false,
    },
    link: {
        type: Boolean,
        default: false,
    },
});

const classes = computed(() => {
    return {
        'blue': 'bg-indigo-500 text-white active:bg-indigo-600 hover:bg-indigo-600 shadow hover:shadow-md',
        'blue-outline': 'text-indigo-500 border border-indigo-500 hover:bg-indigo-500 hover:text-white active:bg-indigo-600',
        'yellow': 'bg-amber-500 text-white active:bg-amber-600 hover:bg-amber-600 shadow hover:shadow-md',
        'yellow-outline': 'text-amber-500 border border-amber-500 hover:bg-amber-500 hover:text-white active:bg-amber-600',
        'red': 'bg-red-500 text-white active:bg-red-600 hover:bg-red-600  px-4 py-2 shadow hover:shadow-md',
        'red-outline': 'text-red-500 border border-red-500 hover:bg-red-500 hover:text-white active:bg-red-600',
        'primary-outline': 'text-emerald-500 border border-emerald-500 hover:bg-emerald-500 hover:text-white active:bg-emerald-600',
        'default': 'inline-flex items-center bg-violet-950 border border-transparent text-white tracking-widest hover:bg-violet-800 active:bg-violet-900',
    }[props.color];
});

const sizeClasses = computed(() => {
    return {
        'small': 'text-xs px-3 py-2',
        'default': 'text-xs px-4 py-2',
        'large': 'text-base px-6 py-3',
    }[props.size];
});

const roundedClasses = computed(() => {
    return props.rounded ? 'rounded-full' : 'rounded';
});

const outline = computed(() => {
    return {
        'blue': 'text-indigo-500 border border-indigo-500 hover:bg-indigo-500 hover:text-white active:bg-indigo-600',
        'yellow': 'text-amber-500 border border-amber-500 hover:bg-amber-500 hover:text-white active:bg-amber-600',
        'red': 'text-red-500 border border-red-500 hover:bg-red-500 hover:text-white active:bg-red-600',
        'primary': 'text-emerald-500 border border-emerald-500 hover:bg-emerald-500 hover:text-white active:bg-emerald-600',
        'default': 'inline-flex items-center bg-violet-950 border border-transparent text-white tracking-widest hover:bg-violet-800 active:bg-violet-900',
    }[props.color];
});

let defaultClass = classes.value;
if (props.outline) {
    defaultClass = outline.value;
}
</script>

<template>
    <button
            v-if="!link"
            :type="type"
            class="transition-all duration-150 disabled:opacity-25 ease-linear outline-none focus:outline-none font-semibold"
            :class="[defaultClass, sizeClasses, roundedClasses]"
    >
        <slot/>
    </button>
    <MyLink v-else
            without-classes
            class="transition-all duration-150 disabled:opacity-25 ease-linear outline-none focus:outline-none font-semibold"
            :class="[defaultClass, sizeClasses, roundedClasses]">
        <slot/>
    </MyLink>
</template>
