import type { EntityInterface } from '@/types/EntityInterface'
import type { EventInterface } from '@/types/Event'

export class Event implements EventInterface, EntityInterface {
  id: number
  name: string
  guests: number
  host_name: string
  start_date: Date
  end_date: Date
  start_hour: string
  orga_mail: string
  orga_tel: string
  orga_name: string
  room_configuration: string
  end_hour: string
  room_configuration_precision: string
  configuration_size: number
  configuration_quantity: number
  pause_date: number
  start_hour_offset: string
  end_hour_offset: string
  host_table: boolean
  paperboard: number
  chair_sup: number
  table_sup: number
  pen: boolean
  paper: boolean
  scissors: boolean
  scotch: boolean
  post_it_xl: boolean
  paper_a1: boolean
  bloc_note: boolean
  gomette: boolean
  post_it: boolean
  coffee_groom: number
  meal: number
  morning_coffee: number
  afternoon_coffee: number
  coktail: number
  vegetarian: number
  gluten_free: number
  meal_precision: string

  constructor() {
    this.id = 0
    this.name = ''
    this.guests = 0
    this.host_name = ''
    this.start_date = new Date()
    this.end_date = new Date()
    this.start_hour = ''
    this.orga_mail = ''
    this.orga_tel = ''
    this.orga_name = ''
    this.room_configuration = ''
    this.end_hour = ''
    this.room_configuration_precision = ''
    this.configuration_size = 0
    this.configuration_quantity = 0
    this.pause_date = 0
    this.start_hour_offset = ''
    this.end_hour_offset = ''
    this.host_table = false
    this.paperboard = 0
    this.chair_sup = 0
    this.table_sup = 0
    this.pen = false
    this.paper = false
    this.scissors = false
    this.scotch = false
    this.post_it_xl = false
    this.paper_a1 = false
    this.bloc_note = false
    this.gomette = false
    this.post_it = false
    this.coffee_groom = 0
    this.meal = 0
    this.morning_coffee = 0
    this.afternoon_coffee = 0
    this.coktail = 0
    this.vegetarian = 0
    this.gluten_free = 0
    this.meal_precision = ''
  }

  setId(id: number): void {
    this.id = id
  }
  getEntityName(): string {
    return 'event'
  }
}
