<?php
return [
    'settings' => [
        'determineRouteBeforeAppMiddeware' => false,
        'displayErrorDetails' => true,
        'db' => [
            'driver' => 'mysql',
            'host' => '10.0.4.30',
            'database' => 'gestion_arroba',
            'username' => 'arrobamedellin',
            'password' => 'vex3SP83xLeGQFNZ',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'options' => [\PDO::ATTR_EMULATE_PREPARES => true],
        ],
        'db_pregrado' => [
            'driver' => 'mysql',
            'host' => '10.0.4.30',
            'database' => 'zadmin_mdlpregradoproduccion',
            'username' => 'arrobamedellin',
            'password' => 'vex3SP83xLeGQFNZ',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'options' => [\PDO::ATTR_EMULATE_PREPARES => true],
        ],
        'db_postgrado' => [
            'driver' => 'mysql',
            'host' => '10.0.4.30',
            'database' => 'zadmin_mdlpostgradoproduccion',
            'username' => 'arrobamedellin',
            'password' => 'vex3SP83xLeGQFNZ',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'options' => [\PDO::ATTR_EMULATE_PREPARES => true],
        ],
        'db_itm' => [
            'driver' => 'mysql',
            'host' => '10.0.4.30',
            'database' => 'zadmin_mdlitmproduccion',
            'username' => 'arrobamedellin',
            'password' => 'vex3SP83xLeGQFNZ',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'options' => [\PDO::ATTR_EMULATE_PREPARES => true],
        ],
        'db_colmayor' => [
            'driver' => 'mysql',
            'host' => '10.0.4.30',
            'database' => 'zadmin_mdlcolmayorproduccion',
            'username' => 'arrobamedellin',
            'password' => 'vex3SP83xLeGQFNZ',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'options' => [\PDO::ATTR_EMULATE_PREPARES => true],
        ],
    ]
];