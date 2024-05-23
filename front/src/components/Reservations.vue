<script lang="ts">
import ResaForm from '@/components/ResaForm.vue'
import ResaGrid from '@/components/ResaGrid.vue'

export default {
  components: {
    ResaForm,
    ResaGrid
  },
  setup() {
  }
}
</script>

<template>
  <div id="gridResas">
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
