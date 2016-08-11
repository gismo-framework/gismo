# Gismo Partial Component


## Partials

Partials are a container for either a List of Sections (`ListPartial`) or
a Tree (Navigation) (`NavigationPartial`)

## Hook into Partials

```php
$app[SomePartial::class] = $app->filter(function (SomePartial $partial) {
    $partial[] = new PlainSection("Some Data");
    return $partial;
});


```


## Pages

