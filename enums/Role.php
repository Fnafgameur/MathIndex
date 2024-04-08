<?php

enum Role: string
{
    case ADMIN = "Administrateur";
    case CONTRIBUTOR = "Contributeur";

    public static function isGranted(string $role) : bool {
        return in_array($role, [Role::ADMIN->value, Role::CONTRIBUTOR->value]);
    }
}

