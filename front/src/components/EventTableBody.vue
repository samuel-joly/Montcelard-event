<script setup lang="ts">
    import type {EventInterface} from '../types/Event'
    import {ref,defineProps} from 'vue'
    import {useLogin} from '@/stores/login'
    import ValidationModal from '@/components/ValidationModal.vue'
    import {Client} from '@/helpers/client'

    const loginStore = useLogin()
    const client = new Client();
    const events = ref<EventInterface[]>([]);
    const getEvent = await client.get('event');
    getEvent.data.map((e: EventInterface) => events.value.push(e));

    const props = defineProps({
        limit: {
            type: Number,
            default: 10,
        },
    }) 

    async function deleteEvent(id:number) {
         await client.delete('event', id);
         events.value = events.value.filter((e:EventInterface) => e.id != id)
    }

</script>

<template>
    <tr v-for="event in events.slice(0, props.limit)" :key="event.id">
        <td>{{event.start_date}}</td>
        <td>{{event.end_date}}</td>
        <td>{{event.name}}</td>
        <td>{{event.orga_name}}</td>
        <td>{{event.guests}}</td>
        <td>{{event.id}}</td>
        <td class="action">
            <router-link class="edit-act" :to="{name:'event-edit', params:{id:event.id}}"/>
                <ValidationModal :func="deleteEvent" action="supprimer" :id="event.id"/>
        </td>
    </tr>
</template>

<style scoped>
.action {
    background-color: white;
    display:flex;
    flex-direction: row;
    justify-content: flex-start;
}

.edit-act {
    width:26px;
    height:26px;
    display: flex;
    border:0px;
    background: url('@/assets/edit.png');
    background-size: cover;
    transition: 0.3s;
}

.edit-act:hover {
    cursor: pointer;
    background: url('@/assets/edit-color.png');
    background-size: cover;
}

tr:hover {
    background-color: lightgray;
}

td {
    text-align:center
}

</style>
