<script lang="ts">
import type { EventInterface } from '../types/Event'
import { Event } from '@/classes/Event'
import { onMounted, defineComponent, ref } from 'vue'
import ValidationModal from '@/components/ValidationModal.vue'
import { Client } from '@/helpers/client'

const client = new Client()
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
  setup(props) {
    const events = ref<Event[]>([])

    onMounted(async () => {
      try {
        const res = await client.get<Event>(new Event())
        res.data.map((value) => {
          events.value.push(value)
        })
      } catch (error: any) {
        console.error(error)
      }
    })
    function deleteEvent(id: number) {
      client.delete('event', id)
      events.value = events.value.filter((e: EventInterface) => e.id != id)
    }
    return { events, props, deleteEvent }
  }
})
</script>

<template>
  <div>
    <table>
      <tr id="tableHead">
        <th>Début</th>
        <th>Fin</th>
        <th>Formation</th>
        <th>Organisateur</th>
        <th>Participants</th>
        <th>ID</th>
        <th style="background-color: white"></th>
      </tr>

      <tr v-for="event in events.slice(0, props.limit)" :key="event.id">
        <td>
          {{
            event.start_date.toLocaleDateString('fr-FR', {
              weekday: 'long',
              year: 'numeric',
              month: 'long',
              day: 'numeric'
            })
          }}
        </td>
        <td>
          {{
            event.end_date.toLocaleDateString('fr-FR', {
              weekday: 'long',
              year: 'numeric',
              month: 'long',
              day: 'numeric'
            })
          }}
        </td>
        <td>{{ event.name }}</td>
        <td>{{ event.orga_name }}</td>
        <td>{{ event.guests }}</td>
        <td>{{ event.id }}</td>
        <td class="action">
          <router-link class="edit-act" :to="{ name: 'event-edit', params: { id: event.id } }" />
          <ValidationModal :func="deleteEvent" action="supprimer" :id="event.id" />
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
  justify-content: flex-start;
}

.edit-act {
  width: 26px;
  height: 26px;
  display: flex;
  border: 0px;
  background: url('@/assets/edit.png');
  background-size: cover;
  transition: 0.3s;
}

.edit-act:hover {
  cursor: pointer;
  background: url('@/assets/edit-color.png');
  background-size: cover;
}

tr:hover {
  background-color: lightgray;
}

td {
  text-align: center;
}

div {
  width: 80vw;
  display: flex;
  flex-direction: column;
}

#tableHead {
  background-color: gray;
  color: white;
}

td {
  text-align: center;
}
</style>
