<?php

    $informations = [
        "email" => [
            "value" => $_POST['email']??null,
            "errorMsg" => "Email incorrecte",
            "displayValue" => "none",
        ],
        "password" => [
            "value" => $_POST['password']??null,
            "errorMsg" => "Mot de passe incorrecte",
            "displayValue" => "none",
        ],
    ];

    $pageName = "";
    $displayPasswordLabel = null;
    $buttonName = "";
    $email = $informations["email"]["value"];
    $displaySuccess = false;

    if (isset($_GET["mdpoublié"])) {
        $pageName = "Mot de passe oublié";
        $displayPasswordLabel = false;
        $buttonName = "Envoyer";
    }
    else {
        $pageName = "Connexion";
        $displayPasswordLabel = true;
        $buttonName = "Connexion";
    }

    if (isset($_POST["envoi"])) {

        if ($_POST["envoi"] === "Connexion") {
            if (isset($informations['email']["value"]) && isset($informations['password']["value"])) {
                $email = htmlspecialchars($informations['email']["value"]);
                $password = htmlspecialchars($informations['password']["value"]);

                $user = get_user_with_email($email);

                if (!$user && $email !== "") {
                    $informations["email"]["displayValue"] = "block";
                    $informations["email"]["errorMsg"] = "Cet email n'existe pas.";
                }
                if ($email === "") {
                    $informations["email"]["displayValue"] = "block";
                    $informations["email"]["errorMsg"] = "Veuillez saisir votre email.";
                }
                else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $informations["email"]["displayValue"] = "block";
                    $informations["email"]["errorMsg"] = "Email invalide.";
                }
                if ($password === "") {
                    $informations["password"]["displayValue"] = "block";
                    $informations["password"]["errorMsg"] = "Veuillez saisir votre mot de passe.";
                }

                if (!isset($user["email"]) || $email !== $user["email"]) {
                    $informations["email"]["displayValue"] = "block";
                }
                if (!isset($user["password"]) || !password_verify($password, $user["password"])) {
                    $informations["password"]["displayValue"] = "block";
                }
                else {
                    $_SESSION["user"] = $user;
                    header("Location: index.php");
                }
            }
        }
        else if ($_POST["envoi"] === "Mdp") {
            if (isset($informations['email']["value"])) {
                $email = htmlspecialchars($informations['email']["value"]);

                $user = get_user_with_email($email);

                if (!$user && $email !== "") {
                    $informations["email"]["displayValue"] = "block";
                    $informations["email"]["errorMsg"] = "Cet email n'existe pas.";
                }
                if ($email === "") {
                    $informations["email"]["displayValue"] = "block";
                    $informations["email"]["errorMsg"] = "Veuillez saisir votre email.";
                }
                else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $informations["email"]["displayValue"] = "block";
                    $informations["email"]["errorMsg"] = "Email invalide.";
                }

                if (isset($user["email"]) && $email === $user["email"]) {
                    $informations["email"]["displayValue"] = "none";
                    $informations["email"]["errorMsg"] = "";
                    $informations["password"]["displayValue"] = "none";
                    $informations["password"]["errorMsg"] = "";
                    $displaySuccess = true;

                    // Envoyer le mail
                    // mail(to, subject, msg);
                }
            }

            if (!$displaySuccess) {
                $_GET["email"] = $informations["email"]["value"];
            }
            else {
                $_GET["email"] = "";
            }
        }
    }

    if (isset($_POST["mdpoublié"])) {
        header("Location: index.php?page=Connexion&mdpoublié&email=$email");
    }

    if (isset($_GET["email"])) {
        $email = $_GET["email"];
    }

    else if ($informations["email"]["value"] !== "") {
        $email = $informations["email"]["value"];
    }
?>

<div class="connexion">
    <h1 class="connexion__title"><?= $pageName ?></h1>
    <div class="connexion__content">
        <p class="connexion__description">Cet espace est réservé aux enseignants du lycée Saint-Vincent - Senlis. Si vous n’avez pas encore de compte, veuillez effectuer votre
demande directement en envoyant un email à <a href="mailto:contact@lyceestvincent.net">contact@lyceestvincent.net</a>.
        </p>
        <form action="#" method="post">
            <label for="email" class="connexion__label">Email :</label>
            <input type="text" name="email" id="email" class="connexion__input" placeholder="Saisissez votre adresse email" value="<?= $email ?>">
            <p class="connexion__errormsg" style="display: <?= $informations["email"]["displayValue"] ?>;"><?= $informations["email"]["errorMsg"] ?></p>
            <?php if ($displayPasswordLabel) { ?>
                <label for="password" class="connexion__label">Mot de passe :</label>
                <input type="password" name="password" id="password" class="connexion__input" placeholder="Saisissez votre mot de passe">
                <p class="connexion__errormsg" style="display: <?= $informations["password"]["displayValue"] ?>;"><?= $informations["password"]["errorMsg"] ?></p>
            <?php } ?>
            <div class="connexion__buttons">
                <button type="submit" class="connexion__button-connexion" name="envoi" value="<?= $displayPasswordLabel ? "Connexion" : "Mdp" ?>"><?= $buttonName ?></button>
                <?php if ($displayPasswordLabel) { ?>
                    <button type="submit" class="connexion__button-pwforget" name="mdpoublié" value="true">Mot de passe oublié</button>
                <?php } ?>
            </div>
            <?php if ($displaySuccess) { ?>
                <p class="connexion__password-success">Votre demande a bien été envoyée</p>
            <?php } ?>
        </form>
    </div>
</div>