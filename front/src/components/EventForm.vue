<script lang="ts">
import { defineComponent } from 'vue'
import Icon from '@/components/Icon.vue'
import { Client } from '@/helpers/client'
import { Event as Ev } from '@/classes/Event'
import { useEventFilter } from '@/stores/useEventFilter'
export default defineComponent({
  components: {
    Icon
  },
  setup() {
    const filterStore = useEventFilter()
    const client = new Client()
    async function putEvent(e: Event) {
      e.preventDefault()
      if (filterStore.selected != null) {
        client.put<Ev>('event', filterStore.selected)
        filterStore.results.map((e) => {
          if (filterStore.selected != null) {
            if (e.id == filterStore.selected.id) {
              e = filterStore.selected
            }
          }
        })
      }
    }
    return {
      filterStore: filterStore,
      putEvent
    }
  },
  computed: {
    getHostsName(): string[] | null {
      if (this.filterStore.selected != null) {
        return this.filterStore.selected.hostName.split(',')
      }
      return null
    }
  }
})
</script>

<template>
  <form id="eventForm" v-if="filterStore.selected != null">
    <button @click="putEvent">PUT</button>
    <div id="topInfo">
      <span>
        <input id="formationName" name="name" v-model="filterStore.selected.name" />
        <div id="topBar">
          <div class="flex-row just-between">
            <span>
              <input
                name="start_date"
                type="date"
                :value="filterStore.selected.startDate.toISOString().split('T')[0]"
                @input="
                  (event) => {
                    const newDate = (event.target as HTMLInputElement).valueAsDate
                    if (filterStore.selected != null && newDate != null) {
                      filterStore.selected.startDate = newDate
                    }
                  }
                "
              />
            </span>

            <span class="inlineInput just-between">
              <input
                id="paxNumber"
                type="number"
                name="guests"
                v-model="filterStore.selected.guests"
              />
            </span>
          </div>
          <div class="flex-row just-between">
            <span>
              <input
                name="end_date"
                type="date"
                :value="filterStore.selected.endDate.toISOString().split('T')[0]"
                @input="
                  (event) => {
                    const newDate = (event.target as HTMLInputElement).valueAsDate
                    if (filterStore.selected != null && newDate != null) {
                      filterStore.selected.endDate = newDate
                    }
                  }
                "
              />
            </span>
            <span id="room-select" class="just-between">
              <select v-model="filterStore.selected.roomId">
                <option value="1">Chine</option>
                <option value="2">Cambodge</option>
                <option value="3">Laos</option>
                <option value="4">Jardin d'hiver</option>
                <option value="5">Mali</option>
                <option value="6">Myanmar</option>
                <option value="7">Haiti</option>
                <option value="8">Liban</option>
                <option value="9">Madagascar</option>
                <option value="10">Tadjikistan</option>
                <option value="11">Bresil</option>
                <option value="12">Orangerie</option>
              </select>
            </span>
          </div>
        </div>
      </span>
      <div id="orgaInfo">
        <span>
          <label class="iconLabel" for="orga_name">
            <Icon :w="23" :h="23" n="user" />
          </label>
          <input name="orgaName" v-model="filterStore.selected.orgaName" />
        </span>
        <span>
          <label class="iconLabel" for="orga_mail">
            <Icon :w="23" :h="23" n="mail" />
          </label>
          <input name="orgaMail" v-model="filterStore.selected.orgaMail" />
        </span>
        <span>
          <label class="iconLabel" for="orga_tel">
            <Icon :w="23" :h="23" n="phone" />
          </label>
          <input name="orgaTel" v-model="filterStore.selected.orgaTel" />
        </span>
      </div>
    </div>
    <div id="formEventContainer">
      <div id="roomInfo">
        <div id="conf">
          <span>
            <h3>Configuration</h3>
            <select v-model="filterStore.selected.roomConfiguration">
              <option value="U">En U</option>
              <option value="Ilots">Ilots</option>
              <option value="Theatre">Théâtre</option>
              <option value="Board">Board</option>
            </select>
          </span>
          <span v-if="filterStore.selected.roomConfiguration == 'Ilots'">
            <div class="roomConfigurationArg">
              <small></small>
            </div>
            <div class="roomConfigurationValue" style="display: flex; flex-direction: row">
              <input
                class="configuration_opt"
                type="number"
                v-model="filterStore.selected.configurationQuantity"
              />
              <p style="margin: 0 0.5rem 0 0.5rem">x</p>
              <input
                class="configuration_opt"
                type="number"
                v-model="filterStore.selected.configurationSize"
              />
            </div>
          </span>
          <span v-if="filterStore.selected.roomConfiguration == 'U'">
            <div id="roomConfigurationArg">
              <small></small>
            </div>
            <div id="roomConfigurationValue">
              <select v-model="filterStore.selected.configurationSize">
                <option :value="8">8</option>
                <option :value="10">10</option>
                <option :value="12">12</option>
                <option :value="14">14</option>
                <option :value="16">16</option>
                <option :value="18">18</option>
              </select>
            </div>
          </span>
        </div>
        <div id="roomFurnitures">
          <span>
            <label for="paperboard">ppbd</label>
            <input
              type="number"
              id="paperboard"
              name="paperboard"
              v-model="filterStore.selected.paperboard"
            />
          </span>
          <span>
            <label for="chair_sup">chaise +</label>
            <input type="number" name="chairSup" v-model="filterStore.selected.chairSup" />
          </span>
          <span>
            <label for="table_sup">tables +</label>
            <input type="number" name="tableSup" v-model="filterStore.selected.tableSup" />
          </span>
          <span id="hostTable">
            <label
              :class="[filterStore.selected.hostTable ? 'selectedFurniture' : '']"
              for="host_table"
              >Table Formateur</label
            >
            <input
              type="checkbox"
              id="host_table"
              name="host_table"
              v-model="filterStore.selected.hostTable"
            />
          </span>
        </div>
        <h3>Matériel</h3>
        <div id="furnituresBox">
          <span class="furnitures">
            <label :class="[filterStore.selected.pen ? 'selectedFurniture' : '']" for="pen"
              >stylos</label
            >
            <input type="checkbox" id="pen" name="pen" v-model="filterStore.selected.pen" />
          </span>
          <span class="furnitures">
            <label
              :class="[filterStore.selected.blocNote ? 'selectedFurniture' : '']"
              for="bloc_note"
              >bloc-note</label
            >
            <input
              id="bloc_note"
              type="checkbox"
              name="bloc_note"
              v-model="filterStore.selected.blocNote"
            />
          </span>
          <span class="furnitures">
            <label :class="[filterStore.selected.paper ? 'selectedFurniture' : '']" for="paper"
              >A4</label
            >
            <input id="paper" type="checkbox" name="paper" v-model="filterStore.selected.paper" />
          </span>
          <span class="furnitures">
            <label :class="[filterStore.selected.paperA1 ? 'selectedFurniture' : '']" for="paper_a1"
              >A1</label
            >
            <input
              id="paper_a1"
              type="checkbox"
              name="paper_a1"
              v-model="filterStore.selected.paperA1"
            />
          </span>
          <span class="furnitures">
            <label
              :class="[filterStore.selected.scissors ? 'selectedFurniture' : '']"
              for="scissors"
              >ciseau</label
            >
            <input
              id="scissors"
              type="checkbox"
              name="scissors"
              v-model="filterStore.selected.scissors"
            />
          </span>
          <span class="furnitures">
            <label :class="[filterStore.selected.scotch ? 'selectedFurniture' : '']" for="scotch"
              >scotch</label
            >
            <input
              id="scotch"
              type="checkbox"
              name="scotch"
              v-model="filterStore.selected.scotch"
            />
          </span>
          <span class="furnitures">
            <label :class="[filterStore.selected.postIt ? 'selectedFurniture' : '']" for="post_it"
              >post-it</label
            >
            <input
              id="postIt"
              type="checkbox"
              name="postIt"
              v-model="filterStore.selected.postIt"
            />
          </span>
          <span class="furnitures">
            <label
              :class="[filterStore.selected.postItXl ? 'selectedFurniture' : '']"
              for="post_it_xl"
              >post-it XL</label
            >
            <input
              id="post_it_xl"
              type="checkbox"
              name="post_it_xl"
              v-model="filterStore.selected.postItXl"
            />
          </span>
          <span class="furnitures">
            <label :class="[filterStore.selected.gomette ? 'selectedFurniture' : '']" for="gomette"
              >gomette</label
            >
            <input
              id="gomette"
              type="checkbox"
              name="gomette"
              v-model="filterStore.selected.gomette"
            />
          </span>
        </div>
        <span>
          <label for="room_configuration_precision">Précision</label>
          <textarea
            name="room_configuration_precision"
            v-model="filterStore.selected.roomConfigurationPrecision"
          />
        </span>
      </div>
      <div id="startDay">
        <span>
          <label for="host_name">Nom formateur</label>
          <div id="hosts-name" v-if="getHostsName != null">
            <input
              :v-model="getHostsName[index]"
              :value="hostName"
              v-for="(hostName, index) in getHostsName"
            />
          </div>
        </span>
        <div>
          <span>
            <label for="start_hour">Heure de début</label>
            <input name="startHour" v-model="filterStore.selected.startHour" />
          </span>
          <span>
            <label for="end_hour">Heure de fin</label>
            <input name="endHour" v-model="filterStore.selected.endHour" />
          </span>
        </div>
      </div>
      <div id="restauration">
        <span>
          <label for="coffee_groom">Café accueil</label>
          <input name="coffeeGroom" v-model="filterStore.selected.coffeeGroom" />
        </span>
        <span>
          <label for="meal">Repas</label>
          <input name="meal" v-model="filterStore.selected.meal" />
        </span>
        <span>
          <label for="afternoon_coffee">Viennoiserie</label>
          <input name="afternoonCoffee" v-model="filterStore.selected.afternoonCoffee" />
        </span>
        <span>
          <label for="coktail">coktail</label>
          <input name="coktail" v-model="filterStore.selected.coktail" />
        </span>
        <span>
          <label for="vegetarian">vegetarian </label>
          <input name="vegetarian" v-model="filterStore.selected.vegetarian" />
        </span>
        <span>
          <label for="gluten_free">gluten_free </label>
          <input name="glutenFree" v-model="filterStore.selected.glutenFree" />
        </span>
        <span>
          <label for="meal_precision">meal_precision </label>
          <input name="mealPrecision" v-model="filterStore.selected.mealPrecision" />
        </span>
      </div>
    </div>
  </form>
