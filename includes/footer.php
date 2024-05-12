<?php
    if (isset($_GET["page"])) {
        switch ($_GET['page'])
        {
            case Page::RECHERCHE->value:
            case Page::MATHEMATIQUE->value:
            case Page::MES_EXERCICES->value:
                include_once("includes/pagination.php");
                break;
            case Page::SOUMETTRE->value:
                include_once("exercices/soumettre_ex.php");
                break;
            case Page::ADMINISTRATION->value:
                if (Role::isAdmin($_SESSION["user"]["role"])) {
                    include_once("includes/pagination.php");
                } else {
                    include_once("exercices/accueil.php");
                }
                break;
        }
    }
    ?>

    
    <!--fin de "main-content"-->
    </div>
<!--fin de "main--flex"-->
</div>
<script src="assets/scripts/script.js"></script>
</body>
</html>