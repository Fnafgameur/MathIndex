<?php
    include_once("header.php");

    if (isset($_SESSION["user"])) {
        $prenom = $_SESSION["user"]["last_name"];
        $nom = $_SESSION["user"]["first_name"];
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
                    <input type="hidden" id="page" name="page" value="Accueil">
                    <button type="submit">
                        <img src="./assets/icons/home.svg">
                        <p>Accueil</p>
                    </button>
                </form>


                <form action="#" method="GET">
                    <input type="hidden" id="page" name="page" value="Recherche">
                    <button type="submit">
                        <img src="./assets/icons/research.svg">
                        <p>Recherche</p>
                    </button>
                </form>


                <form action="#" method="GET">
                    <input type="hidden" id="page" name="page" value="Mathématique">
                    <button type="submit">
                        <img src="./assets/icons/maths.svg">
                        <p>Mathématique</p>
                    </button>
                </form>


                <?php if(isset($_SESSION)){ ?>

                        <form action="#" method="GET">
                            <input type="hidden" id="page" name="page" value="Mes exercices">
                            <button type="submit">
                                <img src="./assets/icons/menu.svg">
                                <p>Mes exercices</p>
                            </button>
                        </form>

                        <form action="#" method="GET">
                            <input type="hidden" id="page" name="page" value="Soumettre">
                            <button type="submit">
                                <img src="./assets/icons/upload_file.svg">
                                <p>Soumettre</p>
                            </button>
                        </form>
                    <?php } ?>
            </div>


            <?php if(isset($_SESSION)){ ?>
                    <a class="side-bar__menu__logout">
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
                            <img src="">
                        </div>
                    <?php } else { ?>
                        <a>
                            <button>
                                <img src="./assets/icons/connect.svg">
                                <p>Connexion</p>
                            </button>
                        </a>

                    <?php } else { ?>
                        <form action="#" method="GET">
                            <input type="hidden" id="page" name="page" value="Connexion">
                            <button type="submit">
                                <img src="./assets/icons/connect.svg">
                                <p>Connexion</p>
                            </button>
                        </form>
                    <?php } ?>
        </header>
