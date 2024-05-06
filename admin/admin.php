<?php

    $pageName = $_GET["onglet"]??null;
    $current_page = get_current_page();
    $per_page = 4;
?>

<div class="administration">
    <h1 class="administration__title">Administration</h1>
    <div class="administration__content">
        <div class="administration__head-bar">
            <a href="index.php?page=Administration&onglet=contributeurs" class="administration__buttons-button">Contributeurs</a>
        
            <a href="index.php?page=Administration&onglet=exercices" class="administration__buttons-button">Exercices</a>
        
            <a class="administration__buttons-button">Matières</a>
        
            <a class="administration__buttons-button">Classes</a>
        
            <a class="administration__buttons-button">Thématiques</a>
        
            <a class="administration__buttons-button">Niveaux</a>
        
            <a class="administration__buttons-button">Compétences</a>
        
            <a class="administration__buttons-button">Origines</a>
        </div>
        <div class="administration__gestion">
            <?php
            switch ($pageName) {
                case "contributeurs":
                    include_once("admin/gestion_pages/contributeurs.php");
                    break;
                case "exercices":
                    include_once("admin/gestion_pages/exercices.php");
                    break;
                default:
                    header("Location: index.php?page=Administration&onglet=contributeurs");
            }
            ?>
        </div>

<?php

    $pages = ceil($number / $per_page);

?>