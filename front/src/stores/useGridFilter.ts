import { Reservation } from '@/classes/Reservation'
import { defineStore } from 'pinia'
import { useResaFilter } from './useResaFilter'

export const useGridFilter = defineStore('gridFilter', {
  state: () => ({
    selected: null as Reservation | null,
    selectedCopy: null as Reservation | null,
    changesNotSaved: false as boolean,
    lastSelectedId: 0 as Number,
    hovered: 1 as number
  }),
  getters: {
    getHovered: (state): number | null => state.hovered,
    getSelected: (state): Reservation | null => state.selected
  },
  actions: {
    getReservationFromGridId(id: number) {
      const room = Math.ceil(id / 5)
      const dayVal = id % 5
      const day = dayVal != 0 ? dayVal : 5

      const filterStore = useResaFilter()
      return filterStore.results.filter((resa) => {
        return resa.roomId == room && resa.date.getDay() <= day
      })[0]
    },
    selectResa(resa: Reservation | null) {
      if (this.selectedCopy != null && this.selected != null) {
        if (JSON.stringify(this.selected) != JSON.stringify(this.selectedCopy)) {
          this.changesNotSaved = true
        } else {
          this.changesNotSaved = false
        }
      }
      if (this.selected != null) {
        this.lastSelectedId = this.selected.id
      }
      this.selected = resa
      this.selectedCopy = Object.assign({}, resa)
    }
  }
})
