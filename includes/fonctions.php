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
     * @return array Un tableau avec comme clé "result", un booléen indiquant si l'email est correct ou non, et comme clé "msg", le message d'erreur
     */
    function is_mail_correct(string $email, string $errorMsgEmpty = null, string $errorMsgNotMail = null) : array {

        $result = [
            "result" => true,
            "msg" => "",
        ];

        if (is_null_or_empty($email)["result"]) {
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

    /**
     * @param string $value La valeur à vérifier
     * @param string|null $errorMsg Message d'erreur si la valeur est vide (non obligatoire)
     * @return array Un tableau avec comme clé "result", un booléen indiquant si la valeur est vide ou non, et comme clé "msg", le message d'erreur
     */
    function is_null_or_empty(string $value, string $errorMsg = null) : array {
        $result = [
            "result" => false,
            "msg" => "",
        ];

        if (!isset($value) || trim($value) === "") {
            $result["result"] = true;
            $result["msg"] = $errorMsg??"Veuillez remplir ce champ.";
            return $result;
        }
        return $result;
    }

    /**
     * Permet de checker si les filtres de recherche sont corrects et n'ont pas été modifiés côté client
     * @param array $searchFilter Les filtres de recherche
     * @return array Un tableau avec comme clé "result", un booléen indiquant si les filtres sont corrects ou non, et comme clé "msg", le message d'erreur
     */
    function is_searching_filter_correct(array $searchFilter) : array {

        $result = [
            "result" => false,
            "msg" => "",
        ];

        $niveau = $searchFilter["niveau"];
        $thematique = $searchFilter["thematique"];

        if (!ctype_digit($niveau) || intval($niveau) < 1 || intval($niveau) > 3) {
            $result["msg"] = "Niveau invalide.";
            return $result;
        }
        if (!ctype_digit($thematique) || intval($thematique) < 0 || intval($thematique) > 8) {
            $result["msg"] = "Thématique invalide.";
            return $result;
        }

        $result["result"] = true;
        return $result;
    }

    /**
     * Permet de checker si les input ont moins de 255 caractères
     * @param string $input La chaine de caractère à vérifié
     * @return bool true si > 255 false sinon
     */
    function is_under_255(string $input) : bool {
        if (strlen($input) > 255){
            return false;
        } else {
            return true;
        }
    }

    /**
     * Permet de verifier si une valeur est dans un array d'array
     * @param mixed $value la valeur cherchée
     * @param array $array l'array d'array
     * @return bool true s
     */
    function name_in_array(mixed $value, array $arrays) : bool {
        foreach ($arrays as $kay => $names){
            if(in_array($value,$names,true)){
                return true;
            }
        }
        return false;
    }