import { defineStore } from 'pinia'
import type Login from '@/classes/Login'

export const useLogin = defineStore('loginStore', {
    state: () => ({
        userName: localStorage.getItem("name") ?? "",
        jwt: localStorage.getItem("jwt") ?? "",
    }),
    getters: {
        getUserName: (state): string => state.userName,
        getJwt: (state): string => state.jwt,
    },
    actions: {
        logIn(login: Login, password: string) {
            fetch('http://localhost/api/login', {
                method: 'POST',
                body: JSON.stringify({
                    name: login.name,
                    password: password
                }),
                headers: {
                    accept: 'application/json',
                    'Content-Type': 'application/json'
                }
            }).then((response) => {
                switch (response.status) {
                    case 200:
                        return response.json()
                    case 500:
                        throw new Error('API return status ' + response.status)
                    case 401: {
                        throw new Error('Wrong email or password')
                    }
                    default:
                        throw new Error('' + response.status)
                }
            }).then((response) => {
                this.jwt = response.data.jwt
                this.userName = login.name
                localStorage.setItem('jwt', this.jwt)
                localStorage.setItem('name', this.userName)
            }).catch((e: Error) => {
                console.error('Login failed:', e)
            })
        }

    }
})
