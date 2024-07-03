<script lang="ts">
import { defineComponent } from 'vue'
import { useGridFilter } from '@/stores/useGridFilter'
import { Client } from '@/helpers/client'
import { useResaFilter } from '@/stores/useResaFilter'
import type { Reservation } from '@/classes/Reservation'

export default defineComponent({
  setup() {
    const gridStore = useGridFilter()
    const filterStore = useResaFilter()

    return {
      gridStore,
      filterStore
    }
  },
  methods: {
    save() {
      if (this.gridStore.selected != null) {
        this.gridStore.selectedCopy = this.gridStore.selected
        let cli = new Client()
        cli.put('reservation', this.gridStore.selected)
      }
    },
    deleteResa() {
      if (this.gridStore.selected != null) {
        let cli = new Client()
        cli.delete('reservation', this.gridStore.selected.id)
        this.filterStore.results.filter((e: Reservation) => {
          if (this.gridStore.selected != null) {
            e.id != this.gridStore.selected.id
          }
        })
      }
    },
    exportResa() {
      if (this.gridStore.selected != null) {
        console.log('export on the way...')
      }
    }
  }
})
</script>

<template>
  <div class="flex-row just-end">
    <button
      id="save"
      @click="
        (e) => {
          e.preventDefault()
          save()
        }
      "
    >
      Sauvegarder
    </button>
    <button
      id="export"
      @click="
        (e) => {
          e.preventDefault()
          exportResa()
        }
      "
    >
      Exporter
    </button>
    <button
      id="delete"
      @click="
        (e) => {
          e.preventDefault()
          deleteResa()
        }
      "
    >
      Supprimer
    </button>
  </div>
</template>

<style scoped>
div {
  margin-bottom: 0.25em;
}

button {
  border-radius: 0.15em;
  font-size: 1em;
  box-shadow: none;
  border: none;
  transition-duration: 0.3s;
  margin-left: 0.25em;
  color: white;
}

button:hover {
  filter: brightness(120%);
  cursor: pointer;
  color: black;
}

#save {
  background-color: rgba(80, 150, 80);
}

#delete {
  background-color: rgba(150, 80, 80);
}

#export {
  background-color: rgba(80, 80, 150);
}
</style>
