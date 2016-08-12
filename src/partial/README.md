# Gismo Partial Component


## Partials

Pages consist of Partials. So partials are registred under page
Namespace:

```
page.shop.somePage.mainNavigation
```

## Hook into Partials

```php
$app["page.somePage.mainNavigation"] = $app->filter(function ($§§value) use ($app) {
    $§§value[5] = "page.main.someOtherPartial";
    $§§value[1] = function ($§§data) {
    }
    return $partial;
});
```

This will register the callback handlers. 




## Execute Partials

```php
$app["page.shop.somePage.mainNavigation"]($optionalData);
```

Will execute the Partial and return 


## Pages

Pages are Partials but under a different name:

```
page.somePage
```

Nothing more than less.