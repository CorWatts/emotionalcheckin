# The Faster Scale App 
> An online version of Michael Dye's Faster Relapse Awareness Scale 

[![codecov](https://codecov.io/gh/CorWatts/fasterscale/branch/master/graph/badge.svg)](https://codecov.io/gh/CorWatts/fasterscale)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/CorWatts/fasterscale/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/CorWatts/fasterscale/?branch=master)

## Getting Started
These instructions will help you get a local installation set up for development and testing purposes. See the deployment instructions for how to deploy this to a live system.

### Pre-requisites
* PHP >= 8.2
  * Composer
  * Additional PHP modules: php-pgsql, php-gd, php-mbstring, php-curl
* NodeJS >= 12 & npm >= 6
  * ```npm install uglify-js uglifycss node-sass -g```
* PostgreSQL 9.1 or later

### Development Installation
1) Install necessary dependencies with:  
    ```composer install --dev```
1) Execute the init file with:  
    ```./init --env=Development```
1) Edit ```common/config/main-local.php``` and add your database connection information (host, dbname, username, password)
1) run all yii2 db migrations ```./yii migrate```
1) startup local PHP server with ```composer start```
1) visit [http://localhost:8080/signup](http://localhost:8080/signup) and create a new user
1) log in, start working

## Assets
We have built-in support for minimizing JS and CSS assets. This is optional in both the dev and prod environments but is strongly recommended in prod to reduce the page load time. The following steps should be sufficient:  
1. Ensure the npm packages `uglify-js`,  `uglifycss`, and `node-sass` are installed via ```npm install uglify-js uglifycss node-sass -g```
2. Swap the commented and uncommented code blocks in ```site/config/bundles-local.php```
3. Run the asset compression by executing ```composer assets```

**Note:** It's best to avoid executing scripts as root whenever possible. Follow [NPM's instructions](https://docs.npmjs.com/resolving-eacces-permissions-errors-when-installing-packages-globally#manually-change-npms-default-directory) to set up a global NPM folder for a non-root user.

That should result the browser downloading published asset bundles instead of each JS/CSS file individually.

## Testing
Testing is provided by Codeception unit tests. The necessary libraries should have already been installed by Composer. First, the test database and user must be created:
* Create a PostgreSQL database and user for tests via the following SQL:  
```sql
CREATE DATABASE fsatest;  
CREATE USER fsatest WITH SUPERUSER PASSWORD 'test123';  
GRANT ALL PRIVILEGES ON DATABASE "fsatest" TO fsatest;  
```
or not via SQL
```bash
createuser -s -W fsatest   # enter password 'test123' when prompted
createdb -O fsatest fsatest
```

Then run migrations within the test database to create the tables
```bash
./yii_test migrate
```

If you choose to modify the name of db or user or the password, be sure to also modify the corresponding value in `common/config/tests-local.php`.

* Next, scaffold the test configuration files with:
```bash
composer test-scaffold
```
* To run the tests, execute:
```bash
composer test
```
To view the code coverage of these tests run: (the xdebug extension is required for generation of code coverage)
```bash
composer test-coverage
```

## Production Deployment
The authoritative instance of this application is deployed at https://fasterscaleapp.com using Capistrano. A recipe for that can be found in ```config/deploy.rb```.  In-depth Production deployment instructions can be found at [PRODUCTION.md](/PRODUCTION.md).

## License
This application is under the BSD-3 license. See [LICENSE.md](https://github.com/CorWatts/fasterscale/blob/master/LICENSE.md) for details.

The _FASTER Relapse Awareness Scale_ is copyrighted by Michael Dye and Patricia Fancher. We have been granted permission to use it.

## Contributions
Want to contribute? Wonderful! We're excited to hear any and every idea you have about the Faster Scale App. If you're struggling to come up with a task of your own to work on, take a look at our issues list and feel free to tackle any of the unassigned issues.

## Questions?
Questions can be answered by [filing an issue](https://github.com/CorWatts/fasterscale/issues/new) on this project or by joining our mailing list: <https://www.freelists.org/list/fsa-discuss>.
