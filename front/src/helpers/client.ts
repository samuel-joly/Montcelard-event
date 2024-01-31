import {useLogin} from '@/stores/login'

export class Client {
    baseUrl: string = "http://localhost/api/";
    loginStore: ReturnType<typeof useLogin>;

    constructor() {
        this.loginStore = useLogin();
    }

    request(entity: String,  method: string, body: any, id?: number) {
        let req_uri = this.baseUrl+entity;
        if (id != null) {
            req_uri = this.baseUrl+entity+"/"+id
        } 
        fetch(req_uri, {
            method: method,
            headers: {
                accept: 'application/json',
                'Content-Type' :  'application/json',
                Bearer: this.loginStore.jwt
            },
            body: body,
        })
        .then((res) => {
            if (res.status != 200) {
                throw new Error("request failed with status "+ res.status);
                this.handleStatus(res.status);
            } else {
                return res.json();
            }
        })
        .then((res) => {
            return res;
        })
        .catch((error: Error) => {
            console.error(error);
        })
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
