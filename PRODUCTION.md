# The Faster Scale App -- A Production Deployment

### Pre-requisites
* PHP >= 8.2
  * Composer
  * Additional PHP modules: php-pgsql, php-gd, php-mbstring
* NodeJS >= 12 & npm >= 6
  * ```npm install uglify-js uglifycss node-sass -g```
* PostgreSQL 9.1 or later

This application is deployed live on https://fasterscaleapp.com using Capistrano. A recipe for that can be found in ```config/deploy.rb```. Additional instructions will be added in the near future.

Setup in a Production environment would be something like the following:  
1. ```composer install --no-dev --optimize-autoloader```  
1. ```./init --env=Production```  
1. Edit ```common/config/main-local.php``` and add your database connection information (host, dbname, username, password)  
1. Run all the database migrations via ```./yii migrate```. This should be successful if your database is set up and your connection information is correct.   
1. Generate the bundled static assets (CSS & JS) via ```composer assets```  
1. Start up your webserver and PHP (beyond the scope of these instructions)  

**Note: You should always serve this site over https. TLS Certificates are free to obtain and easy to install. We suggest [https://certbot.eff.org/](https://certbot.eff.org/) but there are many options.**

## Set up a cache
We strongly suggest configuring an application cache when running this application in a Production environment. By default this application is configured without a cache (see ```common/config/main.php``` the components->cache value is set to ```yii\caching\DummyCache::class```).

For the best speed we recommend using Redis or Memcached. For low or medium usage instances the basic FileCache component would likely be sufficient.

[Click here to learn about Yii2's support for cache mechanisms](https://www.yiiframework.com/doc/guide/2.0/en/caching-data#supported-cache-storage).

### Asset Troubleshooting
Yii's support for asset transpilation and compression is mediocre. Particularly, debugging is difficult and tedious. Most often, issues stem from the specific installation of the required NPM packages. Triple check the required cli (```uglifyjs```, ```uglifycss```, ```node-sass```) modules are installed, available in the ```PATH``` of the correct user, and executable. For other asset related issues please file an issue and ask for assistance.
