<script setup lang="ts">
import { useLogin } from '@/stores/login'
import PanelNav from '@/components/PanelNav.vue'
import { ref } from 'vue'

const loginStore = useLogin()
const password = ref('')
</script>

<template>
  <div v-if="loginStore.jwt.length > 0">
    <PanelNav />
    <RouterView />
  </div>
  <div v-else="loginStore.jwt.length == 0">
    <input v-model="loginStore.userName" />
    <input v-model="password" />
    <button @click="loginStore.logIn(loginStore.userName, password)">Login</button>
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
