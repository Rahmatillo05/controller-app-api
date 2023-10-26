<?php
/*
 * 1. Add new file as db.php
 * 2. Write this code in new db.php
 * 3. All done! :)
 */
$connection = env('DB_CONNECTION');
$host = env('DB_HOST');
$db = env('DB_DATABASE');
$user = env('DB_USERNAME');
$password = env('DB_PASSWORD');
return [
    'class' => 'yii\db\Connection',
    'dsn' => "{$connection}:host={$host};dbname={$db}",
    'username' => "$user",
    'password' => "$password",
    'charset' => 'utf8',
];