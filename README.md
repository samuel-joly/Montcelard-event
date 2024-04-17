# Montcelard

## Building project
```sh
./mk docker up -d
```
## The `mk` command
All the project action are done throught the `mk` script.
I recommand you to use thoses aliases 
![mk command](https://github.com/samuel-joly/Montcelard-gen-fiche-technique/blob/main/mk_command.png)

## Quirks

- Last inserted entity ID must alway be highest ID in table
- If you use the limit keyword in query params to get values, dont pass it as the last query param argument
- !!!All public properties (name and type) of crud entities can be fetched with a GET method with the schema 
query parameter `GET localhost/api/[entity]/2?schema=true`!!!


## HowTo

### Fetch an entity from database
```php
$query_filter = [
    "attr_name" => 
        ["=", "comparison_value"],
    "attr_name_2" =>
        [">=", "comparison_value_2"],
]
$get_query = $entity->get($query_filter)
$entity->mysql->queryPrepare($get_query, $query_filter)
```
