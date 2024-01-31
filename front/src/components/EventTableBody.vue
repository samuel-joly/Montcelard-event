<script setup lang="ts">
    import type {EventInterface} from '../types/Event'
    import {ref,defineProps} from 'vue'
    import {useLogin} from '@/stores/login'
    import {ApiErrorHandle} from '@/helpers/ApiErrorHandle'
    import ValidationModal from '@/components/ValidationModal.vue'

    const loginStore = useLogin()
    const events = ref<EventInterface[]>([]);
    const props = defineProps({
        limit: {
            type: Number,
            default: 10,
        },
    }) 

    fetch("http://localhost/api/event", {
        method: 'GET',
        headers: {
            'Content-type': 'application/json',
            accept: 'application/json',
            Bearer: loginStore.jwt
        },
    })
    .then((res) => {
        if (res.status != 200) {
            ApiErrorHandle(res.status)
            throw new Error("failed to get events");
        } else {
            return res.json()
        }
    }).then((res) => {
        res.data.map((e: EventInterface) => {
            events.value.push(e)
        })
    })

    function deleteEvent(id:number) :void {
        fetch("http://localhost/api/event/"+id, {
            method: 'DELETE',
            headers: {
                'Content-type': 'application/json',
                accept: 'application/json',
                Bearer: loginStore.jwt
            },
        })
        .then((res) => {
            if (res.status != 200) {
                throw new Error("res.status");
            }
        }).then(() => {
             events.value = events.value.filter((e:EventInterface) => e.id != id)
        }).catch((e) => {
            ApiErrorHandle(e)
        });
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
