<?php

include_once 'includes/db.php';

if (isset($db)) {

    function get_contributors() : array|false {
        global $db;
        $query = $db->prepare("SELECT id, email, last_name, first_name, role FROM user WHERE role = 'Contributeur' LIMIT 4");
        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Permet de supprimer un utilisateur en fonction de son ID
     * @param $id - L'ID de l'utilisateur à supprimer
     * @return void
     */
    function delete_user_by_id($id) : void {
        global $db;
        $query = $db->prepare("DELETE FROM user WHERE id = :id");
        $query->bindParam(':id', $id);
        $query->execute();
    }

    /**
     * Permet de modifier les informations d'un utilisateur en fonction de son ID
     * @param $id - L'ID de l'utilisateur
     * @param $email - L'email de l'utilisateur à mettre à jour
     * @param $last_name - Le nom de famille de l'utilisateur à mettre à jour
     * @param $first_name - Le prénom de l'utilisateur à mettre à jour
     * @param $password - Le mot de passe de l'utilisateur à mettre à jour
     * @param $role - Le rôle de l'utilisateur à mettre à jour
     * @return void
     */
    function update_user_by_id($id, $email, $last_name, $first_name, $password, $role) : void {
        global $db;
        $query = $db->prepare("UPDATE user SET email = :email, last_name = :last_name, first_name = :first_name, password = :password, role = :role WHERE id = :id");
        $query->bindParam(':id', $id);
        $query->bindParam(':email', $email);
        $query->bindParam(':last_name', $last_name);
        $query->bindParam(':first_name', $first_name);
        $query->bindParam(':password', $password);
        $query->bindParam(':role', $role);
        $query->execute();
    }

    /**
     * Permet d'obtenir les informations d'un utilisateur en fonction de son ID
     * @param $id - L'ID de l'utilisateur
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
     * @param $email - L'email de l'utilisateur
     * @return mixed - Retourne un tableau associatif contenant les informations de l'utilisateur ou null si l'utilisateur n'existe pas
     */
    function get_user_with_email($email) : mixed {
        global $db;
        $query = $db->prepare("SELECT * FROM user WHERE email = :email");
        $query->bindParam(':email', $email);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    function get_exercises_with_limit($currentPage, $perPage) : mixed {
        global $db;
        $first = max(0, ($currentPage - 1) * $perPage);
        $query = $db->prepare("SELECT * FROM exercise LIMIT :first, :perpage");
        $query->bindParam(':first', $first, PDO::PARAM_INT);
        $query->bindParam(':perpage', $perPage, PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Permet d'obtenir tous les exercices stockés en DB
     * @param int|null $limit - Le nombre d'exercices à retourner (non obligatoire)
     * @param array|null $filtres - Les filtres de recherche (non obligatoire)
     * @return array|false - Retourne un tableau associatif contenant les informations de tous les exercices ou false si aucun exercice n'existe
     */
    function get_exercises(int $limit = null, array $filtres = null) : array|false
    {
        global $db;

        if ($limit !== null) {
            $limitReq = "LIMIT " . $limit;
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
        $result["number"] = $query->rowCount();

        return $result;
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
     * @return int - Le numero de la page
     */
    function get_current_page() : int
    {
        if(isset($_GET['pagination'])){
            return (int) strip_tags($_GET['pagination']);
        }else{
            return 1;
        }
    }


}










