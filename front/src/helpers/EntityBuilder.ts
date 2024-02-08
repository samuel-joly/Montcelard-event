import type { EntityInterface, EntitySchema } from '@/types/EntityInterface'

export default class EntityBuilder {
  static build_entity<EntityType extends EntityInterface>(
    entity: EntityType,
    data: any,
    entitySchema: EntitySchema
  ): EntityType {
    entity.setId(data['id'])
    Object.keys(entitySchema).map((attr_name: string) => {
      const attr_type: string = this.renameType(entitySchema[attr_name])

      if (Object.prototype.hasOwnProperty.call(entity, attr_name)) {
        if (typeof data[attr_name] != attr_type) {
          data[attr_name] = this.cast(data[attr_name], attr_type)
        }
        entity.setValue(attr_name, data[attr_name])
      } else {
        throw new Error(
          'Attribute "' + attr_name + '" is not present in "' + entity.getEntityName() + '" entity'
        )
      }
    })
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
              '" to "' +
              to +
              '" if necessary'
          )
        }
        break
      case 'DateTimeImmutable':
        if (typeof data == 'string') {
          data = new Date(data)
        } else {
          throw new Error(
            'Can only cast string to Date. Create the cast ' + typeof data + ' if necessary'
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
