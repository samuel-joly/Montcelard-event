import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import {ApiErrorHandle} from '@/helpers/ApiErrorHandle'

export enum RoleEnum {
    SuperUser = 'su',
    User = 'u',
}

export interface UserInterface {
    name: string
    email: string
    role: RoleEnum
}

export const useLogin = defineStore('loginStore',() => {
    const userName = ref(localStorage.getItem('name') ?? '')
    const jwt = ref(localStorage.getItem('jwt') ?? '')
    async function logIn(name: string, password: string) {
        await fetch('http://localhost/api/login', {
            method: 'POST',
            body: JSON.stringify({
                'name': name,
                'password': password
            }),
            headers: {
                accept:  'application/json',
                'Content-Type': 'application/json',
            },
            })
            .then((response) => response.json())
            .then((response) => {
                if (response.code === 200) {
                    jwt.value = response.data.jwt
                    userName.value = name;
                    localStorage.setItem('jwt', jwt.value);
                    localStorage.setItem('name', userName.value);
                } else {
                    ApiErrorHandle(response.code)
                }
            })
    }   
    
    return {jwt, userName, logIn}
})
