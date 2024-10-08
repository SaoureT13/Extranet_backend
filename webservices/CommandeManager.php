<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
/**
 * Created by PhpStorm.
 * User: chaar
 * Date: 14/08/2018
 * Time: 11:31
 */
require '../services/scripts/php/core_transaction.php';
include '../services/scripts/php/lib.php';

// Permettre l'accès depuis n'importe quelle origine (CORS)
header("Access-Control-Allow-Origin: *");

// Autoriser les méthodes HTTP spécifiées
header("Access-Control-Allow-Methods: POST");

// Autoriser certains en-têtes HTTP
header("Access-Control-Allow-Headers: Content-Type");

$arrayJson = array();
$OJson = array();
$search_value = "";

$total = 0;
$start = 0;
$length = 25;
$INT_CPRQUANTITY = 1;
$LG_COMMID = "";

$ConfigurationManager = new ConfigurationManager();
$CommandeManager = new CommandeManager();
//$OneSignal = new OneSignal();

$mode = $_REQUEST['mode'];

if (isset($_REQUEST['start'])) {
    $start = $_REQUEST['start'];
}

if (isset($_REQUEST['length'])) {
    $length = $_REQUEST['length'];
}

if (isset($_REQUEST['search_value'])) {
    $search_value = $_REQUEST['search_value'];
}

if (isset($_REQUEST['search_value[value]'])) {
    $search_value = $_REQUEST['search_value[value]'];
}

if (isset($_REQUEST['query'])) {
    $search_value = $_REQUEST['query'];
}

if (isset($_REQUEST['LG_UTIID'])) {
    $LG_UTIID = $_REQUEST['LG_UTIID'];
}

if (isset($_REQUEST['LG_CLIID'])) {
    $LG_CLIID = $_REQUEST['LG_CLIID'];
}

if (isset($_REQUEST['LG_COMMID']) && $_REQUEST['LG_COMMID'] != "") {
    $LG_COMMID = $_REQUEST['LG_COMMID'];
}

if (isset($_REQUEST['LG_AGEID'])) {
    $LG_AGEID = $_REQUEST['LG_AGEID'];
}

if (isset($_REQUEST['STR_UTITOKEN'])) {
    $STR_UTITOKEN = $_REQUEST['STR_UTITOKEN'];
    $OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);
}

if ($mode == "listCommande") {
    $arrayJson = $CommandeManager->showAllOrOneCommande($search_value, $LG_CLIID, $start, $length);
} else if ($mode == "listCommandeproduct") {
    $STR_COMMSTATUT = Parameters::$statut_process;
    if (isset($_REQUEST['STR_COMMSTATUT']) && $_REQUEST['STR_COMMSTATUT'] != "") {
        $STR_COMMSTATUT = $_REQUEST['STR_COMMSTATUT'];
    }

    $token = $ConfigurationManager->generateToken();
    $value = $CommandeManager->getLastCommandeByAgence($LG_AGEID, $STR_COMMSTATUT);
    $arrayJson = $CommandeManager->showAllOrOneCommandeproduit($value[0]["lg_socextid"], $value[0]["lg_commid"], $token);
} else if ($mode == "getClientPanier") {
    $value = $CommandeManager->getClientPanier($LG_AGEID);
    if ($value) {
        $arrayJson["data"] = $value;
    }
} else if ($mode == "getExternalClientPanier") {
    $value = $CommandeManager->getExternalClientPanier($LG_AGEID, $LG_COMMID);
    if ($value) {
        $arrayJson["data"] = $value;
    }
} else {

    if (isset($_REQUEST['STR_COMMNAME'])) {
        $STR_COMMNAME = $_REQUEST['STR_COMMNAME'];
    }

    if (isset($_REQUEST['STR_COMMADRESSE'])) {
        $STR_COMMADRESSE = $_REQUEST['STR_COMMADRESSE'];
    }

    if (isset($_REQUEST['STR_LIVADRESSE'])) {
        $STR_LIVADRESSE = $_REQUEST['STR_LIVADRESSE'];
    }

    if (isset($_REQUEST['LG_PROID'])) {
        $LG_PROID = $_REQUEST['LG_PROID'];
    }

    if (isset($_REQUEST['INT_CPRQUANTITY']) && $_REQUEST['INT_CPRQUANTITY'] != "") {
        $INT_CPRQUANTITY = (int)$_REQUEST['INT_CPRQUANTITY'];
    }

    if (isset($_REQUEST['LG_CPRID'])) {
        $LG_CPRID = $_REQUEST['LG_CPRID'];
    }

    if ($mode == "getTypetransaction") {
        $value = $ConfigurationManager->getTypetransaction($LG_AGEID);
        if ($value != null) {
            $arrayJson["TTRNAME"] = $value[0]['STR_TTRNAME'];
            $arrayJson["TTRDESCRIPTION"] = $value[0]['STR_TTRDESCRIPTION'];
        }
    } else if ($mode == "createCommproduit") {
        $token = $ConfigurationManager->generateToken();
        $OJson = $CommandeManager->createCommande($LG_AGEID, $STR_COMMNAME, $STR_COMMADRESSE, $STR_LIVADRESSE, $OUtilisateur, $token);
        if ($OJson["LG_COMMID"] != "") {
            $CommandeManager->createCommandeProduit($OJson["LG_COMMID"], $OJson["LG_CLIID"], $LG_PROID, $INT_CPRQUANTITY, $OUtilisateur, $token);
            $arrayJson["LG_COMMID"] = $OJson["LG_COMMID"];
            //Mise à jour de la commande chez nous
            $PanierClient = $CommandeManager->getExternalClientPanier($OJson["LG_CLIID"], $OJson["LG_COMMID"], $token);
            $CommandeManager->updateCommande($OJson["LG_COMMID"], $PanierClient->pieces[0]->PcvMtHT, $PanierClient->pieces[0]->PcvMtTTC);
        }
    } else if ($mode == "updateCommproduit") {
        $token = $ConfigurationManager->generateToken();
        $LG_COMMID = $CommandeManager->updateCommandeProduit($LG_CPRID, $INT_CPRQUANTITY, $OUtilisateur, $token);
        $arrayJson["LG_COMMID"] = $LG_COMMID;
    } else if ($mode == "deleteCommproduit") {
        $token = $ConfigurationManager->generateToken();
        $LG_COMMID = $CommandeManager->deleteCommandeProduit($LG_CPRID, $token);
        $arrayJson["LG_COMMID"] = $LG_COMMID;
    } else if ($mode == "getClientPanier") {
        $value = $CommandeManager->getExternalClientPanier($LG_CLIID, $LG_COMMID);
        if ($value) {
            $arrayJson["data"] = $value;
        }
    } else if ($mode == "updateCommande") {
        $value = $CommandeManager->updateCommande($LG_COMMID, "111111", "111111");
        if ($value) {
            $arrayJson["data"] = $value;
        }
    } //moi
    else if ($mode == "listeCommande") {
        $value = $CommandeManager->showAllCommandeproduit();
        if ($value) {
            $arrayJson["data"] = $value;
        }
    } //moi
    else if ($mode == "validationCommande") {
        $token = $ConfigurationManager->generateToken();
        $value = $CommandeManager->handleCommande($LG_AGEID, $token, $OUtilisateur);
        if ($value) {
            $arrayJson["data"] = $value;
        }
    }

    $arrayJson["code_statut"] = Parameters::$Message;
    $arrayJson["desc_statut"] = Parameters::$Detailmessage;
}

echo json_encode($arrayJson);


