<?php

/**
 * Created by PhpStorm.
 * User: chaar
 * Date: 14/08/2018
 * Time: 11:31
 */
require '../services/scripts/php/core_transaction.php';
include '../services/scripts/php/lib.php';
require '../services/scripts/php/lib/phpqrcode/qrlib.php';


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
$DBL_TICAMOUNT = 5;
$STR_EVEDISPLAYROOM = Parameters::$PROCESS_FAILED;
$STR_EVESTATUTFREE = Parameters::$PROCESS_SUCCESS;
$LG_LSTCATEGORIEPLACEID = "";
$STR_CURRENCY = Parameters::$currencyDev;

$TicketManager = new TicketManager();
$ConfigurationManager = new ConfigurationManager();

$mode = $_REQUEST['mode'];


//$OneSignal = new OneSignal();


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

/* if (isset($_REQUEST['file'])) {
  $file = $_REQUEST['file'];
  } */

$OUtilisateur = $ConfigurationManager->getUtilisateur($STR_UTITOKEN);
//echo "===".$OUtilisateur[0][1];

if ($mode == "listEvenement") {
    $LG_LSTID = "%";
    if (isset($_REQUEST['LG_LSTID']) && $_REQUEST['LG_LSTID'] != "") {
        $LG_LSTID = $_REQUEST['LG_LSTID'];
    }

    $listEvenement = $TicketManager->showAllOrOneEvenement($search_value, $LG_LSTID, $DT_BEGIN, $DT_END, $start, $length);
    $total = $TicketManager->totalEvenement($search_value, $LG_LSTID, $DT_BEGIN, $DT_END);
    foreach ($listEvenement as $value) {
        $arrayJson_chidren = array();
        $arrayJson_chidren["LG_EVEID"] = $value['lg_eveid'];
        $arrayJson_chidren["LG_LSTID"] = $value['lg_lstid'];
        $arrayJson_chidren["STR_EVENAME"] = $value['str_evename'];
        $arrayJson_chidren["STR_EVEDESCRIPTION"] = $value['str_evedescription'];
        $arrayJson_chidren["LG_LSTPLACEID"] = $value['lg_lstplaceid'];

//        $arrayJson_chidren["OPEPIC"] = Parameters::$rootFolderRelative . "logos/" . $value['STR_OPEPIC']; //a decommenter en cas de probleme
//        $arrayJson_chidren["TRACREATED"] = DateToString($value['dt_evebegin'], 'd/m/Y H:i:s'); //a decommenter en cas de probleme
        $arrayJson_chidren["DT_EVEBEGIN"] = DateToString($value['dt_evebegin'], 'd/m/Y');
        $arrayJson_chidren["DT_EVEEND"] = DateToString($value['dt_eveend'], 'd/m/Y');
        $arrayJson_chidren["HR_EVEBEGIN"] = $value['hr_evebegin'];
        $arrayJson_chidren["HR_EVEEND"] = $value['hr_eveend'];
        $arrayJson_chidren["STR_EVEPIC"] = Parameters::$rootFolderRelative . $value['str_evepic'];
        $arrayJson_chidren["STR_EVEBANNER"] = Parameters::$rootFolderRelative . $value['str_evebanner'];
        $arrayJson_chidren["DT_EVECREATED"] = DateToString($value['dt_evecreated'], 'd/m/Y H:i:s');
        $arrayJson_chidren["DT_EVEUPDATED"] = $value['dt_eveupdated'] != null ? DateToString($value['dt_eveupdated'], 'd/m/Y H:i:s') : "";
        $arrayJson_chidren["STR_EVEANNONCEUR"] = $value['str_eveannonceur'];
        $arrayJson_chidren["LG_UTICREATEDID"] = $value['lg_utcreatedid'];
        $arrayJson_chidren["LG_AGEID"] = $value['lg_ageid'];
        $arrayJson_chidren["STR_EVEDISPLAYROOM"] = $value['str_evedisplayroom'];
        $arrayJson_chidren["STR_EVESTATUTFREE"] = $value['str_evestatutfree'];
        $arrayJson_chidren["str_ACTION"] = "<span class='text-primary' title='Consultation de l'evenement " . $value['str_evename'] . "'></span>";
        $OJson[] = $arrayJson_chidren;
    }
    $arrayJson["data"] = $OJson;
    $arrayJson["recordsTotal"] = $total;
    $arrayJson["recordsFiltered"] = $total;
} else if ($mode == "listEvenementFront") {
    $LG_LSTID = "%";
    $totalEvent = 0;
    if (isset($_REQUEST['LG_LSTID']) && $_REQUEST['LG_LSTID'] != "") {
        $LG_LSTID = $_REQUEST['LG_LSTID'];
    }

    $listActivity = $ConfigurationManager->showAllOrOneListe($search_value, Parameters::$typelisteValue[0], "%", "");
    foreach ($listActivity as $activity) {
        $arrayJson_parent = array();
        $arrayJson_parent["LG_LSTID"] = $activity['lg_lstid'];
        $arrayJson_parent["STR_LSTDESCRIPTION"] = $activity['str_lstdescription'];
        $listEvenement = $TicketManager->showAllOrOneEvenementFront($search_value, $activity['lg_lstid'], $DT_BEGIN, $DT_END, $start, $length);
        $totalEvent += count($listEvenement);
        foreach ($listEvenement as $value) {
            $arrayJson_chidren = array();
            $arrayJson_chidren["LG_EVEID"] = $value['lg_eveid'];
            $arrayJson_chidren["LG_LSTID"] = $value['str_lstdescription'];
            $arrayJson_chidren["STR_EVENAME"] = $value['str_evename'];
            $arrayJson_chidren["STR_EVEDESCRIPTION"] = $value['str_evedescription'];
            $arrayJson_chidren["LG_LSTPLACEID"] = $value['lg_lstplaceid'];
            $arrayJson_chidren["DT_EVEBEGIN"] = DateToString($value['dt_evebegin'], 'd/m/Y');
            $arrayJson_chidren["DT_EVEEND"] = DateToString($value['dt_eveend'], 'd/m/Y');
            $arrayJson_chidren["HR_EVEBEGIN"] = $value['hr_evebegin'];
            $arrayJson_chidren["HR_EVEEND"] = $value['hr_eveend'];
            $arrayJson_chidren["STR_EVEPIC"] = Parameters::$rootFolderRelative . $value['str_evepic'];
            $arrayJson_chidren["STR_EVEBANNER"] = Parameters::$rootFolderRelative . $value['str_evebanner'];
            $arrayJson_chidren["DT_EVECREATED"] = DateToString($value['dt_evecreated'], 'd/m/Y H:i:s');
            $arrayJson_chidren["DT_EVEUPDATED"] = $value['dt_eveupdated'] != null ? DateToString($value['dt_eveupdated'], 'd/m/Y H:i:s') : "";
            $arrayJson_chidren["STR_EVEANNONCEUR"] = $value['str_eveannonceur'];
            $arrayJson_chidren["LG_UTICREATEDID"] = $value['lg_utcreatedid'];
            $arrayJson_chidren["LG_AGEID"] = $value['lg_ageid'];
            $arrayJson_chidren["STR_EVEDISPLAYROOM"] = $value['str_evedisplayroom'];
            $arrayJson_chidren["STR_EVESTATUTFREE"] = $value['str_evestatutfree'];
//            $arrayJson_chidren["str_ACTION"] = "<span class='text-primary' title='Consultation de l'evenement " . $value['str_evename'] . "'></span>";
            $OJson[] = $arrayJson_chidren;
        }
        $arrayJson_parent["evenements"] = $OJson;
        $OJsonParent[] = $arrayJson_parent;
        $OJson = array();
    }
    $arrayJson["data"] = $OJsonParent;
    $arrayJson["recordsTotal"] = $totalEvent;
    $arrayJson["recordsFiltered"] = $totalEvent;
} else if ($mode == "listTicket") {
    $LG_LSTID = "%";
    $LG_EVEID = "%";
    $LG_AGEID = "%";
    $LG_CLIID = $OUtilisateur != null ? "%" : "";
    if (isset($_REQUEST['LG_LSTID']) && $_REQUEST['LG_LSTID'] != "") {
        $LG_LSTID = $_REQUEST['LG_LSTID'];
    }

    if (isset($_REQUEST['LG_EVEID']) && $_REQUEST['LG_EVEID'] != "") {
        $LG_EVEID = $_REQUEST['LG_EVEID'];
    }

    if (isset($_REQUEST['LG_REQUESTID']) && $_REQUEST['LG_REQUESTID'] != "") {
        $LG_AGEID = $_REQUEST['LG_REQUESTID'];
    }

    if (isset($_REQUEST['LG_CLIID']) && $_REQUEST['LG_CLIID'] != "") {
        $LG_CLIID = $_REQUEST['LG_CLIID'];
    }

    $listTicket = $TicketManager->showAllOrOneTicket($search_value, $LG_EVEID, $LG_LSTID, $LG_AGEID, $LG_CLIID, $DT_BEGIN, $DT_END, $start, $length);
    $total = $TicketManager->totalTicket($search_value, $LG_EVEID, $LG_LSTID, $LG_AGEID, $LG_CLIID, $DT_BEGIN, $DT_END);
    foreach ($listTicket as $value) {
        $arrayJson_chidren = array();
        $arrayJson_chidren["LG_TICID"] = $value['lg_ticid'];
        $arrayJson_chidren["STR_TICNAME"] = $value['str_ticname'];
        $arrayJson_chidren["STR_TICPHONE"] = $value['str_ticphone'];
        $arrayJson_chidren["STR_TICMAIL"] = $value['str_ticmail'];
        $arrayJson_chidren["STR_TICBARECODE"] = Parameters::$rootFolderRelative . $value['str_ticbarecode'];
        $arrayJson_chidren["DT_TCICREATED"] = DateToString($value['dt_ticcreated'], 'd/m/Y H:i:s');
        $arrayJson_chidren["DT_TCIVALIDATED"] = $value['dt_ticvalidated'] != null ? DateToString($value['dt_ticvalidated'], 'd/m/Y') : "";
        $arrayJson_chidren["STR_EVENAME"] = $value['str_evename'];
        $arrayJson_chidren["STR_EVEDESCRIPTION"] = $value['str_evedescription'];
        $arrayJson_chidren["STR_EVEPIC"] = Parameters::$rootFolderRelative . $value['str_evepic'];
        $arrayJson_chidren["STR_EVEBANNER"] = Parameters::$rootFolderRelative . $value['str_evebanner'];
        $arrayJson_chidren["DT_EVECREATED"] = DateToString($value['dt_evecreated'], 'd/m/Y H:i:s');
        $arrayJson_chidren["DT_EVEUPDATED"] = $value['dt_eveupdated'] != null ? DateToString($value['dt_eveupdated'], 'd/m/Y H:i:s') : "";
        $arrayJson_chidren["STR_EVEANNONCEUR"] = $value['str_eveannonceur'];
        $arrayJson_chidren["LG_LSTPLACEID"] = $value['lg_lstplaceid'];
        $arrayJson_chidren["str_ACTION"] = "<span class='text-primary' title='Consultation de l'evenement " . $value['str_evename'] . "'></span>";
        $OJson[] = $arrayJson_chidren;
    }
    $arrayJson["data"] = $OJson;
    $arrayJson["recordsTotal"] = $total;
    $arrayJson["recordsFiltered"] = $total;
} else {
    if (isset($_REQUEST['LG_LSTID'])) {
        $LG_LSTID = $_REQUEST['LG_LSTID'];
    }

    if (isset($_REQUEST['STR_EVENAME'])) {
        $STR_EVENAME = $_REQUEST['STR_EVENAME'];
    }

    if (isset($_REQUEST['STR_EVEDESCRIPTION'])) {
        $STR_EVEDESCRIPTION = $_REQUEST['STR_EVEDESCRIPTION'];
    }

    if (isset($_REQUEST['LG_LSTPLACEID'])) {
        $LG_LSTPLACEID = $_REQUEST['LG_LSTPLACEID'];
    }

    if (isset($_REQUEST['DT_EVEBEGIN'])) {
        $DT_EVEBEGIN = $_REQUEST['DT_EVEBEGIN'];
    }

    if (isset($_REQUEST['DT_EVEEND'])) {
        $DT_EVEEND = $_REQUEST['DT_EVEEND'];
    }

    if (isset($_REQUEST['HR_EVEBEGIN'])) {
        $HR_EVEBEGIN = $_REQUEST['HR_EVEBEGIN'];
    }

    if (isset($_REQUEST['HR_EVEEND'])) {
        $HR_EVEEND = $_REQUEST['HR_EVEEND'];
    }

    if (isset($_FILES['STR_EVEBANNER'])) {
        $STR_EVEBANNER = $_FILES['STR_EVEBANNER'];
        $STR_EVEBANNER = uploadFile(Parameters::$path_import, $STR_EVEBANNER);
    }

    if (isset($_FILES['STR_EVEPIC'])) {
        $STR_EVEPIC = $_FILES['STR_EVEPIC'];
        $STR_EVEPIC = uploadFile(Parameters::$path_import, $STR_EVEPIC);
    }

    if (isset($_REQUEST['STR_EVEANNONCEUR'])) {
        $STR_EVEANNONCEUR = $_REQUEST['STR_EVEANNONCEUR'];
    }

    if (isset($_REQUEST['LG_EVEID'])) {
        $LG_EVEID = $_REQUEST['LG_EVEID'];
    }

    if (isset($_REQUEST['STR_TICPHONE'])) {
        $STR_TICPHONE = $_REQUEST['STR_TICPHONE'];
    }

    if (isset($_REQUEST['STR_TICMAIL'])) {
        $STR_TICMAIL = $_REQUEST['STR_TICMAIL'];
    }

    if (isset($_REQUEST['DBL_TICAMOUNT'])) {
        $DBL_TICAMOUNT = $_REQUEST['DBL_TICAMOUNT'];
    }

    if (isset($_REQUEST['LG_AGEREQUESTID'])) {
        $LG_AGEID = $_REQUEST['LG_AGEREQUESTID'];
    }

    if (isset($_REQUEST['STR_EVEDISPLAYROOM']) && $_REQUEST['STR_EVEDISPLAYROOM'] != "") {
        $STR_EVEDISPLAYROOM = $_REQUEST['STR_EVEDISPLAYROOM'];
    }

    if (isset($_REQUEST['STR_EVESTATUTFREE']) && $_REQUEST['STR_EVESTATUTFREE'] != "") {
        $STR_EVESTATUTFREE = $_REQUEST['STR_EVESTATUTFREE'];
    }
    
    if (isset($_REQUEST['STR_EVESTATUT'])) {
        $STR_EVESTATUT = $_REQUEST['STR_EVESTATUT'];
    }
    
    if (isset($_REQUEST['STR_PROVIDER'])) {
        $STR_PROVIDER = $_REQUEST['STR_PROVIDER'];
    }
    
    if (isset($_REQUEST['LG_LSTCATEGORIEPLACEID']) && $_REQUEST['LG_LSTCATEGORIEPLACEID'] != "") {
        $LG_LSTCATEGORIEPLACEID = json_decode($_REQUEST['LG_LSTCATEGORIEPLACEID'], true);
    }
    
    if (isset($_REQUEST['STR_CURRENCY']) && $_REQUEST['STR_CURRENCY'] != "") {
        $STR_CURRENCY = $_REQUEST['STR_CURRENCY'];
    }

    if ($mode == "getTicket") {
        $value = $TicketManager->getTicket($LG_TRAID);
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
    } else if ($mode == "getEvenement") {
        $listEvenement = $TicketManager->getEvenement($LG_EVEID);
        foreach ($listEvenement as $value) {
            $arrayJson_chidren = array();
            $arrayJson_chidren["LG_EVEID"] = $value['lg_eveid'];
            $arrayJson_chidren["LG_LSTID"] = $value['lg_lstid'];
            $arrayJson_chidren["STR_EVENAME"] = $value['str_evename'];
            $arrayJson_chidren["STR_EVEDESCRIPTION"] = $value['str_evedescription'];
            $arrayJson_chidren["LG_LSTPLACEID"] = $value['lg_lstplaceid'];
            $arrayJson_chidren["DT_EVEBEGIN"] = DateToString($value['dt_evebegin'], 'd/m/Y');
            $arrayJson_chidren["DT_EVEEND"] = DateToString($value['dt_eveend'], 'd/m/Y');
            $arrayJson_chidren["HR_EVEBEGIN"] = $value['hr_evebegin'];
            $arrayJson_chidren["HR_EVEEND"] = $value['hr_eveend'];
            $arrayJson_chidren["STR_EVEPIC"] = Parameters::$rootFolderRelative . $value['str_evepic'];
            $arrayJson_chidren["STR_EVEBANNER"] = Parameters::$rootFolderRelative . $value['str_evebanner'];
            $arrayJson_chidren["DT_EVECREATED"] = DateToString($value['dt_evecreated'], 'd/m/Y H:i:s');
            $arrayJson_chidren["DT_EVEUPDATED"] = $value['dt_eveupdated'] != null ? DateToString($value['dt_eveupdated'], 'd/m/Y H:i:s') : "";
            $arrayJson_chidren["STR_EVEANNONCEUR"] = $value['str_eveannonceur'];
            $arrayJson_chidren["LG_UTICREATEDID"] = $value['lg_utcreatedid'];
            $arrayJson_chidren["LG_AGEID"] = $value['lg_ageid'];
            $arrayJson_chidren["STR_EVEDISPLAYROOM"] = $value['str_evedisplayroom'];
            $arrayJson_chidren["STR_EVESTATUTFREE"] = $value['str_evestatutfree'];
            //$arrayJson_chidren["str_ACTION"] = "<span class='text-primary' title='Consultation de l'evenement " . $value['str_evename'] . "'></span>";
            $OJson[] = $arrayJson_chidren;
        }
        $arrayJson_parent["evenements"] = $OJson;
        //$OJsonParent[] = $arrayJson_parent;
        //$OJson = array();
        $arrayJson["data"] = $OJson;
    } else if ($mode == "createEvenement") {
        $TicketManager->createEvenement($LG_LSTID, $STR_EVENAME, $STR_EVEDESCRIPTION, $LG_LSTPLACEID, $DT_EVEBEGIN, $DT_EVEEND, $HR_EVEBEGIN, $HR_EVEEND, $STR_EVEPIC, $STR_EVEBANNER, $STR_EVEANNONCEUR, $LG_AGEID, $STR_EVEDISPLAYROOM, $STR_EVESTATUTFREE, $LG_LSTCATEGORIEPLACEID, $OUtilisateur);
    } else if ($mode == "updateEvenement") {
        $TicketManager->updateEvenement($LG_EVEID, $LG_LSTID, $STR_EVENAME, $STR_EVEDESCRIPTION, $LG_LSTPLACEID, $DT_EVEBEGIN, $DT_EVEEND, $HR_EVEBEGIN, $HR_EVEEND, $STR_EVEPIC, $STR_EVEBANNER, $STR_EVEANNONCEUR, $LG_AGEID, $STR_EVEDISPLAYROOM, $STR_EVESTATUTFREE, $LG_LSTCATEGORIEPLACEID, $OUtilisateur);
    } else if ($mode == "deleteEvenement") {
        $TicketManager->deleteEvenement($LG_EVEID, $STR_EVESTATUT, $OUtilisateur);
    } else if ($mode == "createTicket") {        
        $TicketManager->createTicket($LG_EVEID, $LG_LSTID, $STR_TICPHONE, $STR_TICMAIL, $DBL_TICAMOUNT, $STR_CURRENCY, $STR_PROVIDER);
    } else if ($mode == "deleteTicket") {
        $TicketManager->deleteTicket($LG_TRAID, $OUtilisateur);
    } else if ($mode == "uploadfile") {
        //uploadFile(Parameters::$path_import, $STR_EVEPIC);
        // Exemple d'utilisation
        $text = "Bonjour, voici un exemple de QR code généré en PHP !";
        $file_name = Parameters::$path_import . "qr_code_example.png";
        generate_qr_code($text, $file_name);
    }

    $arrayJson["code_statut"] = Parameters::$Message;
    $arrayJson["desc_statut"] = Parameters::$Detailmessage;
}

//$arrayJson["filename"] = $file;

echo json_encode($arrayJson);


