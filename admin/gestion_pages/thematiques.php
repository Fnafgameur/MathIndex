<?php

$currentAction = "";

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
    ]
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
        $thematic = get_thematics($idToUpdate);
        $informations["name"]["value"] = $thematic["name"];
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
                        <form action="index.php?page=Administration&updating=<?= $thematic["id"] ?>&first" method="post">
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

        <form action="index.php?page=Administration&<?= $currentAction ?><?= $currentAction === "updating" ? "=" . $_GET["updating"] : "" ?>&onglet=thematiques" method="post">
            <label for="nom">Nom :</label>
            <input type="text" name="nom" id="nom" placeholder="Nom" value="<?= $informations["name"]["value"] ?>">
            <p class="errormsg" style="display: <?= $informations["lastname"]["displayValue"] ?>;"><?= $informations["name"]["errorMsg"] ?></p>
        </form>
    <?php } ?>

    <p class="successmsg" style="margin-top: 1%; display: <?= $didDelete ? "block" : "none" ?>"><?= $nameDeleted ?> supprimé avec succès.</p>

