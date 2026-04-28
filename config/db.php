<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'pgsql:host=127.0.0.1;port=5432;dbname=petshop',
    'username' => 'siemens',
    'password' => '1q2w3e4rt5',
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,php -r '$db=require "config/db.php"; print_r($db);'
    //'schemaCache' => 'cache',
];