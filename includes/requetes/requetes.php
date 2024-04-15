<?php

include_once 'includes/db.php';

if (isset($db)) {

    /**
     * Permet d'obtenir les infos d'un utilisateur stocké en DB avec son email
     * @param string $email - L'email de l'utilisateur
     * @return mixed - Retourne un tableau associatif contenant les informations de l'utilisateur ou false si l'utilisateur n'existe pas
     */
    function get_user_with_email(string $email): mixed
    {
        global $db;
        $query = $db->prepare("SELECT * FROM user WHERE email = :email");
        $query->bindParam(':email', $email);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Permet d'obtenir tous les exercices stockés en DB
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

            $keywordsReq = "";

            foreach ($mots_cles as $mot) {
                $keywordsReq .= "keywords LIKE '%$mot%'";
                if ($mot !== end($mots_cles)) {
                    $keywordsReq .= " OR ";
                }
            }

            if ($keywordsReq === "") {
                $query = $db->prepare("SELECT * FROM exercise WHERE classroom_id = :niveau AND thematic_id = :thematique");
            }
            else {
                $query = $db->prepare("SELECT * FROM exercise
            WHERE classroom_id = :niveau
            AND thematic_id = :thematique
            AND $keywordsReq");
            }
            $query->bindParam(':niveau', $niveau);
            $query->bindParam(':thematique', $thematique);
        } else {
            $query = $db->prepare("SELECT * FROM exercise");
        }

        $query->execute();

        $result["exercise"] = $query->fetchAll(PDO::FETCH_ASSOC);
        $result["number"] = $query->rowCount();

        return $result;
    }
}

