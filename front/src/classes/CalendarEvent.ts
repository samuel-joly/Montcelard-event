import { EventGridContent } from "@/helpers/EventGrid";
import { useEventFilter } from "@/stores/useEventFilter";
import { useEventGrid } from "@/stores/useEventGrid";

export class CalendarEvent {
    eventId: number
    roomIndex: number
    duration: number
    startDayNumber: number
    endDayNumber: number
    colSpan: { from: number, to: number }
    DOMElement: HTMLDivElement
    host_name: any

    constructor(grid: Element, id: number, roomId: number, from: Date, to: Date) {
        this.eventId = id;
        this.startDayNumber = from.getDay();
        this.endDayNumber = to.getDay();
        this.duration = (this.endDayNumber - this.startDayNumber);
        this.roomIndex = roomId * 6;
        this.colSpan = {
            from: this.startDayNumber + this.roomIndex,
            to: this.endDayNumber + this.roomIndex
        };
        if (grid != null) {
            this.DOMElement = grid.childNodes[this.colSpan.from] as HTMLDivElement;
        } else {
            throw new Error("gridContainer is not defined")
        }
    }

    draw(grid: HTMLDivElement) {
        this.DOMElement.style.backgroundColor =
            "rgba("
            + ((this.startDayNumber * 25) + 102) + ","
            + ((this.endDayNumber * 25) + 102) + " ,"
            + Math.floor((this.roomIndex * 3.54) + 102) + ", 100%)"
            ;
        const filterStore = useEventFilter();
        this.DOMElement.innerHTML = EventGridContent(filterStore.getFromId(this.eventId));
        this.DOMElement.classList.add('evented');
        this.DOMElement.style.gridColumn = 1 + this.startDayNumber + '/-' + (6 - this.endDayNumber);

        for (let i = 0; i < this.duration; i++) {
            const event_day = grid.childNodes[1 + this.colSpan.from + i] as HTMLDivElement
            event_day.classList.add("hidden")
        }
        if (6 - this.endDayNumber == 1) {
            const start_day = grid.childNodes[this.colSpan.from] as HTMLDivElement;
            start_day.classList.add("last")
        }
        this.defineEvents();
    }

    remove(grid: HTMLDivElement) {
        this.removeEvents()
        this.DOMElement.style.gridColumn = "";
        this.DOMElement.innerHTML = "";
        this.DOMElement.style.backgroundColor = "";
        console.log(this.colSpan.to, this.colSpan.to % 7, this.DOMElement)
        for (let i = 0; i < this.duration; i++) {
            const event_day = grid.childNodes[1 + this.colSpan.from + i] as HTMLDivElement
            event_day.classList.remove("hidden")
            if (6 - this.endDayNumber == 1) {
                event_day.classList.remove("last")
            }
        }
    }

    removeEvents() {
        this.DOMElement.removeEventListener('click', (e: Event) => {
            e.stopPropagation()
            const gridStore = useEventGrid();
            if (gridStore.selectedEventId == null) {
                gridStore.selectEvent(this.eventId);
            } else if (gridStore.selectedEventId != this.eventId) {
                const old_selected = gridStore.events.filter((e) => (e.eventId == gridStore.selectedEventId))[0];
                old_selected.DOMElement.classList.toggle('selectedEvent')
                gridStore.selectEvent(this.eventId);
            } else {
                gridStore.selectEvent(null);
            }
            this.DOMElement.classList.toggle('selectedEvent')
        })
    }

    defineEvents() {
        this.DOMElement.addEventListener('click', (e: Event) => {
            e.stopPropagation()
            const gridStore = useEventGrid();
            if (gridStore.selectedEventId == null) {
                gridStore.selectEvent(this.eventId);
            } else if (gridStore.selectedEventId != this.eventId) {
                const old_selected = gridStore.events.filter((e) => (e.eventId == gridStore.selectedEventId))[0];
                old_selected.DOMElement.classList.toggle('selectedEvent')
                gridStore.selectEvent(this.eventId);
            } else {
                gridStore.selectEvent(null);
            }
            this.DOMElement.classList.toggle('selectedEvent')
        })
    }
}
