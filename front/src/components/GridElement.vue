<script lang="ts">
import type { Reservation } from '@/classes/Reservation'
import { useGridFilter } from '@/stores/useGridFilter'
import { useResaFilter } from '@/stores/useResaFilter'
import { defineComponent, ref } from 'vue'

export default defineComponent({
  props: {
    id: Number
  },
  setup(props) {
    const filterStore = useResaFilter()
    const gridStore = useGridFilter()
    const resa = ref<Reservation | null>()
    const color = ref<string | null>()
    let room: number | null = null
    let day: number | null = null
    if (props.id != null) {
      const dayVal = props.id % 5
      room = Math.ceil(props.id / 5)
      day = dayVal != 0 ? dayVal : 5
    }

    return { gridStore, filterStore, props, resa, day, room, color, id: props.id }
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

    setGridContentStyle() {
      let style = ''
      const hoveredGridId = this.gridStore.getHovered
      let hoveredGridResa = null
      if (hoveredGridId != null) {
        hoveredGridResa = this.gridStore.getReservationFromGridId(hoveredGridId)
      }
      if (this.resa != null && this.day != null && this.room != null) {
        // Dynamic background color
        let redVal = 100 + (this.resa.startDate.getDay() - 1) * 17.5 // [100-170]
        let greenVal = 130 + (this.room - 1) * 1.81 // [130 - 150]
        let blueVal = 130 + (this.room - 1 + (this.resa.startDate.getDay() - 1) * 5) * 2.9 // [130-220]
        let color = 'background-color:rgba(' + redVal + ',' + greenVal + ',' + blueVal + ', 100%);'
        style += color
        redVal -= 35
        greenVal -= 35
        blueVal -= 35
        let shadowColor = 'rgb(' + redVal + ',' + greenVal + ',' + blueVal + ')'
        redVal += 60
        greenVal += 60
        blueVal += 60
        let borderColor = 'rgb(' + redVal + ',' + greenVal + ',' + blueVal + ')'
        // if resa started today and end later
        if (this.resa.startDate.getDay() == this.day && this.resa.endDate.getDay() != this.day) {
          style += 'border-radius:0.5rem 0 0 0.5rem;'
          // if resa is hovered or selected
          if (
            (hoveredGridResa != null && this.resa.id == hoveredGridResa.id) ||
            (this.gridStore.selected != null && this.resa.id == this.gridStore.selected.id)
          ) {
            style += 'filter:brightness(115%);box-shadow:inset 3px -3px 3px ' + shadowColor + ';'
            style += 'border: solid 1px ' + borderColor + ';border-right:0px; '
          }
        }

        // if resa started before today and end today
        else if (
          this.resa.startDate.getDay() < this.day &&
          this.resa.endDate.getDay() == this.day
        ) {
          style += 'border-radius:0 0.5rem 0.5rem 0;'
          if (
            (hoveredGridResa != null && this.resa.id == hoveredGridResa.id) ||
            (this.gridStore.selected != null && this.resa.id == this.gridStore.selected.id)
          ) {
            style +=
              'filter:brightness(115%);box-shadow:0px -7px 4px -4px inset ' + shadowColor + ';'
            style += 'border: solid 1px ' + borderColor + ';border-left:0px;'
          }
        }
        // if reservation started before today and end after
        else if (this.resa.startDate.getDay() < this.day && this.resa.endDate.getDay() > this.day) {
          style += 'border-radius:0 0 0 0;'
          if (
            (hoveredGridResa != null && this.resa.id == hoveredGridResa.id) ||
            (this.gridStore.selected != null && this.resa.id == this.gridStore.selected.id)
          ) {
            style +=
              'filter:brightness(115%);box-shadow:0px -7px 3px -4px inset ' + shadowColor + ';'
            style += 'border: solid 1px ' + borderColor + ';border-left:0px;border-right:0px; '
          }
        }
        // if resa start and end today
        else {
          style += 'border-radius:0.5rem 0.5rem 0.5rem 0.5rem;'
          if (
            (hoveredGridResa != null && this.resa.id == hoveredGridResa.id) ||
            (this.gridStore.selected != null && this.resa.id == this.gridStore.selected.id)
          ) {
            style +=
              'filter:brightness(115%);box-shadow: 3px -3px 3px 0px inset ' + shadowColor + ';'
            style += 'border: solid 1px ' + borderColor + ';'
          }
        }
      }
      return style
    },
    setEmptyGridElementHover() {
      let style = ''
      // Empty grid day
      if (
        this.resa == null &&
        this.day != null &&
        this.room != null &&
        this.gridStore.hovered == this.id
      ) {
        style += 'background-color: rgba(74,90,128,30%);'
      }
      return style
    },
    setGridBoxStyle() {
      let style = ''
      if (this.resa != null && this.day != null && this.room != null) {
        // if event started today and end tomorow or after;
        if (this.resa.startDate.getDay() == this.day && this.resa.endDate.getDay() != this.day) {
          style += 'padding:0.1rem 0 0.1rem 0.1rem;'
        }
        // if event started before this day and end this day
        else if (
          this.resa.startDate.getDay() < this.day &&
          this.resa.endDate.getDay() == this.day
        ) {
          style += 'padding:0.1rem 0.1rem 0.1rem 0;'
        }
        // if event started before this day and end after
        else if (this.resa.startDate.getDay() < this.day && this.resa.endDate.getDay() > this.day) {
          style += 'padding:0.1rem 0 0.1rem 0;'
        } else {
          style += 'padding:0.1rem 0.1rem 0.1rem 0.1rem;'
        }
      }

      return style
    },
    splitReservationName() {
      let evName = ''
      if (this.resa != null) {
        evName = this.resa.name.slice(0, 28)
        if (this.resa.name.length > 28) {
          evName += '...'
        }
      }
      return evName
    }
  },
  methods: {
    selectReservation() {
      if (this.resa != null) {
        if (this.gridStore.selected != null && this.resa.id == this.gridStore.selected.id) {
          this.gridStore.selectResa(null)
        } else {
          this.gridStore.selectResa(this.resa)
        }
      } else {
        this.gridStore.selectResa(null)
      }
    },
    selectHoveredGridId() {
      if (this.id != null) {
        this.gridStore.hovered = this.id
      }
    }
  }
})
</script>

