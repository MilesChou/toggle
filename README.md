# toggle

[![Build Status](https://travis-ci.com/MilesChou/toggle.svg?branch=master)](https://travis-ci.com/MilesChou/toggle)
[![codecov](https://codecov.io/gh/MilesChou/toggle/branch/master/graph/badge.svg)](https://codecov.io/gh/MilesChou/toggle)

The feature toggle library for PHP

## Concept

Coming soon...

## Usage

The `Manager` class is the core class. All feature config will set on this object.

### Feature Toggle

Use the static result:

```php
use MilesChou\Toggle\Manager;

$manager = new Manager();
$manager->createFeature('f1', true);

// Will return true
$manager->isActive('f1');
```

Use the object with static return:

```php
use MilesChou\Toggle\Feature;
use MilesChou\Toggle\Manager;

$manager = new Manager();
$manager->createFeature('f1', Feature::create()->on());

// Will return true
$manager->isActive('f1');
```

Use callable to decide the return dynamically:

```php
use MilesChou\Toggle\Feature;
use MilesChou\Toggle\Manager;

$manager = new Manager();
$manager->createFeature('f1', function() {
    return true;
});

// Will return true
$manager->isActive('f1');
```
