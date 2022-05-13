# Proposed Talk for Symfony World Live - Summer 2022

* https://live.symfony.com/2022-world-summer/

## Script

* Warn about flashing lights sensitivity.

* Briefly introduce the Symfony Console package.
* Introduce the Phue package and my fork.

* Start with an empty project.
* `composer require symfony/console`
* TAG: 0.1.0 = empty symfony console application.

* How to find your bridge and register a username.
  * `./vendor/syntaxseed/phue/bin/phue-bridge-finder`
  * `./vendor/syntaxseed/phue/bin/phue-create-user 192.168.1.2

* `composer require syntaxseed/phue`
* Add the autoload section to the composer.json.
  * `composer dump-autoload`

* Create a mock container and init the Phue Client object.
* Create a `src/Command/` directory.
* Create a `GetLightsCommand` Command class.
* TAG: 0.2.0 = simple command to get lights.

* Add better error handling and create a 'turn on' command. This one takes in arguments.

* Later commands - just show code as slides.