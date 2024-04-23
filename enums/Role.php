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
    public static function isEligible(string $role) : bool {
        return in_array($role, [Role::ADMIN->value, Role::CONTRIBUTOR->value]);
    }

    public static function isAdmin(string $role) : bool {
        return $role === Role::ADMIN->value;
    }

    public static function isContributor(string $role) : bool {
        return $role === Role::CONTRIBUTOR->value;
    }
}

