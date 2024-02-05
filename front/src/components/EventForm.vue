<script lang="ts">
    import {useRoute} from 'vue-router'
    import {Client} from '@/helpers/client'
    import { defineComponent, ref, onMounted } from 'vue';
    import type { EventInterface } from '@/types/Event';

    export default defineComponent({
      setup() {
        const data = ref<EventInterface | null>(null);
        onMounted(async () => {
          try {
            const result = await fetchData();

            if (result.data.length > 0) {
                data.value = result.data[0];
            } else {
                data.value = null
            }
          } catch (error) {
            console.error('Error:', error);
          }
        });
        return {
          data,
        };
      },
    });

    async function fetchData(): Promise<{data:EventInterface[], message:string}> {
        const client = new Client();
        const router = useRoute()
        return await client.get('event', Number(router.params.id));
    }
</script>

<template>
    <p v-if="data != null">{{data.id}}</p>
</template>

<style scoped>
</style>

