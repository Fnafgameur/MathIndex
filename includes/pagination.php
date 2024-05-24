<?php

    $doDisplayPagination = true;
    $newPage = $_GET["pagination"]??$current_page;
    $onglet = isset($_GET["onglet"]) ? "&onglet=".$_GET["onglet"] : "";

    $formValueKeys = [];
    $formValues = [];
    $isPlaced = false;

    if ($pages != 0) {
        if ($newPage > $pages) {
            $newPage = $pages;
            header("Location: index.php?page=".$_GET["page"]."&pagination=".$newPage.$onglet);
        } else if ($newPage < 1) {
            $newPage = 1;
            header("Location: index.php?page=".$_GET["page"]."&pagination=".$newPage.$onglet);
        }
    }

    if ($_GET["page"] === Page::RECHERCHE->value) {

        $formValueKeys = ["niveau", "thematique", "mots-cles"];

        $formValues = $_SESSION["formValues"] ?? [
            "niveau" => $_POST["niveau"] ?? "1",
            "thematique" => $_POST["thematique"] ?? "0",
            "mots-cles" => $_POST["mots-cles"] ?? "",
        ];
    } else if ($_GET["page"] === Page::ADMINISTRATION->value) {

        if (isset($_GET["adding"]) || isset($_GET["updating"])) {
            $doDisplayPagination = false;
        }

        switch ($_GET["onglet"]) {
            case AdminTab::CONTRIBUTEURS->value:
                $formValueKeys = ["search_contrib"];
                $formValues = $_SESSION["formValues"] ?? [
                    "search" => $_POST["search_contrib"] ?? "",
                ];
                break;
            case AdminTab::EXERCICES->value:
                $formValueKeys = ["search_ex"];
                $formValues = $_SESSION["formValues"] ?? [
                    "search" => $_POST["search_ex"] ?? "",
                ];
                break;
            case AdminTab::ORIGINES->value:
                $formValueKeys = ["search_orig"];
                $formValues = $_SESSION["formValues"] ?? [
                    "search" => $_POST["search_orig"] ?? "",
                ];
                break;
            case AdminTab::CLASSES->value:
                $formValueKeys = ["search_classe"];
                $formValues = $_SESSION["formValues"] ?? [
                    "search" => $_POST["search_classe"] ?? "",
                ];
                break;
            case AdminTab::THEMATIQUES->value:
                $formValueKeys = ["search_them"];
                $formValues = $_SESSION["formValues"] ?? [
                    "search" => $_POST["search_them"] ?? "",
                ];
                break;
            default:
                $formValueKeys = [];
                $formValues = [];
        }
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["newPagination"])) {

        if ($_POST["newPagination"] === "-1") {
            $newPage = ($current_page - 1) < 1 ? 1 : $newPage - 1;
        }
        else if ($_POST["newPagination"] === "+1") {
            $newPage = ($current_page + 1) > $pages ? $pages : $newPage + 1;
        }
        else {
            $newPage = $_POST["newPagination"];
        }

        $_SESSION["formValues"] = $formValues;
        header("Location: index.php?page=".$_GET["page"]."&pagination=".$newPage.$onglet);

    }

    if ($doDisplayPagination) {
?>

        <div class="mathematics__pagination">
            <form method="post" action="index.php?page=<?= $_GET["page"] ?>&pagination=<?= $newPage; echo $onglet; ?>">
                <?php foreach ($formValueKeys as $key) { ?>
                    <input type="hidden" name="<?= $key??'' ?>" value="<?= $formValues[$key]??'' ?>">
                <?php } ?>
                <button type="submit" class="link link__pagination <?= ($current_page) === 1 ? 'link__arrow': null;?>" name="newPagination" value="-1"><img src="./assets/icons/left_arrow.svg" alt="fleche gauche"></button>
                <?php if ($pages > 4) {
                    if ($current_page > $pages-2) { ?>
                        <input type="submit" class="link link__pagination" name="newPagination" value="...">
                        <input type="submit" class="link link__pagination link__number" name="newPagination" value="<?= $current_page ?>" onclick="window.location.href='index.php?page=<?= $_GET['page']?>&pagination=<?= $current_page;?>'">
                        <?php if ($current_page != $pages) { ?>
                            <input type="submit" class="link link__pagination" name="newPagination" value="<?= $pages ?>" onclick="window.location.href='index.php?page=<?= $_GET['page']?>&pagination=<?= $pages;?>'">
                        <?php } ?>
                    <?php } else { ?>
                        <input type="submit" class="link link__pagination link__number" name="newPagination" value="<?= $current_page ?>" onclick="window.location.href='index.php?page=<?= $_GET['page']?>&pagination=<?= $current_page;?>'">
                        <input type="submit" class="link link__pagination" name="newPagination" value="<?= $current_page+1 ?>" onclick="window.location.href='index.php?page=<?= $_GET['page']?>&pagination=<?= $current_page+1;?>'">
                        <input type="submit" class="link link__pagination" name="newPagination" value="...">
                        <input type="submit" class="link link__pagination" name="newPagination" value="<?= $pages-1 ?>" onclick="window.location.href='index.php?page=<?= $_GET['page']?>&pagination=<?= $pages-1;?>'">
                        <input type="submit" class="link link__pagination" name="newPagination" value="<?= $pages ?>" onclick="window.location.href='index.php?page=<?= $_GET['page']?>&pagination=<?= $pages;?>'">
                    <?php } ?>
                    <?php /*for ($i = $current_page; $i <= $pages; $i++) {



                        if ($i > $current_page+1 && $i < $pages-1) {
                            continue;
                        } */?><!--
                        <input type="submit" class="link link__pagination <?php /*= ($current_page) === $i ? 'link__number' : null;*/?>" name="newPagination" value="<?php /*= $i */?>" onclick="window.location.href='index.php?page=<?php /*= $_GET['page']*/?>&pagination=<?php /*= $i;*/?>'">
                    --><?php /*} */?>
                <?php } else { ?>
                    <?php for ($i = 1; $i <= $pages; $i++) { ?>
                        <input type="submit" class="link link__pagination <?= ($current_page) === $i ? 'link__number' : null;?>" name="newPagination" value="<?= $i ?>" onclick="window.location.href='index.php?page=<?= $_GET['page']?>&pagination=<?= $i;?>'">
                    <?php } ?>
                <?php } ?>


                <button type="submit" class="link link__pagination <?= ($current_page) >= $pages ? 'link__arrow': null;?>" name="newPagination" value="+1"><img src="./assets/icons/right_arrow.svg" alt="fleche droite"></button>
            </form>
        </div>
    <?php } ?>
    </div>
</div>