<template>
  <div
    :style="setGridBoxStyle"
    class="gridElement"
    @click="selectReservation"
    @mouseover="selectHoveredGridId"
  >
    <div
      :style="setGridContentStyle"
      :class="getReservation != null ? 'hoverable' : ''"
      v-if="getReservation != null && day != null && room != null"
    >
      <div class="infoReservation" v-if="getReservation.startDate.getDay() == day">
        <h1 style="align-self: flex-start" :title="getReservation.name">
          {{ splitReservationName }}
        </h1>
        <span class="flex-row just-between" style="width: 100%">
          <p style="align-self: flex-start">
            {{ getReservation.roomConfiguration }}
            {{ getReservation.configurationSize }}
            {{
              getReservation.configurationQuantity != null &&
              getReservation.roomConfiguration == 'Ilots'
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
    <div v-else class="add-event" :style="setEmptyGridElementHover">
      <Transition>
        <button v-if="id == gridStore.getHovered" class="add-btn">+</button>
      </Transition>
    </div>
  </div>
</template>

<style>
.gridElement div h1 {
  font-weight: bold;
  font-size: 1.5em;
}

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
  display: flex;
  flex-direction: row;
  justify-content: center;
  align-items: center;
  transition-duration: 0.3s;
  border-radius: 0.5em;
}

.hoverable:hover {
  cursor: pointer;
}

.add-btn {
  background-color: #434f77;
  color: white;
  border-radius: 0.65em;
  border-style: none;
  align-self: self-end;
  width: 3em;
  height: 100%;
  transition-duration: 0.2s;
  position: relative;
}

.add-btn:hover {
  cursor: pointer;
  border-style: none;
  color: #eeeeee;
  filter: brightness(120%);
}

.add-event {
  margin: 0.2em;
  border-radius: 0.8em;
  overflow: hidden;
  width: 90%;
  height: 90%;
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
  left: 0;
}

.v-enter-from,
.v-leave-to {
  left: 120%;
}
</style>
