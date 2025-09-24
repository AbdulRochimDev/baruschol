<?php
require __DIR__ . '/../vendor/autoload.php';
$classes = [\Laravel\Sanctum\HasApiTokens::class];
foreach ($classes as $c) {
    echo $c . ' => ' . (class_exists($c) ? 'yes' : 'no') . PHP_EOL;
}
