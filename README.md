# Toggle

[![Build Status](https://travis-ci.com/MilesChou/toggle.svg?branch=master)](https://travis-ci.com/MilesChou/toggle)
[![codecov](https://codecov.io/gh/MilesChou/toggle/branch/master/graph/badge.svg)](https://codecov.io/gh/MilesChou/toggle)

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

$manager = new Toggle();
$manager->createFeature('f1', true);

// Will return true
$manager->isActive('f1');
```

Use the object with static return:

```php
<?php

use MilesChou\Toggle\Feature;
use MilesChou\Toggle\Toggle;

$manager = new Toggle();
$manager->addFeature(Feature::create('f1', true));

// Will return true
$manager->isActive('f1');
```

Use callable to decide the return dynamically:

```php
<?php

use MilesChou\Toggle\Toggle;

$manager = new Toggle();
$manager->createFeature('f1', function() {
    return true;
});

// Will return true
$manager->isActive('f1');
```

Use callable with Context:

```php
<?php

use MilesChou\Toggle\Context;
use MilesChou\Toggle\Toggle;

$manager = new Toggle();
$manager->createFeature('f1', function(Context $context) {
    return $context->return;
});

// Will return true
$manager->isActive('f1', Context::create(['return' => true]));
```

### Parameters

Both `Feature` and `Group` can store some parameter. For example:

```php
<?php

use MilesChou\Toggle\Toggle;

$manager = new Toggle();

$manager->createFeature('f1', ['name' => 'Miles']);
$manager->createFeature('f2', ['name' => 'Chou']);

// Will return 'Chou'
$manager->feature('f1')->getParam('name');
```

### Serializer

Sometimes, we should store the toggle state in somewhere. Using `export()` to serialize and `import()` to restore.

```php
<?php

// $dataProvider just like DTO.
$dataProvider = $manager->export();

// $str is JSON, default. 
$str = (new JsonSerializer())->serialize($dataProvider);

// store $str in cache / stroage / etc.
```

### Control Structure

This snippet is like `if` / `switch` structure:

```php
<?php

use MilesChou\Toggle\Toggle;

$manager = new Toggle();
$manager->createFeature('f1');
$manager->createFeature('f2');
$manager->createFeature('f3');

$manager
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
