<script lang="ts">
import { defineComponent } from 'vue'
import { Event } from '@/classes/Event'
import ValidationModal from '@/components/ValidationModal.vue'
import { useEventFilter } from '@/stores/useEventFilter'

export default defineComponent({
  components: {
    ValidationModal
  },
  props: {
    limit: {
      type: Number,
      default: 10
    }
  },
  data(props) {
    const filterStore = useEventFilter()
    return { filterStore, props }

  },
  methods: {
    getStartDate(event: Event): string {
      return event.startDate.toISOString().split('T')[0].replace(/-/g, '/')
    },
    getEndDate(event: Event): string {
      return event.endDate.toISOString().split('T')[0].replace(/-/g, '/')
    }
  }
})
</script>

<template>
  <div v-if="filterStore.getSelected != null">

    <table>
      <tr id="tableHead">
        <th class="short">ID</th>
        <th class="short">Pax</th>
        <th>Formation</th>
        <th>Organisateur</th>
        <th class="medium">Début</th>
        <th class="medium">Fin</th>
        <th class="short">Arrivée</th>
        <th class="short">Départ</th>
        <th>Salle</th>
        <th class="headAction"></th>
      </tr>

      <tr
        v-for="event in filterStore.results.slice(0, props.limit)"
        :key="event.id"
        class="event"
        :class="event.id == filterStore.getSelected.id ? 'selected' : ''"
        @click="filterStore.selectEvent(event)"
      >
        <td>{{ event.id }}</td>
        <td>{{ event.guests }}</td>
        <td>{{ event.name }}</td>
        <td>{{ event.orgaMail }}</td>
        <td>
          {{ getStartDate(event) }}
        </td>
        <td>
          {{ getEndDate(event) }}
        </td>
        <td>{{ event.startHour }}</td>
        <td>{{ event.endHour }}</td>
        <td>{{ event.roomId }}</td>
        <td class="action">
          <ValidationModal
            color="#337788"
            :func="filterStore.deleteEvent"
            action="supprimer"
            :id="event.id"
          />
        </td>
      </tr>
    </table>
  </div>
</template>

<style scoped>
.action {
  background-color: white;
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: flex-start;
  padding-left: 2em;
  height: 3em;
}

.event {
  height: 3em;
}

.edit-act:hover {
  filter: brightness(120%);
}

.edit-act {
  background-color: var(--op-3);
  font-size: 1em;
  color: white;
  padding: 0.3em;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: 0.3s;
}

tr:hover {
  background-color: var(--op-2);
}

td {
  text-align: center;
}

div {
  display: flex;
  flex-direction: column;
  align-items: center;
  width: 100vw;
}

#tableHead {
  background-color: var(--op-4);
  color: white;
}

.headAction {
  background-color: var(--op-1);
  width: fit-content;
}

.short {
  width: 2em;
}

.medium {
  width: 5em;
}

.selected {
  background-color: var(--op-2);
}
</style>
