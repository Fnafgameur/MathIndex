<?php

    $isAdding = isset($_GET["adding"]);
    $doSendInfos = false;

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

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (!$isAdding) {
            // RECHERCHE AVEC FILTRES
        }
        else {
            $informations["email"]["value"] = strtolower($informations["email"]["value"]);

            $firstName = htmlspecialchars($informations["firstname"]["value"]);
            $lastName = htmlspecialchars($informations["lastname"]["value"]);
            $email = htmlspecialchars($informations["email"]["value"]);
            $password = htmlspecialchars($informations["password"]["value"]);
            $role = htmlspecialchars($informations["role"]["value"]);

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

                if ($isAlreadyAssigned !== false) {
                    $informations["assigned"]["displayValue"] = "block";
                    $informations["assigned"]["errorMsg"] = "Cet email est déjà associé à un compte.";
                    $doSendInfos = false;
                }

                if ($doSendInfos) {

                    $password = password_hash($password, PASSWORD_ARGON2ID);

                    $query = $db->prepare("INSERT INTO user (last_name, first_name, email, password, role) VALUES (:last_name, :first_name, :email, :password, :role)");
                    $query->bindParam(':last_name', $lastName);
                    $query->bindParam(':first_name', $firstName);
                    $query->bindParam(':email', $email);
                    $query->bindParam(':password', $password);
                    $query->bindParam(':role', $role);
                    $query->execute();
                }
            }
        }
    } else {
        $contributeurs = get_contributors();
    }

?>

<div class="contributors">

    <h2><?= $isAdding ? "Ajouter un contributeur" : "Gestion des contributeurs" ?></h2>

    <?php if (!$isAdding) { ?>

    <p class="contributors__description">Rechercher un contributeur par nom, prénom ou email :</p>
    <div class="contributors__action-bar">
        <form action="#" method="GET" class="contributeurs__form">
            <input type="text" name="search" id="search" class="contributeurs__input" placeholder="Rechercher">
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
                <td>DOE</td>
                <td>John</td>
                <td>Enseignant</td>
                <td>mail@mail.com</td>
                <td>
                    <button type="button" class="contributeurs__button"><img src="assets/icons/edit_file.svg">Modifier</button>
                    <button type="button" class="contributeurs__button"><img src="assets/icons/delete_file.svg">Supprimer</button>
                </td>
                <?php foreach ($contributeurs as $contributeur) { ?>
                    <tr>
                        <td><?= $contributeur["last_name"] ?></td>
                        <td><?= $contributeur["first_name"] ?></td>
                        <td><?= $contributeur["role"] ?></td>
                        <td><?= $contributeur["email"] ?></td>
                        <td>
                            <button type="button" class="contributeurs__button"><img src="assets/icons/edit_file.svg">Modifier</button>
                            <button type="button" class="contributeurs__button"><img src="assets/icons/delete_file.svg">Supprimer</button>
                        </td>
                    </tr>
                <?php } ?>
        </tbody>
    </table>

    <?php } else { ?>

        <form action="#" method="POST">
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

            <button type="submit">Enregistrer</button>
            <a href="index.php?page=Administration" class="contributeurs__button">Retour à la liste</a>
            <p class="errormsg" style="display: <?= $informations["assigned"]["displayValue"] ?>;"><?= $informations["assigned"]["errorMsg"] ?></p>
            <p class="successmsg" style="display: <?= $doSendInfos ? "block" : "none" ?>"><?= $informations["role"]["value"] ?> ajouté avec succès.</p>
        </form>
    <?php } ?>
</div>


