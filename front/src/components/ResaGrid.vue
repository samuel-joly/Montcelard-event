<script lang="ts">
import { useResaFilter } from '@/stores/useResaFilter'
import GridElement from '@/components/GridElement.vue'
import { defineComponent } from 'vue'
import { useGridFilter } from '@/stores/useGridFilter'

export default defineComponent({
  components: {
    GridElement: GridElement
  },
  setup() {
    const filterStore = useResaFilter()
    const gridStore = useGridFilter()
    filterStore.fetchResa()
    return { filterStore, gridStore }
  },
  computed: {
    monDate() {
      return this.filterStore.getFirstWeekDay().toLocaleDateString('fr-FR').slice(0, 5)
    },
    tueDate() {
      const firstDay = this.filterStore.getFirstWeekDay()
      firstDay.setDate(firstDay.getDate() + 1)
      return firstDay.toLocaleDateString('fr-FR').slice(0, 5)
    },
    wedDate() {
      const firstDay = this.filterStore.getFirstWeekDay()
      firstDay.setDate(firstDay.getDate() + 2)
      return firstDay.toLocaleDateString('fr-FR').slice(0, 5)
    },
    thuDate() {
      const firstDay = this.filterStore.getFirstWeekDay()
      firstDay.setDate(firstDay.getDate() + 3)
      return firstDay.toLocaleDateString('fr-FR').slice(0, 5)
    },
    friDate() {
      const firstDay = this.filterStore.getFirstWeekDay()
      firstDay.setDate(firstDay.getDate() + 4)
      return firstDay.toLocaleDateString('fr-FR').slice(0, 5)
    }
  }
})
</script>

<template>
  <div id="gridContainer">
    <div class=""></div>
    <div class="gridElement date" :class="(gridStore.hovered - 1) % 5 == 0 ? 'hovered' : ''">
      Lundi {{ monDate }}
    </div>
    <div class="gridElement date" :class="(gridStore.hovered - 1) % 5 == 1 ? 'hovered' : ''">
      Mardi {{ tueDate }}
    </div>
    <div class="gridElement date" :class="(gridStore.hovered - 1) % 5 == 2 ? 'hovered' : ''">
      Mercredi {{ wedDate }}
    </div>
    <div class="gridElement date" :class="(gridStore.hovered - 1) % 5 == 3 ? 'hovered' : ''">
      Jeudi {{ thuDate }}
    </div>
    <div class="gridElement last date" :class="(gridStore.hovered - 1) % 5 == 4 ? 'hovered' : ''">
      Vendredi {{ friDate }}
    </div>
    <div class="gridElement room" :class="gridStore.hovered / 5 <= 1 ? 'hovered' : ''">Chine</div>
    <GridElement :id="1" />
    <GridElement :id="2" />
    <GridElement :id="3" />
    <GridElement :id="4" />
    <GridElement :id="5" class="last" />
    <div
      class="gridElement room"
      :class="gridStore.hovered / 5 > 1 && gridStore.hovered / 5 <= 2 ? 'hovered' : ''"
    >
      Cambodge
    </div>
    <GridElement :id="6" />
    <GridElement :id="7" />
    <GridElement :id="8" />
    <GridElement :id="9" />
    <GridElement :id="10" class="last" />
    <div
      class="gridElement room"
      :class="gridStore.hovered / 5 > 2 && gridStore.hovered / 5 <= 3 ? 'hovered' : ''"
    >
      Laos
    </div>
    <GridElement :id="11" />
    <GridElement :id="12" />
    <GridElement :id="13" />
    <GridElement :id="14" />
    <GridElement :id="15" class="last" />
    <div
      class="gridElement room"
      :class="gridStore.hovered / 5 > 3 && gridStore.hovered / 5 <= 4 ? 'hovered' : ''"
    >
      Jardin d'hiver
    </div>
    <GridElement :id="16" />
    <GridElement :id="17" />
    <GridElement :id="18" />
    <GridElement :id="19" />
    <GridElement :id="20" class="last" />
    <div
      class="gridElement room"
      :class="gridStore.hovered / 5 > 4 && gridStore.hovered / 5 <= 5 ? 'hovered' : ''"
    >
      Mali
    </div>
    <GridElement :id="21" />
    <GridElement :id="22" />
    <GridElement :id="23" />
    <GridElement :id="24" />
    <GridElement :id="25" class="last" />
    <div
      class="gridElement room"
      :class="gridStore.hovered / 5 > 5 && gridStore.hovered / 5 <= 6 ? 'hovered' : ''"
    >
      Myanmar
    </div>
    <GridElement :id="26" />
    <GridElement :id="27" />
    <GridElement :id="28" />
    <GridElement :id="29" />
    <GridElement :id="30" class="last" />
    <div
      class="gridElement room"
      :class="gridStore.hovered / 5 > 6 && gridStore.hovered / 5 <= 7 ? 'hovered' : ''"
    >
      Haiti
    </div>
    <GridElement :id="31" />
    <GridElement :id="32" />
    <GridElement :id="33" />
    <GridElement :id="34" />
    <GridElement :id="35" class="last" />
    <div
      class="gridElement room"
      :class="gridStore.hovered / 5 > 7 && gridStore.hovered / 5 <= 8 ? 'hovered' : ''"
    >
      Liban
    </div>
    <GridElement :id="36" />
    <GridElement :id="37" />
    <GridElement :id="38" />
    <GridElement :id="39" />
    <GridElement :id="40" class="last" />
    <div
      class="gridElement room"
      :class="gridStore.hovered / 5 > 8 && gridStore.hovered / 5 <= 9 ? 'hovered' : ''"
    >
      Madagascar
    </div>
    <GridElement :id="41" />
    <GridElement :id="42" />
    <GridElement :id="43" />
    <GridElement :id="44" />
    <GridElement :id="45" class="last" />
    <div
      class="gridElement room"
      :class="gridStore.hovered / 5 > 9 && gridStore.hovered / 5 <= 10 ? 'hovered' : ''"
    >
      Tadjikistan
    </div>
    <GridElement :id="46" />
    <GridElement :id="47" />
    <GridElement :id="48" />
    <GridElement :id="49" />
    <GridElement :id="50" class="last" />
    <div
      class="gridElement room"
      :class="gridStore.hovered / 5 > 10 && gridStore.hovered / 5 <= 11 ? 'hovered' : ''"
    >
      Brésil
    </div>
    <GridElement :id="51" />
    <GridElement :id="52" />
    <GridElement :id="53" />
    <GridElement :id="54" />
    <GridElement :id="55" class="last" />
    <div
      class="gridElement room"
      :class="gridStore.hovered / 5 > 11 && gridStore.hovered / 5 <= 12 ? 'hovered' : ''"
    >
      Orangerie
    </div>
    <GridElement :id="56" />
    <GridElement :id="57" />
    <GridElement :id="58" />
    <GridElement :id="59" />
    <GridElement :id="60" class="last" />
  </div>
</template>

<style>
#gridContainer {
  display: grid;
  width: 73%;
  height: 88vh;
  max-height: 93vh;
  padding: 1em;
  grid-template-columns: repeat(6, 1fr);
  grid-template-rows: repeat(13, 1fr);
  gap: 0.2em;
  font-size: 1.2em;
}

.gridElement {
  color: black !important;
}

.room {
  display: flex;
  align-items: center;
  justify-content: center;
}

.last {
  border: 0px !important;
  border-right: 1px;
  border-color: black;
  border-style: solid;
}

.hidden {
  display: none;
}

.hovered {
  background-color: rgba(74, 90, 128, 30%) !important;
}

.date {
  height: 90%;
}
</style>
