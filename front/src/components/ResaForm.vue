<script lang="ts">
import { defineComponent } from 'vue'
import Icon from '@/components/Icon.vue'
import { Client } from '@/helpers/client'
import { Reservation as Resa } from '@/classes/Reservation'
import { useResaFilter } from '@/stores/useResaFilter'
import TopForm from '@/components/ResaForm/TopForm.vue'
import RoomConfig from '@/components/ResaForm/RoomConfig.vue'
import Restauration from '@/components/ResaForm/Restauration.vue'
import { useGridFilter } from '@/stores/useGridFilter'

export default defineComponent({
  components: {
    Icon,
    TopForm,
    RoomConfig,
    Restauration
  },
  setup() {
    const filterStore = useResaFilter()
    const gridStore = useGridFilter()
    const client = new Client()
    async function putResa(e: Event) {
      e.preventDefault()
      if (gridStore.selected != null) {
        client.put<Resa>('reservation', gridStore.selected)
        filterStore.results.map((e) => {
          if (gridStore.selected != null) {
            if (e.id == gridStore.selected.id) {
              e = gridStore.selected
            }
          }
        })
      }
    }

    return {
      filterStore,
      gridStore,
      putResa
    }
  },
  computed: {
    getHostsName(): string[] | null {
      if (this.gridStore.selected != null) {
        return this.gridStore.selected.hostName.split(',')
      }
      return null
    }
  }
})
</script>

<template>
  <form id="eventForm" v-if="gridStore.selected != null">
    <TopForm />
    <RoomConfig />
    <Restauration/>
  </form>
</template>

<style scoped>

#eventForm {
  background-color: var(--op-1);
}

form {
  display: flex;
  flex-direction: column;
  width: 25vw;
  margin-right: 1em;
}
</style>
