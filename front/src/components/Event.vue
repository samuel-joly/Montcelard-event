<script setup lang="ts">
import { defineProps } from 'vue'
import type { PropType } from 'vue'
import type { EventInterface } from '@/types/Event'
defineProps({
  event: {
    type: Object as PropType<EventInterface>,
    required: true
  }
})
</script>

<template>
  <p>{{ event.name }}</p>
</template>

<style scoped></style>
