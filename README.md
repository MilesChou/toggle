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

### Feature Group

Just like Feature Toggle, but it's difference is `Group` will link to `Featrue`. All features name will be unique and only link one group. 

For example, it's illegal because feature in Group instance and feature manager created is duplicated:

```php
<?php

use MilesChou\Toggle\Group;
use MilesChou\Toggle\Feature;
use MilesChou\Toggle\Toggle;

$manager = new Toggle();
$manager->createFeature('f1', true);

// RuntimeException: Feature 'f1' is exist 
$manager->addGroup(Group::create('g1', [
    Feature::create('f1'),
]));
```

Another example, it's illegal because the intent to link two Groups:

```php
<?php

use MilesChou\Toggle\Toggle;

$manager = new Toggle();
$manager->createFeature('f1');
$manager->createFeature('f2');
$manager->createFeature('f3');

$manager->createGroup('g1', ['f1', 'f2', 'f3'], 'f1');

// RuntimeException: Feature has been set for 'g1'
$manager->createGroup('g2', ['f1', 'f2', 'f3'], 'f1');
```

Following is a example of return static result:

```php
<?php

use MilesChou\Toggle\Toggle;

$manager = new Toggle();
$manager->createFeature('f1');
$manager->createFeature('f2');
$manager->createFeature('f3');
$manager->createGroup('g1', ['f1', 'f2', 'f3'], 'f1');

// Will return 'f1'
$manager->select('g1');

// Will return true
$manager->isActive('f1');

// Will return false
$manager->isActive('f2');
$manager->isActive('f3');
```

It's describe we have three feature: `f1`, `f2`, `f3`, in `g1` group and choice the `f1` feature.

### Parameters

Both `Feature` and `Group` can store some parameter. For example:

```php
<?php

use MilesChou\Toggle\Toggle;

$manager = new Toggle();

$manager->createFeature('f1', ['name' => 'Miles']);
$manager->createFeature('f2', ['name' => 'Chou']);
$manager->createGroup('g1', ['f1', 'f2'], 'f2');

// Will return 'Chou'
$manager->group('g1')->selectFeature()->getParam('name');
```

### Serializer

Sometimes, we should store the toggle state in somewhere. Using `export()` to serialize and `import()` to restore.

```php
// $dataProvider just like DTO.
$dataProvider = $manager->export();

// $str is JSON, default. 
$str = $dataProvider->serialize();

// store $str in cache / stroage / etc.
```

See more [examples](/examples).

### Control Structure

This snippet is like `if` / `switch` structure:

```php
use MilesChou\Toggle\Toggle;

$manager = new Toggle();
$manager->createFeature('f1');
$manager->createFeature('f2');
$manager->createFeature('f3');
$manager->createFeature('g1', ['f1', 'f2', 'f3'], 'f1');

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

### Built-in Processor

#### Timer

Timer can auto toggle when time is up. If you want use `Timer`, you should require `nesbot/carbon`:
                                       
```
composer require nesbot/carbon
```

For example:

```php
<?php

use MilesChou\Toggle\Toggle;

$manager = new Toggle();

$manager->createFeature('old-feature');
$manager->createFeature('new-feature');

$manager->createGroup('auto-toggle', [
    'old-feature',
    'new-feature',
], new \MilesChou\Toggle\Processors\Timer([
    'default' => 'old-feature',
    'timer' => [
        '2018-08-01' => 'new-feature',
    ]
]));

// Default is 'old-feature' before '2018-08-01 00:00:00'
// Change to 'new-feature' after '2018-08-01 00:00:00'
$manager->group('auto-toggle')->select();
```

### Config Factory

Toggle provide instance builder with config file. If you want use config file to build instance, you should require `hassankhan/config`:

```
composer require hassankhan/config
```

For example, we have the config in following:

```yaml
# features.yaml
feature:
  f1:
  f2:
  f3:
group:
  g1:
    list:
    - f1
    - f2
    - f3
    processor:
      class: MilesChou\Toggle\Processors\Timer
      config:
        default: f1
        timer:
          '2018-08-01': f2
```

And example code:

```php
$actual = (new Factory())->createFromFile('/path/to/features.yaml');

// Default is 'f1' before '2018-08-01 00:00:00'
// Change to 'f2' after '2018-08-01 00:00:00'
$actual->select('g1');
```

> It is using `Timer` processor

Config using `hassankhan/config` package, so we can using YAML / XML / php / etc. format to describe config.
