<?php

    /*
     * Ne pas oublier de documenter ses fonctions comme fait ci-dessous
     * @param [type] -> permet de décrire le type de la variable attendue
     * @return [type] -> permet de décrire le type de la variable retournée
     *
     */



    /**
     * Permet de checker si un email est vide ou invalide et de retourner un message d'erreur si besoin
     * @param string $email L'email à vérifier
     * @param string|null $errorMsgEmpty Message d'erreur si l'email est vide (non obligatoire)
     * @param string|null $errorMsgNotMail Message d'erreur si l'email n'est pas valide (non obligatoire)
     * @return array - Un tableau avec comme clé "result", un booléen indiquant si l'email est correct ou non, et comme clé "msg", le message d'erreur
     */
    function is_mail_correct(string $email, string $errorMsgEmpty = null, string $errorMsgNotMail = null) : array {

        $result = [
            "result" => true,
            "msg" => "",
        ];

        if ($email === "") {
            $result["result"] = false;
            $result["msg"] = $errorMsgEmpty??"Veuillez saisir votre email.";
            return $result;
        }
        else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $result["result"] = false;
            $result["msg"] = $errorMsgNotMail??"Email invalide.";
            return $result;
        }
        return $result;
    }