ZfrRest
=======

[![Build Status](https://travis-ci.org/zf-fr/zfr-rest.png?branch=master)](https://travis-ci.org/zf-fr/zfr-rest)
[![Coverage Status](https://coveralls.io/repos/zf-fr/zfr-rest/badge.png?branch=master)](https://coveralls.io/r/zf-fr/zfr-rest?branch=master)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/zf-fr/zfr-rest/badges/quality-score.png?s=78ed408c927e01cb27ab7f3cc04349a770132550)](https://scrutinizer-ci.com/g/zf-fr/zfr-rest/)
[![Dependency Status](https://www.versioneye.com/package/php--zfr--zfr-rest/badge.png)](https://www.versioneye.com/package/php--zfr--zfr-rest)

## Installation

Install the module by typing (or add it to your `composer.json` file):

`php composer.phar require zfr/zfr-rest:0.1.*`

Then, add the keys "ZfrRest" to your modules list in `application.config.php` file, and copy-paste the file
`zfr_rest.global.php.dist` into your `autoload` folder (don't forget to remove the .dist extension at the end!).

## ZfrRest vs Apigility

[Apigility](http://www.apigility.org) is a Zend Framework 2 module that also aims to simplify the creation of REST
APIs.

ZfrRest and Apigility philosophies are completely different. ZfrRest is Doctrine only, and focuses only on a very
small subset on your REST API: it provides routing, validation and hydration.

On the other hand, Apigility comes with a graphical user interface, versioning support, authorization, authentication
HAL, content negotiation... ZfrRest will never provide **all** those functionalities, so if you need them, just go
with Apigility.

ZfrRest's scope is much more limited (although I'd really like to add support for versioning and links), but I
really think it's a nice product too. So give it a try to both products, and choose the one you prefer!

## Documentation

The official documentation is available is the [/docs](/docs) folder.
