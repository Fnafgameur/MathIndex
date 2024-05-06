<?php

include_once 'includes/db.php';

if (isset($db)) {

    /**
     * Permet d'obtenir tous les utilisateurs stockés en DB (limité à 4)
     * @return array|false Retourne un tableau associatif contenant les informations de tous les utilisateurs ou false si aucun utilisateur n'existe
     */
    function get_all_users($currentPage, $limit) : array|false {
        global $db;

        $return = [];

        if ($limit !== null) {
            $first = max(0, ($currentPage - 1) * $limit);
            $limitReq = "LIMIT " . $first.','. $limit;
        }
        else {
            $limitReq = "";
        }

        $query = $db->prepare("SELECT id, email, last_name, first_name, role FROM user $limitReq");
        $query->execute();

        $return["users"] = $query->fetchAll(PDO::FETCH_ASSOC);
        $return["number"] = get_number_of_rows($query->queryString, $limitReq);

        return $return;
    }

    /**
     * Permet d'obtenir tous les utilisateurs ayant un nom, un prénom ou un email correspondant à la recherche
     * @param string $filter Le filtre de recherche
     * @return array|false Retourne un tableau associatif contenant les informations de tous les utilisateurs correspondant à la recherche ou false si aucun utilisateur n'existe
     */
    function get_user_by_keywords($current_page, $limit, string $filter) : array|false {
        global $db;

        $result = [];

        $first = max(0, ($current_page - 1) * $limit);
        $limitReq = "LIMIT " . $first.','. $limit;

        $query = $db->prepare("SELECT id, email, last_name, first_name, role FROM user WHERE last_name LIKE '%$filter%' OR first_name LIKE '%$filter%' OR email LIKE '%$filter%' $limitReq");
        $query->execute();

        $result["users"] = $query->fetchAll(PDO::FETCH_ASSOC);
        $result["number"] = get_number_of_rows($query->queryString, $limitReq);

        return $result;
    }

    /**
     * Permet de supprimer un utilisateur en fonction de son ID
     * @param string $type Le type de l'élément à supprimer
     * @param string $id L'ID de l'utilisateur
     * @return void
     */
    function delete_by_id(string $type, int $id) : bool {
        global $db;
        $query = $db->prepare("DELETE FROM $type WHERE id = :id");
        $query->bindParam(':id', $id);
        $query->execute();

        if ($query->rowCount() === 0) {
            return false;
        }
        return true;
    }

    /**
     * Permet de modifier les informations d'un utilisateur en fonction de son ID
     * @param string $id L'ID de l'utilisateur
     * @param string $email L'email de l'utilisateur à mettre à jour
     * @param string $last_name Le nom de famille de l'utilisateur à mettre à jour
     * @param string $first_name Le prénom de l'utilisateur à mettre à jour
     * @param string $password Le mot de passe de l'utilisateur à mettre à jour
     * @param string $role Le rôle de l'utilisateur à mettre à jour
     * @return bool Retourne true si la mise à jour a été effectuée, false sinon
     */
    function update_user_by_id(string $id, string $email, string $last_name, string $first_name, string $password, string $role) : bool {
        global $db;
        $query = $db->prepare("UPDATE user SET email = :email, last_name = :last_name, first_name = :first_name, password = :password, role = :role WHERE id = :id");
        $query->bindParam(':id', $id);
        $query->bindParam(':email', $email);
        $query->bindParam(':last_name', $last_name);
        $query->bindParam(':first_name', $first_name);
        $query->bindParam(':password', $password);
        $query->bindParam(':role', $role);
        $query->execute();

        if ($query->rowCount() === 0) {
            return false;
        }
        return true;
    }

    /**
     * Permet d'obtenir les informations d'un utilisateur en fonction de son ID
     * @param $id L'ID de l'utilisateur
     * @return mixed - Retourne un tableau associatif contenant les informations de l'utilisateur ou null si l'utilisateur n'existe pas
     */
    function get_user_by_id($id) : mixed {
        global $db;
        $query = $db->prepare("SELECT email, last_name, first_name, role FROM user WHERE id = :id");
        $query->bindParam(':id', $id);
        $query->execute();

        return $query->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Permet d'obtenir les informations d'un utilisateur en fonction de son email
     * @param $email L'email de l'utilisateur
     * @return mixed Retourne un tableau associatif contenant les informations de l'utilisateur ou null si l'utilisateur n'existe pas
     */
    function get_user_with_email($email) : mixed {
        global $db;
        $query = $db->prepare("SELECT * FROM user WHERE email = :email");
        $query->bindParam(':email', $email);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Permet de limiter le nombre d'exercices retournés en fonction du nombre d'exercices par page
     * @param int $currentPage La page actuelle
     * @param int $perPage  Le nombre d'exercices par page
     * @param int|null $user_id  L'id de l'utilisateur
     * @return mixed Retourne un tableau associatif contenant les informations des exercices ou null si aucun exercice n'existe
     */
    function get_exercises_with_limit(int $currentPage, int $perPage, int $user_id = null) : mixed {
        global $db;

        $result = [];

        if ($user_id !== null)
        {
            $user_id = "WHERE created_by_id = $user_id";
        }
        $first = max(0, ($currentPage - 1) * $perPage);
        $query = $db->prepare("SELECT * FROM exercise $user_id LIMIT :first, :perpage");
        $query->bindParam(':first', $first, PDO::PARAM_INT);
        $query->bindParam(':perpage', $perPage, PDO::PARAM_INT);
        $query->execute();

        $result["exercises"] = $query->fetchAll(PDO::FETCH_ASSOC);
        $result["number"] = get_number_of_rows($query->queryString, "LIMIT :first, :perpage");

        return $result;
    }


    /**
     * Permet d'obtenir tous les exercices stockés en DB
     * @param int|null $limit Le nombre d'exercices à retourner (non obligatoire)
     * @param array|null $filtres Les filtres de recherche (non obligatoire)
     * @return array|false Retourne un tableau associatif contenant les informations de tous les exercices ou false si aucun exercice n'existe
     */
    function get_exercises(int $currentPage = null, int $limit = null, array $filtres = null) : array|false
    {
        global $db;
        $thematique = "0";
        $niveau = "";

        if ($limit !== null) {
            $first = max(0, ($currentPage - 1) * $limit);
            $limitReq = "LIMIT " . $first.','. $limit;
        }
        else {
            $limitReq = "";
        }

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
                    $query = $db->prepare("SELECT * FROM exercise 
            WHERE classroom_id = :niveau $limitReq");
                }
                else {
                    $query = $db->prepare("SELECT * FROM exercise 
            WHERE classroom_id = :niveau 
            AND thematic_id = :thematique $limitReq");
                }
            }
            else {
                if ($thematique === "0") {
                    $query = $db->prepare("SELECT * FROM exercise
            WHERE classroom_id = :niveau
            AND ($keywordsReq) $limitReq");
                }
                else {
                    $query = $db->prepare("SELECT * FROM exercise
            WHERE classroom_id = :niveau
            AND thematic_id = :thematique
            AND ($keywordsReq) $limitReq");
                }
            }

            $query->bindParam(':niveau', $niveau);
            if ($thematique !== "0") {
                $query->bindParam(':thematique', $thematique);
            }
        } else {
            $query = $db->prepare("SELECT * FROM exercise $limitReq");
        }

        $query->execute();

        $result["exercise"] = $query->fetchAll(PDO::FETCH_ASSOC);
        $result["number"] = get_number_of_rows($query->queryString, $limitReq, $niveau === "" ? "" : [':niveau', $niveau], $thematique === "0" ? "" : [':thematique', $thematique]);

        return $result;
    }

    function get_exercises_by_keywords($currentPage, $limit, $keywords) : mixed {
        global $db;

        $result = [];

        $first = max(0, ($currentPage - 1) * $limit);
        $limitReq = "LIMIT " . $first.','. $limit;

        // A FAIRE : recherche par nom pour thematic & difficulty
        $query = $db->prepare("SELECT * FROM exercise WHERE name LIKE '%$keywords%' OR thematic_id LIKE '%$keywords%' OR difficulty LIKE '%$keywords%' $limitReq");
        $query->execute();

        $result["exercise"] = $query->fetchAll(PDO::FETCH_ASSOC);
        $result["number"] = get_number_of_rows($query->queryString, $limitReq);

        echo $result["number"];
        return $result;
    }

    /**
     * Permet d'obtenir le nombre de lignes d'une requête
     * @param string $query La requête à exécuter
     * @param string|null $actionToErase L'action à supprimer de la requête (en général le "LIMIT x,y") (non obligatoire)
     * @param array|string ...$params Les paramètres à passer à la requête (du bindParam) (non obligatoire)
     * @return int Le nombre de lignes de la requête
     */
    function get_number_of_rows(string $query, string $actionToErase = null, array|string ...$params) : int {
        global $db;

        if ($actionToErase !== null)
        {
            $query = str_replace($actionToErase, "", $query);
        }

        $query = $db->prepare($query);
        if (count($params) > 0)
        {
            foreach ($params as $param)
            {
                if ($param != "")
                {
                    $query->bindParam($param[0], $param[1]);
                }
            }
        }
        $query->execute();

        return $query->rowCount();
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
    function get_exercise_number() : int {
        global $db;
        $query = $db->prepare("SELECT COUNT(*) FROM exercise");
        $query->execute();
        return $query->fetchColumn();
    }

    /**
     * Permet de vérifier si la pge existe et sur quel page on se trouve
     * @return int Le numero de la page
     */
    function get_current_page() : int
    {
        if(isset($_GET['pagination'])) {
            $page = (int) strip_tags($_GET['pagination']);

            // Limite de 1 à 1,000,000 afin d'éviter tout bug concernant la limite d'un entier
            if ($page < 1) {
                return 1;
            } else if ($page > 1000000) {
                return 1000000;
            }
            return (int) strip_tags($_GET['pagination']);
        } else {
            return 1;
        }
    }


}










