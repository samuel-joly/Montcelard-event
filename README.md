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

- If you use the limit keyword in query params to get values, pass it as the first query param argument
- !!!All public properties (name and type) of crud entities can be fetched with a GET method with the schema 
query parameter `GET localhost/api/[entity]/2?schema=true`!!!


## HowTo

### Filter with query params
Request template is as usual
`<host>/api/<entity>?<attr_name><operator>=<value>`

Allowed operators are typicals `<` `>` `<=` `>=` `!`
Notice that when you want tou use greater/lesser or equal, your will have two `=` after the `<attr_name>`
```localhost/api/reservation?startDate>==2024-06-03```

When you chain comparisons in request, the default is to bind them with the `and` logical operator.
If you want to use `or` logical operator, you can use the pipe `|` just before the `<attr_name>` as follow
```localhost/api/reservation?startDate>=2024-06-03&|name=resa with name```


### Fetch an entity from database
```php
$query_filter = [
    [
        "id" => 
            ["=", 2],
        "size" => 
            ["<=",5],
    ],
    // When adding more than one array in query_filter, sqlBuilder use the OR logical operator to bind filters.
    [ 
        "id" =>
            [">=", 2],
        "name" =>
            ["!=", "simpleName"],
    ],
];
$db_entity = ($entity->get($query_filter))->data;
// Query will be :
// SELECT * from entityTable where id = 2 AND size <= 5 OR id >= 2 AND name != "simpleName";
```
