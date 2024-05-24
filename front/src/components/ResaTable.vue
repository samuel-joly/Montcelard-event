<script lang="ts">
import { defineComponent } from 'vue'
import { Reservation } from '@/classes/Reservation'
import ValidationModal from '@/components/ValidationModal.vue'
import { useResaFilter } from '@/stores/useResaFilter'
import { useGridFilter } from '@/stores/useGridFilter'

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
    const filterStore = useResaFilter()
    const gridStore = useGridFilter()
    filterStore.fetchResa()
    if (gridStore.selected == null && filterStore.results.length > 0) {
      gridStore.selected = filterStore.results[0]
    }
    return { filterStore, gridStore, props }
  },
  methods: {
    getStartDate(resa: Reservation): string {
      return resa.startDate.toISOString().split('T')[0].replace(/-/g, '/')
    },
    getEndDate(resa: Reservation): string {
      return resa.endDate.toISOString().split('T')[0].replace(/-/g, '/')
    }
  }
})
</script>

<template>
  <div v-if="gridStore.selected != null">
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
        v-for="resa in filterStore.results.slice(0, props.limit)"
        :key="resa.id"
        class="resa"
        :class="resa.id == gridStore.selected.id ? 'selected' : ''"
        @click="gridStore.selectResa(resa)"
      >
        <td>{{ resa.id }}</td>
        <td>{{ resa.guests }}</td>
        <td>{{ resa.name }}</td>
        <td>{{ resa.orgaMail }}</td>
        <td>
          {{ getStartDate(resa) }}
        </td>
        <td>
          {{ getEndDate(resa) }}
        </td>
        <td>{{ resa.startHour }}</td>
        <td>{{ resa.endHour }}</td>
        <td>{{ resa.roomId }}</td>
        <td class="action">
          <ValidationModal
            color="#337788"
            :func="filterStore.deleteResa"
            action="supprimer"
            :id="resa.id"
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

.resa {
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
