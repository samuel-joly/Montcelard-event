<script setup lang="ts">
    import {Suspense} from 'vue'
    import EventTableBody from '@/components/EventTableBody.vue'
</script>

<template>
    <div>
        <table>
            <tr id="tableHead">
                <th>Début</th>
                <th>Fin</th>
                <th>Formation</th>
                <th>Organisateur</th>
                <th>Participants</th>
                <th>ID</th>
                <th style="background-color:white"></th>
            </tr>
            <Suspense>
                <EventTableBody/>
                <template #fallback>
                    Loading...
                </template>
            </Suspense>
        </table>
    </div>
</template>

<style scoped>
div {
    width: 80vw;
    display:flex;
    flex-direction:column;
}

#tableHead {
    background-color: gray;
    color: white;
}   

td {
    text-align:center
}
</style>
