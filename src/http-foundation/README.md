# Gismo Http Foundation Component

Access to Request and Response.


## Server Configuration

### Apache 2.4 (Development)

Enable Mod-Rewrite on `.htaccess` base (slow).
 
```
sudo a2enmod rewrite
sudo service apache2 restart
```

In `/etc/apache2/sites-enabled/000-default.conf` add the `AllowOverride All`
Directive:

```
<Directory /var/www>
    Options +Indexes
    AllowOverride All
</Directory>
```

Create a `.htaccess` -File

```
RewriteEngine On
# RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.*)$ app.php [QSA,L]
```

