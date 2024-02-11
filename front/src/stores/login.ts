import { ref } from 'vue'
import { defineStore } from 'pinia'
import type Login from '@/classes/Login'

export const useLogin = defineStore('loginStore', () => {
  const userName = ref(localStorage.getItem('name') ?? '')
  const jwt = ref(localStorage.getItem('jwt') ?? '')
  function logIn(login: Login, password: string) {
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
    })
      .then((response) => {
        switch (response.status) {
          case 200:
            return response.json()
          case 500:
            throw new Error('API crashed with code :' + response.status)
          case 401: {
            throw new Error('Wrong email or password')
          }
          default:
            throw new Error('' + response.status)
        }
      })
      .then((response) => {
        jwt.value = response.data.jwt
        userName.value = login.name
        localStorage.setItem('jwt', jwt.value)
        localStorage.setItem('name', userName.value)
      })
      .catch((e: Error) => {
        console.error('Login failed:', e)
      })
  }

  return { jwt, userName, logIn }
})
