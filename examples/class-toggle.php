<?php

use Examples\OldClass;
use Examples\NewClass;
use MilesChou\Toggle\Toggle;

require_once __DIR__ . '/../vendor/autoload.php';

$manager = new Toggle();

$manager->createFeature(NewClass::class);
$manager->createFeature(OldClass::class);

$manager->createGroup('class-toggle', [
    NewClass::class,
    OldClass::class,
], function () {
    return array_rand([true, false])
        ? NewClass::class
        : OldClass::class;
});

echo $manager->group('class-toggle')->select() . PHP_EOL;
