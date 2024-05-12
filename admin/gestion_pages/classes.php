<?php

$type = Type::CLASSROOM->value;
$currentAction = "";
$successMessage = "";

if (isset($_GET["adding"])) {
    $currentAction = "adding";
}
else if (isset($_GET["updating"])) {
    $currentAction = "updating";
}

$doSendInfos = false;
$didDelete = false;
$nameDeleted = "";

$classes = [];

$informations = [
    "name" => [
        "value" => $_POST["nom"]??"",
        "displayValue" => "none",
        "errorMsg" => "",
    ],
    "assigned" => [
        "displayValue" => "none",
        "errorMsg" => "",
    ],
];

if (isset($_SESSION["formValues"])) {
    if (!array_key_exists("search_orig", $_SESSION["formValues"])) {
        $_SESSION["formValues"] = null;
    }
}

$research = $_POST["search"]??$_SESSION["formValues"]["search_classe"]??"";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($research)) {
        if (is_null_or_empty($research)["result"]) {
            $classes = get_all($type, $current_page, $per_page);
        }
        else {
            $classes = get_by_keywords($type, $current_page, $per_page, $research);
        }
    }

    if (isset($_POST["delete"])) {
        $idDeleted = explode(",", $_POST["delete"])[0];
        $nameDeleted = explode(",", $_POST["delete"])[1];
        delete_by_id($type, $idDeleted);
        $classes = get_all($type, $current_page, $per_page);
        $didDelete = true;
    }
    else if (isset($_GET["updating"])) {

        $idToUpdate = $_GET["updating"];

        if (!isset($_POST["Envoyer"])) {
            $userInfos = get_by_id($type, $idToUpdate);

            $informations["name"]["value"] = $userInfos["name"];
        }
    }

    $classeName = htmlspecialchars($informations["name"]["value"]);

    if ((isset($_GET["adding"]) || isset($_GET["updating"])) && !isset($_GET["first"])) {
        $isCorrect = true;

        if (is_null_or_empty($classeName)["result"]) {
            $informations["name"]["displayValue"] = "block";
            $informations["name"]["errorMsg"] = "Nom invalide.";
            $isCorrect = false;
        }

        if ($isCorrect) {
            $doSendInfos = true;
            $doAlreadyExist = get_with_name($type, $classeName);

            if ($doAlreadyExist && $currentAction === "adding") {
                $informations["assigned"]["displayValue"] = "block";
                $informations["assigned"]["errorMsg"] = "Cet classe existe déjà.";
                $doSendInfos = false;
            }

            if ($doSendInfos) {

                if ($currentAction === "updating") {
                    $isIdUpdated = update_value_by_id($type, ["name" => $classeName], $idToUpdate);

                    if (!$isIdUpdated) {
                        $doSendInfos = false;
                        $informations["assigned"]["displayValue"] = "block";
                        $informations["assigned"]["errorMsg"] = "Erreur lors de la modification de la classe : la classe n'existe pas.";
                    }
                    else {
                        $successMessage = "Classe modifié avec succès.";
                    }
                }
                else {

                    $isAdded = add_to_db($type, ["name"], [$classeName]);

                    if (!$isAdded) {
                        $doSendInfos = false;
                        $informations["assigned"]["displayValue"] = "block";
                        $informations["assigned"]["errorMsg"] = "Erreur lors de l'ajout de la classe.";
                    }
                    else {
                        $successMessage = $informations["name"]["value"] . " ajouté avec succès.";
                    }
                }
            }
        }
    }

    $_SESSION["formValues"]["search_classe"] = $research;

} else {
    if ($research === "") {
        $classes = get_all($type, $current_page, $per_page);
    }
    else {
        $classes = get_by_keywords($type, $current_page, $per_page, $research);
    }
}

$number = $classes["number"]??0;
$classes = $classes["values"];

?>

<div class="contributors">

    <h2><?= $currentAction === "adding" ? "Ajouter une classe" : (($currentAction === "updating") ? "Modifier une classe" : "Gestion des classes") ?></h2>

    <?php if ($currentAction === "") { ?>

        <p class="contributors__description">Rechercher une classe par nom :</p>
        <div class="contributors__action-bar">
            <form action="index.php?page=Administration&onglet=classes" method="POST" class="contributeurs__form">
                <input type="text" name="search" class="contributeurs__input" placeholder="Rechercher" value="<?= $research??'' ?>">
                <button type="submit">Rechercher</button>
            </form>
            <a href="index.php?page=Administration&adding&onglet=classes">
                <button class="contributors__action-add" type="submit">Ajouter
                    <svg width="15" height="14" viewBox="0 0 15 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6.5 6V0H8.5V6H14.5V8H8.5V14H6.5V8H0.5V6H6.5Z" fill="white"/>
                    </svg>
                </button>
            </a>
        </div>
        <table class="contributors__table">
            <thead class="recherche__table-head">
            <tr>
                <th>Nom</th>
                <th style="width: 1%;">Action</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <?php foreach ($classes as $classe) { ?>
            <tr>
                <td><?= $classe["name"] ?></td>
                <td>
                    <form action="index.php?page=Administration&updating=<?= $classe["id"] ?>&first&onglet=classes" method="post">
                        <input type="hidden" name="update" value="<?= $classe["id"] ?>">
                        <button type="submit" class="contributeurs__button"><img src="assets/icons/edit_file.svg">Modifier</button>
                    </form>
                    <form action="#" method="post">
                        <input type="hidden" name="delete" value="<?= $classe["id"] . ',' . $classe["name"] ?>">
                        <button type="button" class="contributeurs__button modal__trigger" onclick="sendData(this.parentElement);"><img src="assets/icons/delete_file.svg">Supprimer</button>
                    </form>
                </td>
            </tr>
            <?php } ?>
            </tbody>
        </table>

    <?php } else { ?>

        <form action="index.php?page=Administration&<?= $currentAction ?><?= $currentAction === "updating" ? "=" . $_GET["updating"] : "" ?>&onglet=classes" method="post">
            <label for="nom">Nom :</label>
            <input type="text" name="nom" id="nom" placeholder="Nom" value="<?= $informations["name"]["value"] ?>">
            <p class="errormsg" style="display: <?= $informations["name"]["displayValue"] ?>;"><?= $informations["name"]["errorMsg"] ?></p>

            <input type="submit" name="Envoyer" value="Envoyer">
            <a href="index.php?page=Administration&onglet=classes" class="contributeurs__button">Retour à la liste</a>
            <p class="errormsg" style="display: <?= $informations["assigned"]["displayValue"] ?>;"><?= $informations["assigned"]["errorMsg"] ?></p>
            <p class="successmsg" style="display: <?= $doSendInfos ? "block" : "none" ?>"><?= $successMessage ?></p>
        </form>
    <?php } ?>

    <p class="successmsg" style="margin-top: 1%; display: <?= $didDelete ? "block" : "none" ?>"><?= $nameDeleted ?> supprimé avec succès.</p>