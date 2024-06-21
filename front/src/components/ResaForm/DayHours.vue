<script lang="ts">
import { useGridFilter } from '@/stores/useGridFilter'
import { useResaFilter } from '@/stores/useResaFilter'
import { defineComponent, ref } from 'vue'

interface OffsetInterface {
  day: number
  hour: string
}

export default defineComponent({
  setup() {
    const filterStore = useResaFilter()
    const gridStore = useGridFilter()
    const isFullWeek = ref<Boolean>(true)
    const resaDays = ref<Array<String> | null>()
    const daySelection = ref<Number | null>()

    const startOffsets = ref<Array<OffsetInterface>>([])
    const endOffsets = ref<Array<OffsetInterface>>([])

    return {
      gridStore,
      filterStore,
      isFullWeek,
      resaDays,
      daySelection,
      startOffsets,
      endOffsets
    }
  },
  methods: {
    fromStrToOffset(base: number, offset: string): OffsetInterface {
      let newHour = 0.0
      let ofsDec = 0
      if (offset.match(/\+/)) {
        const ofsSplit = offset.split('+')
        if (ofsSplit[1].match(/\./)) {
          ofsDec = Number(ofsSplit[1].split('.')[1]) * 0.6
        }
        const ofs = Number(ofsSplit[1].split('.')[0] + '.' + ofsDec)
        newHour = base + ofs
      } else if (offset.match(/-/)) {
        const ofsSplit = offset.split('-')
        if (ofsSplit[1].match(/\./)) {
          ofsDec = Number(ofsSplit[1].split('.')[1]) * 0.6
        }
        const ofs = Number(ofsSplit[1].split('.')[0] + '.' + ofsDec)
        newHour = base - ofs
      }
      let newTime = String(newHour)
      if (newTime.match(/\./) == null) {
        newTime += '.00'
      }
      if (newTime.split('.')[0].length < 2) {
        newTime = '0' + newTime
      }
      if (newTime.split('.')[1].length < 2) {
        newTime = newTime + '0'
      }
      newTime = newTime.replace('.', ':')
      return { day: Number(offset[0]), hour: newTime }
    },

    fromOffsetToStr(ofs: Array<OffsetInterface>, base: string): string {
      let ret = ''

      ofs.map((e: OffsetInterface) => {
        if (this.gridStore.selected != null) {
          let offsetHourSplit = e.hour.split(':')
          let ofsDec = Number('0.' + offsetHourSplit[1]) * 1.66666666666666666666
          let newOfsDec = '00'
          if (String(ofsDec).match(/\./)) {
            newOfsDec = String(ofsDec).split('.')[1]
          }
          const normOfs = Number(offsetHourSplit[0] + '.' + newOfsDec)

          let diff = Math.floor((Number(base) - normOfs) * 10) / 10
          if (diff < 0) {
            ret += e.day + String(diff).replace('-', '+') + ';'
          } else if (diff != 0) {
            ret += e.day + '-' + diff + ';'
          }
        }
      })
      return ret
    },
    getStartHourValue(index: number): String {
      if (this.gridStore.selected != null) {
        if (index == 0) {
          return this.gridStore.selected.startHour
        } else if (index > 0) {
          let offs = this.startOffsets.filter((e) => e.day == index + 1)
          if (offs.length > 0) {
            return offs[0].hour
          } else {
            return this.gridStore.selected.startHour
          }
        }
      }
      return ''
    },
    getEndHourValue(index: number): String {
      if (this.gridStore.selected != null) {
        if (index == 0) {
          return this.gridStore.selected.endHour
        } else if (index > 0) {
          let offs = this.endOffsets.filter((e) => e.day == index + 1)
          if (offs.length > 0) {
            return offs[0].hour
          } else {
            return this.gridStore.selected.endHour
          }
        }
      }
      return ''
    },
    setStartHourOffset(value: string, index: number) {
      if (this.gridStore.selected != null) {
        if (index == 0) {
          this.gridStore.selected.startHour = value
          let offsetStr = this.fromOffsetToStr(this.startOffsets, value.replace(':', '.'))
          let offsets: Array<OffsetInterface> = []
          offsetStr.split(';').map((e) => {
            if (e != '') {
              offsets.push(this.fromStrToOffset(Number(value.replace(':', '.')), e))
            }
          })
          this.startOffsets = offsets
          this.gridStore.selected.startHourOffset = offsetStr
        } else if (index != 0) {
          let found = false
          this.startOffsets.map((e) => {
            if (e.day == index + 1) {
              e.hour = value
              found = true
            }
          })
          if (!found) {
            this.startOffsets.push({ day: index + 1, hour: value })
          }
          this.gridStore.selected.startHourOffset = this.fromOffsetToStr(
            this.startOffsets,
            this.gridStore.selected.startHour.replace(':', '.')
          )
        }
      }
    },
    setEndHourOffset(value: string, index: number) {
      if (this.gridStore.selected != null) {
        if (index == 0) {
          this.gridStore.selected.endHour = value
          let offsetStr = this.fromOffsetToStr(this.endOffsets, value.replace(':', '.'))
          let offsets: Array<OffsetInterface> = []
          offsetStr.split(';').map((e) => {
            if (e != '') {
              offsets.push(this.fromStrToOffset(Number(value.replace(':', '.')), e))
            }
          })
          this.endOffsets = offsets
          this.gridStore.selected.endHourOffset = offsetStr
        } else if (index != 0) {
          let found = false
          this.endOffsets.map((e) => {
            if (e.day == index + 1) {
              e.hour = value
              found = true
            }
          })
          if (!found) {
            this.endOffsets.push({ day: index + 1, hour: value })
          }
          this.gridStore.selected.endHourOffset = this.fromOffsetToStr(
            this.endOffsets,
            this.gridStore.selected.endHour.replace(':', '.')
          )
        }
      }
    }
  },
  computed: {
    getResaDays() {
      if (
        this.gridStore.selected != null &&
        this.startOffsets.length == 0 &&
        this.endOffsets.length == 0
      ) {
        let endOffs = this.gridStore.selected.endHourOffset
        let startOffs = this.gridStore.selected.startHourOffset
        startOffs.split(';').map((offset) => {
          if (this.gridStore.selected != null && offset != '') {
            this.startOffsets.push(
              this.fromStrToOffset(
                Number(this.gridStore.selected.startHour.replace(':', '.')),
                offset
              )
            )
          }
        })
        endOffs.split(';').map((offset) => {
          if (this.gridStore.selected != null && offset != '') {
            this.endOffsets.push(
              this.fromStrToOffset(
                Number(this.gridStore.selected.endHour.replace(':', '.')),
                offset
              )
            )
          }
        })
        this.resaDays = []
        this.daySelection = 0

        let startDay = this.gridStore.selected.startDate.getDay()
        const endDay = this.gridStore.selected.endDate.getDay()
        const days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi']

        while (startDay <= endDay) {
          this.resaDays.push(days[startDay - 1])
          startDay++
        }
      }
      return this.resaDays
    }
  }
})
</script>

