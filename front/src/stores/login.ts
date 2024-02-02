import { ref, computed } from 'vue'
import { defineStore } from 'pinia'

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
    function logIn(name: string, password: string) {
        fetch('http://localhost/api/login', {
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
            .then((response) => {
                if (response.status != 200) {
                    throw new Error("Login failed")
                } else {
                    return response.json()
                }
            })
            .then((response) => {
                    jwt.value = response.data.jwt
                    userName.value = name;
                    localStorage.setItem('jwt', jwt.value);
                    localStorage.setItem('name', userName.value);
            })
    }   
    
    return {jwt, userName, logIn}
})
