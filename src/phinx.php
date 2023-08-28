<?php

return
[
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/database/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/database/seeds'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'development',
        'production' => [
            'adapter' => '',
            'host' => 'mysql',
            'name' => '',
            'user' => '',
            'pass' => '',
            'port' => '3306',
            'charset' => 'utf8',
        ],
        'development' => [
            'adapter' => 'mysql',
            'host' => 'db',
            'name' => 'main',
            'user' => 'root',
            'pass' => 'adergunov',
        ],
        'testing' => [
            'adapter' => 'mysql',
            'host' => '',
            'name' => '',
            'user' => '',
            'pass' => '',
            'port' => '3306',
            'charset' => 'utf8',
        ]
    ],
    'version_order' => 'creation'
];
