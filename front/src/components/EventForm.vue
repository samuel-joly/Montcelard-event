<script lang="ts">
import { useRoute } from 'vue-router'
import { Client } from '@/helpers/client'
import { defineComponent, ref, onMounted, computed } from 'vue'
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
          if (result.data.length > 0) {
            event.value = result.data[0]
          } else {
            throw new Error('No data found with id ' + router.params.id)
          }
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

      <div></div>
      <div>
        <span>
          <label for="orga_name">Contact administratif</label>
          <input name="orga_name" v-model="event.orga_name" />
        </span>

        <span>
          <label for="orga_mail">Mail</label>
          <input name="orga_mail" v-model="event.orga_mail" />
        </span>
      </div>

      <div>
        <span>
          <label for="orga_tel">Téléphone</label>
          <input name="orga_tel" v-model="event.orga_tel" />
        </span>
      </div>
    </div>

    <span>
      <label for="host_name">Nom formateur</label>
      <input name="host_name" v-model="event.host_name" />
    </span>

    <span>
      <label for="room_configuration">room_configuration</label>
      <input name="room_configuration" v-model="event.room_configuration" />
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
      <label for="room_configuration_precision ">room_configuration_precision </label>
      <input name="room_configuration_precision " v-model="event.room_configuration_precision" />
    </span>

    <span>
      <label for="pause_date ">pause_date </label>
      <input name="pause_date " v-model="event.pause_date" />
    </span>

    <span>
      <label for="start_hour_offset ">start_hour_offset </label>
      <input name="start_hour_offset " v-model="event.start_hour_offset" />
    </span>

    <span>
      <label for="end_hour_offset ">end_hour_offset </label>
      <input name="end_hour_offset " v-model="event.end_hour_offset" />
    </span>

    <span>
      <label for="host_table">host_table</label>
      <input name="host_table" v-model="event.host_table" />
    </span>

    <span>
      <label for="paperboard ">paperboard </label>
      <input name="paperboard " v-model="event.paperboard" />
    </span>

    <span>
      <label for="chair_sup ">chair_sup </label>
      <input name="chair_sup " v-model="event.chair_sup" />
    </span>

    <span>
      <label for="table_sup ">table_sup </label>
      <input name="table_sup " v-model="event.table_sup" />
    </span>

    <span>
      <label for="pen ">pen </label>
      <input name="pen " v-model="event.pen" />
    </span>

    <span>
      <label for="paper ">paper </label>
      <input name="paper " v-model="event.paper" />
    </span>

    <span>
      <label for="scissors ">scissors </label>
      <input name="scissors " v-model="event.scissors" />
    </span>

    <span>
      <label for="scotch ">scotch </label>
      <input name="scotch " v-model="event.scotch" />
    </span>

    <span>
      <label for="post_it_xl ">post_it_xl </label>
      <input name="post_it_xl " v-model="event.post_it_xl" />
    </span>

    <span>
      <label for="paper_a1 ">paper_a1 </label>
      <input name="paper_a1 " v-model="event.paper_a1" />
    </span>

    <span>
      <label for="bloc_note ">bloc_note </label>
      <input name="bloc_note " v-model="event.bloc_note" />
    </span>

    <span>
      <label for="gomette ">gomette </label>
      <input name="gomette " v-model="event.gomette" />
    </span>

    <span>
      <label for="post_it ">post_it </label>
      <input name="post_it " v-model="event.post_it" />
    </span>

    <span>
      <label for="coffee_groom ">coffee_groom </label>
      <input name="coffee_groom " v-model="event.coffee_groom" />
    </span>

    <span>
      <label for="meal ">meal </label>
      <input name="meal " v-model="event.meal" />
    </span>

    <span>
      <label for="morning_coffee ">morning_coffee </label>
      <input name="morning_coffee " v-model="event.morning_coffee" />
    </span>

    <span>
      <label for="afternoon_coffee ">afternoon_coffee </label>
      <input name="afternoon_coffee " v-model="event.afternoon_coffee" />
    </span>

    <span>
      <label for="coktail ">coktail </label>
      <input name="coktail " v-model="event.coktail" />
    </span>

    <span>
      <label for="vegetarian ">vegetarian </label>
      <input name="vegetarian " v-model="event.vegetarian" />
    </span>

    <span>
      <label for="gluten_free ">gluten_free </label>
      <input name="gluten_free " v-model="event.gluten_free" />
    </span>

    <span>
      <label for="meal_precision ">meal_precision </label>
      <input name="meal_precision " v-model="event.meal_precision" />
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
#topInfo {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  padding: 1rem;
  background-color: #333333;
  color: white;
  font-size: 1.2rem;
}

#topInfo input {
  padding: 0.3rem;
  border-radius: 0.2rem;
  border: none;
  background-color: #aaaaaa;
  transition: 0.2s;
}

#topInfo input:focus {
  outline: none;
  background-color: #777777;
  color: white;
}
#topInfo span div {
  padding: 0.2rem;
  display: flex;
  flex-direction: row;
  justify-content: flex-start;
}
#formationName:hover {
  cursor: pointer;
}
#formationName {
  background-color: #333333 !important;
  color: white;
  font-size: 2rem;
  margin: 0px;
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
</style>
