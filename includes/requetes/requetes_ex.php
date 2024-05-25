<?php

/**
 * Permet de limiter le nombre d'exercices retournés en fonction du nombre d'exercices par page
 * @param int $currentPage La page actuelle
 * @param int $perPage Le nombre d'exercices par page
 * @param int|null $user_id L'id de l'utilisateur
 * @return mixed Retourne un tableau associatif contenant les informations des exercices ou null si aucun exercice n'existe
 */
function get_exercises_with_limit(int $currentPage, int $perPage, int $user_id = null): mixed
{
    global $db;

    $result = [];

    if ($user_id !== null) {
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
function get_exercises(int $currentPage = null, int $limit = null, array $filtres = null): array|false
{
    global $db;
    $thematique = "0";
    $niveau = "";

    if ($limit !== null) {
        $first = max(0, ($currentPage - 1) * $limit);
        $limitReq = "LIMIT " . $first . ',' . $limit;
    } else {
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
                if ($niveau === "0") {
                    $query = $db->prepare("SELECT * FROM exercise $limitReq");
                } else {
                    $query = $db->prepare("SELECT * FROM exercise WHERE difficulty = :niveau $limitReq");
                }
            } else {
                if ($niveau === "0") {
                    $query = $db->prepare("SELECT * FROM exercise WHERE thematic_id = :thematique $limitReq");
                } else {
                    $query = $db->prepare("SELECT * FROM exercise WHERE difficulty = :niveau
                         AND thematic_id = :thematique $limitReq");
                }
            }
        } else {
            if ($thematique === "0") {
                if ($niveau === "0") {
                    $query = $db->prepare("SELECT * FROM exercise WHERE ($keywordsReq) $limitReq");
                } else {
                    $query = $db->prepare("SELECT * FROM exercise WHERE difficulty = :niveau
                         AND ($keywordsReq) $limitReq");
                }
            } else {
                if ($niveau === "0") {
                    $query = $db->prepare("SELECT * FROM exercise WHERE thematic_id = :thematique
                         AND ($keywordsReq) $limitReq");
                } else {
                    $query = $db->prepare("SELECT * FROM exercise WHERE difficulty = :niveau
                         AND thematic_id = :thematique
                         AND ($keywordsReq) $limitReq");
                }
            }
        }

        if ($niveau !== "0") {
            $query->bindParam(':niveau', $niveau);
        }
        if ($thematique !== "0") {
            $query->bindParam(':thematique', $thematique);
        }
    } else {
        $query = $db->prepare("SELECT * FROM exercise $limitReq");
        $niveau = "0";
    }

    $query->execute();

    $result["exercise"] = $query->fetchAll(PDO::FETCH_ASSOC);
    $result["number"] = get_number_of_rows($query->queryString, $limitReq, $niveau === "0" ? "" : [':niveau', $niveau], $thematique === "0" ? "" : [':thematique', $thematique]);

    return $result;
}

/**
 * Permet d'obtenir les exercices ayant un nom, une thématique ou un niveau correspondant à la recherche
 * @param string $currentPage La page actuelle
 * @param int $limit Le nombre d'exercices à retourner
 * @param string $keywords Les mots-clés de recherche
 * @return mixed Retourne un tableau associatif contenant les informations de tous les exercices correspondant à la recherche
 */
function get_exercises_by_keywords(string $currentPage, int $limit, string $keywords): mixed
{
    global $db;

    $result = [];

    $first = max(0, ($currentPage - 1) * $limit);
    $limitReq = "LIMIT " . $first . ',' . $limit;

    // A FAIRE : recherche par nom pour thematic & difficulty
    $query = $db->prepare("SELECT * FROM exercise WHERE name LIKE '%$keywords%' OR thematic_id LIKE '%$keywords%' OR difficulty LIKE '%$keywords%' $limitReq");
    $query->execute();

    $result["exercise"] = $query->fetchAll(PDO::FETCH_ASSOC);
    $result["number"] = get_number_of_rows($query->queryString, $limitReq);

    return $result;
}

/**
 * Permet d'obtenir les exercices triés par date d'ajout (limité à 3)
 * @return array|false Retourne un tableau associatif contenant les informations des exercices triés par date d'ajout ou false si aucun exercice n'existe
 */
function get_exercises_sorted(): array|false
{
    global $db;
    $query = $db->prepare("SELECT * FROM exercise ORDER BY date_uploaded DESC LIMIT 3");
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Permet d'obtenir une thématique en fonction de l'ID de la thématique de l'exercice
 * @param $exercise_thematic_id L'ID de la thématique de l'exercice
 * @return mixed Retourne un tableau associatif contenant les informations de la thématique ou null si la thématique n'existe pas
 */
function get_thematic_by_exercises($exercise_thematic_id): mixed
{
    global $db;
    $query = $db->prepare("SELECT thematic.name from thematic INNER JOIN exercise ON thematic.id = exercise.thematic_id WHERE thematic.id = :exercise;");
    $query->bindParam(':exercise', $exercise_thematic_id);
    $query->execute();
    return $query->fetch(PDO::FETCH_ASSOC);
}

/**
 * Permet d'obtenir un fichier en fonction de l'ID de l'exercice
 * @param int $exercise_file_id L'ID de l'exercice
 * @return array Retourne un tableau associatif contenant les informations du fichier ou null si le fichier n'existe pas
 */
function get_file_by_exercises(int $exercise_file_id): array
{
    global $db;

    $result = [];

    $query = $db->prepare("SELECT file.id, file.name, file.extension, file.size from file INNER JOIN exercise ON file.id = exercise.exercise_file_id WHERE file.id = :exercise;");
    $query->bindParam(':exercise', $exercise_file_id);
    $query->execute();

    $result["exercise"] = $query->fetch(PDO::FETCH_ASSOC);

    $exercise_file_id++;

    $query = $db->prepare("SELECT file.id, file.name, file.extension, file.size from file INNER JOIN exercise ON file.id = exercise.correction_file_id WHERE file.id = :exercise;");
    $query->bindParam(':exercise', $exercise_file_id);
    $query->execute();

    $result["correction"] = $query->fetch(PDO::FETCH_ASSOC);

    return $result;
}

/**
 * Permet d'obtenir une thématique en fonction de son ID
 * @param int $id L'ID de la thématique
 * @return mixed Retourne un tableau associatif contenant les informations de la thématique ou null si la thématique n'existe pas
 */
function get_thematics_by_id(int $id): mixed
{
    global $db;
    $query = $db->prepare("SELECT * FROM thematic WHERE id = :id");
    $query->bindParam(':id', $id);
    $query->execute();
    return $query->fetch(PDO::FETCH_ASSOC);
}

/**
 * Permet de compter le nombre d'exercices par thématique
 * @return array Retourne un tableau associatif contenant le nombre d'exercices par thématique
 */
function get_exercises_count_by_thematic(): array
{
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
function update_thematic(int $idToUpdate, string $name): bool
{
    $thematic = get_thematics_by_id($idToUpdate);
    if ($thematic["name"] === $name) {
        return false;
    }
    return update_by_id(Type::THEMATIC->value, $idToUpdate, $name);
}

/**
 * Permet de vérifier si une thématique existe déjà
 * @param string $name Le nom de la thématique
 * @return bool Retourne true si la thématique existe déjà, false sinon
 */
function is_thematic_exists(string $name): bool
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
function add_thematic(string $name): bool
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
 * Permet d'obtenir le nom original d'un fichier en fonction de son ID
 * @param int $fileId L'ID du fichier
 * @return string Retourne le nom original du fichier
 */
function get_original_name_by_file_id(int $fileId) : string
{
    global $db;
    $query = $db->prepare("SELECT original_name FROM file WHERE id = :file");
    $query->bindParam(':file', $fileId);
    $query->execute();

    return $query->fetch(PDO::FETCH_ASSOC)["original_name"];
}

