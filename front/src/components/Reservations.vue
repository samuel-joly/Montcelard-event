<script lang="ts">
import ResaForm from '@/components/ResaForm.vue'
import ResaGrid from '@/components/ResaGrid.vue'
import SaveChangeModal from './SaveChangeModal.vue'
import { useGridFilter } from '@/stores/useGridFilter'
import { useResaFilter } from '@/stores/useResaFilter'
import { Client } from '@/helpers/client'

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
    changeNotSaved() {
      const filterStore = useResaFilter()
      filterStore.results.map((e) => {
        if (e.id == this.gridStore.lastSelectedId) {
          const cli = new Client()
          cli.put('reservation', e)
        }
      })
    }
  }
}
</script>

<template>
  <div id="gridResas">
    <SaveChangeModal
      text="Sauvegarder les modifications ?"
      :showValue="gridStore.changesNotSaved == true"
      :func="changeNotSaved"
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
