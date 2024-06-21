<script lang="ts">
import { defineComponent } from 'vue'
import Icon from '@/components/Icon.vue'
import { Client } from '@/helpers/client'
import { Reservation as Resa } from '@/classes/Reservation'
import { useResaFilter } from '@/stores/useResaFilter'
import TopForm from '@/components/ResaForm/TopForm.vue'
import RoomConfig from '@/components/ResaForm/RoomConfig.vue'
import { useGridFilter } from '@/stores/useGridFilter'

export default defineComponent({
    components: {
        Icon,
        TopForm,
        RoomConfig
    },
    setup() {
        const filterStore = useResaFilter()
        const gridStore = useGridFilter()
        const client = new Client()
        async function putResa(e: Event) {
            e.preventDefault()
            if (gridStore.selected != null) {
                client.put<Resa>('reservation', gridStore.selected)
                filterStore.results.map((e) => {
                    if (gridStore.selected != null) {
                        if (e.id == gridStore.selected.id) {
                            e = gridStore.selected
                        }
                    }
                })
            }
        }

        return {
            filterStore,
            gridStore,
            putResa
        }
    },
    computed: {
        getHostsName(): string[] | null {
            if (this.gridStore.selected != null) {
                return this.gridStore.selected.hostName.split(',')
            }
            return null
        }
    }
})
</script>

<template>
    <form id="eventForm" v-if="gridStore.selected != null">
        <TopForm />
        <RoomConfig />
        <div id="startDay">
            <span> </span>
            <div id="restauration">
                <span>
                    <label for="coffee_groom">Café accueil</label>
                    <input name="coffeeGroom" v-model="gridStore.selected.coffeeGroom" />
                </span>
                <span>
                    <label for="meal">Repas</label>
                    <input name="meal" v-model="gridStore.selected.meal" />
                </span>
                <span>
                    <label for="afternoon_coffee">Viennoiserie</label>
                    <input name="afternoonCoffee" v-model="gridStore.selected.afternoonCoffee" />
                </span>
                <span>
                    <label for="coktail">coktail</label>
                    <input name="coktail" v-model="gridStore.selected.coktail" />
                </span>
                <span>
                    <label for="vegetarian">vegetarian </label>
                    <input name="vegetarian" v-model="gridStore.selected.vegetarian" />
                </span>
                <span>
                    <label for="gluten_free">gluten_free </label>
                    <input name="glutenFree" v-model="gridStore.selected.glutenFree" />
                </span>
                <span>
                    <label for="meal_precision">meal_precision </label>
                    <input name="mealPrecision" v-model="gridStore.selected.mealPrecision" />
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
    margin-right: 1em;
}
</style>
