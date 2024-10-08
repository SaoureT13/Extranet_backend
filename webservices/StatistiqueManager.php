<?php

/**
 * Created by PhpStorm.
 * User: chaar
 * Date: 14/08/2018
 * Time: 11:31
 */
require '../services/scripts/php/core_transaction.php';
include '../services/scripts/php/lib.php';

$arrayJson = array();
$OJson = array();
$search_value = "";

$total = 0;
$start = 0;
$length = 25;

$StatistiqueManager = new StatistiqueManager();
$ConfigurationManager = new ConfigurationManager();
$OneSignal = new OneSignal();

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

if (isset($_REQUEST['STR_UTITOKEN'])) {
    $STR_UTITOKEN = $_REQUEST['STR_UTITOKEN'];
}

if (isset($_REQUEST['LG_SOCID'])) {
    $LG_SOCID = $_REQUEST['LG_SOCID'];
}

if (isset($_REQUEST['DT_BEGIN']) && $_REQUEST['DT_BEGIN'] != "") {
    $DT_BEGIN = $_REQUEST['DT_BEGIN'];
}

if (isset($_REQUEST['DT_END']) && $_REQUEST['DT_END'] != "") {
    $DT_END = $_REQUEST['DT_END'];
}

$OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);


if ($mode == "topOperateur") {
    $listOperateur = $StatistiqueManager->topOperateur($LG_SOCID, $DT_BEGIN, $DT_END, $length);
    foreach ($listOperateur as $value) {
        $arrayJson_chidren = array();
        $arrayJson_chidren["NOMBRE"] = $value['NOMBRE'];
        $arrayJson_chidren["OPENAME"] = $value['STR_OPENAME'];
        $arrayJson_chidren["OPEDESCRIPTION"] = $value['STR_OPEDESCRIPTION'];
        $arrayJson_chidren["MONTANT"] = $value['MONTANT'];
        $arrayJson_chidren["OPEID"] = $value['LG_OPEID'];
        $arrayJson_chidren["OPEPIC"] = Parameters::$rootFolderRelative . "logos/" . $value['STR_OPEPIC'];
        $arrayJson_chidren["SOCID"] = $value['LG_SOCID'];
        $arrayJson[] = $arrayJson_chidren;
    }
} else if ($mode == "topTransaction") {
    $listOperateur = $StatistiqueManager->topTransaction($LG_SOCID, $DT_BEGIN, $DT_END, $length);
    foreach ($listOperateur as $value) {
        $arrayJson_chidren = array();
        $arrayJson_chidren["TRAID"] = $value['LG_TRAID'];
        $arrayJson_chidren["OPENAME"] = $value['STR_OPENAME'];
        $arrayJson_chidren["OPEDESCRIPTION"] = $value['STR_OPEDESCRIPTION'];
        $arrayJson_chidren["TRAREFERENCE"] = $value['STR_TRAREFERENCE'];
        $arrayJson_chidren["TRAPHONE"] = $value['STR_TRAPHONE'];
        $arrayJson_chidren["OPEPHONE"] = $value['STR_OPEPHONE'];
        $arrayJson_chidren["TRAAMOUNT"] = $value['DBL_TRAAMOUNT'];
        $arrayJson_chidren["TRAOTHERVALUE"] = $value['STR_TRAOTHERVALUE'];
        $arrayJson_chidren["TRACREATED"] = DateToString($value['DT_TRACREATED'], 'd/m/Y H:i:s');
        $arrayJson_chidren["OPEPIC"] = Parameters::$rootFolderRelative . "logos/" . $value['STR_OPEPIC'];
        $arrayJson_chidren["SOCID"] = $value['LG_SOCID'];
        $arrayJson_chidren["TTRDESCRIPTION"] = $value['STR_TTRDESCRIPTION'];
        $arrayJson[] = $arrayJson_chidren;
    }
}

echo json_encode($arrayJson);


