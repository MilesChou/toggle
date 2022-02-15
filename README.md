# Toggle

![tests](https://github.com/MilesChou/toggle/workflows/tests/badge.svg)
[![codecov](https://codecov.io/gh/MilesChou/toggle/branch/master/graph/badge.svg)](https://codecov.io/gh/MilesChou/toggle)
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/5689e468a545404bab06316878564e71)](https://www.codacy.com/gh/MilesChou/toggle/dashboard)
[![Latest Stable Version](https://poser.pugx.org/MilesChou/toggle/v/stable)](https://packagist.org/packages/MilesChou/toggle)
[![Total Downloads](https://poser.pugx.org/MilesChou/toggle/d/total.svg)](https://packagist.org/packages/MilesChou/toggle)
[![License](https://poser.pugx.org/MilesChou/toggle/license)](https://packagist.org/packages/MilesChou/toggle)

The feature toggle library for PHP

## Concept

Coming soon...

## Usage

The `Toggle` class is the core class. All feature config will set on this object.

### Feature Toggle

Use the fixed result:

```php
<?php

use MilesChou\Toggle\Toggle;

$toggle = new Toggle();
$toggle->create('f1', true);

// Will return true
$toggle->isActive('f1');
```

Use the object with fixed result:

```php
<?php

use MilesChou\Toggle\Feature;
use MilesChou\Toggle\Toggle;

$toggle = new Toggle();
$toggle->add('f1', Feature::create(true));

// Will return true
$toggle->isActive('f1');
```

Use callable to decide the return dynamically:

```php
<?php

use MilesChou\Toggle\Toggle;

$toggle = new Toggle();
$toggle->create('f1', function() {
    return true;
});

// Will return true
$toggle->isActive('f1');
```

Use callable with Context:

```php
<?php

use MilesChou\Toggle\Toggle;

$toggle = new Toggle();
$toggle->create('f1', function($context) {
    return $context['return'];
});

// Will return true
$toggle->isActive('f1', ['return' => true]);
```

### Parameters

`Feature` instance can store some parameter. For example:

```php
<?php

use MilesChou\Toggle\Toggle;

$toggle = new Toggle();

$toggle->create('f1', true, ['name' => 'Miles']);
$toggle->create('f2', false, ['name' => 'Chou']);

// Will return 'Chou'
$toggle->feature('f1')->params('name');

// Also using in callback
$toggle->create('f3', function($context, array $params) {
    return $params['key'] === $context['key'];
}, ['key' => 'foo']);
```

### Control Structure

This snippet is like `if` / `switch` structure:

```php
<?php

use MilesChou\Toggle\Toggle;

$toggle = new Toggle();
$toggle->create('f1');
$toggle->create('f2');
$toggle->create('f3');

$toggle
    ->when('f1', function ($context, $params) {
        // Something when f1 is on
    })
    ->when('f2', function ($context, $params) {
        // Something when f2 is on
    })
    ->when('f3', function ($context, $params) {
        // Something when f3 is on
    });
```

### Processors

Refer the [Toggle Processor](https://github.com/MilesChou/toggle-processor) Project.
