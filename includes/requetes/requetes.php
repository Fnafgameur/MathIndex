<?php

include_once 'includes/db.php';

if (isset($db)) {

    /**
     * Permet de supprimer un utilisateur en fonction de son ID
     * @param string $type Le type de l'élément à supprimer
     * @param int $id L'ID de l'utilisateur
     * @return bool Retourne true si la suppression a été effectuée, false sinon
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

    /**
     * Permet d'obtenir les exercices ayant un nom, une thématique ou un niveau correspondant à la recherche
     * @param string $currentPage La page actuelle
     * @param int $limit Le nombre d'exercices à retourner
     * @param string $keywords Les mots-clés de recherche
     * @return mixed Retourne un tableau associatif contenant les informations de tous les exercices correspondant à la recherche
     */
    function get_exercises_by_keywords(string $currentPage, int $limit, string $keywords) : mixed {
        global $db;

        $result = [];

        $first = max(0, ($currentPage - 1) * $limit);
        $limitReq = "LIMIT " . $first.','. $limit;

        // A FAIRE : recherche par nom pour thematic & difficulty
        $query = $db->prepare("SELECT * FROM exercise WHERE name LIKE '%$keywords%' OR thematic_id LIKE '%$keywords%' OR difficulty LIKE '%$keywords%' $limitReq");
        $query->execute();

        $result["exercise"] = $query->fetchAll(PDO::FETCH_ASSOC);
        $result["number"] = get_number_of_rows($query->queryString, $limitReq);

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

    /**
     * Permet d'obtenir les exercices triés par date d'ajout (limité à 3)
     * @return mixed Retourne un tableau associatif contenant les informations des exercices triés par date d'ajout ou null si aucun exercice n'existe
     */
    function get_exercises_sorted() : mixed {
        global $db;
        $query = $db->prepare("SELECT * FROM exercise ORDER BY date_uploaded ASC LIMIT 3");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Permet d'obtenir une thématique en fonction de l'ID de la thématique de l'exercice
     * @param $exercise_thematic_id L'ID de la thématique de l'exercice
     * @return mixed Retourne un tableau associatif contenant les informations de la thématique ou null si la thématique n'existe pas
     */
    function get_thematic_by_exercises($exercise_thematic_id) : mixed {
        global $db;
        $query = $db->prepare("SELECT thematic.name from thematic INNER JOIN exercise ON thematic.id = exercise.thematic_id WHERE thematic.id = :exercise;");
        $query->bindParam(':exercise', $exercise_thematic_id);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Permet d'obtenir un fichier en fonction de l'ID de l'exercice
     * @param int $exercise_file_id L'ID de l'exercice
     * @return mixed Retourne un tableau associatif contenant les informations du fichier ou null si le fichier n'existe pas
     */
    function get_file_by_exercises(int $exercise_file_id) : mixed {
        global $db;
        $query = $db->prepare("SELECT file.id, file.name, file.extension, file.size from file INNER JOIN exercise ON file.id = exercise.exercise_file_id WHERE file.id = :exercise;");
        $query->bindParam(':exercise', $exercise_file_id);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Permet d'obtenir toutes les thématiques stockées en DB avec une limite si spécifiée
     * @param int|null $currentPage La page actuelle (non obligatoire)
     * @param int|null $limit Le nombre de thématiques à retourner (non obligatoire)
     * @return array Retourne un tableau associatif contenant les informations de toutes les thématiques
     */
    function get_thematics(int $currentPage = null, int $limit = null) : array
    {
        global $db;

        if ($limit !== null) {
            $first = max(0, ($currentPage - 1) * $limit);
            $limitReq = "LIMIT " . $first.','. $limit;
        }
        else {
            $limitReq = "";
        }

        $query = $db->prepare("SELECT * FROM thematic $limitReq");
        $query->execute();

        $result["thematic"] = $query->fetchAll(PDO::FETCH_ASSOC);
        $result["number"] = get_number_of_rows($query->queryString, $limitReq);

        return $result;
    }

    /**
     * Permet d'obtenir une thématique en fonction de son ID
     * @param int $id L'ID de la thématique
     * @return mixed Retourne un tableau associatif contenant les informations de la thématique ou null si la thématique n'existe pas
     */
    function get_thematics_by_id(int $id) : mixed
    {
        global $db;
        $query = $db->prepare("SELECT * FROM thematic WHERE id = :id");
        $query->bindParam(':id', $id);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Permet d'obtenir toutes les thématiques ayant un nom correspondant à la recherche
     * @param int $currentPage La page actuelle
     * @param int $limit Le nombre de thématiques à retourner
     * @param string $keywords Les mots-clés de recherche
     * @return array Retourne un tableau associatif contenant les informations de toutes les thématiques correspondant à la recherche
     */
    function get_thematics_by_keywords(int $currentPage, int $limit, string $keywords) : array
    {
        global $db;

        $result = [];

        $first = max(0, ($currentPage - 1) * $limit);
        $limitReq = "LIMIT " . $first.','. $limit;

        $query = $db->prepare("SELECT * FROM thematic WHERE name LIKE '%$keywords%'$limitReq");
        $query->execute();

        $result["thematic"] = $query->fetchAll(PDO::FETCH_ASSOC);
        $result["number"] = get_number_of_rows($query->queryString, $limitReq);

        return $result;
    }

    /**
     * Permet de compter le nombre d'exercices par thématique
     * @return array Retourne un tableau associatif contenant le nombre d'exercices par thématique
     */
    function get_exercises_count_by_thematic() : array {
        global $db;
        $query = $db->prepare("SELECT thematic.name, COUNT(exercise.id) AS nbExercises FROM exercise INNER JOIN thematic ON exercise.thematic_id = thematic.id GROUP BY thematic.name");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return array_column($result, 'nbExercises', 'name');
    }

    /**
     * Permet de modifier une thématique en fonction de son ID
     * @param int $idToUpdate L'ID de la thématique à modifier
     * @param string $name Le nom de la thématique à mettre à jour
     * @return bool Retourne true si la mise à jour a été effectuée, false sinon
     */
    function update_thematic(int $idToUpdate, string $name) : bool
    {
        $thematic = get_thematics_by_id($idToUpdate);
        if ($thematic["name"] === $name) {
            return false;
        }
        return update_by_id(Type::THEMATIC->value, $idToUpdate, $name);
    }

    /**
     * Permet de modifier une difficulté en fonction de son ID
     * @param string $value La table à mettre à jour
     * @param int $idToUpdate L'ID de la thématique à mettre à jour
     * @param string $name Le nom de la thématique à mettre à jour
     * @return bool Retourne true si la mise à jour a été effectuée, false sinon
     */
    function update_by_id(string $value, int $idToUpdate, string $name) : bool
    {
        global $db;
        $query = $db->prepare("UPDATE $value SET name = :name WHERE id = :id");
        $query->bindParam(':id', $idToUpdate);
        $query->bindParam(':name', $name);
        $query->execute();

        if ($query->rowCount() === 0) {
            return false;
        }
        return true;
    }

    /**
     * Permet de vérifier si une thématique existe déjà
     * @param string $name Le nom de la thématique
     * @return bool Retourne true si la thématique existe déjà, false sinon
     */
    function is_thematic_exists(string $name) : bool
    {
        global $db;
        $query = $db->prepare("SELECT * FROM thematic WHERE name = :name");
        $query->bindParam(':name', $name, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch();
        return $result !== false;
    }

    /**
     * Permet de vérifier si une thématique existe déjà
     * @param string $name Le nom de la thématique
     * @return bool Retourne true si la thématique existe déjà, false sinon
     */
    function add_thematic(string $name) : bool
    {
        global $db;

        if (is_thematic_exists($name)) {
            return false;
        }
        $query = $db->prepare("INSERT INTO thematic (name) VALUES (:name)");
        $query->bindParam(':name', $name, PDO::PARAM_STR);
        $query->execute();
        return $db->lastInsertId();
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

    /**
     * Permet d'obtenir toutes les valeurs d'une table
     * @param string $type Le type de l'élément à obtenir
     * @param int $currentPage La page actuelle
     * @param int $limit Le nombre d'éléments à retourner
     * @return array Retourne un tableau associatif contenant les informations des éléments ou null si aucun élément n'existe
     */
    function get_all($type, $currentPage, $limit): array {
        global $db;

        $return = [];

        if ($limit !== null) {
            $first = max(0, ($currentPage - 1) * $limit);
            $limitReq = "LIMIT " . $first.','. $limit;
        }
        else {
            $limitReq = "";
        }

        $query = $db->prepare("SELECT * FROM $type $limitReq");
        $query->execute();

        $return["values"] = $query->fetchAll(PDO::FETCH_ASSOC);
        $return["number"] = get_number_of_rows($query->queryString, $limitReq);

        return $return;
    }

    /**
     * Permet d'obtenir les valeurs en fonction des mots-clés
     * @param string $type Le type de l'élément à obtenir
     * @param int $currentPage La page actuelle
     * @param int $limit Le nombre d'éléments à retourner
     * @param string $keywords Les mots-clés de recherche
     * @return array|false Retourne un tableau associatif contenant les informations des éléments correspondant à la recherche ou false si aucun élément n'existe
     */
    function get_by_keywords(string $type, int $currentPage, int $limit, string $keywords) : array|false {
        global $db;

        $result = [];

        $first = max(0, ($currentPage - 1) * $limit);
        $limitReq = "LIMIT " . $first.','. $limit;
        $query = "";

        switch ($type ) {
            case Type::USER->value:
                $query = $db->prepare("SELECT * FROM $type WHERE last_name LIKE '%$keywords%' OR first_name LIKE '%$keywords%' OR email LIKE '%$keywords%' $limitReq");
                break;
            case Type::THEMATIC->value:
            case Type::CLASSROOM->value:
            case Type::ORIGIN->value:
                $query = $db->prepare("SELECT * FROM $type WHERE name LIKE '%$keywords%' $limitReq");
                break;
        }

        $query->execute();

        $result["values"] = $query->fetchAll(PDO::FETCH_ASSOC);
        $result["number"] = get_number_of_rows($query->queryString, $limitReq);

        return $result;
    }

    /**
     * Permet d'obtenir une valeur en fonction de son ID
     * @param string $type Le type de l'élément à obtenir
     * @param int $id L'ID de l'élément à obtenir
     * @return mixed Retourne un tableau associatif contenant les informations de l'élément ou null si l'élément n'existe pas
     */
    function get_by_id(string $type, int $id) {
        global $db;
        $query = $db->prepare("SELECT * FROM $type WHERE id = :id");
        $query->bindParam(':id', $id);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Permet d'obtenir une valeur en fonction de son nom
     * @param string $type Le type de l'élément à obtenir
     * @param string $name Le nom de l'élément à obtenir
     * @return mixed Retourne un tableau associatif contenant les informations de l'élément ou null si l'élément n'existe pas
     */
    function get_with_name(string $type, string $name) {
        global $db;
        $query = $db->prepare("SELECT * FROM $type WHERE name = :name");
        $query->bindParam(':name', $name);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Permet de mettre à jour une valeur en fonction de son ID
     * @param string $type Le type de l'élément à mettre à jour
     * @param array $updates Les colonnes à mettre à jour (clé = nom de la colonne, valeur = nouvelle valeur)
     * @param int $id L'ID de l'élément à mettre à jour
     * @return bool Retourne true si la mise à jour a été effectuée, false sinon
     */
    function update_value_by_id(string $type, array $updates, int $id): bool {
        global $db;

        $setClauses = [];
        foreach ($updates as $column => $value) {
            $setClauses[] = "$column = ?";
        }
        $setClauseString = implode(", ", $setClauses);

        $query = $db->prepare("UPDATE $type SET $setClauseString WHERE id = ?");

        $params = array_values($updates);
        $params[] = $id;

        $success = $query->execute($params);

        return $success && $query->rowCount() > 0;
    }

    /**
     * Permet d'ajouter une valeur à la base de données
     * @param string $type Le type de l'élément à ajouter
     * @param array $whereToInsert Les colonnes où insérer les valeurs (à mettre dans le même ordre que les valeurs à insérer)
     * @param array $values Les valeurs à insérer (à mettre dans le même ordre que les colonnes où insérer les valeurs)
     * @return bool Retourne true si l'ajout a été effectué, false sinon
     */
    function add_to_db(string $type, array $whereToInsert, array $values) : bool {
        global $db;

        $columns = implode(", ", $whereToInsert);
        $placeholders = implode(", ", array_fill(0, count($values), '?'));

        $query = $db->prepare("INSERT INTO $type ($columns) VALUES ($placeholders)");

        $success = $query->execute($values);

        return $success && $query->rowCount() > 0;
    }

    function get_next_id($type) : int {
        global $db;
        $query = $db->prepare("SHOW TABLE STATUS LIKE '$type'");
        $query->execute();

        $id = $query->fetch(PDO::FETCH_ASSOC)["Auto_increment"];
        return $id;
    }

}










