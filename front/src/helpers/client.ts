import { useLogin } from '@/stores/login'
import EntityBuilder from '@/helpers/EntityBuilder'
import type { EntityInterface } from '@/types/EntityInterface'
import { Event } from '@/classes/Event'

export class Client {
  baseUrl: string = 'http://localhost/api/'
  loginStore: ReturnType<typeof useLogin>

  constructor() {
    this.loginStore = useLogin()
  }

  async get<EntityType extends EntityInterface>(
    entity: EntityType,
    id?: number,
    qp?: string
  ): Promise<{ data: EntityType[]; message: string }> {
    let req_uri = this.baseUrl + entity.getEntityName()
    if (qp == null) {
      if (id != null) {
        req_uri += '/' + id
      }
    }
    req_uri += '?schema=true'
    if (qp != null) {
      req_uri += '&' + qp
    }
    const res = await fetch(req_uri, {
      method: 'GET',
      headers: {
        accept: 'application/json',
        'Content-Type': 'application/json',
        Bearer: this.loginStore.jwt
      }
    })
    if (res.status != 200) {
      this.handleStatus(res.status)
      throw new Error('request failed with status ' + res.status)
    }

    const resp: { data: any; message: string } = await res.json()
    const data_schema: { [name: string]: string } = resp.data.pop()
    resp.data.map((resp_obj: any) => {
      EntityBuilder.build<EntityType>(entity, resp_obj, data_schema)
    })
    return resp
  }

  async delete(entityName: String, id?: number): Promise<{ data: any[]; message: string }> {
    let req_uri = this.baseUrl + entityName
    let data
    if (id != null) {
      req_uri = this.baseUrl + entityName + '/' + id
    }
    const res = await fetch(req_uri, {
      method: 'DELETE',
      headers: {
        accept: 'application/json',
        'Content-Type': 'application/json',
        Bearer: this.loginStore.jwt
      }
    })

    if (!res.ok) {
      this.handleStatus(res.status)
      throw new Error('request failed with status ' + res.status)
    } else {
      data = await res.json()
    }
    return data
  }

  async put<EntityType extends EntityInterface>(
    entityName: string,
    entity: EntityType
  ): Promise<{ data: any[]; message: string }> {
    let req_uri = this.baseUrl + entityName + '/' + entity.id
    let data
    const res = await fetch(req_uri, {
      method: 'PUT',
      headers: {
        accept: 'application/json',
        'Content-Type': 'application/json',
        Bearer: this.loginStore.jwt
      },
      body: JSON.stringify(entity)
    })

    if (!res.ok) {
      this.handleStatus(res.status)
      throw new Error('PUT request failed with status ' + res.status)
    } else {
      data = await res.json()
    }
    return data
  }

  async post(
    entityName: String,
    body: any,
    id?: number
  ): Promise<{ data: any[]; message: string }> {
    let req_uri = this.baseUrl + entityName
    let data
    if (id != null) {
      req_uri = this.baseUrl + entityName + '/' + id
    }
    const res = await fetch(req_uri, {
      method: 'POST',
      headers: {
        accept: 'application/json',
        'Content-Type': 'application/json',
        Bearer: this.loginStore.jwt
      },
      body: JSON.stringify(body)
    })

    if (!res.ok) {
      this.handleStatus(res.status)
      throw new Error('POST request failed with status ' + res.status)
    } else {
      data = await res.json()
    }
    return data
  }

  handleStatus(status: number) {
    switch (status) {
      case 401:
        console.error('You have not the rights to be here')
        localStorage.removeItem('jwt')
        localStorage.removeItem('name')
        break
      case 403:
        console.warn('JWT token is expired')
        localStorage.removeItem('jwt')
        localStorage.removeItem('name')
        break
      case 500:
        console.error('Server internal error')
        break
    }
  }
}
