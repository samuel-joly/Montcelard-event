<script lang="ts">
import { useGridFilter } from '@/stores/useGridFilter'
import { defineComponent, type PropType } from 'vue'

export default defineComponent({
  props: {
    showValue: {
      type: Boolean,
      required: true
    },
    text: {
      type: String,
      required: true
    },
    color: {
      type: String,
      default: '#337788'
    },
    quantity: {
      type: Number,
      default: 1
    },
    func: {
      type: Function as PropType<() => void>,
      required: true
    }
  },
  setup(props) {
    const gridStore = useGridFilter()
    function validate(): void {
      props.func()
      gridStore.changesNotSaved = false
    }
    return { props, validate, gridStore }
  },
  computed: {
    setBgColor() {
      return 'background-color:' + this.color
    }
  },
  methods: {}
})
</script>

<template>
  <div v-if="props.showValue" class="modal" @click="gridStore.changesNotSaved">
    <div class="modalForm">
      <p>{{ props.text }}</p>
      <span>
        <button class="noBtn" @click="gridStore.changesNotSaved = false">Non</button>
        <button class="yesBtn" @click="validate()">Oui</button>
      </span>
    </div>
  </div>
</template>

<style scoped>
.modal {
  position: absolute;
  top: 0px;
  left: 0px;
  width: 100vw;
  height: 100vh;
  background-color: rgba(0, 0, 0, 50%);
  display: flex;
  justify-content: center;
  align-items: center;
}

.modalForm {
  background-color: white;
  border-radius: 5px;
  padding: 1rem;
  display: flex;
  flex-direction: column;
}

span {
  display: flex;
  justify-content: space-evenly;
}

.noBtn {
  background-color: var(--op-5);
}

.yesBtn {
  background-color: var(--op-4);
}

.noBtn:hover {
  filter: brightness(120%);
}

.yesBtn:hover {
  filter: brightness(120%);
}

button {
  font-size: 1em;
  color: white;
  padding: 0.3em;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: 0.3s;
}

button:hover {
  filter: brightness(120%);
}
</style>
