Phartitura [![Build Status](https://travis-ci.org/cloudson/Phartitura.png)](https://travis-ci.org/cloudson/Phartitura)[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/cloudson/Phartitura/badges/quality-score.png?s=fd65510d7e5e13502575f78fdc0f69c58983e919)](https://scrutinizer-ci.com/g/cloudson/Phartitura/a)[![Code Coverage](https://scrutinizer-ci.com/g/cloudson/Phartitura/badges/coverage.png?s=265d0e6814ac8f3f3985698511a21ff89e84ee57)](https://scrutinizer-ci.com/g/cloudson/Phartitura/)
==================

![Phartitura](./public/images/logo.png)

### At first, you could... 
This project born only for fun! I'm an (ever) student, so, you could help me with source code, fixing bugs, improving new features or simply rewriting text in english (btw yet I'm not fluent, my humble apologies). 


### What is it ? 

Phartitura (reads it like 'partitura', a latin word that refers the music sheet used by musicians) is an application that shows to you the status of an library/package on packagist. 
It will list each dependency from the package, showing the latest version and the current version that composer.json requires and it will downloads. 

### What uses Phartitura? 

* PHP 5.4+ (obviously)
* [Respect tools](http://github.com/respect)
* Redis 
* New Relic extension 

### How it works ?

When you access /symfony/symfony, Phartitura sends a request to packagist for know which the latest version of that package. Then, it will send many requests, one for each dependency, to know which version `composer install` will download and which is the latest version.
Phartitura uses cache for hours, so, if your package has a new release doesn't shown. Take it easy (listening [Mozart](http://www.youtube.com/watch?v=k1-TrAvp_xs)).

### Bugs (?)

* Composer uses [many versions rules comparators](https://getcomposer.org/doc/01-basic-usage.md#package-versions) to download a package. I decide implement everyone instead of use something from [oficial package](http://github.com/composer/composer).  
Some rules are not yet supported, if a package uses of these rules, a 5xx http error will happens.  
* Furthermore, many people aren't following [SEMVER](http://semver.org/), Phartitura uses it and it will hate these package lol.


### License 

See LICENSE file.
