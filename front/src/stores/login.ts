import { defineStore } from 'pinia'

export const useLogin = defineStore('loginStore', {
  state: () => ({
    email: localStorage.getItem('name') ?? '',
    jwt: localStorage.getItem('jwt') ?? ''
  }),
  getters: {
    getUserName: (state): string => state.email,
    getJwt: (state): string => state.jwt
  },
  actions: {
    logIn(email: string, password: string) {
      fetch('http://localhost/api/login', {
        method: 'POST',
        body: JSON.stringify({
          email: email,
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
              throw new Error('API return status ' + response.status)
            case 401: {
              throw new Error('Wrong email or password')
            }
            default:
              throw new Error('' + response.status)
          }
        })
        .then((response) => {
          this.jwt = response.data.jwt
          this.email = email
          localStorage.setItem('jwt', this.jwt)
          localStorage.setItem('name', this.email)
        })
        .catch((e: Error) => {
          console.error('Login failed:', e)
        })
    }
  }
})
