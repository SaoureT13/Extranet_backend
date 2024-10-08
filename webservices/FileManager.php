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

$TransactionManager = new TransactionManager();
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

if (isset($_REQUEST['DT_BEGIN']) && $_REQUEST['DT_BEGIN'] != "") {
    $DT_BEGIN = $_REQUEST['DT_BEGIN'];
}

if (isset($_REQUEST['DT_END']) && $_REQUEST['DT_END'] != "") {
    $DT_END = $_REQUEST['DT_END'];
}

$OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);
//echo $STR_UTITOKEN;

if ($mode == "listTransaction") {
    $LG_OPEID = "%";
    $LG_TTRID = "%";
    if (isset($_REQUEST['LG_OPEID']) && $_REQUEST['LG_OPEID'] != "") {
        $LG_OPEID = $_REQUEST['LG_OPEID'];
    }

    if (isset($_REQUEST['LG_TTRID']) && $_REQUEST['LG_TTRID'] != "") {
        $LG_TTRID = $_REQUEST['LG_TTRID'];
    }

    if (isset($_REQUEST['LG_SOCID'])) {
        $LG_SOCID = $_REQUEST['LG_SOCID'];
    }

    $listTransaction = $TransactionManager->showAllOrOneTransaction($search_value, $LG_OPEID, $LG_TTRID, $LG_SOCID, $DT_BEGIN, $DT_END, $start, $length);
    $total = $TransactionManager->totalTransaction($search_value, $LG_OPEID, $LG_TTRID, $LG_SOCID, $DT_BEGIN, $DT_END);
    foreach ($listTransaction as $value) {
        $arrayJson_chidren = array();
        $arrayJson_chidren["TRAID"] = $value['LG_TRAID'];
        $arrayJson_chidren["OPENAME"] = $value['STR_OPENAME'];
        $arrayJson_chidren["OPEDESCRIPTION"] = $value['STR_OPEDESCRIPTION'];
        $arrayJson_chidren["TTRID"] = $value['STR_TTRDESCRIPTION'];
        $arrayJson_chidren["OPEPIC"] = Parameters::$rootFolderRelative . "logos/" . $value['STR_OPEPIC'];
        $arrayJson_chidren["TRACREATED"] = DateToString($value['DT_TRACREATED'], 'd/m/Y H:i:s');
        $arrayJson_chidren["TRAREFERENCE"] = $value['STR_TRAREFERENCE'];
        $arrayJson_chidren["TRAPHONE"] = $value['STR_TRAPHONE'];
        $arrayJson_chidren["OPEPHONE"] = $value['STR_OPEPHONE'];
        $arrayJson_chidren["TRAAMOUNT"] = $value['DBL_TRAAMOUNT'];
        $arrayJson_chidren["SOCID"] = $value['LG_SOCID'];
        $arrayJson_chidren["str_ACTION"] = "<span class='text-primary' title='Consultation de l'opération N°" . $value['STR_TRAREFERENCE'] . "'></span><span class='text-danger' title='Annulation de l'opération N°" . $value['STR_TRAREFERENCE'] . "'></span>";
        $OJson[] = $arrayJson_chidren;
    }
    $arrayJson["data"] = $OJson;
    $arrayJson["recordsTotal"] = $total;
    $arrayJson["recordsFiltered"] = $total;
} else {
    if (isset($_REQUEST['LG_TRAID'])) {
        $LG_TRAID = $_REQUEST['LG_TRAID'];
    }

    if (isset($_REQUEST['LG_TTRID'])) {
        $LG_TTRID = $_REQUEST['LG_TTRID'];
    }

    if (isset($_REQUEST['LG_OPEID'])) {
        $LG_OPEID = $_REQUEST['LG_OPEID'];
    }

    if (isset($_REQUEST['LG_SOCID'])) {
        $LG_SOCID = $_REQUEST['LG_SOCID'];
    }

    if (isset($_REQUEST['STR_TRAREFERENCE'])) {
        $STR_TRAREFERENCE = $_REQUEST['STR_TRAREFERENCE'];
    }

    if (isset($_REQUEST['STR_TRAPHONE'])) {
        $STR_TRAPHONE = $_REQUEST['STR_TRAPHONE'];
    }

    if (isset($_REQUEST['DBL_TRAAMOUNT'])) {
        $DBL_TRAAMOUNT = $_REQUEST['DBL_TRAAMOUNT'];
    }
    
    if (isset($_REQUEST['STR_TRAOTHERVALUE'])) {
        $STR_TRAOTHERVALUE = $_REQUEST['STR_TRAOTHERVALUE'];
    }

    if ($mode == "getTransaction") {
        $value = $TransactionManager->getTransaction($LG_TRAID);
        if ($value != null) {
            $arrayJson["OPENAME"] = $value[0]['STR_OPENAME'];
            $arrayJson["OPEDESCRIPTION"] = $value[0]['STR_OPEDESCRIPTION'];
            $arrayJson["TTRID"] = $value[0]['STR_TTRDESCRIPTION'];
            $arrayJson["OPEPIC"] = Parameters::$rootFolderRelative . "logos/" . $value[0]['STR_OPEPIC'];
            $arrayJson["TRACREATED"] = DateToString($value[0]['DT_TRACREATED'], 'd/m/Y');
            $arrayJson["TRAREFERENCE"] = $value[0]['STR_TRAREFERENCE'];
            $arrayJson["TRAPHONE"] = $value[0]['STR_TRAPHONE'];
            $arrayJson["OPEPHONE"] = $value[0]['STR_OPEPHONE'];
            $arrayJson["TRAAMOUNT"] = $value[0]['DBL_TRAAMOUNT'];
        }
    } else if ($mode == "createTransaction") {
        $TransactionManager->createTransaction($LG_OPEID, $LG_TTRID, $STR_TRAREFERENCE, $STR_TRAPHONE, $LG_SOCID, $DBL_TRAAMOUNT, $STR_TRAOTHERVALUE, $OUtilisateur);
    } else if ($mode == "deleteTransaction") {
        $TransactionManager->deleteTransaction($LG_TRAID, $OUtilisateur);
    } 
    
    $arrayJson["code_statut"] = Parameters::$Message;
    $arrayJson["desc_statut"] = Parameters::$Detailmessage;
}

echo json_encode($arrayJson);


