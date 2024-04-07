<script setup lang="ts">
import { useLogin } from '@/stores/login'
import { ref } from 'vue'
import Events from '@/components/Events.vue'
import Login from '@/classes/Login'

const loginStore = useLogin()
const password = ref('Montcelard_password')
const login: Login = new Login('Montcelard_user', 'Montcelard_user@gmail.com')
</script>

<template>
    <div v-if="loginStore.jwt.length > 0">
        <Events />
    </div>
    <div v-else>
        <input v-model="login.name" />
        <input v-model="password" />
        <button @click="loginStore.logIn(login, password)">Login</button>
    </div>
</template>

<style scoped>
main {
    display: flex;
    background-color: #333300;
    color: #ffffff;
}
</style>
