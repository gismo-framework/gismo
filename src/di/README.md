# Di Container Component

## Services, Factory, Constant

* `Factory`: Is called each time the key is accessed
* `Service`: Is called only once the key is accessed (Singleton)
* `Constant`: The Value is injected directly

### Adding a Service


A *ClassName* Service

```
$di[] = $di->service(function () : ConcreteClass {
    return new ConcreteClass();
});
```

Or - shortcut:

```
$di[] = function () : ConcreteClass {
    return new ConcreteClass();
}
```

Named Services:

```
$di["someName"] = function () : ConcreteClass {
}
```

Can also return basic types

```
$di["shopName"] = function () : string {
}
```


### Factories

> By default, Dependency Injection relies on Services. 
> Be sure you want to create a new Instance on each call.

Adding a factory.

```
$di[] = $di->factory(function () : ConcreteClass {
});
```



### Constants

```
$di[] = $di->constant(new SomeClass());
```

works only with classes.

Shortcut

```
$di["key"] = "Some basic Value";
```

is Shortcut for

```
$di["key"] = $di->constant("Some basic Value");
```


### Overwriting Factories, etc.

This won't work:
```
$di["key"] = "some value";
$di["key"] = "some other value";
```

You'll have to explicitly unset the value

```
unset($di["key"]);
$di["key"] = "some other value";
```


To prevent someone from overwriting important shit, you can
protect values:

```
$di["key"] = $di->factory(..)->protectOverwriting("Dont change this!")->protectFilter();
```


## Add Object Properties

Instead of using `$di["key"]` you can also use `$di->key`. Keys are 
stored in `$di["properties.key"]`;




## Filter values

```
$di["someValue"] = $di->filter(function ($value) {
    return "You said: $value";
}, 99);
```


## Internal Parameters

Internal Parameters (defined by Framework) start with double `§`: Example 
`$§§parameters`.



## DiProviders (Instanciating a variety of Classes)

DiContainer will check for 

```php
$di[0] = function ($§§name) {
    return $di->factory(function () {
        
    });  
};
```


## DiServices (Extending the DiContainer)

DiServices are traits that extend the DiContainer.

Naming-Convention is `GoDiService_<ComponentName>`. Each trait implements
`private function __di_service_init_<componentName>()`. This method is called automaticly
on DiContainer::__construct();

