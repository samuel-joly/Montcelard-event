<script lang="ts">
import { useRoute } from 'vue-router'
import { Client } from '@/helpers/client'
import { defineComponent, ref, onMounted } from 'vue'
import { Event } from '@/classes/Event'

export default defineComponent({
  setup() {
    const event = ref<Event>()
    onMounted(async () => {
      try {
        const router = useRoute()
        const client = new Client()
        const result = await client.get<Event>(new Event(), Number(router.params.id))
        if (result.data != null) {
          event.value = result.data[0]
        }
      } catch (error) {
        console.error('EventForm client call:', error)
      }
    })
    return {
      event
    }
  }
})
</script>

<template>
  <main v-if="event != null">
    <div id="topInfo">
      <span>
        <input id="formationName" name="name" v-model="event.name" />

        <div class="dateInput">
          <span>
            <label for="start_date">du</label>
            <input
              name="start_date"
              type="date"
              @input="event.start_date"
              :value="event.start_date.toISOString().split('T')[0]"
            />
          </span>
          <span>
            <label for="end_date">au</label>
            <input
              name="end_date"
              type="date"
              @input="event.end_date"
              :value="event.end_date.toISOString().split('T')[0]"
            />
          </span>
        </div>
        <div class="dateInput">
          <span>
            <label for="guests">pour</label>
            <input id="paxNumber" type="number" name="guests" v-model="event.guests" />
          </span>
        </div>
      </span>

      <div id="orgaInfo">
        <span>
          <label for="orga_name">Contact administratif</label>
          <input name="orga_name" v-model="event.orga_name" />
        </span>
        <span>
          <label for="orga_mail">Mail</label>
          <input name="orga_mail" v-model="event.orga_mail" />
        </span>
        <span>
          <label for="orga_tel">Téléphone</label>
          <input name="orga_tel" v-model="event.orga_tel" />
        </span>
      </div>
    </div>

    <div id="roomInfo">
      <h2>Salle</h2>
      <div id="conf">
        <span>
          <label>Configuration</label>
          <select v-model="event.room_configuration">
            <option value="U">En U</option>
            <option value="Ilots">Ilots</option>
            <option value="Theatre">Théâtre</option>
            <option value="Board">Board</option>
          </select>
        </span>
        <span v-if="event.room_configuration == 'Ilots'">
          <div class="roomConfigurationArg">
            <small></small>
          </div>
          <div class="roomConfigurationValue">
            <input type="text" placeholder="2x3 ou 2 par 3" v-model="event.configuration_arg" />
          </div>
        </span>
        <span v-if="event.room_configuration == 'U'">
          <div id="roomConfigurationArg">
            <small></small>
          </div>
          <div id="roomConfigurationValue">
            <select v-model="event.configuration_arg">
              <option value="8">8</option>
              <option value="10">10</option>
              <option value="12" selected>12</option>
              <option value="14">14</option>
              <option value="16">16</option>
            </select>
          </div>
        </span>
        <span>
          <label for="paperboard">ppbd</label>
          <input type="number" id="paperboard" name="paperboard" v-model="event.paperboard" />
        </span>
        <span>
          <label for="chair_sup">chaises +</label>
          <input type="number" name="chair_sup" v-model="event.chair_sup" />
        </span>
        <span>
          <label for="table_sup">tables +</label>
          <input type="number" name="table_sup" v-model="event.table_sup" />
        </span>
        <span id="hostTable">
          <label for="host_table">Table formateur</label>
          <input type="checkbox" id="host_table" name="host_table" v-model="event.host_table" />
        </span>
      </div>
      <h3>Matériel</h3>

      <div id="furnituresBox">
        <span class="furnitures">
          <label :class="[event.pen ? 'selectedFurniture' : '']" for="pen">stylos</label>
          <input type="checkbox" id="pen" name="pen" v-model="event.pen" />
        </span>
        <span class="furnitures">
          <label :class="[event.bloc_note ? 'selectedFurniture' : '']" for="bloc_note"
            >bloc-note</label
          >
          <input id="bloc_note" type="checkbox" name="bloc_note" v-model="event.bloc_note" />
        </span>
        <span class="furnitures">
          <label :class="[event.paper ? 'selectedFurniture' : '']" for="paper">feuilles</label>
          <input id="paper" type="checkbox" name="paper" v-model="event.paper" />
        </span>
        <span class="furnitures">
          <label :class="[event.paper_a1 ? 'selectedFurniture' : '']" for="paper_a1"
            >feuilles A1</label
          >
          <input id="paper_a1" type="checkbox" name="paper_a1" v-model="event.paper_a1" />
        </span>
        <span class="furnitures">
          <label :class="[event.scissors ? 'selectedFurniture' : '']" for="scissors">ciseau</label>
          <input id="scissors" type="checkbox" name="scissors" v-model="event.scissors" />
        </span>
        <span class="furnitures">
          <label :class="[event.scotch ? 'selectedFurniture' : '']" for="scotch">scotch</label>
          <input id="scotch" type="checkbox" name="scotch" v-model="event.scotch" />
        </span>
        <span class="furnitures">
          <label :class="[event.post_it ? 'selectedFurniture' : '']" for="post_it">post-it</label>
          <input id="post_it" type="checkbox" name="post_it" v-model="event.post_it" />
        </span>
        <span class="furnitures">
          <label :class="[event.post_it_xl ? 'selectedFurniture' : '']" for="post_it_xl"
            >post-it XL</label
          >
          <input id="post_it_xl" type="checkbox" name="post_it_xl" v-model="event.post_it_xl" />
        </span>
        <span class="furnitures">
          <label :class="[event.gomette ? 'selectedFurniture' : '']" for="gomette">gomette</label>
          <input id="gomette" type="checkbox" name="gomette" v-model="event.gomette" />
        </span>
      </div>

      <span>
        <label for="room_configuration_precision">Précision</label>
        <textarea
          name="room_configuration_precision"
          v-model="event.room_configuration_precision"
        />
      </span>
    </div>

    <span>
      <label for="host_name">Nom formateur</label>
      <input name="host_name" v-model="event.host_name" />
    </span>

    <span>
      <label for="start_hour">Heure de début</label>
      <input name="start_hour" v-model="event.start_hour" />
    </span>

    <span>
      <label for="end_hour">Heure de fin</label>
      <input name="end_hour" v-model="event.end_hour" />
    </span>

    <span>
      <label for="pause_date">pause_date </label>
      <input name="pause_date" v-model="event.pause_date" />
    </span>

    <span>
      <label for="start_hour_offset">start_hour_offset </label>
      <input name="start_hour_offset" v-model="event.start_hour_offset" />
    </span>

    <span>
      <label for="end_hour_offset">end_hour_offset </label>
      <input name="end_hour_offset" v-model="event.end_hour_offset" />
    </span>

    <span>
      <label for="coffee_groom">coffee_groom </label>
      <input name="coffee_groom" v-model="event.coffee_groom" />
    </span>

    <span>
      <label for="meal">meal </label>
      <input name="meal" v-model="event.meal" />
    </span>

    <span>
      <label for="morning_coffee">morning_coffee </label>
      <input name="morning_coffee" v-model="event.morning_coffee" />
    </span>

    <span>
      <label for="afternoon_coffee">afternoon_coffee </label>
      <input name="afternoon_coffee" v-model="event.afternoon_coffee" />
    </span>

    <span>
      <label for="coktail">coktail </label>
      <input name="coktail" v-model="event.coktail" />
    </span>

    <span>
      <label for="vegetarian">vegetarian </label>
      <input name="vegetarian" v-model="event.vegetarian" />
    </span>

    <span>
      <label for="gluten_free">gluten_free </label>
      <input name="gluten_free" v-model="event.gluten_free" />
    </span>

    <span>
      <label for="meal_precision">meal_precision </label>
      <input name="meal_precision" v-model="event.meal_precision" />
    </span>
  </main>
