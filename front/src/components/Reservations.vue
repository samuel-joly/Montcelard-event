<script lang="ts">
import ResaForm from '@/components/ResaForm.vue'
import ResaGrid from '@/components/ResaGrid.vue'
import SaveChangeModal from './SaveChangeModal.vue'
import { useGridFilter } from '@/stores/useGridFilter'
import { useResaFilter } from '@/stores/useResaFilter'
import { Client } from '@/helpers/client'
import type { Reservation } from '@/classes/Reservation'

export default {
  components: {
    ResaForm,
    ResaGrid,
    SaveChangeModal
  },
  setup() {
    const gridStore = useGridFilter()
    return { gridStore }
  },
  methods: {
    saveChanges() {
      const filterStore = useResaFilter()
      filterStore.results.map((e) => {
        if (e.id == this.gridStore.lastSelectedId) {
          const cli = new Client()
          cli.put('reservation', e)
        }
      })
    },
    revertChanges() {
      const filterStore = useResaFilter()
      filterStore.getCurrentWeekResa()
    }
  }
}
</script>

<template>
  <div id="gridResas">
    <SaveChangeModal
      text="Sauvegarder les modifications ?"
      :showValue="gridStore.changesNotSaved == true"
      :yesFunc="saveChanges"
      :noFunc="revertChanges"
    />
    <ResaGrid />
    <Transition>
      <ResaForm />
    </Transition>
  </div>
</template>

<style scoped>
#gridResas {
  display: flex;
  flex-direction: row;
  width: 100vw;
  overflow-x: hidden;
  justify-content: flex-start;
}

.v-enter-active,
.v-leave-active {
  transition: all 0.5s ease;
  opacity: 1;
}

.v-enter-from,
.v-leave-to {
  opacity: 0;
}
</style>
