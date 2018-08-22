<?php

use MilesChou\Toggle\Manager;

require_once __DIR__ . '/../vendor/autoload.php';

$manager = new Manager();

$manager->createFeature('old-feature');
$manager->createFeature('new-feature');

$manager->createGroup('auto-toggle', [
    'old-feature',
    'new-feature',
], new \MilesChou\Toggle\Processes\Timer([
    'default' => 'old-feature',
    'timer' => [
        '2018-08-01' => 'new-feature',
    ]
]));

// Default is old feature before '2018-08-01 00:00:00'
\Carbon\Carbon::setTestNow(\Carbon\Carbon::parse('2018-07-01'));

// Change to new feature after '2018-08-01 00:00:00'
// \Carbon\Carbon::setTestNow(\Carbon\Carbon::parse('2018-08-01 00:00:00'));
echo $manager->group('auto-toggle')->select() . PHP_EOL;
