<?php

ob_start();
session_start();

if (!isset($_GET["page"])) {
    header("Location: index.php?page=Accueil");
}

include_once("enums/Role.php");
include_once("enums/Page.php");
include_once("enums/Type.php");

include_once("includes/requetes/requetes.php");
include_once("includes/fonctions.php");

include_once("includes/header.php");
include_once ("includes/modal.php");
include_once("includes/sidebar.php");


if (isset($_GET["page"])) {
    if (isset($_SESSION["user"]) && Role::isEligible($_SESSION["user"]["role"])) {
        switch ($_GET["page"]) {
            case Page::RECHERCHE->value:
                include_once("exercices/recherche_ex.php");
                break;
            case Page::MATHEMATIQUE->value:
                include_once("exercices/mathematiques.php");
                break;
            case Page::MES_EXERCICES->value:
                include_once("exercices/mes_ex.php");
                break;
            case Page::SOUMETTRE->value:
                include_once("exercices/soumettre_ex.php");
                break;
            case Page::ADMINISTRATION->value:
                if (Role::isAdmin($_SESSION["user"]["role"])) {
                    include_once("admin/admin.php");
                } else {
                    include_once("exercices/accueil.php");
                }
                break;
            default:
                include_once("exercices/accueil.php");
            }
    }
    else {
        switch ($_GET["page"]) {
            case Page::RECHERCHE->value:
                include_once("exercices/recherche_ex.php");
                break;
            case Page::MATHEMATIQUE->value:
                include_once("exercices/mathematiques.php");
                break;
            case  Page::CONNEXION->value :
                include_once("connexion/connexion.php");
                break;
            default:
                include_once("exercices/accueil.php");
        }
    }
} else {
    include_once("exercices/accueil.php");
}

include_once("includes/footer.php");