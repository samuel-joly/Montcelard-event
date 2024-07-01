import { Reservation } from '@/classes/Reservation'
import { defineStore } from 'pinia'
import { useResaFilter } from './useResaFilter'

export const useGridFilter = defineStore('gridFilter', {
  state: () => ({
    selected: null as Reservation | null,
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
      this.selected = resa
    }
  }
})
