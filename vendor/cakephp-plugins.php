<?php
$baseDir = dirname(dirname(__FILE__));
return [
    'plugins' => [
        'AkkaCKEditor' => $baseDir . '/vendor/akkaweb/cakephp-ckeditor/',
        'Bake' => $baseDir . '/vendor/cakephp/bake/',
        'DebugKit' => $baseDir . '/vendor/cakephp/debug_kit/',
        'Migrations' => $baseDir . '/vendor/cakephp/migrations/',
        'Search' => $baseDir . '/vendor/friendsofcake/search/'
    ]
];