<?php
return [
    /* Database access */
    'database' => [
        'driver'    => 'mysql',
        'host'      => 'localhost',
        'database'  => 'db_marcposasy',
        'username'  => 'root',
        'password'  => '',
        'charset'   => 'utf8',
        'collation' => 'utf8_bin',
        'prefix'    => '',
    ],

    /* Session configuration */
    'session-time' => 2, // hours
    'session-name' => 'application-auth',

    /* Secret key */
    'secret-key' => '@asd9ws.w6*',

    /* Environment */
    'environment' => 'dev', // Options: dev, prod, stop

    /* Timezone */
    'timezone' => 'America/Lima',

    /* Cache */
    'cache' => false
];
// return [
//     /* Database access */
//     'database' => [
//         'driver'    => 'mysql',
//         'host'      => 'localhost',
//         'database'  => 'marcso_marcposasy',
//         'username'  => 'marcso_marcposasy',
//         'password'  => 'sJK(}h?cg8u~',
//         'charset'   => 'utf8',
//         'collation' => 'utf8_bin',
//         'prefix'    => '',
//     ],
//
//     /* Session configuration */
//     'session-time' => 2, // hours
//     'session-name' => 'application-auth',
//
//     /* Secret key */
//     'secret-key' => '@asd9ws.w6*',
//
//     /* Environment */
//     'environment' => 'dev', // Options: dev, prod, stop
//
//     /* Timezone */
//     'timezone' => 'America/Lima',
//
//     /* Cache */
//     'cache' => false
// ];
?>
