<?php

//connexion a la bd
function DoConnexionPDO($host, $user, $pass, $dbname, $port = 3306) {
    $bdd = null;
    try {
        //echo $host ."====". $user ."++++". $pass . "----" . $dbname . "===" . $port;
        // On se connecte à MySQL
        $bdd = new PDO('mysql:host=' . $host . ';port=' . $port . ';dbname=' . $dbname . ';charset=utf8', '' . $user . '', '' . $pass . '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    } catch (Exception $exc) {
        echo $exc->getTraceAsString();
    }
    return $bdd;
}

//fin connexion a la bd


function RandomString() {
    $characters = "0123456789abcdefghijklmnopqrstxwz";
    $randstring = '';
    for ($i = 0; $i < 5; $i++) {
        $randstring = $randstring . $characters[rand(0, strlen($characters))];
    }
    $unique = uniqid($randstring, "");
    return $unique;
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function RandomNumber() {
    $characters = "0123456789034163131";
    $randstring = '';
    for ($i = 0; $i < 5; $i++) {
        $randstring = $randstring . $characters[rand(0, strlen($characters))];
    }
    $unique = uniqid($randstring, "");
    return "0123" . $unique;
}

function generateRandomNumber($length = 10) {
    $characters = '0123456789034163131';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

//fonction d'insertion de données dans la table
function Persist($table, $params, $dbconnexion) {
    $str_values = "";
    $str_fields = "";
    $validation = false;

    try {
        //create name value pairs seperated by ,
        foreach ($params as $k => $v) {
            $str_fields .= $k . ",";
            $str_values .= ':' . $k . ',';
        }

        $str_fields = rtrim($str_fields, ","); //le tableau $fields est transformé en chaine de caractere séparé par des virgules
        $str_values = rtrim($str_values, ",");

        $query = "INSERT INTO " . $table . " (" . $str_fields . ") VALUES (" . $str_values . ")"; //format INSERT INTO t_table (field_name_1,..,field_name_n) VALUES (:field_name_1,..,:field_name_n);    
        //echo ($query);
        $res = $dbconnexion->prepare($query); //on prépare la requete
        $res->execute($params); //on execute la requete    

        if ($res->rowCount() > 0) {
            $validation = true;
        }
        $res->closeCursor();
    } catch (Exception $exc) {
        var_dump($exc);
    }

    return $validation;
}

//fin fonction d'insertion de données dans la table
//fonction de mise a jour de données dans la table
function Merge($table, $params_to_update, $params_condition, $dbconnexion) {

    $str_element_to_update = '';
    $str_element_condition = '';
    $remain_query = '';
    $validation = false;

    //create name value pairs seperated by ,
    foreach ($params_to_update as $k => $v) {
//        $str_element_to_update .= $k . '="' . $v . '",'; //a decommenter en cas de probleme 12/04/2017
        $str_element_to_update .= $k . '=:' . $k . ',';
    }

    if ($params_condition != null || $params_condition != "") {
        foreach ($params_condition as $l => $u) {
//            $str_element_condition .= $l . '="' . $u . '" AND '; //a decommenter en cas de probleme 12/04/2017
            $str_element_condition .= $l . '=:' . $l . ' AND ';
        }
        $str_element_condition = preg_replace('/ AND $/', '', $str_element_condition); //remplace ' AND ' qui se trouve à la fin de la chaine $str_element_condition par ''
        $remain_query .= ' WHERE ' . $str_element_condition;
    }

    $str_element_to_update = rtrim($str_element_to_update, ","); //le tableau $fields est transformé en chaine de caractere séparé par des virgules

    $res = null;
    try {
        //error_log('merge');

        $query = "UPDATE " . $table . " SET " . $str_element_to_update . $remain_query;
//        var_dump($query);
        $res = $dbconnexion->prepare($query); //on prépare la requete
        $params = array_merge($params_to_update, $params_condition); // fusion de deux tableaux en un seul
        $res->execute($params); //on execute la requete

        if ($res->rowCount() > 0) {
            $validation = true;
        }
    } catch (Exception $exc) {
        error_log($exc->getMessage());
        Parameters::buildErrorMessage("Erreur système sur la mise à jour. Veuillez contacter votre administrateur");
    }
    $res->closeCursor();

    return $validation;
}

//fin fonction de mise a jour de données dans la table
//fonction selection d'un objet
function Find($table, $params, $dbconnexion, $operator = "AND") {
    $str_element = '';
    $Object = null;

    //create name value pairs seperated by ,
    foreach ($params as $k => $v) {
        $str_element .= $k . "=:" . $k . " " . $operator . " ";
    }
    $str_element = preg_replace('/ ' . $operator . ' $/', '', $str_element);
    $query = "SELECT * FROM " . $table . " WHERE " . $str_element . " LIMIT 1";

    $res = $dbconnexion->prepare($query); //on prépare la requete
    $res->execute($params); //on execute la requete
    if ($rowObj = $res->fetch()) {
        $Object[] = $rowObj;
    }

    return $Object;
}

//fin fonction selection d'un objet
function Remove($table, $params, $dbconnexion) {
    $str_element = '';
    $validation = false;
    try {
        $Object = Find($table, $params, $dbconnexion); // on verifie si l'objet existe
        if ($Object != null) {
            //create name value pairs seperated by ,
            foreach ($params as $k => $v) {
                $str_element .= $k . '=:' . $k . ' AND ';
            }
            $str_element = preg_replace('/ AND $/', '', $str_element);
            $query = "DELETE FROM " . $table . " WHERE " . $str_element . " LIMIT 1";

            $res = $dbconnexion->prepare($query); //on prépare la requete
            $res->execute($params); //on execute la requete

            if ($res->rowCount() > 0) {
                $validation = true;
            }
            $res->closeCursor();
        }
    } catch (Exception $exc) {
        echo $exc->getTraceAsString();
    }
    return $validation;
}

//suppression temporaire d'un objet


function get_now() {
    return date("Y-m-d H:i:s", time());
}

//fin transforme une chaine en date

function DateToString($dateToTransform, $format) {
    /* $date = new DateTime('2000-01-01');
      $result = $date->format('Y-m-d H:i:s'); */
    $date = new DateTime($dateToTransform);
    $result = $date->format($format);
    return $result;
}

//transforme une chaine en montant
function StringToAmount($numberstring, $numberOfSniferafterseparator, $character, $separator) {
    return number_format($numberstring, $numberOfSniferafterseparator, $character, $separator);
}

//fin transforme une chaine en montant

function generateReference($counter, $lenght_string) {
    $result = "";
    $diff = $lenght_string - strlen($counter);
    if (strlen($counter) < $lenght_string) {
        for ($i = 0; $i < $diff; $i++) {
            $result .= "0";
        }
    }
    $result .= $counter;
    return $result;
}

function generateZero($number, $nbrecaractere) {
    $numberZero = "";
    for ($i = 0; $i < ($nbrecaractere - strlen($number)); $i++) {
        $numberZero .= "0";
    }
    return $numberZero;
}

function generateStrRefEnd($int_QUANTITY, $str_REF_BEGIN) {

    for ($i = 1; $i < $int_QUANTITY; $i++) {


        $str_REF_BEGIN = generateZero(($str_REF_BEGIN + 1), strlen($str_REF_BEGIN)) . ($str_REF_BEGIN + 1);

        //$str_REF_BEGIN += 1;
    }

    return $str_REF_BEGIN;
}

function generate_uuid() {
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),
            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,
            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,
            // 48 bits for "node"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

function Finds($query, $dbconnexion, $params) {
    $Object = null;

    $res = $dbconnexion->prepare($query); //on prépare la requete
    $res->execute($params); //on execute la requete
    if ($rowObj = $res->fetchAll(PDO::FETCH_ASSOC)) {
        $Object[] = $rowObj;
    }
    return $Object;
}

function uploadFile($target_dir, $file, $isFileImage = true): string {
    $validation = "";
//    $prefixe = "";
    try {
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $prefixe = time();
        $target_file = $target_dir . $prefixe . '-' . str_replace(" ", "", basename($file["name"]));

        $uploadOk = 1;
        $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if ($isFileImage) { //si le fichier est une image
            // Vérifiez si le fichier est une image réelle ou une fausse image
            $check = getimagesize($file["tmp_name"]);
            if ($check !== false) {
                //echo "Le fichier est une image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                //echo "Le fichier n'est pas une image." ;
                $uploadOk = 0;
            }
        }


        // Vérifiez si le fichier existe déjà
        if (file_exists($target_file)) {
            //echo "Désolé, le fichier existe déjà." ;
            $uploadOk = 0;
        }

        // Vérifiez la taille du fichier
        if ($file["size"] > 2500000) { // Limite de taille de 500KB
            //echo "Désolé, votre fichier est trop volumineux." ;
            $uploadOk = 0;
        }

        // Autoriser certains formats de fichier
        $allowedTypes = ['jpg', 'png', 'jpeg', 'gif', 'pdf']; // Ajouter d'autres types de fichiers si nécessaire
        if (!in_array($fileType, $allowedTypes)) {
            //"Désolé, seuls les fichiers JPG, JPEG, PNG, GIF et PDF sont autorisés." ;
            $uploadOk = 0;
        }

        //echo $fileType . "===" . $uploadOk . ">>>>>" . $file["name"];
        // Vérifiez si $uploadOk est défini à 0 par une erreur
        if ($uploadOk == 0) {
            //echo "Désolé, votre fichier n'a pas été téléchargé.";
            // Si tout est ok, essayez de télécharger le fichier
        } else {
            if (move_uploaded_file($file["tmp_name"], $target_file)) {
                //echo "Le fichier " . htmlspecialchars(basename($file["name"])) . " a été téléchargé.";
                $validation = $prefixe . '-' . str_replace(" ", "", basename($file["name"]));
            } else {
                //echo "Désolé, une erreur s'est produite lors du téléchargement de votre fichier.";
            }
        }
    } catch (Exception $ecx) {
        error_log($exc->getTraceAsString());
        Parameters::buildErrorMessage("Une erreur est survenue lors du téléchargement.");
    }
    return $validation;
}
