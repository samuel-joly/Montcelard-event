<script setup lang="ts">
    import {useLogin} from '../stores/login'
    import {ref} from 'vue'
    const store = useLogin()
    const password = ref("");
</script>

<template>
    <div v-if="store.jwt.length == 0">
        <span>
            <label for="name">Nom</label><input v-model="store.userName"/>
        </span>
        <span>
            <label for="password">Mot de passe</label><input v-model="password"/>
        </span>
        <button  @click="store.logIn(store.userName, password)">
            Se connecter
        </button>
    </div>
    <div v-if="store.jwt.length > 0">
        <p> Hello {{store.userName}}</p>
        <small> {{store.jwt}}</small>
    </div>
</template>

<style scoped>
</style>
