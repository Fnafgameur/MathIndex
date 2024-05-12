<?php

/**
 * Permet d'obtenir les informations d'un utilisateur en fonction de son email
 * @param $email L'email de l'utilisateur
 * @return mixed Retourne un tableau associatif contenant les informations de l'utilisateur ou null si l'utilisateur n'existe pas
 */
function get_user_with_email($email): mixed
{
    global $db;
    $query = $db->prepare("SELECT * FROM user WHERE email = :email");
    $query->bindParam(':email', $email);
    $query->execute();
    return $query->fetch(PDO::FETCH_ASSOC);
}