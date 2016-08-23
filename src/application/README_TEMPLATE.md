# Templates

## Link

Die Link Funktion generiert, na sowas, Links:

```
link(path, getParams=null)
```

Beispiele:

```
link("/some/path", "abc")               => "/prefix/some/path/abc"
link(["/some/path", "param"])           => "/prefix/some/path/param"
link(["/some/path"], {"var": "val"})    => "/prefix/some/path?var=val 
```
