# Gismo Route Component


## Parameters

The Plugin will register two


```php
$someRoute = $di->route->add("/action/:p1/:p2", function ($p1, $p2=null))->bind("someName");
```

Later

```php
$someRoute->getLink(["p1"=>"some", "p2"=>"other");
```


### Paths

```php
$someRouter = $di->route->add("/action/:p1/::path", function ($p1, ...$path));

$someRouter->getLink(["p1"=>"some", "this", "is", "a", "path"]);
```

### Access Methods

```php
$di->route->add("POST@/action/:p1/:p2", function ($p1, $p2) {
});
```


### Mounting

```php
$di->route->mount("/some", function () use ($di) {
    // this is only executed if route matches /some
    
    $action = $di->route->add("/action", function ());
    
    echo $action->getLink(); // -> Will output: "/some/action"
});
```


### Intercepting

```php
$di->route["route.alias"][100] = function (array $§§parameters) { return $§§parameters; };
$di->route["route.alias"][-1] = function ($§§return) { return $§§return };
```


## Http Masquery

```php
$di->route->add("/some/route", function ($body) {})->useBodyAsParameter("body");
```

Will look at "content-type" header and tries to treat the Content
as whatever is specified.


