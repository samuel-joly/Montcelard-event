<script setup lang="ts">
import { useLogin } from '@/stores/login'
import PanelNav from '@/components/PanelNav.vue'
import { ref } from 'vue'
import Login from '@/classes/Login'

const loginStore = useLogin()
const password = ref('Montcelard_password')
const login: Login = new Login('Montcelard_user', 'Montcelard_user@gmail.com')
</script>

<template>
  <div v-if="loginStore.jwt.length > 0">
    <PanelNav />
    <RouterView />
  </div>
  <div v-else="loginStore.jwt.length == 0">
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

div {
  display: flex;
}
</style>
