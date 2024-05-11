<?php

$currentAction = "";
$successMessage = "";
$doSendInfos = false;

if (isset($_GET["adding"])) {
    $currentAction = "adding";
}
else if (isset($_GET["updating"])) {
    $currentAction = "updating";
}

$thematics = [];

$informations = [
    "name" => [
        "value" => $_POST["nom"]??"",
        "displayValue" => "none",
        "errorMsg" => ""
    ],
];


$didDelete = false;

if (isset($_SESSION["formValues"])) {
    if (!array_key_exists("search_ex", $_SESSION["formValues"])) {
        $_SESSION["formValues"] = null;
    }
}

$research = $_POST["search"]??$_SESSION["formValues"]["search_ex"]??"";



if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($research)) {
        if (is_null_or_empty($research)["result"]) {
            $thematics = get_thematics($current_page, $per_page);
        }
        else {
            $thematics = get_thematics_by_keywords($current_page, $per_page, $research);
        }
    }

    if (isset($_POST["search"])) {
        $thematics = get_thematics_by_keywords($current_page, $per_page, $_POST["search"]);
    }
    else if (isset($_POST["delete"])) {
        $id_thematic = explode(",", $_POST["delete"])[0];
        $nameDeleted = explode(",", $_POST["delete"])[1];
        $delete = delete_by_id(Type::THEMATIC->value, $id_thematic);
        if ($delete) {
            $didDelete = true;
        }
        else {
            echo "<script>alert('Une erreur est survenue lors de la suppression de la thématique de $nameDeleted.');</script>";
            header('Location: index.php?page='.$_GET["page"].'&pagination='.$current_page.'&onglet=thematiques');
        }
    }
    else if (isset($_GET["updating"])) {
        $idToUpdate = $_GET["updating"];
        $thematic = get_thematics_by_id($idToUpdate);
        $informations["name"]["value"] = $thematic["name"];

        if (isset($_POST["submit"])) {
            $informations["name"]["value"] = $_POST["name"];
            $informations["name"]["displayValue"] = "none";
            $informations["name"]["errorMsg"] = "";
            $doSendInfos = true;
            $update = update_thematic($idToUpdate, $informations["name"]["value"]);
            if (!$update) {
                $doSendInfos = false;
                $informations["name"]["displayValue"] = "block";
                $informations["name"]["errorMsg"] = "Le nom de la thématique est déjà utilisé.";
            }
            else {
                $successMessage = "La thématique a bien été modifiée.";
            }
        }
    }

    $_SESSION["formValues"]["search_ex"] = $research;
}
else {
    if ($research === "") {
        $thematics = get_thematics($current_page, $per_page);
    }
    else {
        $thematics = get_thematics_by_keywords($current_page, $per_page, $research);
    }
}

$number = $thematics["number"] ?? 0;

?>

<div class="contributors">

    <h2><?= $currentAction === "adding" ? "Ajouter une thématique" : (($currentAction === "updating") ? "Modifier une thématique" : "Gestion des thématiques") ?></h2>

    <?php if ($currentAction === "") { ?>

        <p class="contributors__description">Rechercher une thématique par nom :</p>
        <div class="contributors__action-bar">
            <form action="index.php?page=Administration&onglet=thematiques" method="POST" class="contributeurs__form">
                <input type="text" name="search" class="contributeurs__input" placeholder="Rechercher" value="<?= $research ?>">
                <button type="submit">Rechercher</button>
            </form>
            <a href="index.php?page=Administration&adding">
                <button type="submit">Ajouter +</button>
            </a>
        </div>
        <table class="contributors__table">
            <thead class="recherche__table-head">
            <tr>
                <th class="name">Nom</th>
                <th class="difficulty">Matiere </th>
                <th class="difficulty">Nombre d'exercices</th>
                <th class="actions">Action</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($thematics["thematic"] as $thematic) {
                $nb_exercises = get_exercises_count_by_thematic();
                ?>
                <tr>
                    <td><?= $thematic["name"] ?></td>
                    <td>Mathématiques</td>
                    <td><?= $nb_exercises[$thematic["name"]]??0?></td>
                    <td>
                        <form action="index.php?page=Administration&updating=<?= $thematic["id"]?>&onglet=thematiques" method="post">
                            <input type="hidden" name="update" value="<?= $thematic["id"] ?>">
                            <button type="submit" class="contributeurs__button"><img src="assets/icons/edit_file.svg">Modifier</button>
                        </form>
                        <form action="" method="post" class="exercise_delete_form">
                            <input type="hidden" name="delete" value="<?= $thematic["id"] . ',' . $thematic["name"] ?>">
                            <button type="button" class="contributeurs__button modal__trigger" onclick="sendData(this.parentElement)"><img src="assets/icons/delete_file.svg">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>

    <?php } else { ?>

        <form action="index.php?page=Administration&<?= $currentAction ?><?= $currentAction === "updating" ? "=" . $_GET["updating"] : "" ?>&onglet=thematiques" class="contributors__form" method="post">
            <div class="contributors__input">
                <label for="name">Nom :</label>
                <input type="text" name="name" id="name" placeholder="Nom" value="<?= $informations["name"]["value"] ?>">
                <p class="errormsg" style="display: <?= $informations["name"]["displayValue"] ?>;"><?= $informations["name"]["errorMsg"] ?></p>
            </div>
            <div class="contributors__errormsg" style="display: <?= $informations['name']['errorMsg'] === "" ? 'flex' : 'none';?>;">
                <p class="successmsg" style="display: <?= $doSendInfos ? "block" : "none" ?>"><?= $successMessage?></p>
            </div>
            <div class="contributors__submit-button">
                <a href="index.php?page=Administration&onglet=thematiques" class="btn btn--border-radius btn--paddingmodal btn--no-decoration btn--textgrey btn--bglightgrey btn--fontNoto">
                    <svg width="9" height="12" viewBox="0 0 9 12" fill="#757575" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8.16 1.41L3.58 6L8.16 10.59L6.75 12L0.750004 6L6.75 0L8.16 1.41Z" fill=""/>
                    </svg>
                    Retour à la liste
                </a>
                <input type="submit" name="submit" class="btn btn--border-radius btn--paddingmodal btn--textgrey btn--bglightgrey btn--fontNoto" value="Enregistrer">
            </div>
        </form>
    <?php } ?>

    <p class="successmsg" style="margin-top: 1%; display: <?= $didDelete ? "block" : "none" ?>"><?= $nameDeleted ?> supprimé avec succès.</p>

