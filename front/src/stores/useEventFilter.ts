import { Event } from '@/classes/Event'
import { Client } from '@/helpers/client'
import { defineStore } from 'pinia'

export const useEventFilter = defineStore('eventFilter', {
  state: () => ({
    selected: null as Event | null,
    results: [] as Event[],
    week: 24 as number,
    year: new Date().getFullYear() as number
  }),
  getters: {
    getEvents: (state): Event[] => state.results,
    getNumber: (state): number => state.week,
    getSelected: (state): Event | null => state.selected
  },
  actions: {
    getFirstWeekDay(): Date {
      const firstWeekDay = new Date(this.year, 0, this.week * 7)
      while (firstWeekDay.getDay() != 1) {
        firstWeekDay.setDate(firstWeekDay.getDate() - 1)
      }
      return firstWeekDay
    },
    selectEvent(event: Event | null) {
      this.selected = event
    },
    getFromDate(date: Date): Event {
      return this.results.filter((e) => e.startDate == date)[0]
    },
    getFromId(id: number): Event {
      return this.results.filter((e) => e.id == id)[0]
    },
    async fetchEvent() {
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
        await cli.get<Event>(
          new Event(),
          0,
          'startDate>==' + weekDateString + '&endDate<=' + weekEndDateString
        )
      ).data
    },
    async deleteEvent(id: number) {
      const cli = new Client()
      await cli.delete('event', id)
      this.results.filter((e) => e.id != id)
    }
  }
})
