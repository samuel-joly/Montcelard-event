<script setup lang="ts">
import { useLogin } from '@/stores/login'
import Reservations from '@/components/Reservations.vue'
import ResaTable from '@/components/ResaTable.vue'
import Login from '@/classes/Login'
import { ref } from 'vue'
import { useResaFilter } from '@/stores/useResaFilter'
import { Reservation } from '@/classes/Reservation'

const loginStore = useLogin()
const login: Login = new Login('Montcelard_user', 'Montcelard_password')
const isListView = ref(false)
const filter = useResaFilter()
//        const filterStore = useResaFilter();
//        function fillGrid() {
//            let i = 0;
//            while (i <= 60) {
//                let resa = new Reservation();
//                const dayVal = i % 5
//                resa.roomId = Math.ceil(i / 5)
//                let day = dayVal != 0 ? dayVal : 5
//                resa.startDate = new Date()
//                do {
//                    resa.startDate.setDate(resa.startDate.getDate() - 1)
//                } while (resa.startDate.getDay() != day)
//                resa.endDate = resa.startDate;
//                resa.name = "filled reservation " + i;
//                filterStore.results.push(resa);
//                resa.id = 100+i;
//                i++;
//            }
//        }
</script>

<template>
  <div class="bg-primary">
    <div id="adminView" v-if="loginStore.jwt.length > 0">
      <div id="panelNav">
        <div id="filterResas">
          <form>
            <label for="weekNumber">semaine</label>
            <input
              id="weekNumber"
              type="number"
              min="0"
              max="52"
              v-model="filter.week"
              @change="filter.getCurrentWeekResa"
            />
            <label for="year">année</label>
            <input
              id="year"
              type="number"
              v-model="filter.year"
              @change="filter.getCurrentWeekResa"
            />
          </form>
        </div>
        <label id="calendar" for="pill1" :class="isListView ? '' : 'text-mark'">Calendrier</label>
        <div class="toggle-pill">
          <input
            type="checkbox"
            id="pill1"
            name="check"
            @change="isListView = !isListView"
            :class="isListView ? 'toggled-pill' : ''"
          />
          <label for="pill1"></label>
        </div>
        <label for="pill1" :class="isListView ? 'text-mark' : ''">Liste</label>
      </div>
      <div id="main">
        <Reservations v-if="!isListView" />
        <ResaTable v-else />
      </div>
    </div>
    <div v-else>
      <input v-model="login.email" />
      <input v-model="login.password" />
      <button @click="loginStore.logIn(login.email, login.password)">Login</button>
    </div>
  </div>
</template>

<style scoped>
#weekNumber,
#year {
  max-width: 5rem;
}
#filterResas {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}

#adminView {
  display: flex;
  flex-direction: column;
  justify-content: start;
  align-items: start;
  width: 100vw;
  min-height: 100vh;
}

#panelNav {
  display: flex;
  flex-direction: row;
  justify-content: end;
  align-items: center;
  padding: 0.4em;
  width: 100%;
  height: 5vh;
  background-color: var(--op-6);
}

#panelNav label {
  color: var(--op-1);
  font-size: 1.2em;
  padding: 0.2em;
  transition: 0.2s;
}

#panelNav label:hover {
  cursor: pointer;
  color: var(--op-5);
}

.toggle-pill input[type='checkbox'] {
  display: none;
}

.toggle-pill input[type='checkbox'] + label {
  display: block;
  position: relative;
  width: 3em;
  height: 1.6em;
  border-radius: 1em;
  background: #f3f3f3;
  box-shadow: inset 0px 0px 3px 1px rgba(0, 0, 0, 0.3);
  cursor: pointer;
  -webkit-transition: background 0.1s ease-in-out;
  transition: background 0.1s ease-in-out;
}

.toggle-pill input[type='checkbox'] + label:before {
  content: '';
  display: block;
  width: 1.2em;
  height: 1.2em;
  border-radius: 1em;
  background: #50565a;
  box-shadow: 2px 0px 5px rgba(0, 0, 0, 0.2);
  position: absolute;
  left: 0.2em;
  top: 0.2em;
  -webkit-transition: all 0.2s ease-in-out;
  transition: all 0.2s ease-in-out;
}

.toggle-pill input[type='checkbox']:checked + label {
  background: #fff;
}

.toggle-pill input[type='checkbox']:checked + label:before {
  box-shadow: -2px 0px 5px rgba(0, 0, 0, 0.2);
  left: 1.6em;
}

.toggled-pill {
  box-shadow: -2px 0px 5px rgba(0, 0, 0, 0.2);
  left: 1.6em;
}

.text-mark {
  font-weight: bold;
  font-size: 1em;
}
</style>