</template>

<style scoped>
#eventForm {
  background-color: #cfd4e4;
  height: 100vh;
}
form {
  position: absolute;
  left: 75vw;
  top: 0;
  display: flex;
  flex-direction: column;
  width: 25vw;
  z-index: 999;
}

span input,
span select {
  background-color: #eff1f6;
}

input,
select {
  padding: 0.3rem;
  border-radius: 0.2rem;
  border: none;
  transition: 0.2s;
}

input:focus {
  outline: none;
  background-color: #313956 !important;
  color: white;
}

.inlineInput {
  display: flex;
  flex-direction: row;
  margin-top: 0.15em;
  justify-content: flex-end;
}

#topInfo {
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  padding: 1rem;
  padding-top: 0.5rem;
  padding-bottom: 0.5em;
  background-color: #434f77;
  color: white;
  font-size: 1.2rem;
}

#formationName:hover {
  cursor: pointer;
}

#formationName {
  font-size: 1.3rem;
  margin: 0px;
  width: 100%;
}

.dateInput {
  display: flex;
  flex-direction: column;
  justify-content: center;
}

span label {
  margin-left: 0.2rem;
  margin-right: 0.2rem;
}

.dateInput span input {
  margin: 0px;
  padding: 0px !important;
  font-size: 0.7em;
}

