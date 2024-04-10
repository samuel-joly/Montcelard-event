<script lang="ts">
import type { Event } from '@/classes/Event'
import { EventGridContent } from '@/helpers/EventGrid'
import { useEventFilter } from '@/stores/useEventFilter'
import GridElement from '@/components/GridElement.vue'
import { defineComponent, onMounted, onUpdated } from 'vue'

export default defineComponent({
  components: {
    GridElement: GridElement
  },
  setup() {
    const filterStore = useEventFilter()
    return { filterStore }
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
    <div class="gridElementDay">Lundi {{ monDate }}</div>
    <div class="gridElementDay">Mardi {{ tueDate }}</div>
    <div class="gridElementDay">Mercredi {{ wedDate }}</div>
    <div class="gridElementDay">Jeudi {{ thuDate }}</div>
    <div class="gridElementDay last">Vendredi {{ friDate }}</div>
    <div style="border-top: 1px solid black" class="gridElement room">Chine</div>
    <GridElement :id="1" />
    <GridElement :id="2" />
    <GridElement :id="3" />
    <GridElement :id="4" />
    <GridElement :id="5" class="last" />
    <div class="gridElement room">Cambodge</div>
    <GridElement :id="6" />
    <GridElement :id="7" />
    <GridElement :id="8" />
    <GridElement :id="9" />
    <GridElement :id="10" class="last" />
    <div class="gridElement room">Laos</div>
    <GridElement :id="11" />
    <GridElement :id="12" />
    <GridElement :id="13" />
    <GridElement :id="14" />
    <GridElement :id="15" class="last" />
    <div class="gridElement room">Jardin d'hiver</div>
    <GridElement :id="16" />
    <GridElement :id="17" />
    <GridElement :id="18" />
    <GridElement :id="19" />
    <GridElement :id="20" class="last" />
    <div class="gridElement room">Mali</div>
    <GridElement :id="21" />
    <GridElement :id="22" />
    <GridElement :id="23" />
    <GridElement :id="24" />
    <GridElement :id="25" class="last" />
    <div class="gridElement room">Myanmar</div>
    <GridElement :id="26" />
    <GridElement :id="27" />
    <GridElement :id="28" />
    <GridElement :id="29" />
    <GridElement :id="30" class="last" />
    <div class="gridElement room">Haiti</div>
    <GridElement :id="31" />
    <GridElement :id="32" />
    <GridElement :id="33" />
    <GridElement :id="34" />
    <GridElement :id="35" class="last" />
    <div class="gridElement room">Liban</div>
    <GridElement :id="36" />
    <GridElement :id="37" />
    <GridElement :id="38" />
    <GridElement :id="39" />
    <GridElement :id="40" class="last" />
    <div class="gridElement room">Madagascar</div>
    <GridElement :id="41" />
    <GridElement :id="42" />
    <GridElement :id="43" />
    <GridElement :id="44" />
    <GridElement :id="45" class="last" />
    <div class="gridElement room">Tadjikistan</div>
    <GridElement :id="46" />
    <GridElement :id="47" />
    <GridElement :id="48" />
    <GridElement :id="49" />
    <GridElement :id="50" class="last" />
    <div class="gridElement room">Brésil</div>
    <GridElement :id="51" />
    <GridElement :id="52" />
    <GridElement :id="53" />
    <GridElement :id="54" />
    <GridElement :id="55" class="last" />
    <div class="gridElement room">Orangerie</div>
    <GridElement :id="56" />
    <GridElement :id="57" />
    <GridElement :id="58" />
    <GridElement :id="59" />
    <GridElement :id="60" class="last" />
  </div>
</template>

<style>
#gridContainer {
  justify-self: center;
  width: 95vw;
  padding: 0em 1em 1em 1em;
  display: grid;
  grid-template-columns: repeat(6, 1fr);
  grid-template-rows: repeat(13, 1fr);
  font-size: 1.2em;
}

.gridElementDay {
  text-align: center;
  border-top: 1px;
  border-right: 0px;
  border-left: 1px;
  border-bottom: 1px;
  border-color: black;
  border-style: solid;
  max-height: 1.75em;
  align-self: self-end;
}

.room {
  border-top: 0px;
  border-right: 0px;
  border-left: 1px;
  border-bottom: 1px;
  border-color: black;
  border-style: solid;
  max-height: 4em;
  display: flex;
  align-items: center;
  justify-content: center;
}

.last {
  border-right: 1px;
  border-color: black;
  border-style: solid;
}

.hidden {
  display: none;
}

.selectedEvent {
  filter: brightness(120%);
}
</style>
