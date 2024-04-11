<?php
    include_once("header.php");

    if (isset($_SESSION["user"])) {
        $prenom = $_SESSION["user"]["last_name"];
        $nom = $_SESSION["user"]["first_name"];
        $role = $_SESSION["user"]["role"];
    }
?>


<div class="main--flex">
    <div class="side-bar">


        <div class="side-bar__flex-logo">
            <img src="./assets/icons/stvincentlogo.png">
            <p>
                <span>
                    Math Index
                </span><br>
                Lycée Saint-Vincent - Senlis
            </p>
        </div>


        <div class="side-bar__menu">
            <div class="side-bar__menu__flex">
                <form action="#" method="GET">
                    <input type="hidden" id="page" name="page" value="<?= Page::ACCUEIL->value ?>">
                    <button type="submit">
                        <img src="./assets/icons/home.svg">
                        <p><?= Page::ACCUEIL->value ?></p>
                    </button>
                </form>


                <form action="#" method="GET">
                    <input type="hidden" id="page" name="page" value="<?= Page::RECHERCHE->value ?>">
                    <button type="submit">
                        <img src="./assets/icons/research.svg">
                        <p><?= Page::RECHERCHE->value ?></p>
                    </button>
                </form>


                <form action="#" method="GET">
                    <input type="hidden" id="page" name="page" value="<?= Page::MATHEMATIQUE->value ?>">
                    <button type="submit">
                        <img src="./assets/icons/maths.svg">
                        <p><?= Page::MATHEMATIQUE->value ?></p>
                    </button>
                </form>


                <?php if(isset($_SESSION["user"])){
                        if(Role::isGranted($role)) { ?>

                        <form action="#" method="GET">
                            <input type="hidden" id="page" name="page" value="<?= Page::MES_EXERCICES->value ?>">
                            <button type="submit">
                                <img src="./assets/icons/menu.svg">
                                <p><?= Page::MES_EXERCICES->value ?></p>
                            </button>
                        </form>

                        <form action="#" method="GET">
                            <input type="hidden" id="page" name="page" value="<?= Page::SOUMETTRE->value ?>">
                            <button type="submit">
                                <img src="./assets/icons/upload_file.svg">
                                <p><?= Page::SOUMETTRE->value ?></p>
                            </button>
                        </form>
                    <?php } ?>
                    <?php } ?>
            </div>


            <?php if(isset($_SESSION["user"])){ ?>
                    <a class="side-bar__menu__logout" href="connexion/deconnexion.php">
                        <button>
                            <img src="./assets/icons/disconnect.svg">
                            <p>Déconnexion</p>
                        </button>
                    </a>
                    <?php } ?>
        </div>
    </div>


    <div class="main-content">
        <header>


            <?php if (isset($_SESSION["user"])) { ?>
                        <div class="header__container">
                            <p>
                                <?= $nom . " " . $prenom ?>
                            </p>
                            <img src="https://f.hellowork.com/blogdumoderateur/2013/02/nyan-cat-gif-1.gif">
                        </div>
                    <?php } else { ?>
                        <form action="#" method="GET">
                            <input type="hidden" id="page" name="page" value="<?= Page::CONNEXION->value ?>">
                            <button type="submit">
                                <img src="./assets/icons/connect.svg">
                                <p><?= Page::CONNEXION->value ?></p>
                            </button>
                        </form>
                    <?php } ?>
        </header>
