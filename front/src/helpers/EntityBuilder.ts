import type { EntityInterface } from '@/types/EntityInterface'

export default class EntityBuilder {
  static build<EntityType extends EntityInterface>(
    entity: EntityType,
    data: any,
    entitySchema: { [name: string]: string }
  ): EntityType {
    let a = Object.keys(entity)
    let b = Object.keys(data)
    const a_diff_b = a.filter((e: string) => !b.includes(e))
    const b_diff_a = b.filter((e: string) => !a.includes(e))
    if (a_diff_b.length > 0) {
      throw new Error(
        "Attribute(s) '" +
          [...a_diff_b] +
          "'is not present in data for entity '" +
          entity.getEntityName() +
          "'"
      )
    } else if (b_diff_a.length > 0) {
      throw new Error(
        "Attribute(s) '" +
          [...b_diff_a] +
          "'is not present in data for entity '" +
          entity.getEntityName() +
          "'"
      )
    }
    Object.keys(entitySchema).map((schema_name: string) => {
      const schema_type: string = this.renameType(entitySchema[schema_name])
      if (Object.prototype.hasOwnProperty.call(entity, schema_name)) {
        if (typeof data[schema_name] != schema_type) {
          data[schema_name] = this.cast(data[schema_name], schema_type)
        }
      } else {
        throw new Error(
          'Attribute "' +
            schema_name +
            '" is not present in "' +
            entity.getEntityName() +
            '" entity'
        )
      }
    })
    Object.assign(entity, data)
    entity.setId(data['id'])
    return entity
  }

  static cast(data: any, to: string): any {
    switch (to) {
      case 'boolean':
        if (typeof data == 'number') {
          data = data == 1 ? true : false
        } else {
          throw new Error(
            'Can only cast number to boolean. Create the cast "' +
              typeof data +
              '" to boolean if necessary'
          )
        }
        break
      case 'DateTimeImmutable':
        if (typeof data == 'string') {
          data = new Date(data)
        } else {
          throw new Error(
            'Can only cast string to Date. Create the cast "' +
              typeof data +
              '" to "Date" if necessary'
          )
        }
        break
      default:
        throw new Error('No cast set for "' + typeof data + '" to "' + to + '"')
        break
    }
    return data
  }

  static renameType(type: string): string {
    switch (type) {
      case 'integer':
        return 'number'
        break
      default:
        return type
        break
    }
  }
}
