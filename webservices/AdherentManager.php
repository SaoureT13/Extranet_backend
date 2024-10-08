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
$OParams = array();

$search_value = "";
$str_adhpic = Parameters::$default_picture;
$str_tputype = Parameters::$type_activite;
$lg_tpuid = "";
$lg_adhid = "";
$str_adhprofession = "";
$str_phodescription = "";
$targetDir = '../reports/import/images/';


$total = 0;
$start = 0;
$length = 25;

if (isset($_POST['start'])) {
    $start = $_POST['start'];
}

if (isset($_POST['length'])) {
    $length = $_POST['length'];
}

if (isset($_POST['search_value'])) {
    $search_value = $_POST['search_value'];
}

if (isset($_POST['search_value[value]'])) {
    $search_value = $_POST['search_value[value]'];
}

if (isset($_POST['query'])) {
    $search_value = $_POST['query'];
}

if (isset($_POST['lg_adhid'])) {
    $lg_adhid = $_POST['lg_adhid'];
}

if (isset($_POST['lg_pubid'])) {
    $lg_pubid = $_POST['lg_pubid'];
}

if (isset($_POST['lg_tchid'])) {
    $lg_tchid = $_POST['lg_tchid'];
}

if (isset($_POST['str_tputype'])) {
    $str_tputype = $_POST['str_tputype'];
}

if (isset($_POST['lg_adhsendid'])) {
    $lg_adhsendid = $_POST['lg_adhsendid'];
}

if (isset($_POST['lg_adhreceiverid'])) {
    $lg_adhreceiverid = $_POST['lg_adhreceiverid'];
}

$arrayJsonAdherent = array();

$AdherentManager = new AdherentManager();
$ConfigurationManager = new ConfigurationManager();
$OneSignal = new OneSignal();

$mode = $_POST['mode'];

