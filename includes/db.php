<?php

include_once 'database/config.php';

$db = new PDO("mysql:host=$host;dbname=$dbName;charset=UTF8", $username, $password);