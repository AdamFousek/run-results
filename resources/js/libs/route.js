import { usePage } from '@inertiajs/vue3'
import { computed, toRefs } from 'vue'
import ziggyRoute from 'ziggy';

const ziggy = computed(() => {
    return {
        ...usePage().props.ziggy,
        location: new URL(usePage().props.ziggy.location)
    }
})

export default function useRoute() {
    const route = (name, params, absolute, config = ziggy) => ziggyRoute(name, params, absolute, config)

    return {
        ...toRefs(ziggy),
        route
    }
}