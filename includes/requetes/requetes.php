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

if (isset($db)) {
    function get_thematic_by_exercices($exercice_thematic) : mixed {
        global $db;
        $query = $db->prepare("SELECT thematic.name from thematic INNER JOIN exercise ON thematic.id = exercise.thematic_id WHERE thematic.id = :exercice;");
        $query->bindParam(':exercice', $exercice_thematic);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }
}

if (isset($db)) {
    function get_file_by_exercices($exercice_file) : mixed {
        global $db;
        $query = $db->prepare("SELECT file.name,extension from file INNER JOIN exercise ON file.id = exercise.exercise_file_id WHERE file.id = :exercice;");
        $query->bindParam(':exercice', $exercice_file);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }
}






