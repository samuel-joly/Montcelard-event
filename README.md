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
- If you use the limit keyword in query params to get values, pass it as the first query param argument
- !!!All public properties (name and type) of crud entities can be fetched with a GET method with the schema 
query parameter `GET localhost/api/[entity]/2?schema=true`!!!


## HowTo

### Fetch an entity from database
```php
$query_filter = [
    [
        "attr_name" => 
            ["=", "comparison_value"],
        "attr_2" => 
            ["<=", "comparison_value", OR],
    ],
    [
        "attr_name" =>
            [">=", "comparison_value_2", OR],
        "attr_2" =>
            ["!=", "comparison_value_2", OR],
    ],
];
$db_entity = ($entity->get($query_filter))->data;
```
