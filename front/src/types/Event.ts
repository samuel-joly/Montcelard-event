import type { EntityInterface } from './EntityInterface'

export interface EventInterface extends EntityInterface {
  id: number
  roomId: number | null
  name: string
  guests: number
  hostName: string
  startDate: Date
  endDate: Date
  startHour: string
  orgaMail: string
  orgaTel: string
  orgaName: string
  roomConfiguration: string
  configurationQuantity: number | null
  configurationSize: number
  endHour: string
  roomConfigurationPrecision: string
  pauseDate: number
  startHourOffset: string
  endHourOffset: string
  hostTable: boolean
  paperboard: number
  chairSup: number
  tableSup: number
  pen: boolean
  paper: boolean
  scissors: boolean
  scotch: boolean
  postItXl: boolean
  paperA1: boolean
  blocNote: boolean
  gomette: boolean
  postIt: boolean
  coffeeGroom: number
  meal: number
  morningCoffee: number
  afternoonCoffee: number
  coktail: number
  vegetarian: number
  glutenFree: number
  mealPrecision: string
}
