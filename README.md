# Toggle

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
<?php

use MilesChou\Toggle\Manager;

$manager = new Manager();
$manager->createFeature('f1', true);

// Will return true
$manager->isActive('f1');
```

Use the object with static return:

```php
<?php

use MilesChou\Toggle\Feature;
use MilesChou\Toggle\Manager;

$manager = new Manager();
$manager->addFeature(Feature::create('f1', true));

// Will return true
$manager->isActive('f1');
```

Use callable to decide the return dynamically:

```php
<?php

use MilesChou\Toggle\Manager;

$manager = new Manager();
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
use MilesChou\Toggle\Manager;

$manager = new Manager();
$manager->createFeature('f1', function(Context $context) {
    return $context->return;
});

// Will return true
$manager->isActive('f1', Context::create(['return' => true));
```

### Feature Group

Just like Feature Toggle, but it's difference is `Group` will link to `Featrue`. All features name will be unique and only link one group. 

For example, it's illegal because feature in Group instance and feature manager created is duplicated:

```php
<?php

use MilesChou\Toggle\Group;
use MilesChou\Toggle\Feature;
use MilesChou\Toggle\Manager;

$manager = new Manager();
$manager->createFeature('f1', true);

// RuntimeException: Feature 'f1' is exist 
$manager->addGroup(Group::create('g1', [
    Feature::create('f1'),
]));
```

Another example, it's illegal because the intent to link two Groups:

```php
<?php

use MilesChou\Toggle\Manager;

$manager = new Manager();
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

use MilesChou\Toggle\Manager;

$manager = new Manager();
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

use MilesChou\Toggle\Manager;

$manager = new Manager();

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
