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
    function get_exercises_with_limit() : mixed {
        global $db;
        $query = $db->prepare("SELECT * FROM exercise LIMIT 5");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Permet d'obtenir tous les exercices stockés en DB
     * @param array|null $filtres - Les filtres de recherche (non obligatoire)
     * @return array|false - Retourne un tableau associatif contenant les informations de tous les exercices ou false si aucun exercice n'existe
     */
    function get_exercices(array $filtres = null) : array|false
    {
        global $db;

        if (isset($filtres)) {
            $result = [
                "exercise" => [],
                "number" => 0,
            ];

            $niveau = $filtres["niveau"];
            $thematique = $filtres["thematique"];
            $mots_cles = explode(" ", $filtres["mots-cles"]);
            $mots_cles = str_replace("'", "", $mots_cles);

            $keywordsReq = "";

            foreach ($mots_cles as $mot) {
                $keywordsReq .= "keywords LIKE '%$mot%'";
                if ($mot !== end($mots_cles)) {
                    $keywordsReq .= " OR ";
                }
            }

            if ($keywordsReq === "") {
                if ($thematique === "0") {
                    $query = $db->prepare("SELECT * FROM exercise WHERE classroom_id = :niveau");
                }
                else {
                    $query = $db->prepare("SELECT * FROM exercise WHERE classroom_id = :niveau AND thematic_id = :thematique");
                }
            }
            else {
                if ($thematique === "0") {
                    $query = $db->prepare("SELECT * FROM exercise
            WHERE classroom_id = :niveau
            AND ($keywordsReq)");
                }
                else {
                    $query = $db->prepare("SELECT * FROM exercise
            WHERE classroom_id = :niveau
            AND thematic_id = :thematique
            AND ($keywordsReq)");
                }

            }

            $query->bindParam(':niveau', $niveau);
            if ($thematique !== "0") {
                $query->bindParam(':thematique', $thematique);
            }
        } else {
            $query = $db->prepare("SELECT * FROM exercise");
        }

        $query->execute();

        $result["exercise"] = $query->fetchAll(PDO::FETCH_ASSOC);
        $result["number"] = $query->rowCount();

        return $result;
    }

    function get_exercises_sorted() : mixed {
        global $db;
        $query = $db->prepare("SELECT * FROM exercise ORDER BY date_uploaded ASC LIMIT 3");
        $query->execute();

        $result["exercise"] = $query->fetchAll(PDO::FETCH_ASSOC);
        $result["number"] = $query->rowCount();

        return $result;
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










