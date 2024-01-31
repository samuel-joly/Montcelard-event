<script setup lang="ts">
    import {useRoute} from 'vue-router'
    const router = useRoute()
</script>

<template>
    <p> Form event {{router.params.id}}</p>
</template>

<style scoped>
</style>

