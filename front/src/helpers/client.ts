import {useLogin} from '@/stores/login'

export class Client {
    baseUrl: string = "http://localhost/api/";
    loginStore: ReturnType<typeof useLogin>;

    constructor() {
        this.loginStore = useLogin();
    }

    async get(entity: String,  id?: number) {
        let req_uri = this.baseUrl+entity;
        let data;
        if (id != null) {
            req_uri = this.baseUrl+entity+"/"+id
        } 
        let res = await fetch(req_uri, {
            method: 'GET',
            headers: {
                accept: 'application/json',
                'Content-Type' :  'application/json',
                Bearer: this.loginStore.jwt
            },
        });
        if (res.status != 200) {
            throw new Error("request failed with status "+ res.status);
            this.handleStatus(res.status);
            data = []
        } else {
            data = await res.json()
        }   
        return data;
    }

    async delete(entity: String,  id?: number) {
        let req_uri = this.baseUrl+entity;
        let data;
        if (id != null) {
            req_uri = this.baseUrl+entity+"/"+id
        } 
        const res = await fetch(req_uri, {
            method: 'DELETE',
            headers: {
                accept: 'application/json',
                'Content-Type' :  'application/json',
                Bearer: this.loginStore.jwt
            },
        })

        if (!res.ok) {
            throw new Error("request failed with status "+ res.status);
            this.handleStatus(res.status);
            data = [];
        } else {
            data = await res.json();
        }
        return data;
    }

    async post(entity: String,  body: any, id?: number) {
        let req_uri = this.baseUrl+entity;
        let data;
        if (id != null) {
            req_uri = this.baseUrl+entity+"/"+id
        } 
        const res = await fetch(req_uri, {
            method: 'POST',
            headers: {
                accept: 'application/json',
                'Content-Type' :  'application/json',
                Bearer: this.loginStore.jwt
            },
            body: body,
        })

        if (!res.ok) {
            throw new Error("request failed with status "+ res.status);
            this.handleStatus(res.status);
            data = []
        } else {
            data = await res.json();
        }
        return data
    }

    handleStatus(status: number) {
        switch (status) {
            case 401:
                console.error("You have not the rights to be here");
            break;
            case 403:
                console.warn("JWT token is expired");
                localStorage.removeitem('jwt')
                localStorage.removeitem('name')
            break;
            case 500:
                console.error("Server internal error")
            break;
        }
    }   
}
