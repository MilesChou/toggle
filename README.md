# Toggle

[![Build Status](https://travis-ci.com/MilesChou/toggle.svg?branch=master)](https://travis-ci.com/MilesChou/toggle)
[![codecov](https://codecov.io/gh/MilesChou/toggle/branch/master/graph/badge.svg)](https://codecov.io/gh/MilesChou/toggle)
[![Latest Stable Version](https://poser.pugx.org/MilesChou/toggle/v/stable)](https://packagist.org/packages/MilesChou/toggle)
[![Total Downloads](https://poser.pugx.org/MilesChou/toggle/d/total.svg)](https://packagist.org/packages/MilesChou/toggle)
[![License](https://poser.pugx.org/MilesChou/toggle/license)](https://packagist.org/packages/MilesChou/toggle)

The feature toggle library for PHP

## Concept

Coming soon...

## Usage

The `Toggle` class is the core class. All feature config will set on this object.

### Feature Toggle

Use the static result:

```php
<?php

use MilesChou\Toggle\Toggle;

$toggle = new Toggle();
$toggle->createFeature('f1', true);

// Will return true
$toggle->isActive('f1');
```

Use the object with static return:

```php
<?php

use MilesChou\Toggle\Feature;
use MilesChou\Toggle\Toggle;

$toggle = new Toggle();
$toggle->addFeature(Feature::create('f1', true));

// Will return true
$toggle->isActive('f1');
```

Use callable to decide the return dynamically:

```php
<?php

use MilesChou\Toggle\Toggle;

$toggle = new Toggle();
$toggle->createFeature('f1', function() {
    return true;
});

// Will return true
$toggle->isActive('f1');
```

Use callable with Context:

```php
<?php

use MilesChou\Toggle\Context;
use MilesChou\Toggle\Toggle;

$toggle = new Toggle();
$toggle->createFeature('f1', function(Context $context) {
    return $context->return;
});

// Will return true
$toggle->isActive('f1', Context::create(['return' => true]));
```

### Parameters

`Feature` instance can store some parameter. For example:

```php
<?php

use MilesChou\Toggle\Toggle;

$toggle = new Toggle();

$toggle->createFeature('f1', ['name' => 'Miles']);
$toggle->createFeature('f2', ['name' => 'Chou']);

// Will return 'Chou'
$toggle->feature('f1')->getParam('name');
```

### Serializer

Sometimes, we should store the toggle state in somewhere. Using `export()` to serialize and `import()` to restore.

```php
<?php

// $dataProvider just like DTO.
$dataProvider = $toggle->export();

// $str is JSON, default. 
$str = (new JsonSerializer())->serialize($dataProvider);

// store $str in cache / stroage / etc.
```

### Control Structure

This snippet is like `if` / `switch` structure:

```php
<?php

use MilesChou\Toggle\Toggle;

$toggle = new Toggle();
$toggle->createFeature('f1');
$toggle->createFeature('f2');
$toggle->createFeature('f3');

$toggle
    ->when('f1', function () {
        // Something when f1 is on
    })
    ->when('f2', function () {
        // Something when f2 is on
    })
    ->when('f3', function () {
        // Something when f3 is on
    });
```
