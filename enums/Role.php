<?php

enum Role: string
{
    case ADMIN = "Administrateur";
    case CONTRIBUTOR = "Contributeur";

    /**
     * Permet de vérifier si un rôle est admin ou contributeur
     * @param string $role Le rôle à vérifier
     * @return bool - Retourne true si le rôle est admin ou contributeur, false sinon
     */
    public static function isGranted(string $role) : bool {
        return in_array($role, [Role::ADMIN->value, Role::CONTRIBUTOR->value]);
    }
}

