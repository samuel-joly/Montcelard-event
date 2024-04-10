<script lang="ts">
import { defineComponent, onMounted, ref } from 'vue'

export default defineComponent({
  props: {
    n: String,
    w: Number,
    h: Number
  },
  setup(props) {
    const iconPath = `/admin/src/assets/${props.n}.png`
    return { props, iconPath }
  }
})
</script>

<template>
  <img :alt="props.n" :src="iconPath" :height="props.h" :width="props.w" />
</template>
