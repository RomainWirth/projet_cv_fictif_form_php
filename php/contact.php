<?php 
    $array = array(
        "firstname" => "", 
        "name" => "", 
        "email" => "", 
        "phone" => "", 
        "message" => "", 
        "firstnameError" => "", 
        "nameError" => "", 
        "emailError" => "", 
        "phoneError" => "", 
        "messageError" => "",
        "isSuccess" => false
    );
    // Code de première isntance avec initialisation des données
    // $firstname = $name = $email = $phone = $message = ""; // supprimé après 
    // $firstnameError = $nameError = $emailError = $phoneError = $messageError = "";
    // $isSuccess = false; // initialisation de la variable de succès d'envoi du formulaire à faux = on affiche pas le message de succès à l'utlisateur
    $emailTo = 'wirth.romain@gmail.com';

    // Code de la deuxième instance
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $array["firstname"] = verifyInput($_POST['firstname']);
        $array["name"] = verifyInput($_POST['name']);
        $array["email"] = verifyInput($_POST['email']);
        $array["phone"] = verifyInput($_POST['phone']);
        $array["message"] = verifyInput($_POST['message']);
        $array["isSuccess"] = true; // une fois le formulaire 'posté' par l'utilisateur, la variable devient vraie = on affiche le message de succès à l'utilisateur
        $emailText = ""; // variable est une string vide

        if(empty($array["firstname"])) { // si la case prénom est vide
            $array["firstnameError"] = "Merci de m'indiquer ton prénom"; // ce message s'affiche
            $array["isSuccess"] = false; // la variable isSuccess redevient false = pas d'affichage du message de validation
        } else { // si la case est remplie (else)
            $emailText .= "First Name: " . $array["firstname"] ."\n"; // on ajoute ces infos au message contenu dans l'email (variable emailText) avec la concaténation 
        }
        if (empty($array["name"])) {
            $array["nameError"] = "Merci de m'indiquer ton nom";
            $array["isSuccess"] = false;
        } else {
            $emailText .= "Last Name: " . $array["name"] . "\n";
        }
        if(!isEmail($array["email"])) {
            $array["emailError"] = "Merci d'indiquer une adresse email valide";
            $array["isSuccess"] = false;
        } else {
            $emailText .= "email : " . $array["email"] . "\n";
        }
        if(!isPhone($array["phone"])) {
            $array["phoneError"] = "Merci d'indiquer un numéro de téléphone valide (chiffres et espaces uniquement)";
            $array["isSuccess"] = false;
        } else {
            $emailText .= "Telephone : " . $array["phone"] . "\n";
        }
        if(empty($array["message"])) {
            $array["messageError"] = "Votre message est vide, merci de remplir ce champ";
            $array["isSuccess"] = false;
        } else {
            $emailText .= "Message: " . $array["message"] . "\n";
        }
        // ATTENTION : cette étape affiche une erreur en phase de développement (serveur fictif), ne fonctionnera qu'une fois en ligne
        // afin de tester : modifier le fichier texte PHP (php.ini) à la ligne sendmail_path et ajouter le chemin de xampp\mailtodisk\mailtodisk.exe
        if($array["isSuccess"]) { // une fois toutes les étapes de validation passées (isSuccess est 'true')
            $headers = "From: {$array["firstname"]} {$array["name"]} <{$array["email"]}>\r\nReply-to: {$array["email"]}";
            mail($emailTo, "Vous avez un nouveau message depuis votre site", $emailText, $headers);
        }

        echo json_encode($array);
    }

    function isPhone($var) { // validation du téléphone avec la regEx
        return preg_match("/^[0-9 ]*$/", $var); // regEx chiffres de 0 à 9 et espaces acceptés
    }

    function isEmail($var) {
        return filter_var($var, FILTER_VALIDATE_EMAIL);
    }
    // protection du formulaire : ajout de la fonction à chaque étape du formulaire
    function verifyInput($var) {
        $var = trim($var); // enlève tous les espaces
        $var = stripslashes($var); // enlève tous les '\'
        $var = htmlspecialchars($var); // permet de se protéger contre la faille XSS (injection de script dans le document par l'utilisateur)
        return $var;
    }
?>