<script lang="ts">
import { useEventFilter } from '@/stores/useEventFilter'
import EventForm from '@/components/EventForm.vue'
import EventGrid from '@/components/EventGrid.vue'

export default {
    components: {
        EventForm,
        EventGrid
    },
    setup() {
        const filterStore = useEventFilter();
        return {filter: filterStore}
    },
}
</script>

<template>
    <div id="filterEvents">
        <Transition>
            <EventForm/>
        </Transition>
        <form>
            <input type="number" min="0" max="52" v-model="filter.week" @change="filter.fetchEvent"/>
            <input type="number" v-model="filter.year" @change="filter.fetchEvent"/>
        </form>
        <div id="gridEvents">
            <EventGrid/>
        </div>
    </div>
</template>

<style scoped>
#filterEvents {
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items: center;
}
#gridEvents {
    display: flex;
    width: 100vw;
    overflow-x: hidden;
    justify-content:center;
}

.v-enter-active,
.v-leave-active {
    transition: all 0.5s ease;
    left: 75vw;
}

.v-enter-from,
.v-leave-to {
    left: 100vw;
}
</style>
