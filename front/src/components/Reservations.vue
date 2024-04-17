<script lang="ts">
import { useResaFilter } from '@/stores/useResaFilter'
import ResaForm from '@/components/ResaForm.vue'
import ResaGrid from '@/components/ResaGrid.vue'

export default {
  components: {
    ResaForm,
    ResaGrid
  },
  setup() {
    const filterStore = useResaFilter()
    return { filter: filterStore }
  }
}
</script>

<template>
  <div id="filterResas">
    <Transition>
      <ResaForm />
    </Transition>
    <form>
      <input type="number" min="0" max="52" v-model="filter.week" @change="filter.fetchResa" />
      <input type="number" v-model="filter.year" @change="filter.fetchResa" />
    </form>
    <div id="gridResas">
      <ResaGrid />
    </div>
  </div>
</template>

<style scoped>
#filterResas {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}
#gridResas {
  display: flex;
  width: 100vw;
  overflow-x: hidden;
  justify-content: center;
}

.v-enter-active,
.v-leave-active {
  transition: all 0.5s ease;
  left: 75vw;
}

.v-enter-from,
.v-leave-to {
  left: 100vw;
}
</style>
