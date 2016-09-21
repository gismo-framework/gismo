# Cache Component


Usage:

```
$cache = new Cache(new FileSystemCacheDriver(), new DiContainer());
echo $cache(function ($name) {
    echo "Fresh: ";
    return "Hello $name";
}, "SomeName");
```