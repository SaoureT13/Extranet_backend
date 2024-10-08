<?php

interface PaymentInterface {

    public function generateToken();

    public function doPayment($STR_PROVIDER, $data, $currency);

    public function doPaymentOrangemoney($data, $currency);
}

class PaymentManager implements PaymentInterface {

    private $dbconnnexion;

    //constructeur de la classe 
    public function __construct() {
        $this->dbconnnexion = DoConnexionPDO(Parameters::$host, Parameters::$user, Parameters::$pass, Parameters::$db, Parameters::$port);
    }

    public function generateToken() {
        $validation = "";
        try {
            // URL de l'API
            $url = Parameters::$urlOrangemoneyToken;

// Headers de la requête
            $headers = array(
                'Accept: application/json',
                'Content-Type: application/x-www-form-urlencoded',
                "Authorization: Basic " . Parameters::$authentificationBasic
            );

// Données à envoyer
            $data = array(
                'grant_type' => 'client_credentials'
            );

// Initialisation de cURL
            $ch = curl_init();

// Configuration de cURL
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Exécution de la requête
            $response = curl_exec($ch);

// Vérification des erreurs
            if (curl_errno($ch)) {
                echo 'Erreur cURL : ' . curl_error($ch);
            }

// Fermeture de la session cURL
            curl_close($ch);

//            echo $response;
            // Convertir le JSON en objet PHP
            $obj = json_decode($response);

// Vérifier si la conversion a réussi
            if ($obj === null && json_last_error() !== JSON_ERROR_NONE) {
                die('Erreur lors du décodage JSON');
            }

            $validation = $obj->access_token;
            // Accéder aux propriétés de l'objet JSON
            /* echo "ID: " . $obj->id . "<br>";
              echo "Name: " . $obj->name . "<br>";
              echo "Age: " . $obj->age . "<br>";
              echo "Email: " . $obj->email . "<br>"; */

// Affichage de la réponse
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
            $exc->getTraceAsString();
//            Parameters::buildErrorMessage("Echec de l'opération. Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    public function doPayment($STR_PROVIDER, $data, $currency) {
        $validation = false;
        try {
            if ($STR_PROVIDER == "orangemoney") {
                $validation = $this->doPaymentOrangemoney($data, $currency);
            }
        } catch (Exception $ex) {
            
        }
        return $validation;
    }

    public function doPaymentOrangemoney($param, $currency) {
        $validation = "";
        $token = "";
        $orderId = "";
        $dbl_amount = 0;
        try {
            $token = $this->generateToken();
//            echo "=========" . $token;
            if ($token == "") {
                Parameters::buildErrorMessage("Echec du paiement. Veuillez réessayer dans quelques instants");
                return $validation;
            }
//            echo "token======>" . $token;
//            $url = 'https://api.orange.com/orange-money-webpay/dev/v1/webpayment';
            $url = Parameters::$urlOrangemoneyPayment;
            $headers = array(
                'Accept: application/json',
                'Content-Type: application/json',
                "Authorization: Bearer " . $token,
            );

            foreach ($param as $k => $v) {
                if ($k == "lg_ticid") {
                    $orderId = $v;
                } else if ($k == "dbl_ticamount") {
                    $dbl_amount = $v;
                }
            }
            
//            echo 'order id===='.$orderId."==========amount:::" . $dbl_amount;

            /* $data = array(
              'merchant_key' => '9e57bf53',
              'currency' => 'OUV',
              //              'order_id' => '1234567890',
              'order_id' => $param->lg_ticid,
              'amount' => 10,
              'return_url' => 'http://www.merchant-example.org/return',
              'cancel_url' => 'http://www.merchant-example.org/cancel',
              'notif_url' => 'http://www.merchant-example.org/notif',
              'lang' => 'fr',
              'reference' => 'ref-xyz.456',
              ); */

            //var_dump($param);

            $data = array(
                'merchant_key' => Parameters::$merchantKey,
                //                        'currency' => 'OUV', //devise utilisee lors des tests
                'currency' => $currency,
                'order_id' => $orderId, //LG_CASID: Id de Cashtransaction a la fin des developpement
                'amount' => $dbl_amount,
                'return_url' => Parameters::$urlResponseOrangemoney . "?mode=return",
                'cancel_url' => Parameters::$urlResponseOrangemoney . "?mode=cancel",
                'notif_url' => Parameters::$urlResponseOrangemoney . "?mode=notification",
//                'return_url' => 'http://www.merchant-example.org/return',
//                'cancel_url' => 'http://www.merchant-example.org/cancel',
//                'notif_url' => 'http://www.merchant-example.org/notif',
                'lang' => 'fr',
                'reference' => $orderId,
            );

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

            $response = curl_exec($ch);
            curl_close($ch);


            $obj = json_decode($response);
            var_dump($obj);

// Vérifier si la conversion a réussi
            if ($obj === null && json_last_error() !== JSON_ERROR_NONE) {
                die('Erreur lors du décodage JSON');
            }

            $validation = ($obj->message == "OK" ? true : false);
            // Accéder aux propriétés de l'objet JSON
            /* echo "ID: " . $obj->id . "<br>";
              echo "Name: " . $obj->name . "<br>";
              echo "Age: " . $obj->age . "<br>";
              echo "Email: " . $obj->email . "<br>";
             * 
             */
            $validation = $validation;
        } catch (Exception $ex) {
            echo $ex->getTraceAsString();
        }
        return $validation;
    }

}