</template>

<style scoped>
main {
  display: flex;
  flex-direction: column;
  background-color: #ffffff;
  color: black;
  width: 90vw;
}

input,
select {
  padding: 0.3rem;
  border-radius: 0.2rem;
  border: none;
  background-color: #cfd4e4;
  transition: 0.2s;
}

input:focus {
  outline: none;
  background-color: #313956 !important;
  color: white;
}

#topInfo {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  padding: 1rem;
  background-color: #434f77;
  color: white;
  font-size: 1.2rem;
}

topInfo span div {
  padding: 0.2rem;
  display: flex;
  flex-direction: row;
  justify-content: flex-start;
}

#formationName:hover {
  cursor: pointer;
}

#formationName {
  background-color: #434f77 !important;
  color: white;
  font-size: 2rem;
  margin: 0px;
}
#formationName:focus {
  outline: none;
  background-color: #313956 !important;
  color: white;
}
.dateIput {
  margin: 0px;
}
.dateInput span label {
  margin-left: 0.2rem;
  margin-right: 0.2rem;
}

.dateInput span input {
  margin: 0px;
  padding: 0px !important;
  font-size: 0.85rem;
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

#orgaInfo span label {
  margin-right: 0.8rem;
  font-size: 0.8em;
}

#roomInfo {
  padding: 1rem;
  padding-top: 0px;
  background-color: #cfd4e4;
  width: 50%;
}

#roomInfo span input,
#roomInfo span select {
  background-color: #eff1f6;
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
  border-radius: 5px;
  transition: 0.2s;
  border: 1px solid white;
}
.furnitures label:hover {
  cursor: pointer !important;
  border-radius: 5px;
  background-color: #313956;
  color: white;
}
.furnitures {
  margin-right: 0.4rem;
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
</style>
