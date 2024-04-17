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
    const evnt = ref<Reservation | null>()
    let room: number | null = null
    let day: number | null = null
    if (props.id != null) {
      const dayVal = props.id % 5
      room = Math.ceil(props.id / 5)
      day = dayVal != 0 ? dayVal : 5
    }
    return { filterStore, props, ev: evnt, day: day, room: room }
  },
  computed: {
    getReservation() {
      this.ev = this.filterStore.results.filter((e) => {
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
      return this.ev
    },
    setGridStyle() {
      let style = ''
      const filterStore = useResaFilter()
      if (this.ev != null && this.day != null && this.room != null) {
        // if event started before this day and end this day
        if (this.ev.startDate.getDay() < this.day && this.ev.endDate.getDay() == this.day) {
          style += 'border-left:0px;'
        }
        // if event started before this day and end after
        else if (this.ev.startDate.getDay() < this.day && this.ev.endDate.getDay() > this.day) {
          style += 'border-left:0px;'
        }
        if (filterStore.selected != null && this.ev.id == filterStore.selected.id) {
          style += 'color:white;'
        }
        let color =
          'background-color:rgba(' +
          this.ev.startDate.getDay() * 42.5 +
          ',' +
          this.room * 23.18 +
          ',' +
          (this.room - this.ev.startDate.getDay() + 5) * 25 +
          ', 50%);'
        style += color
      }
      return style
    },
    splitReservationName() {
      let evName = ''
      if (this.ev != null) {
        evName = this.ev.name.slice(0, 35)
      }
      return evName
    }
  },
  methods: {
    selectReservation() {
      if (this.ev != null) {
        if (this.filterStore.selected != null && this.ev.id == this.filterStore.selected.id) {
          this.filterStore.selectResa(null)
        } else {
          this.filterStore.selectResa(this.ev)
        }
      } else {
        this.filterStore.selectResa(null)
      }
    }
  }
})
</script>

<template>
  <div class="gridElement" :style="setGridStyle" @click="selectReservation">
    <div v-if="getReservation != null && day != null && room != null">
      <div class="infoReservation" v-if="getReservation.startDate.getDay() == day">
        <p style="align-self: flex-start">{{ splitReservationName }}</p>
        <span
          style="display: flex; flex-direction: row; width: 100%; justify-content: space-between"
        >
          <p style="align-self: flex-start">
            {{ getReservation.roomConfiguration }}
            {{ getReservation.configurationSize }}
            {{
              getReservation.configurationQuantity != null
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
    <div v-else class="empty">
      <button id="add-event">+</button>
    </div>
  </div>
</template>

<style>
.gridElement div {
  border-radius: 0.1em;
  height: 100%;
  width: 100%;
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  align-items: center;
  margin: 0px;
  font-size: 0.7em;
}

.gridElement {
  width: 100%;
  height: 100%;
  min-height: 3em;
  color: black;
  border-top: 0px;
  border-left: 1px;
  border-bottom: 1px;
  border-right: 0px;
  border-color: black;
  border-style: solid;
  display: flex;
  flex-direction: row;
  justify-content: center;
  align-items: center;
  padding: 0.2em;
  transition-duration: 0.3s;
}

#add-event:hover {
  cursor: pointer;
  background-color: darkgreen;
  color: white;
}

#add-event {
  background-color: lightgreen;
  border-radius: 5px;
  border-style: none;
  align-self: self-end;
  width: 1.5em;
  height: 1.5em;
  transition-duration: 0.2s;
}

.gridElement .empty {
  width: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
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
</style>