#paxNumber {
  width: 3rem;
}

#orgaInfo {
  display: flex;
  flex-direction: column;
  justify-content: center;
}

#orgaInfo span {
  display: flex;
  justify-content: flex-end;
  padding: 0.1rem;
}

.iconLabel {
  display: flex;
  width: fit-content;
  justify-content: center;
  align-items: center;
}

#orgaInfo span label {
  margin-right: 0.8rem;
  font-size: 0.8em;
}

#roomInfo h2 {
  margin-bottom: 0.5rem;
}

#roomInfo span {
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

#conf {
  display: flex;
  justify-content: flex-start;
}

#conf span {
  margin-right: 0.5rem;
}

#conf span label {
  text-align: center;
}

#furnituresBox {
  display: flex;
  flex-direction: row;
  justify-content: flex-start;
  align-items: flex-start;
  flex-wrap: wrap;
  transition: 0.2s;
}

.selectedFurniture {
  background-color: #434f77;
  color: white;
  border-color: black !important;
}

#furnituresBox input[type='checkbox'] {
  display: none;
}

.furnitures label {
  padding: 0.2rem;
  transition: 0.2s;
  border-radius: 5px;
  border: 1px solid white;
}

.furnitures label:hover {
  cursor: pointer !important;
  border-radius: 5px;
  background-color: #313956;
  color: white;
}

