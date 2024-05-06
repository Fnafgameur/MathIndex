<?php

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

    $contributeurs = [];

    $informations = [
        "firstname" => [
            "value" => $_POST["prenom"]??"",
            "displayValue" => "none",
            "errorMsg" => "",
        ],
        "lastname" => [
            "value" => $_POST["nom"]??"",
            "displayValue" => "none",
            "errorMsg" => "",
        ],
        "email" => [
            "value" => $_POST["email"]??"",
            "displayValue" => "none",
            "errorMsg" => "",
        ],
        "password" => [
            "value" => $_POST["password"]??"",
            "displayValue" => "none",
            "errorMsg" => "",
        ],
        "role" => [
            "value" => $_POST["role"]??"",
            "displayValue" => "none",
            "errorMsg" => "",
        ],
        "assigned" => [
            "displayValue" => "none",
            "errorMsg" => "",
        ],
    ];

    if (isset($_SESSION["formValues"])) {
        if (!array_key_exists("search_contrib", $_SESSION["formValues"])) {
            $_SESSION["formValues"] = null;
        }
    }

    $research = $_POST["search"]??$_SESSION["formValues"]["search_contrib"]??"";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        if (isset($research)) {
            if (is_null_or_empty($research)["result"]) {
                $contributeurs = get_all_users($current_page, $per_page);
            }
            else {
                $contributeurs = get_user_by_keywords($current_page, $per_page, $research);
            }
        }

        if (isset($_POST["delete"])) {
            $idDeleted = explode(",", $_POST["delete"])[0];
            $nameDeleted = explode(",", $_POST["delete"])[1];
            delete_by_id(Type::USER->value, $idDeleted);
            $contributeurs = get_all_users($current_page, $per_page);
            $didDelete = true;
        }
        else if (isset($_GET["updating"])) {

            $idToUpdate = $_GET["updating"];

            if (!isset($_POST["Envoyer"])) {
                $userInfos = get_user_by_id($idToUpdate);

                $informations["firstname"]["value"] = $userInfos["first_name"];
                $informations["lastname"]["value"] = $userInfos["last_name"];
                $informations["email"]["value"] = $userInfos["email"];
                $informations["role"]["value"] = $userInfos["role"];
            }
        }
        else {
            $informations["email"]["value"] = strtolower($informations["email"]["value"]);

            $firstName = htmlspecialchars($informations["firstname"]["value"]);
            $lastName = htmlspecialchars($informations["lastname"]["value"]);
            $email = htmlspecialchars($informations["email"]["value"]);
            $password = htmlspecialchars($informations["password"]["value"]);
            $role = htmlspecialchars($informations["role"]["value"]);

            if ((isset($_GET["adding"]) || isset($_GET["updating"])) && !isset($_GET["first"])) {
                $isCorrect = true;

                foreach ($informations as $infoKey => $infoValue) {

                    if ($infoKey === "assigned") {
                        continue;
                    }

                    if (is_null_or_empty($infoValue["value"])["result"]) {
                        $informations[$infoKey]["displayValue"] = "block";
                        $informations[$infoKey]["errorMsg"] = "Veuillez remplir ce champ.";
                        $isCorrect = false;
                    }

                    if ($infoKey === "email") {
                        $checkMail = is_mail_correct($email);

                        if (!$checkMail["result"]) {
                            $informations["email"]["displayValue"] = "block";
                            $informations["email"]["errorMsg"] = $checkMail["msg"];
                            $isCorrect = false;
                        }
                    }
                }

                if ($role !== Role::CONTRIBUTOR->value && $role !== "Eleve") {
                    $informations["role"]["displayValue"] = "block";
                    $informations["role"]["errorMsg"] = "Rôle invalide.";
                    $isCorrect = false;
                }

                if ($isCorrect) {
                    $doSendInfos = true;
                    $isAlreadyAssigned = get_user_with_email($email);

                    if ($isAlreadyAssigned !== false && $currentAction === "adding") {
                        $informations["assigned"]["displayValue"] = "block";
                        $informations["assigned"]["errorMsg"] = "Cet email est déjà associé à un compte.";
                        $doSendInfos = false;
                    }

                    if ($doSendInfos) {

                        $password = password_hash($password, PASSWORD_ARGON2ID);

                        if ($currentAction === "updating") {
                            $isIdUpdated = update_user_by_id($idToUpdate, $email, $lastName, $firstName, $password, $role);

                            if (!$isIdUpdated) {
                                $doSendInfos = false;
                                $informations["assigned"]["displayValue"] = "block";
                                $informations["assigned"]["errorMsg"] = "Erreur lors de la modification de l'utilisateur : l'utilisateur n'existe pas.";
                            }
                            else {
                                $successMessage = "Contributeur modifié avec succès.";
                            }
                        }
                        else {
                            $query = $db->prepare("INSERT INTO user (last_name, first_name, email, password, role) VALUES (:last_name, :first_name, :email, :password, :role)");
                            $query->bindParam(':last_name', $lastName);
                            $query->bindParam(':first_name', $firstName);
                            $query->bindParam(':email', $email);
                            $query->bindParam(':password', $password);
                            $query->bindParam(':role', $role);
                            $query->execute();

                            $successMessage = $informations["role"]["value"] . " ajouté avec succès.";
                        }
                    }
                }
            }
        }

        $_SESSION["formValues"]["search_contrib"] = $research;

    } else {
        if ($research === "") {
            $contributeurs = get_all_users($current_page, $per_page);
        }
        else {
            $contributeurs = get_user_by_keywords($current_page, $per_page, $research);
        }
    }

    $number = $contributeurs["number"]??0;
    $contributeurs = $contributeurs["users"];

