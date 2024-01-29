<script setup lang="ts">
    import {ApiErrorHandle} from '../helpers/ApiErrorHandle'
    import {useLogin} from '../stores/login'
    import {ref} from 'vue'
    import EventClass from '@/classes/Event'
    import type {EventInterface} from '@/types/Event'
    import Event from '../components/Event.vue'

    const loginStore = useLogin()
    const events = ref<[EventInterface]|null>();

    fetch("http://localhost/api/event", {
        method: 'GET',
        headers: {
            'Content-type': 'application/json',
            accept: 'application/json',
            Bearer: loginStore.jwt
        },
    })
    .then((res) => res.json()).then((res) => {
        if (res.code == 200) {
        events.value = (res.data)
        console.log(res.data)
        } else {
            ApiErrorHandle(res.code);
        }
    });
</script>

<template>
    <div>
        <Event 
            v-for="event in events"
            :event="event">
        </Event>
    </div>
</template>

<style scoped>
    div {
        display: flex;
        flex-direction: column;
    }
</style>