.furnitures {
  margin-right: 0.2rem;
  margin-bottom: 0.2rem;
  display: flex;
  flex-direction: row;
  justify-content: space-evenly;
  max-width: 5rem;
}

#conf input[type='number'] {
  width: 3rem;
  align-self: center;
}

#hostTable {
  display: flex;
  flex-direction: row !important;
  align-items: flex-end;
}

#hostTable input {
  align-self: flex-end;
  margin-bottom: 0.3rem;
}

#hostTable label {
  margin-right: 0.5rem;
}

#roomConfigurationValue {
  display: flex;
  flex-direction: row !important;
  align-items: flex-end !important;
}

.roomConfigurationValue input,
.roomConfigurationValue select {
  max-width: 9em;
}

.roomConfigurationArg {
  display: flex;
  flex-direction: row;
}

.configuration_opt {
  width: 2rem;
}

#room-select {
  display: flex;
  flex-direction: row;
  margin-top: 0.2rem;
}

#formEventContainer {
  display: flex;
  flex-direction: row;
  justify-content: space-center;
  flex-wrap: wrap;
}

#roomInfo {
  padding: 1em;
  padding-bottom: 0.5em;
  padding-top: 0px;
}

#startDay {
  display: flex;
  flex-direction: column;
  padding: 1em;
  padding-bottom: 0.5em;
  padding-top: 0px;
}

#startDay div,
#startDay span div {
  display: flex;
  flex-direction: column;
  padding-top: 0.5rem;
}

#restauration {
  display: flex;
  flex-direction: column;
  padding: 1em;
}

#topBar {
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.flex-col {
  display: flex;
  flex-direction: column;
}

.just-center {
  justify-content: center;
}

.pt-1 {
  padding-top: 1em;
}

.flex-row {
  display: flex;
  flex-direction: row;
}

.just-between {
  justify-content: space-between;
}

#roomFurnitures span input[type='checkbox'] {
  display: none;
}

#roomFurnitures span input[type='number'] {
  width: 4rem;
}

#roomFurnitures {
  display: flex;
  flex-direction: row;
  justify-content: flex-start;
}

#roomFurnitures span {
  margin: 0.2rem;
}

#hostTable label {
  margin-left: 0px;
  padding: 0.2rem;
  transition: 0.2s !important;
  border-radius: 5px;
  border: 1px solid white;
}

#hostTable label:hover {
  cursor: pointer;
}

#hostTable {
  display: flex;
  flex-direction: column !important;
  justify-content: flex-end !important;
}
#hosts-name input {
  margin-top: 0.2em;
}
</style>
