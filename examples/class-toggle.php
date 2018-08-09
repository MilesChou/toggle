<?php

use MilesChou\Toggle\Manager;

require_once __DIR__ . '/../vendor/autoload.php';

$manager = new Manager();

$manager->createFeature('Examples\\NewClass');
$manager->createFeature('Examples\\OldClass');

$manager->createGroup('class-toggle', [
    'Examples\\NewClass',
    'Examples\\OldClass',
], function () {
    return array_rand([true, false])
        ? 'Examples\\NewClass'
        : 'Examples\\OldClass';
});

echo $manager->group('class-toggle')->select() . PHP_EOL;