<template>
  <div id="resaHour" class="flex-row just-around" style="width: 100%">
    <div
      v-for="(_, index) in getResaDays"
      v-show="daySelection == index"
      class="flex-col align-center just-start dayHour"
    >
      <div class="flex-row">
        <label :for="'start_hour_' + index" style="width: 4em">Arrivée</label>
        <input
          type="time"
          :name="'start_hour_' + index"
          :value="getStartHourValue(index)"
          @input="
            (v) => {
              setStartHourOffset((v.target as HTMLInputElement).value, index)
            }
          "
        />
      </div>
      <div class="flex-row">
        <label :for="'end_hour_' + index" style="width: 4em">Départ</label>
        <input
          type="time"
          :name="'end_hour_' + index"
          :value="getEndHourValue(index)"
          @input="
            (v) => {
              setEndHourOffset((v.target as HTMLInputElement).value, index)
            }
          "
        />
      </div>
    </div>
    <div class="flex-col just-end align-start" style="height: 100%">
      <select v-model="daySelection">
        <option v-for="(day, index) in getResaDays" :value="index">{{ day }}</option>
      </select>
    </div>
  </div>
</template>

<style scoped>
.selectedFurniture {
  background-color: #434f77;
  color: white;
  border: 1px solid black !important;
}

#resaHour label {
  font-size: 0.7em;
  margin-top: 0.2em;
}

#resaHour input {
  width: 4em;
  margin-top: 0.2em;
}

#isHourFull:hover {
  cursor: pointer;
}

#isHourFull {
  padding: 0.2em;
  transition: 0.2s;
  border: 1px solid white;
  border-radius: 5px;
  margin-bottom: 0.1em;
}

.dayHour div input {
  width: 5em !important;
}
</style>
