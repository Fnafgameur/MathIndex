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
        $query = $db->prepare("SELECT * FROM users WHERE email = :email");
        $query->bindParam(':email', $email);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Permet d'obtenir tous les exercices stockés en DB
     * @return array|false - Retourne un tableau associatif contenant les informations de tous les exercices ou false si aucun exercice n'existe
     */
    function get_exercices() : array|false
    {
        global $db;
        $query = $db->prepare("SELECT * FROM exercise");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}

