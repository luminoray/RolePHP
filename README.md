# RolePHP

Welcome to RolePHP, a simple PHP library for creating your own browser RPG. This project is still under very early development.

## Installation

RolePHP is available via [Composer](https://getcomposer.org/), so there are a few options for installation (assuming composer is installed, if not, please refer to [Composer Installation](https://getcomposer.org/download/)):

#### Terminal
Navigate to your project folder and execute the following command:

```
php composer.phar require luminoray/role-php
```

#### Dependency definition
This method is useful if you intend your project to depend on more packages other than RolePHP. Create a file on your project's root named _composer.json_ with the following contents:

```
{
	"require": {
		"luminoray/role-php" : "dev-master"
	},
	"minimum-stability" : "dev"
}
```

The _dev-master_ version defined refers to the current status of the master branch in this github project. As development goes on, this should be changed to RolePHP's current version (_v1.0_, for example). Since RolePHP is still under development, the minimum stability at the moment of updating this README file must be set to _dev_.

Once the file has been created, navigate to your project folder and execute the following command:

```
php composer.phar install
```

## Updating

In order to update your installation of RolePHP, you just need to navigate to your project folder and execute the following command:

```
php composer.phar update
```

This will fetch the latest matching version according to your _composer.json_ file. Please refer to [Composer Basic Usage](https://getcomposer.org/doc/01-basic-usage.md#composer-lock-the-lock-file) for more information.

## Basic usage

RolePHP provides a framework for creating your own PHP-based browser RPG. As such, RolePHP is not immediately useable, as it allows the developer to create their own damage formulas.

Once RolePHP has been installed, you must include the _autoload.php_ file, located in the _vendor_ folder:

```php
<?php
require_once('vendor/autoload.php');
```

Once that file has been included, you will have access to the RolePHP classes, as well as any other dependencies you defined on _composer.json_ during installation. However, the **Character** class included is an abstract class, and must be extended to fit the developer's needs. When extending **Character**, you must define the methods as described on the character interface **iCharacter**, located in _/Vendor/LuminoRay/RolePHP/src/character/iCharacter.php_:

```php
interface iCharacter {
	public function __construct(iStatus $status);
	public function attack(iCharacter $target);
	public function skill($name, $level, iCharacter $target);
	public function __get($stat_prop);
}
```

The **Character** class already defines the `__construct()` and `__get()` methods, which means only `attack()` and `skill()` must be defined in our class extension. Here's an example:

```php
class MY_Character extends LuminoRay\RolePHP\Character\Character {
	public function attack(LuminoRay\RolePHP\Character\iCharacter $target) {
		$damage = $this->attack - $target->defense;
		if ($damage > 0) {
			$new_health = $target->health - $damage;
		} else {
			$new_health = $target->health - 1;
		}
		$target->status->stat_set('health', $new_health, 0, 'greater_equal', 'set_constraint');
	}
	
	public function skill($name, $level, LuminoRay\RolePHP\Character\iCharacter $target) {
		echo ('Skills are not yet implemented.\n');
	}
}
```

Notice that we are using class properties such as `attack`, `defense` and `health`. These properties are defined by the `__construct` method of the **Character** class, when passing a **Status** object to the constructor. At this moment the **Character** object will contain a **Status** object, with its own properties and methods.

Notice how at the the end of the `attack()` method, we call the **Status** `stat_set()` method to update the target's health. The function's parameters can be viewed in the status interface **iStatus**, located in _/Vendor/LuminoRay/RolePHP/src/character/iCharacter.php_:

```php
interface iStatus {
	public function __construct(array $stat_array);
	public function stat_set($stat_prop, $value, $constraint, $compare, $fallback);
}
```

* `$stat_prop` is the status property to update.
* `$value` is the new value we will set the property to.
* `$constraint` is necessary if we will be making a comparison between the new value and the constraint value. Defaut 0.
* `$compare` is the type of comparison we will perform. This can be 'equal', 'not_equal', 'less', 'less_equal', 'greater', 'greater_equal'. Default 'not_equal'.
* `$fallback` defines what will be done in case the comparison is false. This can be 'cancel', 'set_constraint'. Default 'set_constraint'.

Once we have created our own **MY_Character** class, we can now begin using RolePHP.

#### Instantiation

Before we create a **MY_Character** object, we must first create a **Status** object that will be contained within. To do this we must first define a status associative array, and then we instantiate **Status** by passing this array to the constructor. Here's an example where we generate stats for two characters:

```php
$player_array = array(
	'name' => 'LuminoRay',
	'health' => 20,
	'max_health' => 20,
	'attack' => 10,
	'defense' => 4
);

$enemy_array = array(
	'name' => 'Slime',
	'health' => 10,
	'max_health' => 10,
	'attack' => 7,
	'defense' => 2
);

$player_stats = new LuminoRay\RolePHP\Character\Status($player_array);
$enemy_array = new LuminoRay\RolePHP\Character\Status($enemy_array);
```

With the **Status** objects instantiated, we can now proceed to create our **MY_Character** instances:

```php
$player = new MY_Character($player_stats);
$enemy = new MY_Character($enemy_stats);
```

From here on, we can play around, view their stats via `$player->health` or `$player->status->health`, and command them to attack each other:

```php
echo ("{$player->name} - {$player->health} / {$player->max_health} HP\n"); // LuminoRay - 20 / 20 HP
echo ("{$enemy->name} - {$enemy->health} / {$enemy->max_health} HP\n\n"); // Slime - 10 / 10 HP

$player->attack($enemy);
echo ("{$player->name} attacked {$enemy->name}!\n"); // LuminoRay attacked Slime!

echo ("{$player->name} - {$player->health} / {$player->max_health} HP\n"); // LuminoRay - 20 / 20 HP
echo ("{$enemy->name} - {$enemy->health} / {$enemy->max_health} HP\n\n"); // Slime - 2 / 10 HP
```

Keep in mind that that properties are read-only, and must be updated via the **Status** object's `stat_set()` method.

Have fun!