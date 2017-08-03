# Golafix Application


## Config-File

### Example

```

# Define Templates (relative to the config-file)
tpl.site: tpl/tpl.site.html
tpl.ce.wurst: tpl/tpl.ce.wurst.html

# Define Variables used in templates
tplvar.sitebar.nav:
    - name: "Home"
      link: "/some/routing/url"
      icon: fa-link
      childs:
        - name: "Go home"
          link: "/some/routing/url"
          icon: fa-link


# Define Routes:

routes:
    "login": "tpl.site.login"
    "E404":  "tpl.site.e404"
    "E500":  "tpl.site.e500"


```