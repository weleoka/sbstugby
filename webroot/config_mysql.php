<?php

return [
    'dsn'     => "mysql:host=localhost;dbname=mydb;", //blu-ray.student.bth.se;dbname=kawe14;", localhost;dbname=PHPMVC; 
    'username'        => "root",
    'password'        => "enter112", // 4pido7X]
    'driver_options'  => [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"], 
    'table_prefix'    => "",
//    'fetch_mode'      => \PDO::FETCH_OBJ,
//    'session_key'     => 'CDatabase',
   'verbose' => true,
//    'debug_connect' => 'true',
];