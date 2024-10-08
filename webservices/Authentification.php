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

$arrayJson = array();

$ConfigurationManager = new ConfigurationManager();
$OneSignal = new OneSignal();

$mode = $_REQUEST['mode'];


if (isset($_REQUEST['STR_UTILOGIN'])) {
    $STR_UTILOGIN = $_REQUEST['STR_UTILOGIN'];
}

if (isset($_REQUEST['STR_UTIPASSWORD'])) {
    $STR_UTIPASSWORD = $_REQUEST['STR_UTIPASSWORD'];
}

if (isset($_REQUEST['STR_UTILOGIN'])) {
    $STR_UTILOGIN = $_REQUEST['STR_UTILOGIN'];
}

if (isset($_REQUEST['STR_SOCCODE'])) {
    $STR_SOCCODE = $_REQUEST['STR_SOCCODE'];
}

if (isset($_REQUEST['STR_UTITOKEN'])) {
    $STR_UTITOKEN = $_REQUEST['STR_UTITOKEN'];
}

if (isset($_REQUEST['STR_UTITOKEN'])) {
    $STR_UTITOKEN = $_REQUEST['STR_UTITOKEN'];
}

if (isset($_REQUEST['STR_SOCCODE'])) {
    $STR_SOCCODE = $_REQUEST['STR_SOCCODE'];
}

if (isset($_REQUEST['IS_ADMIN'])) {
    $IS_ADMIN = $_REQUEST['IS_ADMIN'];
}

if ($mode == "doConnexion") {
    $value = $ConfigurationManager->doConnexion($STR_UTILOGIN, $STR_UTIPASSWORD, $IS_ADMIN);
    if ($value != null) {
        $arrayJson["LG_UTIID"] = $value[0]['lg_utiid'];
        $arrayJson["STR_UTIFIRSTLASTNAME"] = $value[0]['str_utifirstlastname'];
        $arrayJson["STR_UTIPHONE"] = $value[0]['str_utiphone'];
        $arrayJson["STR_UTIMAIL"] = $value[0]['str_utimail'];
        $arrayJson["STR_UTILOGIN"] = $value[0]['str_utilogin'];
        $arrayJson["STR_UTIPIC"] = $value[0]['str_utipic'] ?: null;
        $arrayJson["STR_UTITOKEN"] = $value[0]['str_utitoken'];
        $arrayJson["LG_PROID"] = $value[0]['lg_proid'];
        $arrayJson["STR_PROTYPE"] = $value[0]['str_protype'] ?: null;
        $arrayJson["STR_PRODESCRIPTION"] = $value[0]['str_prodescription'] ?: null;
        $arrayJson["STR_SOCNAME"] = $value[0]['str_socname'];
        $arrayJson["STR_SOCDESCRIPTION"] = $value[0]['str_socdescription'];
        $arrayJson["STR_SOCLOGO"] = $value[0]['str_soclogo'] ?: null;
        $arrayJson["LG_SOCID"] = $value[0]['lg_socid'];
        $arrayJson["LG_AGEID"] = $value[0]['lg_ageid'];
        $arrayJson["LG_CLIID"] = $value[0]['lg_socextid'];
    }
} else if ($mode == "doDisConnexion") {
    $ConfigurationManager->doDisConnexion($STR_UTITOKEN);
}
$arrayJson["code_statut"] = Parameters::$Message;
$arrayJson["desc_statut"] = Parameters::$Detailmessage;

echo json_encode($arrayJson);


