<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");


if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}
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

if (isset($_REQUEST['LG_UTIID'])) {
    $LG_UTIID = $_REQUEST['LG_UTIID'];
}

if (isset($_REQUEST['STR_UTITOKEN'])) {
    $STR_UTITOKEN = $_REQUEST['STR_UTITOKEN'];
}

//$OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);

if ($mode == "listTypetransaction") {
    $listTypetransaction = $ConfigurationManager->showAllOrOneTypetransaction($search_value);

    foreach ($listTypetransaction as $value) {
        $arrayJson_chidren = array();
        $arrayJson_chidren["TTRID"] = $value['LG_TTRID'];
        $arrayJson_chidren["TTRDESCRIPTION"] = $value['STR_TTRDESCRIPTION'];
        $arrayJson[] = $arrayJson_chidren;
    }
} else if ($mode == "listProfile") {
    if (isset($_REQUEST['STR_PROTYPE'])) {
        $STR_PROTYPE = $_REQUEST['STR_PROTYPE'];
    }
    $listProfile = $ConfigurationManager->showAllOrOneProfile($search_value, ($STR_PROTYPE == Parameters::$type_system ? "%" : $STR_PROTYPE));

    foreach ($listProfile as $value) {
        $arrayJson_chidren = array();
        $arrayJson_chidren["PROID"] = $value['LG_PROID'];
        $arrayJson_chidren["PRONAME"] = $value['STR_PRONAME'];
        $arrayJson_chidren["PRODESCRIPTION"] = $value['STR_PRODESCRIPTION'];
        $arrayJson_chidren["PROTYPE"] = ($value['STR_PROTYPE'] == Parameters::$type_system ? "Système" : "Standard");
        $OJson[] = $arrayJson_chidren;
    }
    $arrayJson["data"] = $OJson;
} else if ($mode == "listOperateur") {
    $listOperateur = $ConfigurationManager->showAllOrOneOperateur($search_value);

    foreach ($listOperateur as $value) {
        $arrayJson_chidren = array();
        $arrayJson_chidren["OPEID"] = $value['LG_OPEID'];
        $arrayJson_chidren["OPENAME"] = $value['STR_OPENAME'];
        $arrayJson_chidren["OPEDESCRIPTION"] = $value['STR_OPEDESCRIPTION'];
        $arrayJson_chidren["OPEPIC"] = Parameters::$rootFolderRelative . "logos/" . $value['STR_OPEPIC'];
        $OJson[] = $arrayJson_chidren;
    }
    $arrayJson["data"] = $OJson;
} else if ($mode == "listSociete") {
    $listSociete = $ConfigurationManager->showAllOrOneSocieteLimit($search_value, $start, $length);
    $total = $ConfigurationManager->totalSociete($search_value);
    foreach ($listSociete as $value) {
        $arrayJson_chidren = array();
        $arrayJson_chidren["SOCID"] = $value['LG_SOCID'];
        $arrayJson_chidren["SOCNAME"] = $value['STR_SOCNAME'];
        $arrayJson_chidren["SOCDESCRIPTION"] = $value['STR_SOCDESCRIPTION'];
        $arrayJson_chidren["SOCLOGO"] = Parameters::$rootFolderRelative . "logos/" . $value['STR_SOCLOGO'];
        $arrayJson_chidren["SOCCREATED"] = $value['DT_SOCCREATED'];
        $arrayJson_chidren["SOCADDRESS"] = $value['STR_SOCADDRESS'];
        $arrayJson_chidren["SOCMAIL"] = $value['STR_SOCMAIL'];
        $arrayJson_chidren["SOCPHONE"] = $value['STR_SOCPHONE'];
        $arrayJson_chidren["SOCNOTIFICATION"] = ($value['BOOL_SOCNOTIFICATION'] == Parameters::$PROCESS_FAILED ? false : true);
        $arrayJson_chidren["SOCLASTABONNEMENT"] = ($value['DT_SOCLASTABONNEMENT'] != null ? DateToString($value['DT_SOCLASTABONNEMENT'], 'd/m/Y') : "");
        $arrayJson_chidren["str_ACTION"] = "<span class='text-warning' title='Mise à jour de la société " . $value['STR_SOCDESCRIPTION'] . "'></span>";
        $OJson[] = $arrayJson_chidren;
    }
    $arrayJson["data"] = $OJson;
    $arrayJson["recordsTotal"] = $total;
    $arrayJson["recordsFiltered"] = $total;
} else if ($mode == "listSocieteUtilisateur") {
    $LG_SOCID = "%";
    if (isset($_REQUEST['LG_SOCID']) && $_REQUEST['LG_SOCID'] != "") {
        $LG_SOCID = $_REQUEST['LG_SOCID'];
    }

    $listSocieteUtilisateur = $ConfigurationManager->showAllOrOneSocieteUtilisateur($search_value, $LG_SOCID, $LG_UTIID);

    foreach ($listSocieteUtilisateur as $value) {
        $arrayJson_chidren = array();
        $arrayJson_chidren["SUTID"] = $value['LG_SUTID'];
        $arrayJson_chidren["SOCNAME"] = $value['STR_SOCNAME'];
        $arrayJson_chidren["SOCDESCRIPTION"] = $value['STR_SOCDESCRIPTION'];
        $arrayJson_chidren["UTIFIRSTLASTNAME"] = $value['STR_UTIFIRSTLASTNAME'];
        $OJson[] = $arrayJson_chidren;
    }
    $arrayJson["data"] = $OJson;
} else if ($mode == "listSocieteOperateur") {
    $LG_OPEID = "%";
    if (isset($_REQUEST['LG_OPEID']) && $_REQUEST['LG_OPEID'] != "") {
        $LG_OPEID = $_REQUEST['LG_OPEID'];
    }

    if (isset($_REQUEST['LG_SOCID'])) {
        $LG_SOCID = $_REQUEST['LG_SOCID'];
    }

    $listSocieteOperateur = $ConfigurationManager->showAllOrOneSocieteOperateur($search_value, $LG_SOCID, $LG_OPEID);

    foreach ($listSocieteOperateur as $value) {
        $arrayJson_chidren = array();
        $arrayJson_chidren["SOPID"] = $value['LG_SOPID'];
        $arrayJson_chidren["SOPPHONE"] = $value['STR_SOPPHONE'];
        $arrayJson_chidren["OPENAME"] = $value['STR_OPENAME'];
        $arrayJson_chidren["OPEDESCRIPTION"] = $value['STR_OPEDESCRIPTION'];
        $OJson[] = $arrayJson_chidren;
    }
    $arrayJson["data"] = $OJson;
} else {
    if (isset($_REQUEST['LG_TTRID'])) {
        $LG_TTRID = $_REQUEST['LG_TTRID'];
    }

    if (isset($_REQUEST['LG_OPEID'])) {
        $LG_OPEID = $_REQUEST['LG_OPEID'];
    }

    if (isset($_REQUEST['LG_SOCID'])) {
        $LG_SOCID = $_REQUEST['LG_SOCID'];
    }

    if (isset($_REQUEST['STR_SOCNAME'])) {
        $STR_SOCNAME = $_REQUEST['STR_SOCNAME'];
    }

    if (isset($_REQUEST['STR_SOCDESCRIPTION'])) {
        $STR_SOCDESCRIPTION = $_REQUEST['STR_SOCDESCRIPTION'];
    }

    if (isset($_REQUEST['STR_SOCADDRESS'])) {
        $STR_SOCADDRESS = $_REQUEST['STR_SOCADDRESS'];
    }

    if (isset($_REQUEST['STR_SOCMAIL'])) {
        $STR_SOCMAIL = $_REQUEST['STR_SOCMAIL'];
    }

    if (isset($_REQUEST['STR_SOCPHONE'])) {
        $STR_SOCPHONE = $_REQUEST['STR_SOCPHONE'];
    }

    if (isset($_REQUEST['BOOL_SOCNOTIFICATION'])) {
        $BOOL_SOCNOTIFICATION = $_REQUEST['BOOL_SOCNOTIFICATION'];
    }

    if (isset($_REQUEST['STR_SOPPHONE'])) {
        $STR_SOPPHONE = $_REQUEST['STR_SOPPHONE'];
    }

    if (isset($_REQUEST['LG_SOPID'])) {
        $LG_SOPID = $_REQUEST['LG_SOPID'];
    }

    if (isset($_REQUEST['STR_SOPSTATUT'])) {
        $STR_SOPSTATUT = $_REQUEST['STR_SOPSTATUT'];
    }

    if (isset($_REQUEST['LG_PROID'])) {
        $LG_PROID = $_REQUEST['LG_PROID'];
    }

    if (isset($_REQUEST['LG_SUTID'])) {
        $LG_SUTID = $_REQUEST['LG_SUTID'];
    }

    if (isset($_REQUEST['LG_CLIID'])) {
        $LG_CLIID = $_REQUEST['LG_CLIID'];
    }

    if (isset($_REQUEST['STR_SOCSIRET'])) {
        $STR_SOCSIRET = $_REQUEST['STR_SOCSIRET'];
    }

    if (isset($_REQUEST['LG_LSTTYPESOCID'])) {
        $LG_LSTTYPESOCID = $_REQUEST['LG_LSTTYPESOCID'];
    }

    if (isset($_REQUEST['LG_LSTPAYID'])) {
        $LG_LSTPAYID = $_REQUEST['LG_LSTPAYID'];
    }

    if (isset($_REQUEST['STR_SOCCODE'])) {
        $STR_SOCCODE = $_REQUEST['STR_SOCCODE'];
    }

    if (isset($_FILES['STR_SOCLOGO'])) {
        $STR_SOCLOGO = $_FILES['STR_SOCLOGO'];
    }

    if (isset($_REQUEST['STR_AGENAME'])) {
        $STR_AGENAME = $_REQUEST['STR_AGENAME'];
    }

    if (isset($_REQUEST['STR_AGEDESCRIPTION'])) {
        $STR_AGEDESCRIPTION = $_REQUEST['STR_AGEDESCRIPTION'];
    }

    if (isset($_REQUEST['STR_AGELOCALISATION'])) {
        $STR_AGELOCALISATION = $_REQUEST['STR_AGELOCALISATION'];
    }

    if (isset($_REQUEST['STR_AGEPHONE'])) {
        $STR_AGEPHONE = $_REQUEST['STR_AGEPHONE'];
    }

    if (isset($_REQUEST['LG_LSTID'])) {
        $LG_LSTID = $_REQUEST['LG_LSTID'];
    }

    //moi
    if (isset($_REQUEST['STR_UTIFIRSTLASTNAME'])) {
        $STR_UTIFIRSTLASTNAME = $_REQUEST['STR_UTIFIRSTLASTNAME'];
    }

    if (isset($_REQUEST['STR_UTIPHONE'])) {
        $STR_UTIPHONE = $_REQUEST['STR_UTIPHONE'];
    }

    if (isset($_REQUEST['STR_UTISTATUT'])) {
        $STR_UTISTATUT = $_REQUEST['STR_UTISTATUT'];
    }

    if (isset($_REQUEST['STR_UTIMAIL'])) {
        $STR_UTIMAIL = $_REQUEST['STR_UTIMAIL'];
    }

    if (isset($_REQUEST['STR_UTILOGIN'])) {
        $STR_UTILOGIN = $_REQUEST['STR_UTILOGIN'];
    }

    if (isset($_REQUEST['STR_UTIPASSWORD'])) {
        $STR_UTIPASSWORD = $_REQUEST['STR_UTIPASSWORD'];
    }
    //moi
    if (isset($_REQUEST['LG_SOCEXTID'])) {
        $LG_SOCEXTID = $_REQUEST['LG_SOCEXTID'];
    }

    if (isset($_REQUEST['LG_AGEID'])) {
        $LG_AGEID = $_REQUEST['LG_AGEID'];
    }

    if (isset($_REQUEST['LG_PROID'])) {
        $LG_PROID = $_REQUEST['LG_PROID'];
    }
    //

    if (isset($_REQUEST['STR_DOCNAME'])) {
        $STR_DOCNAME = $_REQUEST['STR_DOCNAME'];
    }

    if (isset($_REQUEST['LG_DOCPKEY'])) {
        $LG_DOCPKEY = $_REQUEST['LG_DOCPKEY'];
    }

    if (isset($_FILES['STR_UTIPIC'])) {
        $STR_UTIPIC = $_FILES['STR_UTIPIC'];
    }

    if (isset($_REQUEST['SEARCH_VALUE'])) {
        $SEARCH_VALUE = $_REQUEST['SEARCH_VALUE'];
    }

    if (isset($_REQUEST['STR_SOCSTATUT'])) {
        $STR_SOCSTATUT = $_REQUEST['STR_SOCSTATUT'];
    }

    if(isset($_REQUEST['STR_UTIPIC'])){
        $STR_UTIPIC = $_FILES['STR_UTIPIC'];
    }

    if (isset($_REQUEST['LG_DOCID'])) {
        $LG_DOCID = $_POST['LG_DOCID'];
    }

    if ($mode == "getTypetransaction") {
        $value = $ConfigurationManager->getTypetransaction($LG_TTRID);
        if ($value != null) {
            $arrayJson["TTRNAME"] = $value[0]['STR_TTRNAME'];
            $arrayJson["TTRDESCRIPTION"] = $value[0]['STR_TTRDESCRIPTION'];
        }
    } else if ($mode == "getOperateur") {
        $value = $ConfigurationManager->getOperateur($LG_OPEID);
        if ($value != null) {
            $arrayJson["OPENAME"] = $value[0]['STR_OPENAME'];
            $arrayJson["OPEDESCRIPTION"] = $value[0]['STR_OPEDESCRIPTION'];
            $arrayJson["OPEPIC"] = Parameters::$rootFolderRelative . "logos/" . $value[0]['STR_OPEPIC'];
        }
    } else if ($mode == "getSociete") {
        $value = $ConfigurationManager->getSociete($LG_SOCID);
        if ($value != null) {
            $arrayJson["str_socname"] = $value[0]['str_socname'];
            $arrayJson["str_socdescription"] = $value[0]['str_socdescription'];
            $arrayJson["str_soclogo"] = Parameters::$rootFolderAbsolute . "logos/" . $value[0]['str_soclogo'];
            $arrayJson["dt_soccreated"] = $value[0]['dt_soccreated'];
            $arrayJson["str_socmail"] = $value[0]['str_socmail'];
            $arrayJson["str_socphone"] = $value[0]['str_socphone'];
//            $arrayJson["SOCLASTABONNEMENT"] = ($value[0]['DT_SOCLASTABONNEMENT'] != null ? DateToString($value[0]['DT_SOCLASTABONNEMENT'], 'd/m/Y') : "");
        }
    } else if ($mode == "getProfile") {
        $value = $ConfigurationManager->getProfile($LG_PROID);
        if ($value != null) {
            $arrayJson["str_proname"] = $value[0]['str_proname'];
            $arrayJson["str_prodescription"] = $value[0]['str_prodescription'];
            $arrayJson["str_protype"] = ($value[0]['str_protype'] == Parameters::$type_system ? "Système" : "Standard");
        }
    } else if ($mode == "getSocieteOperateur") {
        $value = $ConfigurationManager->getSocieteOperateurUnique($LG_SOPID);
        if ($value != null) {
            $arrayJson["SOPPHONE"] = $value[0]['STR_SOPPHONE'];
        }
    } else if ($mode == "getClient") {
        $value = $ConfigurationManager->getAgence($LG_CLIID);
        foreach ($value as $k => $v) {
            $arrayJson[$k] = $v;
        }
        /*if ($value != null) {
            $arrayJson = $value;
        }*/
    }//moi
    else if ($mode == 'getDocument') {
        $value = $ConfigurationManager->getDocument($LG_DOCID);
        if ($value != null) {
            $arrayJson = $value[0];
        }
    } //moi

    //moi
    else if ($mode == "getAllUtilisateurs") {
        $value = $ConfigurationManager->getAllUtilisateurs();
        if ($value) {
            $arrayJson = $value;
        }
    } else if ($mode == 'getClientDemandes') {
        $value = $ConfigurationManager->getClientDemandes($STR_SOCSTATUT);
        if ($value != null) {
            $arrayJson[] = $value[0];
        }
    } //moi
    else if ($mode == 'getUtilisateur') {
        $value = $ConfigurationManager->getUtilisateur($LG_UTIID);
        if ($value != null) {
            $arrayJson = $value[0];
        }
    } else if ($mode == "getClientDemande") {
        $value = $ConfigurationManager->getClientDemande($LG_SOCID);
        if ($value) {
            $arrayJson[] = $value[0];
        }
    } //moi
    else if ($mode == "showAllOrOneSociete") {
        $value = $ConfigurationManager->showAllOrOneSociete($SEARCH_VALUE, $STR_SOCSTATUT);
        if ($value) {
            $arrayJson[] = $value[0];
        }
    } else if ($mode == "createSociete") {
        $OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);
        $ConfigurationManager->createSociete($STR_SOCNAME, $STR_SOCDESCRIPTION, $STR_SOCLOGO ?? null, $STR_SOCMAIL, $STR_SOCPHONE, $STR_SOCSIRET, $LG_LSTTYPESOCID, $LG_LSTPAYID, $STR_SOCCODE, $OUtilisateur);
    } else if ($mode == "updateSociete") {
        $OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);
        $ConfigurationManager->updateSociete($LG_SOCID, $LG_SOCEXTID, $STR_SOCDESCRIPTION, $STR_SOCNAME, $STR_SOCLOGO ?? null, $STR_SOCMAIL, $STR_SOCPHONE, $STR_SOCSIRET, $LG_LSTTYPESOCID, $LG_LSTPAYID, $STR_SOCCODE, $OUtilisateur);
    } else if ($mode == "deleteSociete") {
        $OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);
        $ConfigurationManager->deleteSociete($LG_SOCID, $OUtilisateur);
    } else if ($mode == "createSocieteOperateur") {
        $OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);
        $ConfigurationManager->createSocieteOperateur($LG_SOCID, $LG_OPEID, $STR_SOPPHONE, $OUtilisateur);
    } else if ($mode == "updateSocieteOperateur") {
        $OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);
        $ConfigurationManager->updateSocieteOperateur($LG_SOPID, $STR_SOPPHONE, $OUtilisateur);
    } else if ($mode == "deleteSocieteOperateur") {
        $OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);
        $ConfigurationManager->deleteSocieteOperateur($LG_SOPID, $STR_SOPSTATUT, $OUtilisateur);
    } else if ($mode == "createSocieteUtilisateur") {
        $OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);
        $ConfigurationManager->createSocieteUtilisateur($LG_SOCID, $LG_UTIID, $OUtilisateur);
    } else if ($mode == "deleteSocieteUtilisateur") {
        $OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);
        $ConfigurationManager->deleteSocieteUtilisateur($LG_SUTID, $OUtilisateur);
    }//moi
    else if ($mode == "createAgence") {
        $OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);
        $ConfigurationManager->createAgence($STR_AGENAME, $STR_AGEDESCRIPTION, $STR_AGELOCALISATION,
            $STR_AGEPHONE, $LG_SOCID, $OUtilisateur);
    } //moi
    else if ($mode == 'createUtilisateur') {
        $OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);
        $ConfigurationManager->createUtilisateur($STR_UTIFIRSTLASTNAME, $STR_UTIPHONE, $STR_UTIMAIL, $STR_UTILOGIN, $STR_UTIPASSWORD, $LG_AGEID, $STR_UTIPIC ?? null, $LG_PROID, $OUtilisateur);
    } //moi
    else if ($mode == "updateUtilisateur") {
        $OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);
        $ConfigurationManager->updateUtilisateur($LG_UTIID, $STR_UTISTATUT, $STR_UTIFIRSTLASTNAME, $STR_UTIPHONE, $STR_UTIMAIL, $STR_UTILOGIN, $STR_UTIPASSWORD, $LG_AGEID, null, $LG_PROID, $OUtilisateur);
    } //moi
    else if ($mode == "createDocument") {
        $OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);
        $STR_DOCPATH = uploadFile(Parameters::$rootFolderAbsolute . "documents/" . $LG_SOCID . "/", $_FILES['STR_DOCPATH'], false);
        $ConfigurationManager->createDocument($LG_SOCID, $STR_DOCPATH, $LG_LSTID, $OUtilisateur);
    } //moi
    else if ($mode == "createClientExternal") {
        $OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);
        $value = $ConfigurationManager->createClientExternal($LG_SOCID, $OUtilisateur);
        if ($value) {
            $arrayJson[] = $value[0];
        }
    } else if ($mode == "test") {
        $OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);
        $arrayJson[] = $ConfigurationManager->dowloadDocuments([$_FILES['documents'], $_POST['documents']], $LG_SOCID, $OUtilisateur);
    } else if ($mode == "registerClient") {
        $OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);
        $ConfigurationManager->createDemande($STR_SOCNAME, $STR_SOCDESCRIPTION, $STR_SOCLOGO ?? null, $STR_SOCMAIL, $STR_SOCPHONE, $STR_SOCSIRET, $LG_LSTTYPESOCID, $LG_LSTPAYID, $STR_SOCCODE, $STR_UTIFIRSTLASTNAME, $STR_UTIMAIL, $STR_UTILOGIN, $STR_UTIPASSWORD, $STR_UTIPIC ?? null, $STR_UTIPHONE, $LG_PROID, [$_FILES['documents'], $_POST['documents']], $OUtilisateur);
    } //moi
    else if ($mode == "rejectRegistration") {
        $OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);
        $value = $ConfigurationManager->rejectRegistration($LG_SOCID, $OUtilisateur);
        if ($value) {
            $arrayJson[] = $value[0];
        }
    }
    //moi
    else if ($mode == "markProductAsViewed"){
        $ConfigurationManager->markProductAsViewed($LG_PROID, $LG_UTIID);
    }
    //moi
    else if ($mode == "uploadProductPicture") {
        $OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);
        $ConfigurationManager->uploadProductPicture($_FILES['images'], $LG_PROID, $OUtilisateur);
    }


    $arrayJson["code_statut"] = Parameters::$Message;
    $arrayJson["desc_statut"] = Parameters::$Detailmessage;
}

echo json_encode($arrayJson);


