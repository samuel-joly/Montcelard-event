<script lang="ts">
import type { Event } from '@/classes/Event'
import { EventGridContent } from '@/helpers/EventGrid'
import { useEventFilter } from '@/stores/useEventFilter'
import { defineComponent, onMounted, ref } from 'vue'

export default defineComponent({
  props: {
    id: Number
  },
  setup(props) {
    const filterStore = useEventFilter()
    const evnt = ref<Event | null>()
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
    getEvent() {
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
      const filterStore = useEventFilter()
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
    splitEventName() {
      let evName = ''
      if (this.ev != null) {
        evName = this.ev.name.slice(0, 35)
      }
      return evName
    }
  },
  methods: {
    selectEvent() {
      if (this.ev != null) {
        if (this.filterStore.selected != null && this.ev.id == this.filterStore.selected.id) {
          this.filterStore.selectEvent(null)
        } else {
          this.filterStore.selectEvent(this.ev)
        }
      } else {
        this.filterStore.selectEvent(null)
      }
    }
  }
})
</script>

<template>
  <div class="gridElement" :style="setGridStyle" @click="selectEvent">
    <div v-if="getEvent != null && day != null && room != null">
      <div class="infoEvent" v-if="getEvent.startDate.getDay() == day">
        <p style="align-self: flex-start">{{ splitEventName }}</p>
        <span
          style="display: flex; flex-direction: row; width: 100%; justify-content: space-between"
        >
          <p style="align-self: flex-start">
            {{ getEvent.roomConfiguration }}
            {{ getEvent.configurationSize }}
            {{ getEvent.configurationQuantity != null ? 'x' + getEvent.configurationQuantity : '' }}
          </p>
          <p style="align-self: flex-end">{{ getEvent.guests }} pax</p>
        </span>
      </div>
      <p v-if="getEvent.startDate.getDay() + 1 == day">{{ getEvent.hostName }}</p>
      <p v-if="getEvent.startDate.getDay() + 2 == day">{{ getEvent.meal }}</p>
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

.infoEvent p {
  font-size: 1.5em;
}

.infoEvent {
  padding: 0px;
  width: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}
</style>
