<?php

use MilesChou\Toggle\Manager;

require_once __DIR__ . '/../vendor/autoload.php';

$manager = new Manager();

$manager->createFeature('api-v1', null, [
    'url' => 'https://api.example.com/v1',
]);

$manager->createFeature('api-v2', null, [
    'url' => 'https://api.example.com/v2',
]);

$manager->createGroup('api-version', ['api-v1', 'api-v2'], function () {
    return (bool)mt_rand(0, 1)
        ? 'api-v1'
        : 'api-v2';
});

echo $manager->getGroup('api-version')->selectFeatureInstance()->getParam('url') . PHP_EOL;
