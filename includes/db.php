<?php

include_once 'database/config.php';
include_once 'errors/database_errors.php';

try {
    $db = new PDO("mysql:host=$host;dbname=$dbName;charset=UTF8", $username, $password);
} catch (PDOException $e) {
    database_not_found();
}