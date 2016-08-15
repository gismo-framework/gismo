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


#### Section Prototypes

```php
$di["event.__PROTO__"] = function () {
    return $di->factory (function () {
        return new DiCallChain($di, false);
    }
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


### Add Object Properties

Instead of using `$di["key"]` you can also use `$di->key`. Keys are 
stored in `$di["properties.key"]`;




### Filter values

```
$di["someValue"] = $di->filter(function ($value) {
    return "You said: $value";
}, 99);
```


### Internal Parameters

Internal Parameters (defined by Framework) start with double `§`: Example 
`$§§parameters`.



### DiProviders (Instanciating a variety of Classes)

DiContainer will check for 

```php
$di[0] = function ($§§name) {
    return $di->factory(function () {
        
    });  
};
```


### DiServices (Extending the DiContainer)

DiServices are traits that extend the DiContainer.

Naming-Convention is `GoDiService_<ComponentName>`. Each trait implements
`private function __di_service_init_<componentName>()`. This method is called automaticly
on DiContainer::__construct();

## Call Chain

For most Services it's best Practice, to not only return a callback
but to provide the ability to hook between this calls.

Therefor there is the Object `DiCallChain`


Constructors

```php
$c = new DiCallChain($di);
$c = new DiCallChain($di, false); // <= Filter only mode (for Events)
```

In FilterOnlyMode there cannot be registered any MainAction nor any
Filters with priority below 1.

### Registering the Main Action

```php
$c = new DiCallChain($diContainer),

$c["BOOTSTRAP"] = function () {
} // The very first callback to be executed

$c[44] = function () {
} // => Filter the Input variables 



$c[0] = function ($a, $b, $c) {
    // The main Action
}

$c[-1] = function ($§§result) {
}

$c["FINALLY"] = function () {
} // 
```

To start the Action just invoke the container by calling `__invoke()`.  
Parameters are not specified by order but by name:

```php
$c(["a"=>"some Val", "b" => "some Val", "c" => "some Val");
```


### Using as Event - Handler

Using the `filter()` Method to bind a Filter to the Event Handler

```
$di["event.someEventName"] = filter(function (DiCallChain $chain) {
    $chain[9] = function (§§data) {
        reuturn false; // Stop processing
    }
});
```

Fireing the Event:

```
$ret = $di["event.someEventName"]([eventData]);

if ($ret === false) {
    // The Chain was aborted
}

```