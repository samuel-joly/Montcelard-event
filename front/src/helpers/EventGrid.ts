import type { Reservation } from '@/classes/Reservation'

export function EventGridContent(event: Reservation) {
  return `<div>
        <h3>${event.name}</h3>
        <span>
        <p>${event.roomConfiguration}${event.roomConfiguration == 'U' ? event.configurationSize ?? event.guests : ''} ${event.roomConfiguration == 'Ilots' ? event.configurationQuantity + 'x' + event.configurationSize : ''}</p>
        <a>${event.guests} pax</a>
        </span>
    </div>`
}
