import type { Event } from '@/classes/Event'

export function EventGridContent(event: Event) {
  return `<div>
        <h3>${event.name}</h3>
        <span>
        <p>${event.roomConfiguration}${event.roomConfiguration == 'U' ? event.configurationSize ?? event.guests : ''} ${event.roomConfiguration == 'Ilots' ? event.configurationQuantity + 'x' + event.configurationSize : ''}</p>
        <a>${event.guests} pax</a>
        </span>
    </div>`
}
