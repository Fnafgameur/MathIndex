<?php

session_start();

include_once("includes/requetes/requetes.php");

include_once("includes/header.php");

include_once("includes/sidebar.php");

if (isset($_GET["page"])){
    switch ($_GET["page"]){
        case "Mes exercices":
            include_once("exercices/mes_ex.php");
            break;
        case "Recherche":
            include_once("exercices/recherche_ex.php");
            break;
        case "Mathématique":
            include_once("exercices/mathematiques.php");
            break;
        case "Soumettre":
            include_once("exercices/soumettre_ex.php");
            break;
        case "Connexion":
            include_once("connexion/connexion.php");
            break;
        default:
            include_once("exercices/accueil.php");
            break;
    }
} else {
    include_once("exercices/accueil.php");
}

include_once("includes/footer.php");
