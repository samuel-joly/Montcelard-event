<script lang="ts">
import type { Reservation } from '@/classes/Reservation'
import { useResaFilter } from '@/stores/useResaFilter'
import { defineComponent, onMounted, ref } from 'vue'

export default defineComponent({
    props: {
        id: Number
    },
    setup(props) {
        const filterStore = useResaFilter()
        const resa = ref<Reservation | null>()
        let room: number | null = null
        let day: number | null = null
        if (props.id != null) {
            const dayVal = props.id % 5
            room = Math.ceil(props.id / 5)
            day = dayVal != 0 ? dayVal : 5
        }
        return { filterStore, props, resa: resa, day: day, room: room, id: props.id }
    },
    computed: {
        getReservation() {
            this.resa = this.filterStore.results.filter((e) => {
                if (this.day != null && this.room != null) {
                    return (
                        e.roomId == this.room &&
                        e.startDate.getDay() <= this.day &&
                        e.endDate.getDay() >= this.day
                    )
                } else {
                    return false
                }
            })[0]
            return this.resa
        },
        setGridStyle() {
            let style = ''
            const filterStore = useResaFilter()
            if (this.resa != null && this.day != null && this.room != null) {
                // if event started today and end tomorow or after;
                if (this.resa.startDate.getDay() == this.day && this.resa.endDate.getDay() != this.day) {
                    style += 'border-radius:0.5rem 0 0 0.5rem;'
                }
                // if event started before this day and end this day
                else if (this.resa.startDate.getDay() < this.day && this.resa.endDate.getDay() == this.day) {
                    style += 'border-radius:0 0.5rem 0.5rem 0;'
                }
                // if event started before this day and end after
                else if (this.resa.startDate.getDay() < this.day && this.resa.endDate.getDay() > this.day) {
                    style += 'border-radius:0 0 0 0;'
                } else {
                    style += 'border-radius:0.5rem 0.5rem 0.5rem 0.5rem;'
                }
                if (filterStore.selected != null && this.resa.id == filterStore.selected.id) {
                    style += 'color:white;'
                }
                let color =
                    'background-color:rgba(' +
                    (50 + this.resa.startDate.getDay() * 41.0) +
                    ',' +
                    (100 + this.room * 8.75) +
                    ',' +
                    (100 + (this.room + (this.resa.endDate.getDay() - this.resa.startDate.getDay())) * 6.56) +
                    ', 100%);'
                style += color;
            }

            return style
        },
        setGridChildStyle() {
            let style = ''
            const filterStore = useResaFilter()
            if (this.resa != null && this.day != null && this.room != null) {
                // if event started today and end tomorow or after;
                if (this.resa.startDate.getDay() == this.day && this.resa.endDate.getDay() != this.day) {
                    style += 'padding:0.1rem 0 0.1rem 0.1rem;'
                }
                // if event started before this day and end this day
                else if (this.resa.startDate.getDay() < this.day && this.resa.endDate.getDay() == this.day) {
                    style += 'padding:0.1rem 0.1rem 0.1rem 0;'
                }
                // if event started before this day and end after
                else if (this.resa.startDate.getDay() < this.day && this.resa.endDate.getDay() > this.day) {
                    style += 'padding:0.1rem 0 0.1rem 0;'
                } else {
                    style += 'padding:0.1rem 0.1rem 0.1rem 0.1rem;'
                }
            }

            if (filterStore.getHovered != null) {
                let hoveredId = filterStore.getHovered;
                if (this.id != null) {
                    let sum = 5;
                    if (this.id < hoveredId) {
                        do {
                            if (this.id == hoveredId - sum) {
                                style += "background-color:#adb2c4";
                                break;
                            } else {
                                sum += 5;
                            }
                        } while (sum <= hoveredId);

                        sum = 1;
                        if ((hoveredId-1)%5 != 0) {
                            do {
                                if (this.id == hoveredId - sum) {
                                    style += "background-color:#adb2c4";
                                    break;
                                } else {
                                    sum += 1;
                                }
                            } while ((hoveredId - sum) % 5 != 0);
                        }
                    } 
                }
            }
            return style;
        },
        splitReservationName() {
            let evName = ''
            if (this.resa != null) {
                evName = this.resa.name.slice(0, 30)
                if (this.resa.name.length > 30) {
                    evName += "...";
                }
            }
            return evName
        },
    },
    methods: {
        selectReservation() {
            if (this.resa != null) {
                if (this.filterStore.selected != null && this.resa.id == this.filterStore.selected.id) {
                    this.filterStore.selectResa(null)
                } else {
                    this.filterStore.selectResa(this.resa)
                }
            } else {
                this.filterStore.selectResa(null)
            }
        },
        setHoverBehavior() {
            if (this.id != null) {
                this.filterStore.hovered = this.id;
            }
        }
    }
})
</script>

<template>
    <div :style="setGridChildStyle" class="gridElement" @click="selectReservation" @mouseover="setHoverBehavior">
        <div :style="setGridStyle" :class="getReservation != null ? 'hoverable' : ''"
            v-if="getReservation != null && day != null && room != null">
            <div class="infoReservation" v-if="getReservation.startDate.getDay() == day">
                <p style="align-self: flex-start" :title="getReservation.name">{{ splitReservationName }}</p>
                <span class="flex-row just-between" style="width:100%;">
                    <p style="align-self: flex-start">
                        {{ getReservation.roomConfiguration }}
                        {{ getReservation.configurationSize }}
                        {{
                            (getReservation.configurationQuantity != null && getReservation.roomConfiguration == "Ilots")
                            ? 'x' + getReservation.configurationQuantity
                            : ''
                        }}
                    </p>
                    <p style="align-self: flex-end">{{ getReservation.guests }} pax</p>
                </span>
            </div>
            <p v-if="getReservation.startDate.getDay() + 1 == day">{{ getReservation.hostName }}</p>
            <p v-if="getReservation.startDate.getDay() + 2 == day">{{ getReservation.meal }}</p>
        </div>
        <div v-else class="add-event">
            <Transition>
                <button v-if="id == filterStore.getHovered"class="add-btn">+</button>
            </Transition>
        </div>
    </div>
</template>

<style>
.gridElement div {
    height: 100%;
    padding: 0.2rem;
    width: 100%;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    align-items: flex-start;
    font-size: 0.7em;
}

.gridElement {
    width: 100%;
    height: 100%;
    min-height: 3.5rem;
    color: black;
    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: center;
    transition-duration: 0.3s;
}

.hoverable:hover {
    cursor: pointer;
}

.add-btn {
    background-color: rgba(175,175,175,30%);
    border-radius:0.5em;
    border-style: none;
    align-self: self-end;
    width: 25%;
    height: 100%;
    transition-duration: 0.2s;
    position:relative;
}

.add-btn:hover {
    cursor: pointer;
    background-color: rgba(175,175,175,70%)
}

.add-event {
    height:100%;
    overflow: hidden;
}

.infoReservation p {
    font-size: 1.5em;
}

.infoReservation {
    padding: 0px;
    width: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.v-enter-active,
.v-leave-active {
    transition: all 0.2s ease;
    left:0;
}

.v-enter-from,
.v-leave-to {
    left: 120%;
}
</style>
