<?php

include_once 'includes/db.php';

if (isset($db)) {
    function get_user_with_email($email) : mixed {
        global $db;
        $query = $db->prepare("SELECT * FROM user WHERE email = :email");
        $query->bindParam(':email', $email);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    function get_exercises() : mixed {
        global $db;
        $query = $db->prepare("SELECT * FROM exercise LIMIT 5");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    function get_exercises_sorted() : mixed {
        global $db;
        $query = $db->prepare("SELECT * FROM exercise ORDER BY date_uploaded ASC LIMIT 3");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    function get_thematic_by_exercises($exercise_thematic) : mixed {
        global $db;
        $query = $db->prepare("SELECT thematic.name from thematic INNER JOIN exercise ON thematic.id = exercise.thematic_id WHERE thematic.id = :exercise;");
        $query->bindParam(':exercise', $exercise_thematic);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    function get_file_by_exercises($exercise_file) : mixed {
        global $db;
        $query = $db->prepare("SELECT file.id, file.name, file.extension, file.size from file INNER JOIN exercise ON file.id = exercise.exercise_file_id WHERE file.id = :exercise;");
        $query->bindParam(':exercise', $exercise_file);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }
}










