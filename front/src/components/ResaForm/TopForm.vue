<script lang="ts">
import Icon from '@/components/Icon.vue'
import { defineComponent } from 'vue'
import { useResaFilter } from '@/stores/useResaFilter'
import { useGridFilter } from '@/stores/useGridFilter'

export default defineComponent({
  components: {
    Icon
  },
  setup() {
    const filterStore = useResaFilter()
    const gridStore = useGridFilter();
    return {
      filterStore,
      gridStore
    }
  }
})
</script>

<template>
  <div id="topInfo" v-if="gridStore.selected != null">
    <input id="formationName" name="name" v-model="gridStore.selected.name" />
    <div id="topBar">
      <div class="flex-row just-between">
        <span>
          <input
            name="start_date"
            type="date"
            :value="gridStore.selected.startDate.toISOString().split('T')[0]"
            @input="
              (event) => {
                const newDate = (event.target as HTMLInputElement).valueAsDate
                if (gridStore.selected != null && newDate != null) {
                  gridStore.selected.startDate = newDate
                }
              }
            "
          />
        </span>
        <span class="inlineInput just-between">
          <input id="paxNumber" type="number" name="guests" v-model="gridStore.selected.guests" />
        </span>
      </div>
      <div class="flex-row just-between">
        <span>
          <input
            name="end_date"
            type="date"
            :value="gridStore.selected.endDate.toISOString().split('T')[0]"
            @input="
              (event) => {
                const newDate = (event.target as HTMLInputElement).valueAsDate
                if (gridStore.selected != null && newDate != null) {
                  gridStore.selected.endDate = newDate
                }
              }
            "
          />
        </span>
        <div class="just-between">
          <span id="room-select">
            <select v-model="gridStore.selected.roomId">
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
    </div>
    <div>
      <div id="flex-row just-between" style="padding-top: 0.3rem">
        <span class="flex-row just-between" style="width: 100%">
          <input name="orgaName" id="orga_name" v-model="gridStore.selected.orgaName" />
          <input name="orgaMail" id="orga_mail" v-model="gridStore.selected.orgaMail" />
        </span>
        <span>
          <input name="orgaTel" id="orga_tel" v-model="gridStore.selected.orgaTel" />
        </span>
      </div>
    </div>
  </div>
</template>

<style scoped>
#topInfo {
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  padding: 1rem;
  color: white;
  font-size: 1.2rem;
  border-bottom: 1px solid #ffffff;
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
</style>
