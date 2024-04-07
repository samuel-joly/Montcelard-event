<script lang="ts">
import { defineComponent, ref } from 'vue'
import type { PropType } from 'vue'

export default defineComponent({
  props: {
    action: {
      type: String,
      required: true
    },
    id: {
      type: Number,
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
      type: Function as PropType<(id: number) => void>,
      required: true
    }
  },
  setup(props) {
    const show = ref(false)
    function validate(resp: boolean): void {
      if (resp) {
        props.func(props.id)
      } else {
        show.value = false
      }
    }
    return { props, validate, show }
  },
  computed: {
    setBgColor() {
      return 'background-color:' + this.color
    }
  }
})
</script>

<template>
  <button :style="setBgColor" @click="show = !show">{{ props.action }}</button>
  <div v-if="show" class="modal">
    <div class="modalForm">
      <p>Êtes-vous sûr de vouloir {{ props.action }} sur {{ props.quantity }} élément ?</p>
      <span>
        <button class="noBtn" @click="validate(false)">Non</button>
        <button class="yesBtn" @click="validate(true)">Oui</button>
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
