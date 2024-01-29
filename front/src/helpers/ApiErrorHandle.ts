export function ApiErrorHandle(responseCode: number) {
    switch (responseCode) {
        case 401:
            localStorage.removeItem('jwt')
            localStorage.removeItem('name')
        break;
        case 403:
            localStorage.removeItem('jwt')
            localStorage.removeItem('name')
        break;
    }
}
