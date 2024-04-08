<?php

include_once 'includes/db.php';

if (isset($db)) {
    function get_user_with_email($email) : mixed {
        global $db;
        $query = $db->prepare("SELECT * FROM users WHERE email = :email");
        $query->bindParam(':email', $email);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }
}
if (isset($db)) {
    function get_exercices() : mixed {
        global $db;
        $query = $db->prepare("SELECT * FROM exercise");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}

