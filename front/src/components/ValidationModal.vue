<script setup lang="ts">
    import {defineProps, ref} from 'vue'
    import type {PropType} from 'vue'
    const show = ref(false)

    const props = defineProps({
        action: {
            type: String,
            required: true,
        },
        id: {
            type: Number,
            required: true,
        },
        quantity: {
            type: Number,
            default: 1,
        },
        func: {
            type: Function as PropType<(id: number) => void>,
            required: true,
        }
    });

    function validate(resp:boolean): void {
        if (resp) {
            props.func(props.id);
        } else {
            show.value = false;
        }
    }
</script>

<template >
    <button class="delete-act" @click="show=!show"></button>
    <div v-if="show" class="modal">
        <div class="modalForm">
            <p>
                Êtes-vous sûr de vouloir {{props.action}} sur {{props.quantity}} élément ?
            </p>
            <span>
                <button class="yesBtn" @click="validate(true)">Oui</button>
                <button class="noBtn" @click="validate(false)">Non</button>
            </span>
        </div>
    </div>
</template>

<style scoped>

.modal {
    position: absolute;
    top:0px;
    left:0px;
    width:100vw;
    height: 100vh;
    background-color: rgba(0,0,0,50%);
    display:flex;
    justify-content:center;
    align-items: center;
}

.modalForm {
    background-color: white;
    border-radius: 5px;
    padding: 1rem;
    display:flex;
    flex-direction:column;
}

span {
    display: flex;
    justify-content: space-evenly;
}

button {
    font-size: 1.2em;
    border: none;
    color: white;
    border-radius: 3px;
    transition: 0.3s;
}

button:hover {
    cursor: pointer;
}

.noBtn {
    background-color: #00DD00;
}
.yesBtn {
    background-color: #DD0000;
}
.noBtn:hover {
    background-color: #00BB00;
}
.yesBtn:hover {
    background-color: #BB0000;
}

.delete-act {
    width:26px;
    height:26px;
    display: flex;
    border:0px;
    background: url('@/assets/delete.png');
    background-size: cover;
    transition: 0.3s;
}

.delete-act:hover {
    cursor: pointer;
    background: url('@/assets/delete-color.png');
    background-size: cover;
}

</style>
