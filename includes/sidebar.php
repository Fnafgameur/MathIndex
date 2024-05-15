<?php
    if (isset($_SESSION["user"])) {
        $prenom = $_SESSION["user"]["last_name"];
        $nom = $_SESSION["user"]["first_name"];
        $role = $_SESSION["user"]["role"];
        $profilepic_path = $_SESSION["user"]["profilepic_path"];
    }
?>


<div class="main--flex">
    <div class="side-bar" id="side-bar">


        <div class="side-bar__flex-logo" onclick="window.location.href='index.php?page=Accueil'">
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
                    <button type="submit" class="<?= @$_GET["page"] === Page::ACCUEIL->value ? "selected" : "" ?>">
                        <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g id="Frame">
                                <path class="<?= @$_GET["page"] === Page::ACCUEIL->value ? "selected-svg" : "" ?>" id="Vector" d="M3.58948 9.49683L12.5895 2.49683L21.5895 9.49683V20.4968C21.5895 21.0273 21.3788 21.536 21.0037 21.911C20.6286 22.2861 20.1199 22.4968 19.5895 22.4968H5.58948C5.05904 22.4968 4.55034 22.2861 4.17526 21.911C3.80019 21.536 3.58948 21.0273 3.58948 20.4968V9.49683Z" stroke="#757575" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path class="<?= @$_GET["page"] === Page::ACCUEIL->value ? "selected-svg" : "" ?>" id="Vector_2" d="M9.58948 22.4968V12.4968H15.5895V22.4968" stroke="#757575" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </g>
                        </svg>
                        <p><?= Page::ACCUEIL->value ?></p>
                    </button>
                </form>


                <form action="#" method="GET">
                    <input type="hidden" id="page" name="page" value="<?= Page::RECHERCHE->value ?>">
                    <button type="submit" class="<?= @$_GET["page"] === Page::RECHERCHE->value ? "selected" : "" ?>">
                        <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g id="Frame">
                                <path class="<?= @$_GET["page"] === Page::RECHERCHE->value ? "selected-svg" : "" ?>" id="Vector" d="M11.5894 19.7683C16.0077 19.7683 19.5894 16.1866 19.5894 11.7683C19.5894 7.35003 16.0077 3.76831 11.5894 3.76831C7.17114 3.76831 3.58942 7.35003 3.58942 11.7683C3.58942 16.1866 7.17114 19.7683 11.5894 19.7683Z" stroke="#757575" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path class="<?= @$_GET["page"] === Page::RECHERCHE->value ? "selected-svg" : "" ?>" id="Vector_2" d="M21.5894 21.7683L17.2894 17.4683" stroke="#757575" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </g>
                        </svg>
                        <p><?= Page::RECHERCHE->value ?></p>
                    </button>
                </form>


                <form action="#" method="GET">
                    <input type="hidden" id="page" name="page" value="<?= Page::MATHEMATIQUE->value ?>">
                    <button type="submit" class="<?= @$_GET["page"] === Page::MATHEMATIQUE->value ? "selected" : "" ?>">
                        <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g id="Frame">
                                <path class="<?= @$_GET["page"] === Page::MATHEMATIQUE->value ? "selected-svg" : "" ?>" id="Vector" d="M19.5894 3.04004H5.58942C4.48485 3.04004 3.58942 3.93547 3.58942 5.04004V19.04C3.58942 20.1446 4.48485 21.04 5.58942 21.04H19.5894C20.694 21.04 21.5894 20.1446 21.5894 19.04V5.04004C21.5894 3.93547 20.694 3.04004 19.5894 3.04004Z" stroke="#757575" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path class="<?= @$_GET["page"] === Page::MATHEMATIQUE->value ? "selected-svg" : "" ?>" id="Vector_2" d="M9.58942 17.04C11.5894 17.04 12.3894 16.04 12.3894 14.24V10.04C12.3894 8.04003 13.3894 6.74003 15.5894 7.04003" stroke="#757575" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path class="<?= @$_GET["page"] === Page::MATHEMATIQUE->value ? "selected-svg" : "" ?>" id="Vector_3" d="M9.58942 11.24H15.2894" stroke="#757575" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </g>
                        </svg>
                        <p><?= Page::MATHEMATIQUE->value ?></p>
                    </button>
                </form>


                <?php if(isset($_SESSION["user"])){
                    if(Role::isEligible($role)) { ?>

                        <form action="#" method="GET">
                            <input type="hidden" id="page" name="page" value="<?= Page::MES_EXERCICES->value ?>">
                            <button type="submit" class="<?= @$_GET["page"] === Page::MES_EXERCICES->value ? "selected" : "" ?>">
                                <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="Group">
                                        <path class="<?= @$_GET["page"] === Page::MES_EXERCICES->value ? "selected-svg-fill" : "" ?> my_exs" id="Vector" d="M8.58942 4.31165H21.5894V6.31165H8.58942V4.31165ZM3.58942 3.81165H6.58942V6.81165H3.58942V3.81165ZM3.58942 10.8116H6.58942V13.8116H3.58942V10.8116ZM3.58942 17.8116H6.58942V20.8116H3.58942V17.8116ZM8.58942 11.3116H21.5894V13.3116H8.58942V11.3116ZM8.58942 18.3116H21.5894V20.3116H8.58942V18.3116Z" fill="#757575"/>
                                    </g>
                                </svg>
                                <p><?= Page::MES_EXERCICES->value ?></p>
                            </button>
                        </form>

                        <form action="#" method="GET">
                            <input type="hidden" id="page" name="page" value="<?= Page::SOUMETTRE->value ?>">
                            <button type="submit" class="<?= @$_GET["page"] === Page::SOUMETTRE->value ? "selected" : "" ?>">
                                <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="Frame">
                                        <path class="<?= @$_GET["page"] === Page::SOUMETTRE->value ? "selected-svg" : "" ?>" id="Vector" d="M22.5895 2.58325L15.5895 22.5833L11.5895 13.5833L2.58948 9.58325L22.5895 2.58325Z" stroke="#757575" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path class="<?= @$_GET["page"] === Page::SOUMETTRE->value ? "selected-svg" : "" ?>" id="Vector_2" d="M22.5895 2.58325L11.5895 13.5833" stroke="#757575" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </g>
                                </svg>
                                <p><?= Page::SOUMETTRE->value ?></p>
                            </button>
                        </form>
                    <?php } if (Role::isAdmin($role)) { ?>
                        <form action="#" method="GET">
                            <input type="hidden" id="page" name="page" value="<?= Page::ADMINISTRATION->value ?>">
                            <button type="submit" class="<?= @$_GET["page"] === Page::ADMINISTRATION->value ? "selected" : "" ?>">
                                <svg width="25" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg" stroke-width="3" stroke="#000000" fill="none">
                                    <g>
                                        <circle cx="32" cy="18.14" r="11.14"/>
                                        <path class="<?= @$_GET["page"] === Page::ADMINISTRATION->value ? "selected-svg" : "" ?>" id="Vector" d="M54.55,56.85A22.55,22.55,0,0,0,32,34.3h0A22.55,22.55,0,0,0,9.45,56.85Z"/>
                                    </g>
                                </svg>

                                <p><?= Page::ADMINISTRATION->value ?></p>
                            </button>
                        </form>
                    <?php } ?>
                <?php } ?>
            </div>


            <?php if(isset($_SESSION["user"])){ ?>
                    <a class="side-bar__menu__logout" href="connexion/deconnexion.php">
                        <button>
                            <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g id="Frame">
                                    <path id="Vector" d="M9.58942 21.8538H5.58942C5.05898 21.8538 4.55028 21.6431 4.1752 21.268C3.80013 20.8929 3.58942 20.3842 3.58942 19.8538V5.85379C3.58942 5.32336 3.80013 4.81465 4.1752 4.43958C4.55028 4.0645 5.05898 3.85379 5.58942 3.85379H9.58942" stroke="#757575" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path id="Vector_2" d="M16.5894 17.8538L21.5894 12.8538L16.5894 7.85379" stroke="#757575" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path id="Vector_3" d="M21.5894 12.8538H9.58942" stroke="#757575" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </g>
                            </svg>
                            <p>Déconnexion</p>
                        </button>
                    </a>
            <?php } ?>
                    <p class="footer__text">Mentions légales • Contact • Lycée Saint-Vincent</p>
        </div>
    </div>


    <div class="main-content">
        <header>
            
            <button  type="button" id="move_side-bar" class="only_smart">
            <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 24 24" fill="none">
                <path d="M4 18L20 18" stroke="#000000" stroke-width="2" stroke-linecap="round"/>
                <path d="M4 12L20 12" stroke="#000000" stroke-width="2" stroke-linecap="round"/>
                <path d="M4 6L20 6" stroke="#000000" stroke-width="2" stroke-linecap="round"/>
            </svg>
            </button>
            <script src="assets/scripts/side_bar.js"></script>
            <?php if (isset($_SESSION["user"])) { ?>
                        <div class="header__container">
                            <p>
                                <?= $nom . " " . $prenom ?>
                            </p>
                            <img src="<?= $profilepic_path; ?>">
                        </div>
                    <?php } else { ?>
                        <form action="#" method="GET">
                            <input type="hidden" id="page" name="page" value="<?= Page::CONNEXION->value ?>">
                            <button type="submit"> 
                                <svg width="21" viewBox="0 0 21 20"  xmlns="http://www.w3.org/2000/svg">
                                    <path   id="Icon login" d="M9 9V6L14 10L9 14V11H0V9H9ZM1.458 13H3.582C4.28005 14.7191 5.55371 16.1422 7.18512 17.0259C8.81652 17.9097 10.7043 18.1991 12.5255 17.8447C14.3468 17.4904 15.9883 16.5142 17.1693 15.0832C18.3503 13.6523 18.9975 11.8554 19 10C19.001 8.14266 18.3558 6.34283 17.1749 4.90922C15.994 3.47561 14.3511 2.49756 12.528 2.14281C10.7048 1.78807 8.81505 2.07874 7.18278 2.96498C5.55051 3.85121 4.27747 5.27778 3.582 7H1.458C2.732 2.943 6.522 0 11 0C16.523 0 21 4.477 21 10C21 15.523 16.523 20 11 20C6.522 20 2.732 17.057 1.458 13Z" fill="#03053D"/>
                                </svg>
                                <p><?= Page::CONNEXION->value ?></p>
                            </button>
                        </form>
            <?php } ?>
        </header>
