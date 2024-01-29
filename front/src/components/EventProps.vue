import type {PropType, defineComponent} from 'vue'
import type {EventInterface} from '@/types/Event'

export default defineComponent({
    props: {
        events: {
            type: Object as PropType<EventInterface>,
            required: false
        }
    },
    mounted: {
        this.events
    }
})
