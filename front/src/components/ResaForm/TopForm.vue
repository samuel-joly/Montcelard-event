<script lang="ts">
import Icon from '@/components/Icon.vue'
import ActionButtons from '@/components/ResaForm/ActionButtons.vue'
import { defineComponent } from 'vue'
import { useGridFilter } from '@/stores/useGridFilter'

export default defineComponent({
  components: {
    Icon,
    ActionButtons
  },
  setup() {
    const gridStore = useGridFilter()

    return {
      gridStore
    }
  },
  computed: {
    getHostsName(): string[] | null {
      if (this.gridStore.selected != null) {
        return this.gridStore.selected.hostName.split(',')
      }
      return null
    }
  },
  methods: {}
})
</script>

<template>
  <div id="topInfo" v-if="gridStore.selected != null">
    <ActionButtons />
    <input id="formationName" name="name" v-model="gridStore.selected.name" />
    <div id="topBar">
      <div class="flex-row just-between">
        <span class="dateRow">
          <input
            name="start_date"
            type="date"
            :value="gridStore.selected.date.toISOString().split('T')[0]"
            @input="
              (event) => {
                const newDate = (event.target as HTMLInputElement).valueAsDate
                if (gridStore.selected != null && newDate != null) {
                  gridStore.selected.date = newDate
                }
              }
            "
          />
        </span>
        <span class="dateRow">
          <label for="paxNumber">pax</label>
          <input id="paxNumber" type="number" name="guests" v-model="gridStore.selected.guests" />
        </span>
      </div>
      <div class="flex-row just-between">
        <span class="flex-row just-between">
          <input type="time" v-model="gridStore.selected.startHour" />
          <input
            type="time"
            v-model="gridStore.selected.endHour"
            style="margin-left: 0.18em; margin-right: 0"
          />
        </span>
        <span id="room-select dateRow">
          <label for="room">Salle</label>
          <select id="room" v-model="gridStore.selected.roomId">
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
    <div>
      <div id="orgaFields">
        <div class="flex-row just-between align-center">
          <span class="flex-col">
            <label for="resp_dossier">Resp. dossier</label>
            <input name="respDossier" id="resp_dossier" v-model="gridStore.selected.orgaName" />
          </span>
          <span class="flex-col">
            <label for="resp_groupe">Resp. groupe</label>
            <input name="resp_groupe" id="resp_groupe" v-model="gridStore.selected.orgaName" />
          </span>
        </div>
        <div class="flex-col">
          <label for="host_name">Anim.</label>
          <input id="host_name" :v-model="gridStore.selected.hostName" :value="getHostsName" />
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
#orgaFields span label {
  font-size: 0.7em;
}

#topInfo {
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  padding: 1rem;
  font-size: 1.2rem;
  border-bottom: 1px solid #ffffff;
}

#topBar {
  margin-top: 0.25em;
}

#topInfo span {
  margin-top: 0.25em;
}

#topInfo label {
  font-size: 0.7em;
}

#topInfo label {
  margin-right: 0.25em;
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
  padding-top: 0.1rem;
}

#orgaInfo span label {
  margin-right: 0.8rem;
  font-size: 0.8em;
}

.dateRow {
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: start;
}

.dateRow label {
  width: 2em;
}
</style>
