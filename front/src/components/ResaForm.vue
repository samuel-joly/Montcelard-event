<script lang="ts">
import { defineComponent, ref } from 'vue'
import Icon from '@/components/Icon.vue'
import { Client } from '@/helpers/client'
import { Reservation as Resa, Reservation } from '@/classes/Reservation'
import { useResaFilter } from '@/stores/useResaFilter'
import TopForm from '@/components/ResaForm/TopForm.vue'
import RoomConfig from '@/components/ResaForm/RoomConfig.vue'
import { useGridFilter } from '@/stores/useGridFilter'

export default defineComponent({
  components: {
    Icon,
    TopForm,
    RoomConfig
  },
  setup() {
    const filterStore = useResaFilter()
    const gridStore = useGridFilter()
    const client = new Client()
    const isFullWeek = ref<Boolean>(true)
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
      isFullWeek,
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
    <div id="startDay">
      <span> </span>
      <div id="resaHour" class="flex-row just-around align-center">
        <div style="margin-right: 2em">
          <label for="start_hour">Arrivée</label>
          <input name="startHour" v-model="gridStore.selected.startHour" />
        </div>
        <div style="margin-right: 2em">
          <label for="end_hour">Départ</label>
          <input name="endHour" v-model="gridStore.selected.endHour" />
        </div>
        <div>
          <label id="isHourFull" :class="[isFullWeek ? 'selectedFurniture' : '']" for="hourFullWeek"
            >Tous les jours</label
          >
          <input
            class="hidden"
            type="checkbox"
            id="hourFullWeek"
            name="hourFullWeek"
            v-model="isFullWeek"
          />
        </div>
      </div>
      <div id="restauration">
        <span>
          <label for="coffee_groom">Café accueil</label>
          <input name="coffeeGroom" v-model="gridStore.selected.coffeeGroom" />
        </span>
        <span>
          <label for="meal">Repas</label>
          <input name="meal" v-model="gridStore.selected.meal" />
        </span>
        <span>
          <label for="afternoon_coffee">Viennoiserie</label>
          <input name="afternoonCoffee" v-model="gridStore.selected.afternoonCoffee" />
        </span>
        <span>
          <label for="coktail">coktail</label>
          <input name="coktail" v-model="gridStore.selected.coktail" />
        </span>
        <span>
          <label for="vegetarian">vegetarian </label>
          <input name="vegetarian" v-model="gridStore.selected.vegetarian" />
        </span>
        <span>
          <label for="gluten_free">gluten_free </label>
          <input name="glutenFree" v-model="gridStore.selected.glutenFree" />
        </span>
        <span>
          <label for="meal_precision">meal_precision </label>
          <input name="mealPrecision" v-model="gridStore.selected.mealPrecision" />
        </span>
      </div>
    </div>
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
  z-index: 999;
  margin-right: 1em;
}

#resaHour {
  margin-top: 0.5rem;
}

#resaHour label {
  font-size: 0.9em;
}

#resaHour input {
  width: 4em;
}

.selectedFurniture {
  background-color: #434f77;
  color: white;
  border-color: black !important;
}

#isHourFull:hover {
  cursor: pointer;
}

#isHourFull {
  padding: 0.3em;
  transition: 0.2s;
  border: 1px solid black;
  border-radius: 5px;
}
</style>
