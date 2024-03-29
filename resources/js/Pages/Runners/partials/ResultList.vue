<script setup>
import { Link, router, usePage } from '@inertiajs/vue3'
import { NIcon } from 'naive-ui'
import { EmojiEventsOutlined, KeyboardArrowDownFilled, KeyboardArrowUpFilled } from '@vicons/material'
import ConfettiExplosion from "vue-confetti-explosion";
import { computed, ref } from 'vue'
import SortBlock from '@/Components/SortBlock.vue'

const props = defineProps({
    results: {
        type: Array,
        required: true,
    },
    sort: {
        type: String,
        required: false,
    }
})

const confettiDuration = 2500
const confetti = ref(false)
const confettiElement = ref(null)

const startConfetti = (event) => {
    if (confetti.value) {
        return
    }
    confetti.value = true
    const x = event.pageX
    const y = event.pageY

    confettiElement.value.style.height = '500px'
    confettiElement.value.style.width = '500px'
    confettiElement.value.style.left = x - 250 + 'px'
    confettiElement.value.style.top = y - 250 + 'px'
    setTimeout(() => {
        confetti.value = false
        confettiElement.value.style.left = '0px'
        confettiElement.value.style.top = '0px'
        confettiElement.value.style.height = '0'
        confettiElement.value.style.width = '0'
    }, confettiDuration)
}

const activeSort = computed(() => {
    const sort = props.sort.split(':')
    return {
        name: sort[0],
        isAsc: sort[1] === 'asc',
    }
});
</script>

<template>
    <section>
        <div v-show="confetti" ref="confettiElement" class="absolute flex justify-center items-center z-50 overflow-hidden" style="width: 500px; height: 500px;">
            <ConfettiExplosion v-if="confetti" :duration="confettiDuration" :particleCount="50" :particleSize="10" :stageWidth="500" :stageHeight="1000"></ConfettiExplosion>
        </div>
        <div class="grid grid-cols-11 gap-2 md:gap-4 border-b">
            <SortBlock class="flex justify-start items-center font-bold p-3 md:px-4" name="date" :is-active="activeSort.name === 'date'" :is-asc="activeSort.isAsc">
                {{ $t('race.date') }}
            </SortBlock>
            <SortBlock class="flex justify-start items-center col-span-3 font-bold p-3 md:px-4" name="name" :is-active="activeSort.name === 'name'" :is-asc="activeSort.isAsc">
                {{ $t('race.name') }}
            </SortBlock>
            <SortBlock class="flex justify-start items-center col-span-2 font-bold p-3 md:px-4" name="location" :is-active="activeSort.name === 'location'" :is-asc="activeSort.isAsc">
                {{ $t('race.location') }}
            </SortBlock>
            <SortBlock class="flex justify-start items-center font-bold p-3 md:px-4" name="distance" :is-active="activeSort.name === 'distance'" :is-asc="activeSort.isAsc">
                {{ $t('race.distance') }}
            </SortBlock>
            <SortBlock class="flex justify-start items-center font-bold p-3 md:px-4" name="time" :is-active="activeSort.name === 'time'" :is-asc="activeSort.isAsc">
                {{ $t('result.time') }}
            </SortBlock>
            <SortBlock class="flex justify-center items-center font-bold p-3 md:px-4" name="position" :is-active="activeSort.name === 'position'" :is-asc="activeSort.isAsc">
                {{ $t('result.position') }}
            </SortBlock>
            <SortBlock class="flex justify-center items-center col-span-2 font-bold p-3 md:px-4" name="categoryPosition" :is-active="activeSort.name === 'categoryPosition'" :is-asc="activeSort.isAsc">
                {{ $t('result.categoryPosition') }}
            </SortBlock>
        </div>
        <Link v-for="(result, index) in results" :key="result.id" :href="route('races.show', { race: result.race.slug, runnerId: result.runner.id })" class="grid grid-cols-11 gap-2 md:gap-4 hover:bg-gray-100"
              :class="{
                'bg-gray-50': index%2 === 0,
                }">
            <div class="p-3 md:px-4 text-center flex justify-center items-center">{{ result.race.date }}</div>
            <div class="col-span-3 p-3 md:px-4">{{ result.race.name }}</div>
            <div class="col-span-2 p-3 md:px-4">{{ result.race.location }}</div>
            <div class="p-3 md:px-4 flex justify-center items-center">{{ result.race.distance }}</div>
            <div class="p-3 md:px-4 flex justify-center items-center">{{ result.time }}</div>
            <div class="p-3 md:px-4 text-center flex justify-center items-center gap-2">
                <NIcon v-if="result.position < 4"
                       :class="{
                            'text-2xl text-gold-500': result.position === 1,
                            'text-xl text-silver-500': result.position === 2,
                            'text-lg text-bronze-500': result.position === 3,
                       }"
                       @mouseenter="startConfetti"
                >
                    <EmojiEventsOutlined />
                </NIcon>
                <span>{{ result.position }}</span>
            </div>
            <div class="col-span-2 p-3 md:px-4 flex justify-center items-center">{{ result.categoryPosition }} ({{ result.category }})</div>
        </Link>
    </section>
</template>

<style scoped>

</style>