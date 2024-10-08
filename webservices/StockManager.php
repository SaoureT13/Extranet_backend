<?php

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

$StockManager = new StockManager();
$ConfigurationManager = new ConfigurationManager();

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

if (isset($_REQUEST['search[value]'])) {
    $search_value = $_REQUEST['search[value]'];
}

if (isset($_REQUEST['query'])) {
    $search_value = $_REQUEST['query'];
}

if (isset($_REQUEST['STR_UTITOKEN'])) {
    $STR_UTITOKEN = $_REQUEST['STR_UTITOKEN'];
    $OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);
}

if (isset($_REQUEST['DT_BEGIN']) && $_REQUEST['DT_BEGIN'] != "") {
    $DT_BEGIN = $_REQUEST['DT_BEGIN'];
}

if (isset($_REQUEST['DT_END']) && $_REQUEST['DT_END'] != "") {
    $DT_END = $_REQUEST['DT_END'];
}

if (isset($_REQUEST['LG_PROID'])) {
    $LG_PROID = $_REQUEST['LG_PROID'];
}

if ($mode == "listProduct") {
    $listProduct = $StockManager->showAllOrOneProduct($search_value);
    foreach ($listProduct as $value) {
        $arrayJson_chidren = array();
        $arrayJson_chidren["ArtID"] = $value['lg_proid'];
        $arrayJson_chidren["ArtCode"] = $value['str_proname'];
        $arrayJson_chidren["ArtLib"] = $value['str_prodescription'];
        $arrayJson_chidren["ArtLastPA"] = $value['int_propriceachat'];
        $arrayJson_chidren["ArtPrixBase"] = $value['int_propricevente'];
        $arrayJson_chidren["ArtGPicID"] = Parameters::$rootFolderRelative . $value['str_propic'];
        $OJson[] = $arrayJson_chidren;
    }
    $arrayJson["products"] = $OJson;
//    $arrayJson = $StockManager->showAllOrOneProductRemote($search_value, $start, $limit); //a decommenter en cas de probleme
} else if ($mode == "getProduct") {
    $arrayJson = $StockManager->getProduct($LG_PROID);
} else if ($mode == "loadProduct") {
    $StockManager->loadExternalProduct();
} else {
    $arrayJson["code_statut"] = Parameters::$Message;
    $arrayJson["desc_statut"] = Parameters::$Detailmessage;
}

echo json_encode($arrayJson);


