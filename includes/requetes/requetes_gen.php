<?php

/**
 * Permet de supprimer un utilisateur en fonction de son ID
 * @param string $type Le type de l'élément à supprimer
 * @param int $id L'ID de l'utilisateur
 * @return bool Retourne true si la suppression a été effectuée, false sinon
 */
function delete_by_id(string $type, int $id): bool
{
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
 * Permet d'obtenir le nombre de lignes d'une requête
 * @param string $query La requête à exécuter
 * @param string|null $actionToErase L'action à supprimer de la requête (en général le "LIMIT x,y") (non obligatoire)
 * @param array|string ...$params Les paramètres à passer à la requête (du bindParam) (non obligatoire)
 * @return int Le nombre de lignes de la requête
 */
function get_number_of_rows(string $query, string $actionToErase = null, array|string ...$params): int
{
    global $db;

    if ($actionToErase !== null) {
        $query = str_replace($actionToErase, "", $query);
    }

    $query = $db->prepare($query);
    if (count($params) > 0) {
        foreach ($params as $param) {
            if ($param != "") {
                $query->bindParam($param[0], $param[1]);
            }
        }
    }
    $query->execute();

    return $query->rowCount();
}

/**
 * Permet de modifier une difficulté en fonction de son ID
 * @param string $value La table à mettre à jour
 * @param int $idToUpdate L'ID de la thématique à mettre à jour
 * @param string $name Le nom de la thématique à mettre à jour
 * @return bool Retourne true si la mise à jour a été effectuée, false sinon
 */
function update_by_id(string $value, int $idToUpdate, string $name): bool
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
 * Permet de vérifier si la pge existe et sur quel page on se trouve
 * @return int Le numero de la page
 */
function get_current_page(): int
{
    if (isset($_GET['pagination'])) {
        $page = (int)strip_tags($_GET['pagination']);

        // Limite de 1 à 1,000,000 afin d'éviter tout bug concernant la limite d'un entier
        if ($page < 1) {
            return 1;
        } else if ($page > 1000000) {
            return 1000000;
        }
        return (int)strip_tags($_GET['pagination']);
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
function get_all($type, $currentPage, $limit): array
{
    global $db;

    $return = [];

    if ($limit !== null) {
        $first = max(0, ($currentPage - 1) * $limit);
        $limitReq = "LIMIT " . $first . ',' . $limit;
    } else {
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
function get_by_keywords(string $type, int $currentPage, int $limit, string $keywords): array|false
{
    global $db;

    $result = [];

    $first = max(0, ($currentPage - 1) * $limit);
    $limitReq = "LIMIT " . $first . ',' . $limit;
    $query = "";

    switch ($type) {
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
function get_by_id(string $type, int $id)
{
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
function get_with_name(string $type, string $name) : mixed
{
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
function update_value_by_id(string $type, array $updates, int $id): bool
{
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
function add_to_db(string $type, array $whereToInsert, array $values): bool
{
    global $db;

    $columns = implode(", ", $whereToInsert);
    $placeholders = implode(", ", array_fill(0, count($values), '?'));

    $query = $db->prepare("INSERT INTO $type ($columns) VALUES ($placeholders)");

    $success = $query->execute($values);

    return $success && $query->rowCount() > 0;
}

/**
 * Permet d'obtenir la prochaine ID d'une table
 * @param string $type Le type de l'élément
 * @return int L'ID suivant
 */
function get_next_id(string $type): int
{
    global $db;
    $query = $db->prepare("SHOW TABLE STATUS LIKE '$type'");
    $query->execute();

    $id = $query->fetch(PDO::FETCH_ASSOC)["Auto_increment"];
    return $id;
}

/**
 * Permet d'obtenir tous les noms des classes
 * @return array un tableau contennant tout les noms des classes
 */
function get_classrooms_names(): array
{
    global $db;
    $query = $db->prepare("SELECT name FROM classroom");
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

/**
 * Permet d'obtenir tous les noms des thématiques
 * @return array un tableau contennant tout les noms des thématiques
 */
function get_thematics_names(): array
{
    global $db;
    $query = $db->prepare("SELECT name FROM thematic");
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

/**
 * Permet d'obtenir tous les noms des sources
 * @return array un tableau contennant tout les noms des sources
 */
function get_origins_names(): array
{
    global $db;
    $query = $db->prepare("SELECT name FROM origin");
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

/**
 * Permet d'obtenir une id avec un nom et une table en paramèttre
 * @param string $name nom du champ
 * @param string $table nom de la table où se trouve le nom du champ
 * @return string retourne l'id du champ
 */
function get_id_by_name(string $name, string $table): string
{
    global $db;
    $query = $db->prepare("SELECT id FROM " . $table . " WHERE name=:name ;");
    $query->bindParam(':name', $name);
    $query->execute();
    $array = $query->fetchAll(PDO::FETCH_ASSOC);
    $result = $array[0]["id"];
    return $result;
}