<script lang="ts">
import { defineComponent } from 'vue'
import Icon from '@/components/Icon.vue'
import { Client } from '@/helpers/client'
import { Reservation as Resa } from '@/classes/Reservation'
import { useResaFilter } from '@/stores/useResaFilter'
import TopForm from '@/components/ResaForm/TopForm.vue'
import RoomConfig from '@/components/ResaForm/RoomConfig.vue'

export default defineComponent({
    components: {
        Icon,
        TopForm,
        RoomConfig,
    },
    setup() {
        const filterStore = useResaFilter()
        const client = new Client()
        async function putResa(e: Event) {
            e.preventDefault()
            if (filterStore.selected != null) {
                client.put<Resa>('reservation', filterStore.selected)
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
            putResa
        }
    },
    computed: {
        getHostsName(): string[] | null {
            if (this.filterStore.selected != null) {
                return this.filterStore.selected.hostName.split(',')
            }
            return null
        }
    },
})
</script>

<template>
    <form id="eventForm" v-if="filterStore.selected != null">
        <TopForm />
        <RoomConfig />
        <div id="startDay">
            <span>
                <label for="host_name">Nom formateur</label>
                <div id="hosts-name" v-if="getHostsName != null">
                    <input :v-model="getHostsName[index]" :value="hostName" v-for="(hostName, index) in getHostsName" />
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
    background-color: var(--op-1);
}

form {
    display: flex;
    flex-direction: column;
    width: 25vw;
    z-index: 999;
    margin-right:1em;
}

#hosts-name input {
    margin-top: 0.2em;
}
</style>
