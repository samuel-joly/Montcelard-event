import type { EntityInterface } from '@/types/EntityInterface'
import { Room } from '@/classes/Event'

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
          "'is not present in entity for for data '" +
          entity.getEntityName() +
          "'"
      )
    }
    Object.keys(entitySchema).map((schema_attr_name: string) => {
      const schema_type: string = this.renameType(entitySchema[schema_attr_name])
      if (Object.prototype.hasOwnProperty.call(entity, schema_attr_name)) {
        try {
          if (typeof data[schema_attr_name] != schema_type) {
            data[schema_attr_name] = this.cast(data[schema_attr_name], schema_type)
          }
        } catch (error) {
          console.error(
            'During mapping of ' +
              entity.getEntityName() +
              ' on ' +
              schema_attr_name +
              ' with type: ' +
              schema_type,
            error
          )
        }
      } else {
        throw new Error(
          'Attribute "' +
            schema_attr_name +
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
      case 'Room':
        if (data == null) {
        } else if (typeof data != 'string') {
          throw new Error('Can only cast string to Room, type="' + typeof data + '" given')
        } else {
          switch (data) {
            case 'Chine':
              data = Room[Room.Chine]
              break
            case 'Bresil':
              data = Room[Room.Bresil]
              break
            case 'Tadjikistan':
              data = Room[Room.Tadjikistan]
              break
            case 'Madagascar':
              data = Room[Room.Madagascar]
              break
            case 'Liban':
              data = Room[Room.Chine]
              break
            case 'Haiti':
              data = Room[Room.Haiti]
              break
            case 'Myanmar':
              data = Room[Room.Myanmar]
              break
            case 'Mali':
              data = Room[Room.Mali]
              break
            case 'Laos':
              data = Room[Room.Laos]
              break
            case 'Cambodge':
              data = Room[Room.Cambodge]
              break
            default:
              throw new Error(
                'String to Room works only if string value is in RoomEnum. "' + data + '" given'
              )
          }
        }
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
