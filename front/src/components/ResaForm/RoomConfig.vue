<script lang="ts">
import { defineComponent } from 'vue'
import { useResaFilter } from '@/stores/useResaFilter'
import { useGridFilter } from '@/stores/useGridFilter';

export default defineComponent({
  setup() {
    const gridStore = useGridFilter();
    return {
      gridStore
    }
  }
})
</script>

<template>
  <div id="formResaContainer" v-if="gridStore.selected != null">
    <div id="roomInfo">
      <div id="conf">
        <span>
          <h3>Configuration</h3>
          <select v-model="gridStore.selected.roomConfiguration">
            <option value="U">En U</option>
            <option value="Ilots">Ilots</option>
            <option value="Theatre">Théâtre</option>
            <option value="Board">Board</option>
          </select>
        </span>
        <span v-if="gridStore.selected.roomConfiguration == 'Ilots'">
          <div class="roomConfigurationArg"></div>
          <div class="roomConfigurationValue" style="display: flex; flex-direction: row">
            <input
              class="configuration_opt"
              type="number"
              v-model="gridStore.selected.configurationSize"
            />
            <p style="margin: 0 0.5rem 0 0.5rem">x</p>
            <input
              class="configuration_opt"
              type="number"
              v-model="gridStore.selected.configurationQuantity"
            />
          </div>
        </span>
        <span v-if="gridStore.selected.roomConfiguration == 'U'">
          <div id="roomConfigurationArg">
            <small></small>
          </div>
          <div id="roomConfigurationValue">
            <select
              :value="gridStore.selected.configurationSize"
              @input="
                (event) => {
                  const value = (event.target as HTMLInputElement).value
                  if (gridStore.selected != null && value != null) {
                    gridStore.selected.configurationSize = Number(value)
                  }
                }
              "
            >
              <option value="8">8</option>
              <option value="10">10</option>
              <option value="12">12</option>
              <option value="14">14</option>
              <option value="16">16</option>
              <option value="18">18</option>
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
            v-model="gridStore.selected.paperboard"
          />
        </span>
        <span>
          <label for="chair_sup">chaise +</label>
          <input type="number" name="chairSup" v-model="gridStore.selected.chairSup" />
        </span>
        <span>
          <label for="table_sup">tables +</label>
          <input type="number" name="tableSup" v-model="gridStore.selected.tableSup" />
        </span>
        <span id="hostTable">
          <label
            :class="[gridStore.selected.hostTable ? 'selectedFurniture' : '']"
            for="host_table"
            >Table Formateur</label
          >
          <input
            type="checkbox"
            id="host_table"
            name="host_table"
            v-model="gridStore.selected.hostTable"
          />
        </span>
      </div>
      <h3>Matériel</h3>
      <div id="furnituresBox">
        <span class="furnitures">
          <label :class="[gridStore.selected.pen ? 'selectedFurniture' : '']" for="pen"
            >stylos</label
          >
          <input type="checkbox" id="pen" name="pen" v-model="gridStore.selected.pen" />
        </span>
        <span class="furnitures">
          <label :class="[gridStore.selected.blocNote ? 'selectedFurniture' : '']" for="bloc_note"
            >bloc-note</label
          >
          <input
            id="bloc_note"
            type="checkbox"
            name="bloc_note"
            v-model="gridStore.selected.blocNote"
          />
        </span>
        <span class="furnitures">
          <label :class="[gridStore.selected.paper ? 'selectedFurniture' : '']" for="paper"
            >A4</label
          >
          <input id="paper" type="checkbox" name="paper" v-model="gridStore.selected.paper" />
        </span>
        <span class="furnitures">
          <label :class="[gridStore.selected.paperA1 ? 'selectedFurniture' : '']" for="paper_a1"
            >A1</label
          >
          <input
            id="paper_a1"
            type="checkbox"
            name="paper_a1"
            v-model="gridStore.selected.paperA1"
          />
        </span>
        <span class="furnitures">
          <label :class="[gridStore.selected.scissors ? 'selectedFurniture' : '']" for="scissors"
            >ciseau</label
          >
          <input
            id="scissors"
            type="checkbox"
            name="scissors"
            v-model="gridStore.selected.scissors"
          />
        </span>
        <span class="furnitures">
          <label :class="[gridStore.selected.scotch ? 'selectedFurniture' : '']" for="scotch"
            >scotch</label
          >
          <input id="scotch" type="checkbox" name="scotch" v-model="gridStore.selected.scotch" />
        </span>
        <span class="furnitures">
          <label :class="[gridStore.selected.postIt ? 'selectedFurniture' : '']" for="post_it"
            >post-it</label
          >
          <input id="postIt" type="checkbox" name="postIt" v-model="gridStore.selected.postIt" />
        </span>
        <span class="furnitures">
          <label
            :class="[gridStore.selected.postItXl ? 'selectedFurniture' : '']"
            for="post_it_xl"
            >post-it XL</label
          >
          <input
            id="post_it_xl"
            type="checkbox"
            name="post_it_xl"
            v-model="gridStore.selected.postItXl"
          />
        </span>
        <span class="furnitures">
          <label :class="[gridStore.selected.gomette ? 'selectedFurniture' : '']" for="gomette"
            >gomette</label
          >
          <input
            id="gomette"
            type="checkbox"
            name="gomette"
            v-model="gridStore.selected.gomette"
          />
        </span>
      </div>
      <span>
        <label for="room_configuration_precision">Précision</label>
        <textarea
          name="room_configuration_precision"
          v-model="gridStore.selected.roomConfigurationPrecision"
        />
      </span>
    </div>
  </div>
</template>

<style scoped>
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

#formResaContainer {
  display: flex;
  flex-direction: row;
  justify-content: space-center;
  flex-wrap: wrap;
  border-bottom: 1px solid #ffffff;
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
</style>
