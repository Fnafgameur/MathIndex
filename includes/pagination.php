<?php

    $newPage = $_GET["pagination"]??$current_page;
    $onglet = isset($_GET["onglet"]) ? "&onglet=".$_GET["onglet"] : "";

    $formValueKeys = [];
    $formValues = [];

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

        $formValueKeys = ["search"];

        $formValues = $_SESSION["formValues"] ?? [
            "search" => $_POST["search"] ?? "",
        ];
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
        header("Location: index.php?page=".$_GET["page"]."&pagination=".$newPage);

    }

?>

        <div class="mathematics__pagination">
            <form method="post" action="index.php?page=<?= $_GET["page"] ?>&pagination=<?= $newPage; echo $onglet; ?>">
                <?php foreach ($formValueKeys as $key) { ?>
                    <input type="hidden" name="<?= $key??'' ?>" value="<?= $formValues[$key]??'' ?>">
                <?php } ?>
                <button type="submit" class="link link__pagination <?php echo ($current_page) === 1 ? 'link__arrow': null;?>" name="newPagination" value="-1"><img src="./assets/icons/left_arrow.svg" alt="fleche gauche"></button>
                <?php for ($i = 1; $i <= $pages; $i++) { ?>
                    <input type="submit" class="link link__pagination <?php echo ($current_page) === $i ? 'link__number' : null;?>" name="newPagination" value="<?php echo $i ?>" onclick="window.location.href='index.php?page=<?php echo $_GET['page']?>&pagination=<?php echo $i;?>'">
                <?php } ?>
                <button type="submit" class="link link__pagination <?php echo ($current_page) >= $pages ? 'link__arrow': null;?>" name="newPagination" value="+1"><img src="./assets/icons/right_arrow.svg" alt="fleche droite"></button>
            </form>
        </div>
    </div>
</div>

