<?php

require_once __DIR__.'/../vendor/autoload.php';

$classes = twine()
    ->with('text-green-300')
    ->when(true, function ($twine) {
        $twine->with('bg-red-400');
    })
    ->build();

echo $classes;
