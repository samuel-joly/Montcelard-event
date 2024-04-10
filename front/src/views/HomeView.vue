<script setup lang="ts">
import { useLogin } from '@/stores/login'
import Events from '@/components/Events.vue'
import Login from '@/classes/Login'

const loginStore = useLogin()
const login: Login = new Login('Montcelard_user', 'Montcelard_password')
</script>

<template>
  <div v-if="loginStore.jwt.length > 0">
    <Events />
  </div>
  <div v-else>
    <input v-model="login.email" />
    <input v-model="login.password" />
    <button @click="loginStore.logIn(login.email, login.password)">Login</button>
  </div>
</template>

<style scoped>
main {
  display: flex;
  background-color: #333300;
  color: #ffffff;
}
</style>
