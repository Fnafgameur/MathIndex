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
        if (!isset($user["password"]) || $password !== $user["password"]) {
            $informations["password"]["displayValue"] = "block";
        }
        else {
            $_SESSION["user"] = $user;
            header("Location: index.php");
        }
    }

?>

<div class="connexion">
    <h1 class="connexion__title">Connexion</h1>
    <div class="connexion__content">
        <p class="connexion__description">Cet espace est réservé aux enseignants du lycée Saint-Vincent - Senlis. Si vous n’avez pas encore de compte, veuillez effectuer votre
demande directement en envoyant un email à <a href="mailto:contact@lyceestvincent.net">contact@lyceestvincent.net</a>.
        </p>
        <form action="#" method="post" class="connexion__form">
            <label for="email" class="connexion__label">Email :</label>
            <input type="text" name="email" id="email" class="connexion__input" placeholder="Saisissez votre adresse email">
            <p class="connexion__errormsg" style="display: <?= $informations["email"]["displayValue"] ?>;"><?= $informations["email"]["errorMsg"] ?></p>
            <label for="password" class="connexion__label">Mot de passe :</label>
            <input type="password" name="password" id="password" class="connexion__input" placeholder="Saisissez votre mot de passe">
            <p class="connexion__errormsg" style="display: <?= $informations["password"]["displayValue"] ?>;"><?= $informations["password"]["errorMsg"] ?></p>
            <div class="connexion__buttons">
                <button type="submit" class="connexion__button connexion__button-connexion">Connexion</button>
                <button type="button" class="connexion__button connexion__button-pwforget">Mot de passe oublié ?</button>
            </div>
        </form>
    </div>
</div>