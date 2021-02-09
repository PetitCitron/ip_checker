# ip_checker

CLI Script **PHP** for **apache2** logs Scan and complete info via Ipdata.com API (/var/log/apache2)

Sort logs by IPs. 
Add ASN name and ASN route / subnet via l'API https://ipdata.co

Possibility JSON export

Script use **grep** for read/filter log content. 

**Warning** : Is not a secure script, **exec** function in script.

## Installation

Copy config.php.sample -> **config.php**

Edit configuration for add **you're API https://ipdata.co KEY ** in $apiKey
and complete $curMonth.
```php
//...
public $apiKey = 'API_KEY';
public $curMonth = 'Dec'; // si null prends le mois en cours, sinon utilise celui la ex 'Oct'
//...
```

Need php 7.


```bash
composer require ipdata/api-client
composer require nyholm/psr7 symfony/http-client
```

```bash
php7.4 /usr/local/bin/composer require ipdata/api-client
php7.4 /usr/local/bin/composer  require nyholm/psr7 symfony/http-client
```

## Screenshots

Out default (config.php - $jsonOut = false)

![screenshot](screen.png?raw=true "Sortie normale")

Out JSON (config.php - $jsonOut = true)

![screenshot2](json.png?raw=true "Sortie json")

## LICENCE

MIT
