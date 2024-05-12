<?php

    $pageName = $_GET["onglet"]??null;
    $current_page = get_current_page();
    $per_page = 10;
?>

<div class="administration">
    <h1 class="administration__title">Administration</h1>
    <div class="administration__content">
        <div class="administration__head-bar">
            <a href="index.php?page=Administration&onglet=contributeurs" class="administration__buttons-button <?= $pageName === AdminTab::CONTRIBUTEURS->value ? "selected-admin" : "" ?>">Contributeurs</a>
        
            <a href="index.php?page=Administration&onglet=exercices" class="administration__buttons-button <?= $pageName === AdminTab::EXERCICES->value ? "selected-admin" : "" ?>">Exercices</a>
        
            <a class="administration__buttons-button <?= $pageName === AdminTab::CLASSES->value ? "selected-admin" : "" ?>">Classes</a>
        
            <a href="index.php?page=Administration&onglet=thematiques" class="administration__buttons-button <?= $pageName === AdminTab::THEMATIQUES->value ? "selected-admin" : "" ?>">Thématiques</a>
        
            <a href="index.php?page=Administration&onglet=origines" class="administration__buttons-button <?= $pageName === AdminTab::ORIGINES->value ? "selected-admin" : "" ?>">Origines</a>
        </div>
        <div class="administration__gestion">
            <?php
            switch ($pageName) {
                case AdminTab::CONTRIBUTEURS->value:
                    include_once("admin/gestion_pages/contributeurs.php");
                    break;
                case AdminTab::EXERCICES->value:
                    include_once("admin/gestion_pages/exercices.php");
                    break;
                case AdminTab::THEMATIQUES->value:
                    include_once("admin/gestion_pages/thematiques.php");
                    break;
                case AdminTab::ORIGINES->value:
                    include_once("admin/gestion_pages/origines.php");
                    break;
                default:
                    header("Location: index.php?page=Administration&onglet=contributeurs");
            }
            ?>
        </div>

<?php

    $pages = ceil($number / $per_page);

?>