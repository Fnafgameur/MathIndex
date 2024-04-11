<?php

    function is_mail_correct($email, $errorMsgEmpty = null, $errorMsgNotMail = null) : array {

        $result = [
            "bool" => true,
            "msg" => "",
        ];

        if ($email === "") {
            $result["bool"] = false;
            $result["msg"] = $errorMsgEmpty??"Veuillez saisir votre email.";
            return $result;
        }
        else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $result["bool"] = false;
            $result["msg"] = $errorMsgNotMail??"Email invalide.";
            return $result;
        }
        return $result;
    }