if ($mode == "listAdherent") {
    $listAdherent = $AdherentManager->ShowAllOrOneAdherent($search_value);

    foreach ($listAdherent as $value) {
        $arrayJson_chidren = array();
        $arrayJson_chidren["adhid"] = $value['lg_adhid'];
        $arrayJson_chidren["adhfirstlastname"] = $value['str_adhfirstlastname'];
        $arrayJson_chidren["adhphone"] = $value['str_adhphone'];
        $arrayJson_chidren["adhmail"] = $value['str_adhmail'];
        $arrayJson_chidren["adhlogin"] = $value['str_adhlogin'];
        $arrayJson_chidren["adhpic"] = Parameters::$rootFolderPictureUser . $value['str_adhpic'];
        $arrayJson_chidren["payid"] = $value['str_paydescription'];
        $arrayJson_chidren["adhville"] = $value['str_adhville'];
        $arrayJson_chidren["adhadmin"] = $value['bool_adhadmin'];
        $arrayJson_chidren["adhentree"] = $value['dt_adhentree'];
        $arrayJson_chidren["adhsortie"] = $value['dt_adhsortie'];
        $arrayJson_chidren["adhobtentionbac"] = $value['dt_adhobtentionbac'];
        $arrayJson_chidren["adhsitpro"] = $value['str_adhsitpro'];
        $arrayJson_chidren["adhprofession"] = $value['str_adhprofession'];
        $arrayJson_chidren["adhstatut"] = $value['str_adhstatut'];
        $arrayJsonAdherent[] = $arrayJson_chidren;
    }
    $arrayJson["data"] = $arrayJsonAdherent;
    $arrayJson["recordsTotal"] = count($arrayJsonAdherent);
    $arrayJson["recordsFiltered"] = count($arrayJsonAdherent);
} else if ($mode == "listPublication") {
    $lg_tpuid = "%";
    if (isset($_POST['lg_tpuid']) && isset($_POST['lg_tpuid']) != "") {
        $lg_tpuid = $_POST['lg_tpuid'];
    }

    $listPublication = $AdherentManager->showAllOrOnePublication($search_value, $lg_tpuid);

    foreach ($listPublication as $value) {
        $arrayJson_chidren = array();
        $arrayJson_chidren["lg_pubid"] = $value['lg_pubid'];
        $arrayJson_chidren["str_pubname"] = $value['str_pubname'];
        $arrayJson_chidren["str_pubdescription"] = $value['str_pubdescription'];
        $arrayJson_chidren["str_publieu"] = $value['str_publieu'];
        $arrayJson_chidren["dt_begin"] = DateToString($value['dt_begin'], 'd/m/Y');
        $arrayJson_chidren["dt_end"] = DateToString($value['dt_end'], 'd/m/Y');
        $arrayJson_chidren["str_pubstatut"] = $value['str_pubstatut'];

        $listPhoPublication = $AdherentManager->showAllOrOnePhotopublication($search_value, $value['lg_pubid']);
        foreach ($listPhoPublication as $value) {
            $arrayJson_Photo = array();
            $arrayJson_Photo["lg_ppuid"] = $value['lg_ppuid'];
            $arrayJson_Photo["str_phoname"] = Parameters::$rootFolderPicturePublication . $value['str_phoname'];
            $arrayJson_Photo["str_phodescription"] = $value['str_phodescription'];
            $arrayJson_chidren["photos"] = $arrayJson_Photo;
        }
        $arrayJsonAdherent[] = $arrayJson_chidren;
    }
    $arrayJson["data"] = $arrayJsonAdherent;
    $arrayJson["recordsTotal"] = count($arrayJsonAdherent);
    $arrayJson["recordsFiltered"] = count($arrayJsonAdherent);
} else if ($mode == "listTypepublication") {
    $listTypepublication = $AdherentManager->showAllOrOneTypepublication($search_value, $str_tputype);

    foreach ($listTypepublication as $value) {
        $arrayJson_chidren = array();
        $arrayJson_chidren["lg_tpuid"] = $value['lg_tpuid'];
        $arrayJson_chidren["str_tpuname"] = $value['str_tpuname'];
        $arrayJson_chidren["str_tpudescription"] = $value['str_tpudescription'];
        $arrayJson_chidren["str_tputype"] = $value['str_tputype'];
        $arrayJsonAdherent[] = $arrayJson_chidren;
    }
    $arrayJson["data"] = $arrayJsonAdherent;
    $arrayJson["recordsTotal"] = count($arrayJsonAdherent);
    $arrayJson["recordsFiltered"] = count($arrayJsonAdherent);
} else if ($mode == "listPays") {
    $listPays = $ConfigurationManager->showAllOrOnePays($search_value);

    foreach ($listPays as $value) {
        $arrayJson_chidren = array();
        $arrayJson_chidren["lg_payid"] = $value['lg_payid'];
        $arrayJson_chidren["str_payname"] = $value['str_payname'];
        $arrayJson_chidren["str_paydescription"] = $value['str_paydescription'];
        $arrayJsonAdherent[] = $arrayJson_chidren;
    }
    $arrayJson["data"] = $arrayJsonAdherent;
    $arrayJson["recordsTotal"] = count($arrayJsonAdherent);
    $arrayJson["recordsFiltered"] = count($arrayJsonAdherent);
} else if ($mode == "listTchat") {
    $listTchat = $AdherentManager->showAllOrOneTchat($search_value, $lg_adhsendid, $lg_adhreceiverid);

    foreach ($listTchat as $value) {
        $arrayJson_chidren = array();
        $arrayJson_chidren["lg_tchid"] = $value['lg_tchid'];
        $arrayJson_chidren["str_tchcontent"] = $value['str_tchcontent'];
        $arrayJson_chidren["dt_tchcreated"] = DateToString($value['dt_tchcreated'], 'd/m/Y H:i:s');
        $arrayJson_chidren["lg_adhsendid"] = $value['lg_adhsendid'];
        $arrayJson_chidren["lg_adhreceiverid"] = $value['lg_adhreceiverid'];
        $arrayJsonAdherent[] = $arrayJson_chidren;
    }
    $arrayJson["data"] = $arrayJsonAdherent;
    $arrayJson["recordsTotal"] = count($arrayJsonAdherent);
    $arrayJson["recordsFiltered"] = count($arrayJsonAdherent);
} else if ($mode == "listCanalcommunication") {
    $listCanalcommunication = $AdherentManager->showAllOrOneCanalcommunication($search_value);

    foreach ($listCanalcommunication as $value) {
        $arrayJson_chidren = array();
        $arrayJson_chidren["lg_ccoid"] = $value['lg_ccoid'];
        $arrayJson_chidren["str_cconame"] = $value['str_cconame'];
        $arrayJson_chidren["str_ccodescription"] = $value['str_ccodescription'];
        $arrayJsonAdherent[] = $arrayJson_chidren;
    }
    $arrayJson["data"] = $arrayJsonAdherent;
    $arrayJson["recordsTotal"] = count($arrayJsonAdherent);
    $arrayJson["recordsFiltered"] = count($arrayJsonAdherent);
} else if ($mode == "getSociete") {
    $value = $AdherentManager->getSociete();
    if ($value != null) {
        $arrayJson_chidren = array();
        $arrayJson_chidren["socid"] = $value[0]['lg_socid'];
        $arrayJson_chidren["socname"] = $value[0]['str_socname'];
        $arrayJson_chidren["socdescription"] = $value[0]['str_socdescription'];
        $arrayJson_chidren["socpic"] = Parameters::$rootFolderPictureUser . $value[0]['str_socpic'];
        $arrayJsonAdherent[] = $arrayJson_chidren;
    }
    $arrayJson["data"] = $arrayJsonAdherent;
} else {
    if (isset($_POST['str_adhfirstlastname'])) {
        $str_adhfirstlastname = $_POST['str_adhfirstlastname'];
    }

    if (isset($_POST['str_adhphone'])) {
        $str_adhphone = $_POST['str_adhphone'];
    }

    if (isset($_POST['str_adhmail'])) {
        $str_adhmail = $_POST['str_adhmail'];
    }

    if (isset($_POST['str_adhlogin'])) {
        $str_adhlogin = $_POST['str_adhlogin'];
    }

    if (isset($_POST['str_adhpassword'])) {
        $str_adhpassword = $_POST['str_adhpassword'];
    }

    if (isset($_POST['str_adhstatut'])) {
        $str_adhstatut = $_POST['str_adhstatut'];
    }

    if (isset($_POST['str_pubstatut'])) {
        $str_pubstatut = $_POST['str_pubstatut'];
    }

    if (isset($_POST['str_adhpic'])) {
        $str_adhpic = $_POST['str_adhpic'];
    }

    if (isset($_POST['str_pubname'])) {
        $str_pubname = $_POST['str_pubname'];
    }

    if (isset($_POST['str_pubdescription'])) {
        $str_pubdescription = $_POST['str_pubdescription'];
    }

    if (isset($_POST['lg_tpuid'])) {
        $lg_tpuid = $_POST['lg_tpuid'];
    }

    if (isset($_POST['str_publieu'])) {
        $str_publieu = $_POST['str_publieu'];
    }

    if (isset($_POST['dt_begin'])) {
        $dt_begin = $_POST['dt_begin'];
    }

    if (isset($_POST['dt_end'])) {
        $dt_end = $_POST['dt_end'];
    }

    if (isset($_POST['str_tpuname'])) {
        $str_tpuname = $_POST['str_tpuname'];
    }

    if (isset($_POST['str_tpudescription'])) {
        $str_tpudescription = $_POST['str_tpudescription'];
    }

    if (isset($_POST['str_tchcontent'])) {
        $str_tchcontent = $_POST['str_tchcontent'];
    }

    if (isset($_POST['lg_adhsendid'])) {
        $lg_adhsendid = $_POST['lg_adhsendid'];
    }

    if (isset($_POST['lg_adhreceiverid'])) {
        $lg_adhreceiverid = $_POST['lg_adhreceiverid'];
    }

    if (isset($_POST['lg_payid'])) {
        $lg_payid = $_POST['lg_payid'];
    }

    if (isset($_POST['str_adhville'])) {
        $str_adhville = $_POST['str_adhville'];
    }

    if (isset($_POST['bool_adhadmin'])) {
        $bool_adhadmin = $_POST['bool_adhadmin'];
    }

    if (isset($_POST['dt_adhentree'])) {
        $dt_adhentree = $_POST['dt_adhentree'];
    }

    if (isset($_POST['dt_adhsortie'])) {
        $dt_adhsortie = $_POST['dt_adhsortie'];
    }

    if (isset($_POST['dt_adhobtentionbac'])) {
        $dt_adhobtentionbac = $_POST['dt_adhobtentionbac'];
    }

    if (isset($_POST['str_adhsitpro'])) {
        $str_adhsitpro = $_POST['str_adhsitpro'];
    }

    if (isset($_POST['str_adhprofession'])) {
        $str_adhprofession = $_POST['str_adhprofession'];
    }

    if (isset($_POST['str_adhnationality'])) {
        $str_adhnationality = $_POST['str_adhnationality'];
    }

    if (isset($_POST['lg_socid'])) {
        $lg_socid = $_POST['lg_socid'];
    }

    if (isset($_POST['str_socname'])) {
        $str_socname = $_POST['str_socname'];
    }

    if (isset($_POST['str_socdescription'])) {
        $str_socdescription = $_POST['str_socdescription'];
    }

    if (isset($_POST['lg_adhupdatedid'])) {
        $lg_adhupdatedid = $_POST['lg_adhupdatedid'];
    }

    if (isset($_POST['lg_ccoid'])) {
        $lg_ccoid = $_POST['lg_ccoid'];
    }

    if (isset($_POST['str_phodescription'])) {
        $str_phodescription = $_POST['str_phodescription'];
    }



    if ($mode == "createAdherent") {
        $str_adhpic = Parameters::uploadFile($targetDir . "users");
        $lg_adhid = $AdherentManager->createAdherent($str_adhfirstlastname, $str_adhphone, $str_adhmail, $str_adhlogin, $str_adhpassword, $str_adhpic, $lg_payid, $str_adhville, $bool_adhadmin, $dt_adhentree, $dt_adhsortie, $dt_adhobtentionbac, $str_adhsitpro, $str_adhprofession, $str_adhnationality);
        if ($lg_adhid != "") {
            $AdherentManager->createAdhCanalcommunication($lg_adhid, $lg_ccoid, false);
            $OParams = $ConfigurationManager->getParams(Parameters::$P_ALERT_SOUSCRIPTION);
            $value = $AdherentManager->getAdherent($lg_adhid);
            if ($value != null) {
                $arrayJson_chidren = array();
                $arrayJson_chidren["adhid"] = $value[0]['lg_adhid'];
                $arrayJson_chidren["adhfirstlastname"] = $value[0]['str_adhfirstlastname'];
                $arrayJson_chidren["adhphone"] = $value[0]['str_adhphone'];
                $arrayJson_chidren["adhmail"] = $value[0]['str_adhmail'];
                $arrayJson_chidren["adhlogin"] = $value[0]['str_adhlogin'];
                $arrayJson_chidren["adhpic"] = Parameters::$rootFolderPictureUser . $value[0]['str_adhpic'];
                $arrayJson_chidren["payid"] = $value[0]['lg_payid'];
                $arrayJson_chidren["adhville"] = $value[0]['str_adhville'];
                $arrayJson_chidren["adhadmin"] = $value[0]['bool_adhadmin'];
                $arrayJson_chidren["adhentree"] = $value[0]['dt_adhentree'];
                $arrayJson_chidren["adhsortie"] = $value[0]['dt_adhsortie'];
                $arrayJson_chidren["adhobtentionbac"] = $value[0]['dt_adhobtentionbac'];
                $arrayJson_chidren["adhsitpro"] = $value[0]['str_adhsitpro'];
                $arrayJson_chidren["adhprofession"] = $value[0]['str_adhprofession'];
                $arrayJson_chidren["adhstatut"] = $value[0]['str_adhstatut'];
                $arrayJson_chidren["adhuuid"] = $value[0]['str_adhuuid'];
                $arrayJsonAdherent[] = $arrayJson_chidren;
            }

            $arrayJson["data"] = $arrayJson_chidren;
            $arrayJson["content"] = array("en" => ($OParams != null ? $OParams[0][2] : ""));
            $arrayJson["included_segments"] = array('All');
            $OneSignal->callOneSignal(array('All'), array(), $arrayJson["content"], $arrayJson["data"]);
        }
    } else if ($mode == "updateAdherent") {
        $str_adhpic = Parameters::uploadFile($targetDir . "users");
        $AdherentManager->updateAdherent($lg_adhid, $str_adhfirstlastname, $str_adhphone, $str_adhmail, $str_adhlogin, $str_adhpic, $lg_payid, $str_adhville, $dt_adhentree, $dt_adhsortie, $dt_adhobtentionbac, $str_adhsitpro, $str_adhprofession, $str_adhnationality, $lg_adhupdatedid);
    } else if ($mode == "activateAdherent") {
        $AdherentManager->activateAdherent($lg_adhid, $lg_adhupdatedid, $str_adhstatut);
    } else if ($mode == "createPublication") {
        $lg_pubid = $AdherentManager->createPublication($str_pubname, $str_pubdescription, $lg_adhid, $lg_tpuid, $str_publieu, $dt_begin, $dt_end);
        if ($lg_pubid != null) {
            $str_pic = Parameters::uploadFile($targetDir . "publications");
            if ($str_pic != "") {
                $AdherentManager->createPhotopublication($lg_pubid, $str_pic, $str_pubdescription);
            }
        }
    } else if ($mode == "updatePublication") {
        $AdherentManager->updatePublication($lg_pubid, $str_pubname, $str_pubdescription, $lg_tpuid, $str_publieu, $dt_begin, $dt_end);
    } else if ($mode == "activatePublication") {
        $AdherentManager->activatePublication($lg_pubid, $lg_adhid, $str_pubstatut);
        $value = $AdherentManager->getPublication($lg_pubid);
        if ($value != null) {
            $arrayJson_chidren = array();
            $arrayJson_chidren["lg_pubid"] = $value[0]['lg_pubid'];
            $arrayJson_chidren["str_pubname"] = $value[0]['str_pubname'];
            $arrayJson_chidren["str_pubdescription"] = $value[0]['str_pubdescription'];
            $arrayJson_chidren["str_publieu"] = $value[0]['str_publieu'];
            $arrayJson_chidren["dt_begin"] = DateToString($value[0]['dt_begin'], 'd/m/Y');
            $arrayJson_chidren["dt_end"] = DateToString($value[0]['dt_end'], 'd/m/Y');

            $arrayJsonAdherent[] = $arrayJson_chidren;
            $OneSignal->callOneSignal(array('All'), array(), array("en" => $str_pubdescription), $arrayJson_chidren);
        }
    } else if ($mode == "deletePublication") {
        $AdherentManager->deletePublication($lg_pubid);
    } else if ($mode == "createTypepublication") {
        $AdherentManager->createTypepublication($str_tpuname, $str_tpudescription, $str_tputype);
    } else if ($mode == "updateTypepublication") {
        $AdherentManager->updateTypepublication($lg_tpuid, $str_tpuname, $str_tpudescription, $str_tputype);
    } else if ($mode == "deleteTypepublication") {
        $AdherentManager->deleteTypepublication($lg_tpuid);
    } else if ($mode == "createTchat") {
        $lg_tchid = $AdherentManager->createTchat($str_tchcontent, $lg_adhsendid, $lg_adhreceiverid);
        //echo generate_uuid();
        $arrayJson_chidren = array();
        if ($lg_tchid != null) {
            $lg_adhreceiverid = "f43599de-afb3-4708-9c3a-c045ab65c123";
            $value = $AdherentManager->getTchat($lg_tchid);
            if ($value != null) {
                $arrayJson_chidren["lg_tchid"] = $value[0]['lg_tchid'];
                $arrayJson_chidren["str_tchcontent"] = $value[0]['str_tchcontent'];
                $arrayJson_chidren["dt_tchcreated"] = DateToString($value[0]['dt_tchcreated'], 'd/m/Y');

                $arrayJsonAdherent[] = $arrayJson_chidren;
            }
        }
        $OneSignal->callOneSignal(array(), array($lg_adhreceiverid), array("en" => $str_tchcontent), $arrayJson_chidren);
    } else if ($mode == "deleteTchat") {
        $AdherentManager->deleteTchat($lg_tchid);
    } else if ($mode == "updateSociete") {
        $str_socpic = Parameters::uploadFile($targetDir . "users");
        $ConfigurationManager->updateSociete($lg_socid, $str_socname, $str_socdescription, $str_socpic);
    } else if ($mode == "createPhotopublication") {
        $str_pic = Parameters::uploadFile($targetDir . "publications");
        if ($str_pic != "") {
            $AdherentManager - createPhotopublication($lg_pubid, $str_pic, $str_pubdescription);
        }
    } else if ($mode == "doConnexion") {
        $value = $AdherentManager->doConnexion($str_adhlogin, $str_adhpassword);
        if ($value != null) {
            $arrayJson_chidren = array();
            $arrayJson_chidren["adhid"] = $value[0]['lg_adhid'];
            $arrayJson_chidren["adhfirstlastname"] = $value[0]['str_adhfirstlastname'];
            $arrayJson_chidren["adhphone"] = $value[0]['str_adhphone'];
            $arrayJson_chidren["adhmail"] = $value[0]['str_adhmail'];
            $arrayJson_chidren["adhlogin"] = $value[0]['str_adhlogin'];
            $arrayJson_chidren["adhpic"] = Parameters::$rootFolderPictureUser . $value[0]['str_adhpic'];
            $arrayJson_chidren["payid"] = $value[0]['lg_payid'];
            $arrayJson_chidren["adhville"] = $value[0]['str_adhville'];
            $arrayJson_chidren["adhadmin"] = $value[0]['bool_adhadmin'];
            $arrayJson_chidren["adhentree"] = $value[0]['dt_adhentree'];
            $arrayJson_chidren["adhsortie"] = $value[0]['dt_adhsortie'];
            $arrayJson_chidren["adhobtentionbac"] = $value[0]['dt_adhobtentionbac'];
            $arrayJson_chidren["adhsitpro"] = $value[0]['str_adhsitpro'];
            $arrayJson_chidren["adhprofession"] = $value[0]['str_adhprofession'];
            $arrayJson_chidren["adhstatut"] = $value[0]['str_adhstatut'];
            $arrayJson_chidren["adhuuid"] = $value[0]['str_adhuuid'];

            $arrayJsonAdherent[] = $arrayJson_chidren;
        }
        $arrayJson["data"] = $arrayJsonAdherent;
    } else if ($mode == "doDisConnexion") {
        $AdherentManager->doDisConnexion($lg_adhid);
    } else if ($mode == "testOneSignal") {
        $OneSignal->callOneSignal();
    }
    $arrayJson["code_statut"] = Parameters::$Message;
    $arrayJson["desc_statut"] = Parameters::$Detailmessage;
}

echo json_encode($arrayJson);


