import { Reservation } from '@/classes/Reservation'
import { Client } from '@/helpers/client'
import { defineStore } from 'pinia'

export const useResaFilter = defineStore('resaFilter', {
  state: () => ({
    selected: null as Reservation | null,
    results: [] as Reservation[],
    week: 24 as number,
    year: new Date().getFullYear() as number
  }),
  getters: {
    getResas: (state): Reservation[] => state.results,
    getNumber: (state): number => state.week,
    getSelected: (state): Reservation | null => state.selected
  },
  actions: {
    getFirstWeekDay(): Date {
      const firstWeekDay = new Date(this.year, 0, this.week * 7)
      while (firstWeekDay.getDay() != 1) {
        firstWeekDay.setDate(firstWeekDay.getDate() - 1)
      }
      return firstWeekDay
    },
    selectResa(resa: Reservation | null) {
      this.selected = resa
    },
    getFromDate(date: Date): Reservation {
      return this.results.filter((e) => e.startDate == date)[0]
    },
    getFromId(id: number): Reservation {
      return this.results.filter((e) => e.id == id)[0]
    },
    async fetchResa() {
      const cli = new Client()
      const firstWeekDay = this.getFirstWeekDay()
      const lastWeekDay = new Date(firstWeekDay)
      lastWeekDay.setDate(lastWeekDay.getDate() + 6)
      const weekDateString =
        firstWeekDay.getFullYear() +
        '-' +
        (1 + firstWeekDay.getMonth()) +
        '-' +
        firstWeekDay.getDate()
      const weekEndDateString =
        lastWeekDay.getFullYear() + '-' + (1 + lastWeekDay.getMonth()) + '-' + lastWeekDay.getDate()
      this.results = (
        await cli.get<Reservation>(
          new Reservation(),
          0,
          'startDate>==' + weekDateString + '&endDate<=' + weekEndDateString
        )
      ).data
    },
    async deleteResa(id: number) {
      const cli = new Client()
      await cli.delete('reservation', id)
      this.results.filter((e) => e.id != id)
    }
  }
})