?>

<div class="contributors">

    <h2><?= $currentAction === "adding" ? "Ajouter un contributeur" : (($currentAction === "updating") ? "Modifier un contributeur" : "Gestion des contributeurs") ?></h2>

    <?php if ($currentAction === "") { ?>

    <p class="contributors__description">Rechercher un contributeur par nom, prénom ou email :</p>
    <div class="contributors__action-bar">
        <form action="index.php?page=Administration&onglet=contributeurs" method="POST" class="contributeurs__form">
            <input type="text" name="search" class="contributeurs__input" placeholder="Rechercher" value="<?= $research??'' ?>">
            <button type="submit">Rechercher</button>
        </form>
        <a href="index.php?page=Administration&adding">
            <button type="submit">Ajouter +</button>
        </a>
    </div>
    <table class="contributors__table">
        <thead class="recherche__table-head">
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Rôle</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <?php foreach ($contributeurs as $contributeur) { ?>
                    <tr>
                        <td><?= $contributeur["last_name"] ?></td>
                        <td><?= $contributeur["first_name"] ?></td>
                        <td><?= $contributeur["role"] ?></td>
                        <td><?= $contributeur["email"] ?></td>
                        <td>
                            <form action="index.php?page=Administration&updating=<?= $contributeur["id"] ?>&first" method="post">
                                <input type="hidden" name="update" value="<?= $contributeur["id"] ?>">
                                <button type="submit" class="contributeurs__button"><img src="assets/icons/edit_file.svg">Modifier</button>
                            </form>
                            <form action="#" method="post">
                                <input type="hidden" name="delete" value="<?= $contributeur["id"] . ',' . $contributeur["first_name"] ?>">
                                <button type="submit" class="contributeurs__button" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');"><img src="assets/icons/delete_file.svg">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
        </tbody>
    </table>

    <?php } else { ?>

        <form action="index.php?page=Administration&<?= $currentAction ?><?= $currentAction === "updating" ? "=" . $_GET["updating"] : "" ?>" method="post">
            <label for="nom">Nom :</label>
            <input type="text" name="nom" id="nom" placeholder="Nom" value="<?= $informations["lastname"]["value"] ?>">
            <p class="errormsg" style="display: <?= $informations["lastname"]["displayValue"] ?>;"><?= $informations["lastname"]["errorMsg"] ?></p>

            <label for="prenom">Prénom :</label>
            <input type="text" name="prenom" id="prenom" placeholder="Prénom" value="<?= $informations["firstname"]["value"] ?>">
            <p class="errormsg" style="display: <?= $informations["firstname"]["displayValue"] ?>;"><?= $informations["firstname"]["errorMsg"] ?></p>

            <label for="email">Email :</label>
            <input type="text" name="email" id="email" placeholder="Email" value="<?= $informations["email"]["value"] ?>">
            <p class="errormsg" style="display: <?= $informations["email"]["displayValue"] ?>;"><?= $informations["email"]["errorMsg"] ?></p>

            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" placeholder="Mot de passe" value="<?= $informations["password"]["value"] ?>">
            <p class="errormsg" style="display: <?= $informations["password"]["displayValue"] ?>;"><?= $informations["password"]["errorMsg"] ?></p>

            <label for="role">Rôle :</label>
            <select name="role" id="role">
                <option value="<?= Role::CONTRIBUTOR->value ?>" <?= $informations["role"]["value"] === Role::CONTRIBUTOR->value ? "selected" : "" ?>>Contributeur</option>
                <option value="Eleve" <?= $informations["role"]["value"] === "Eleve" ? "selected" : "" ?>>Elève</option>
            </select>
            <p class="errormsg" style="display: <?= $informations["role"]["displayValue"] ?>;"><?= $informations["role"]["errorMsg"] ?></p>

            <input type="submit" name="Envoyer" value="Envoyer">
            <a href="index.php?page=Administration" class="contributeurs__button">Retour à la liste</a>
            <p class="errormsg" style="display: <?= $informations["assigned"]["displayValue"] ?>;"><?= $informations["assigned"]["errorMsg"] ?></p>
            <p class="successmsg" style="display: <?= $doSendInfos ? "block" : "none" ?>"><?= $successMessage ?></p>
        </form>
    <?php } ?>

    <p class="successmsg" style="margin-top: 1%; display: <?= $didDelete ? "block" : "none" ?>"><?= $nameDeleted ?> supprimé avec succès.</p>


