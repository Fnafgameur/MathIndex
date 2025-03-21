<?php

    $type = Type::USER->value;
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
        "profilepic" => [
            "value" => $_FILES["profilepic"]??"",
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
                $contributeurs = get_all($type, $current_page, $per_page);
            }
            else {
                $contributeurs = get_by_keywords($type, $current_page, $per_page, $research);
            }
        }

        if (isset($_POST["delete"])) {
            $idDeleted = explode(",", $_POST["delete"])[0];
            $nameDeleted = explode(",", $_POST["delete"])[1];
            $profilepic = get_by_id($type, $idDeleted)["profilepic_path"];
            delete_by_id($type, $idDeleted);
            if (str_starts_with($profilepic, "./assets/profilepics/") && file_exists($profilepic)) {
                unlink($profilepic);
            }
            $contributeurs = get_all($type, $current_page, $per_page);
            $didDelete = true;
        }
        else if (isset($_GET["updating"])) {

            $idToUpdate = $_GET["updating"];

            if (!isset($_POST["Envoyer"])) {
                $userInfos = get_by_id($type, $idToUpdate);

                $informations["firstname"]["value"] = $userInfos["first_name"];
                $informations["lastname"]["value"] = $userInfos["last_name"];
                $informations["email"]["value"] = $userInfos["email"];
                $informations["role"]["value"] = $userInfos["role"];
                $informations["profilepic"]["value"] = $userInfos["profilepic_path"];
            }
        }
        $informations["email"]["value"] = strtolower($informations["email"]["value"]);
        $informations["email"]["value"] = str_replace(' ', '', $informations["email"]["value"]);

        $firstName = htmlspecialchars($informations["firstname"]["value"]);
        $lastName = htmlspecialchars($informations["lastname"]["value"]);
        $email = htmlspecialchars($informations["email"]["value"]);
        $password = htmlspecialchars($informations["password"]["value"]);
        $role = htmlspecialchars($informations["role"]["value"]);
        $profilepic = $informations["profilepic"]["value"];

        if ((isset($_GET["adding"]) || isset($_GET["updating"])) && !isset($_GET["first"])) {
            $isCorrect = true;

            $targetDir = "./assets/profilepics/";
            $target_file = $targetDir . basename($_FILES["profilepic"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

            foreach ($informations as $infoKey => $infoValue) {

                if ($infoKey === "assigned") {
                    continue;
                }

                if ($infoKey !== "profilepic" && is_null_or_empty($infoValue["value"])["result"]) {
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
                if ($infoKey === "profilepic") {

                    if ($profilepic["name"] === "") {
                        $informations["profilepic"]["displayValue"] = "block";
                        $informations["profilepic"]["errorMsg"] = "Veuillez choisir une image.";
                        $isCorrect = false;
                        continue;
                    }

                    $checkPp = getimagesize($profilepic["tmp_name"]);

                    if (!$checkPp) {
                        $informations["profilepic"]["displayValue"] = "block";
                        $informations["profilepic"]["errorMsg"] = "Veuillez choisir une image valide (PNG/JPEG).";
                        $isCorrect = false;
                    }

                    if ($profilepic["size"] > 2000000) {
                        $informations["profilepic"]["displayValue"] = "block";
                        $informations["profilepic"]["errorMsg"] = "Fichier trop volumineux (2Mo max).";
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

                        $currentLastName = get_by_id($type, $idToUpdate)["last_name"];
                        $profilepic = $targetDir . $currentLastName . "_" . $idToUpdate . "." . $imageFileType;

                        if (file_exists($profilepic)) {
                            unlink($profilepic);
                        }

                        $profilepic = $targetDir . $lastName . "_" . $idToUpdate . "." . $imageFileType;

                        $toUpdate = [
                            "email" => $email,
                            "last_name" => $lastName,
                            "first_name" => $firstName,
                            "password" => $password,
                            "role" => $role,
                            "profilepic_path" => $profilepic,
                        ];

                        $isIdUpdated = update_value_by_id($type, $toUpdate, $idToUpdate);

                        if (!$isIdUpdated) {
                            $doSendInfos = false;
                            $informations["assigned"]["displayValue"] = "block";
                            $informations["assigned"]["errorMsg"] = "Erreur lors de la modification de l'utilisateur : l'utilisateur n'existe pas.";
                        }
                        else {
                            $successMessage = "Contributeur modifié avec succès.";
                            $isImageMoved = move_uploaded_file($_FILES["profilepic"]["tmp_name"], $profilepic);
                        }
                    }
                    else {

                        $whereToAdd = ["email", "last_name", "first_name", "password", "role", "profilepic_path"];
                        $id = get_next_id($type);
                        $profilepic = $targetDir . $lastName . "_" . $id . "." . $imageFileType;

                        $toAdd = [
                            $email,
                            $lastName,
                            $firstName,
                            $password,
                            $role,
                            $profilepic,
                        ];

                        $reqResult = add_to_db($type, $whereToAdd, $toAdd);

                        if (!$reqResult) {
                            $doSendInfos = false;
                            $informations["assigned"]["displayValue"] = "block";
                            $informations["assigned"]["errorMsg"] = "Erreur lors de l'ajout de l'utilisateur.";
                        }
                        else {
                            $successMessage = $informations["role"]["value"] . " ajouté avec succès.";
                            $isImageMoved = move_uploaded_file($_FILES["profilepic"]["tmp_name"], $profilepic);
                        }
                    }
                }
            }
        }

        $_SESSION["formValues"]["search_contrib"] = $research;

    } else {
        if ($research === "") {
            $contributeurs = get_all($type, $current_page, $per_page);
        }
        else {
            $contributeurs = get_by_keywords($type, $current_page, $per_page, $research);
        }
    }

    $number = $contributeurs["number"]??0;
    $contributeurs = $contributeurs["values"];

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
        <a href="index.php?page=Administration&adding&onglet=contributeurs">
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
                            <form action="index.php?page=Administration&updating=<?= $contributeur["id"] ?>&first&onglet=contributeurs" method="post">
                                <input type="hidden" name="update" value="<?= $contributeur["id"] ?>">
                                <button type="submit" class="contributeurs__button"><img src="assets/icons/edit_file.svg">Modifier</button>
                            </form>
                            <form action="#" method="post">
                                <input type="hidden" name="delete" value="<?= $contributeur["id"] . ',' . $contributeur["last_name"] ?>">
                                <button type="button" class="contributeurs__button modal__trigger" onclick="sendData(this.parentElement);"><img src="assets/icons/delete_file.svg">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
        </tbody>
    </table>

    <?php } else { ?>

        <form class="contributor__flex" action="index.php?page=Administration&<?= $currentAction ?><?= $currentAction === "updating" ? "=" . $_GET["updating"] : "" ?>&onglet=contributeurs" method="post" enctype="multipart/form-data">
            <div class="contributor__flex__side">
                <div class="contributor__flex__mini-flex">
                    <label for="nom">Nom :</label><br>
                    <input type="text" name="nom" id="nom" placeholder="Nom" value="<?= $informations["lastname"]["value"] ?>"><br>
                    <p class="errormsg" style="display: <?= $informations["lastname"]["displayValue"] ?>;"><?= $informations["lastname"]["errorMsg"] ?></p>

                    <label for="prenom">Prénom :</label><br>
                    <input type="text" name="prenom" id="prenom" placeholder="Prénom" value="<?= $informations["firstname"]["value"] ?>"><br>
                    <p class="errormsg" style="display: <?= $informations["firstname"]["displayValue"] ?>;"><?= $informations["firstname"]["errorMsg"] ?></p>

                    <label for="email">Email :</label><br>
                    <input type="text" name="email" id="email" placeholder="Email" value="<?= $informations["email"]["value"] ?>"><br>
                    <p class="errormsg" style="display: <?= $informations["email"]["displayValue"] ?>;"><?= $informations["email"]["errorMsg"] ?></p>

                    <label for="password">Mot de passe</label><br>
                    <input type="password" name="password" id="password" placeholder="Mot de passe" value="<?= $informations["password"]["value"] ?>"><br>
                    <p class="errormsg" style="display: <?= $informations["password"]["displayValue"] ?>;"><?= $informations["password"]["errorMsg"] ?></p>
                </div>
                <div class="contributor__flex__mini-flex">
                    <label for="role">Rôle :</label><br>
                    <select name="role" id="role">
                        <option value="<?= Role::CONTRIBUTOR->value ?>" <?= $informations["role"]["value"] === Role::CONTRIBUTOR->value ? "selected" : "" ?>>Contributeur</option>
                        <option value="Eleve" <?= $informations["role"]["value"] === "Eleve" ? "selected" : "" ?>>Elève</option>
                    </select><br>
                    <p class="errormsg" style="display: <?= $informations["role"]["displayValue"] ?>;"><?= $informations["role"]["errorMsg"] ?></p>

                    <label for="profilepic">Photo de profil : (png, jpeg, jpg)*<br>
                        <div>
                            <p id="profilepic_choisit">choisir une photo de profil</p>
                            <img src="./assets/icons/upload_cloud.svg">
                        </div>
                        <input type="file" name="profilepic" id="profilepic" accept="image/png, image/jpeg, image/jpg" hidden/>
                    </label>
                    <p class="errormsg" style="display: <?= $informations["profilepic"]["displayValue"] ?>;"><?= $informations["profilepic"]["errorMsg"] ?></p>
                </div>
            </div>
            <div>
                <p class="errormsg" style="display: <?= $informations["assigned"]["displayValue"] ?>;"><?= $informations["assigned"]["errorMsg"] ?></p>
                <p class="successmsg" style="color: limegreen; display: <?= $doSendInfos ? "block" : "none" ?>"><?= $successMessage ?></p>
            </div>
            <div class="contributor__flex__side--right">
                <input class="button" type="submit" name="Envoyer" value="Envoyer">
                <a href="index.php?page=Administration&onglet=contributeurs"><button type="button" class="button">Retour à la liste</button></a>
            </div>
        </form>
    <?php } ?>

    <p class="successmsg" style="color: limegreen;margin-top: 1%; display: <?= $didDelete ? "block" : "none" ?>"><?= $nameDeleted ?> supprimé avec succès.</p>

    
    <script src="assets/scripts/contributor.js"></script>

