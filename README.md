# cheapskate-mvc
version 1.0.0

## Purpose
Yet another tiny mvc app that can be used to create small and simple websites **fast**.
Cheapskate MVC is mainly developed to quickly deploy a PHP-MVC app to those inexpensive
hosting providers that only offer PHP, Apache and MySQL to keep hosting cost to a minimum.

### Cheapskate MVC comes with:
* [Guzzle HTTP Client to get some data](https://github.com/guzzle/guzzle)
* [Twig for templating](https://github.com/twigphp/Twig)
* [Webpack for managing assets and transpiling javascript](https://github.com/webpack/webpack)
* simple file-based caching utility (in JSON format)
* basic routing capabilities
* basic config files for routing and caching
* pretty common file structure, so symfony users should feel somewhat comfortable
* basic asset function

### Cheapskate MVC comes without:
* a webserver (it is made to be used on the before mentioned hosting providers)
* the need to install any additional php extensions (you'll probably don't have the permissions anyway)
* dependency injection (check whether ```inject``` method fits your needs)
* beautiful exception handling and error pages (use default php error warnings etc.)

## Developing with Cheapskate MVC 
Use xampp, mamp, lamp or whatever lol.

### Install dependencies:
```
# clone repo
# install composer dependencies
composer install
# install node dependencies
yarn install
# build assets
yarn run encore dev | yarn run encore dev --watch | yarn run encore production
```

### including assets
Use the ```asset(styles.css)``` twig-function to include an asset from ```public/build``` directory " (this recognizes the webpack cachebust).

### Change .htaccess in public-dir
You might have to change the ```.htaccess``` file in ```./public``` so url-rewriting works.
Change this path to the path that comes after ```localhost:xxxx``` in your browser.
```
RewriteBase /budgie-mvc/public
```
## Documentation

### Controllers
* Controllers live in the ```controller``` directory
* Extend your controllers with ```\BudgieMVC\AppController``` to inherit these methods:
    * ```$this->render('twig-template', templateDataArray, httpStatusCode)```
        * renders a twig template with data and sets the status code (default ```200```)
    * ```$this->inject('folder-name','class-name')```
        * returns a new instance of the given class name.
        * e.g. ```$this->inject('utils', 'myUtilClass')``` will return a new instance of ```myUtilClass.php``` from the ```./utils``` directory
        * use the same name for class and file name for this

### Routing
* Routes can be mapped to Controllers and Methods inside ```./app/config/routing.ini```
* define a route and set the value to the controller and method (separate with '\')
* e.g. ```myroute=MyController\myMethod```

### Dependencies
* use composer

## Deployment
* run ```npm run prod```
* use some oldschool ftp magic and transfer everything to your favorite lamp-hosting provider
* modify ```.htaccess``` in ```/public``` directory
    * change ```RewriteBase /php-mvc/public``` to your path

## Todo
This project is still in its early stages. There's probably a lot of stuff to work on.

### things I'll do
* VueJS support
    * add some basic components

### things I'll maybe do
* add some command line tools to change the htaccess or something
* add a nicer way to use dependencies

### things I probably won't do
* add a webserver (use a real framework like symfony - it's better in every aspect)

## Inspiration
* Base idea from [codecourse.com](https://codecourse.com/)
* Symfony guys