export interface EntityInterface {
  id: number
  setId(id: number): void
  setValue(attribute: string, value: any): void
  getEntityName(): string
}

export class EntitySchema {
    // HAHAHAHAHAHAHAHAH
  [name: string]: string
}
