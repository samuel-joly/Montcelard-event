<script lang="ts">
import { defineComponent } from 'vue'
import { useGridFilter } from '@/stores/useGridFilter';

export default defineComponent({
    setup() {
        const gridStore = useGridFilter()
        return {
            gridStore,
        }
    },
})
</script>

<template>
    <div id="restauration" v-if="gridStore.selected != null">
        <h3>Restauration</h3>
        <div class="flex-row just-between">
            <span>
                <label for="meal">Repas</label>
                <input class="sm-input" type="number" name="meal" v-model="gridStore.selected.meal" />
            </span>
            <span style="margin-left: 0.5em">
                <label for="coktail">Coktail</label>
                <input class="sm-input" type="number" name="coktail" v-model="gridStore.selected.coktail" />
            </span>
        </div>
        <div class="flex-row just-between">
            <span>
                <label for="coffee_am">Pause AM</label>
                <input class="sm-input" type="number" name="coffeeAM" v-model="gridStore.selected.coffeeGroom" />
            </span>
            <input type="text" name="coffeeAMContent" />
        </div>
        <div class="flex-row just-between">
            <span>
                <label for="coffee_pm">Pause PM</label>
                <input class="sm-input" type="number" name="coffeePM" v-model="gridStore.selected.coffeeGroom" />
            </span>
            <input type="text" name="coffeePMContent" />
        </div>
        <div class="flex-row just-between">
            <span>
                <label for="vegetarian">Végétarien</label>
                <input class="sm-input" type="number" name="vegetarian" v-model="gridStore.selected.vegetarian" />
            </span>
            <span>
                <label for="gluten_free">Sans gluten</label>
                <input class="sm-input" type="number" name="glutenFree" v-model="gridStore.selected.glutenFree" />
            </span>
        </div>
        <span class="flex-col just-start">
            <label for="meal_precision">Précision</label>
            <textarea name="mealPrecision" v-model="gridStore.selected.mealPrecision" />
        </span>
    </div>

</template>
<style scoped>
#restauration {
    padding-left: 1em;
    padding-right: 1em;
}

#restauration div {
    margin-top: 0.25em;
}

#restauration div span label {
    width: 6em;
    text-align: left;
}

#restauration div span {
    display: flex;
    flex-direction: row;
    justify-content: flex-start;
}
</style>
