import type { ReservationInterface } from '@/types/Reservation'

export class Reservation implements ReservationInterface {
  id: number
  roomId: number | null
  name: string
  guests: number
  hostName: string
  date: Date
  startHour: string
  orgaMail: string
  orgaTel: string
  orgaName: string
  roomConfiguration: string
  endHour: string
  roomConfigurationPrecision: string
  configurationSize: number
  configurationQuantity: number | null
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

  constructor() {
    this.id = 0
    this.roomId = null
    this.name = ''
    this.guests = 0
    this.hostName = ''
    this.date = new Date()
    this.startHour = ''
    this.orgaMail = ''
    this.orgaTel = ''
    this.orgaName = ''
    this.roomConfiguration = ''
    this.endHour = ''
    this.roomConfigurationPrecision = ''
    this.configurationSize = 0
    this.configurationQuantity = null
    this.hostTable = false
    this.paperboard = 0
    this.chairSup = 0
    this.tableSup = 0
    this.pen = false
    this.paper = false
    this.scissors = false
    this.scotch = false
    this.postItXl = false
    this.paperA1 = false
    this.blocNote = false
    this.gomette = false
    this.postIt = false
    this.coffeeGroom = 0
    this.meal = 0
    this.morningCoffee = 0
    this.afternoonCoffee = 0
    this.coktail = 0
    this.vegetarian = 0
    this.glutenFree = 0
    this.mealPrecision = ''
  }

  setId(id: number): void {
    this.id = id
  }

  getEntityName(): string {
    return 'reservation'
  }
}
