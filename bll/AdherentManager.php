<?php

interface AdherentInterface {

    public function createAdherent($str_adhfirstlastname, $str_adhphone, $str_adhmail, $str_adhlogin, $str_adhpassword, $str_adhpic, $lg_payid, $str_adhville, $bool_adhadmin, $dt_adhentree, $dt_adhsortie, $dt_adhobtentionbac, $str_adhsitpro, $str_adhprofession, $str_adhnationality);

    public function updateAdherent($lg_adhid, $str_adhfirstlastname, $str_adhphone, $str_adhmail, $str_adhlogin, $str_adhpic, $lg_payid, $str_adhville, $dt_adhentree, $dt_adhsortie, $dt_adhobtentionbac, $str_adhsitpro, $str_adhprofession, $str_adhnationality, $lg_adhupdatedid);

    public function activateAdherent($lg_adhid, $lg_adhupdatedid, $str_adhstatut);
    
    public function getAdherent($lg_adhid);

    public function showAllOrOneAdherent($search_value);

    public function doConnexion($str_adhlogin, $str_adhpassword);

    public function doDisConnexion($lg_adhid);

    public function updateTokenUtilisateur($Adherent, $str_adhtoken);

    public function createTypepublication($str_tpuname, $str_tpudescription, $str_tputype);

    public function updateTypepublication($lg_tpuid, $str_tpuname, $str_tpudescription, $str_tputype);

    public function deleteTypepublication($lg_tpuid);

    public function showAllOrOneTypepublication($search_value, $str_tputype);

    public function createPublication($str_pubname, $str_pubdescription, $lg_adhid, $lg_tpuid, $str_publieu, $dt_begin, $dt_end);

    public function updatePublication($lg_pubid, $str_pubname, $str_pubdescription, $lg_adhid, $lg_tpuid, $str_publieu, $dt_begin, $dt_end);
 
    public function activatePublication($lg_pubid, $lg_adhid, $str_pubstatut);
    
    public function getPublication($lg_pubid);
    
    public function deletePublication($lg_pubid);

    public function showAllOrOnePublication($search_value, $lg_tpuid);

    public function createPhoto($lg_phoid, $str_phoname, $str_phodescription);

    public function deletePhoto($lg_phoid);
    
    public function createPhotopublication($lg_pubid, $str_phoname, $str_phodescription);

    public function showAllOrOnePhotopublication($search_value, $lg_pubid);

    public function createTchat($str_tchcontent, $lg_adhsendid, $lg_adhreceiverid);
    
    public function getTchat($lg_tchid);

    public function deleteTchat($lg_tchid);

    public function showAllOrOneTchat($search_value, $lg_adhsendid, $lg_adhreceiverid);

    public function showAllOrOneCanalcommunication($search_value);

    public function createAdhCanalcommunication($lg_adhid, $lg_ccoid, $displayMessage);
}

class AdherentManager implements AdherentInterface {

    private $Adherent = 'adherent';
    private $OAdherent = array();
    private $Typepublication = 'typepublication';
    private $OTypepublication = array();
    private $Publication = 'publication';
    private $OPublication = array();
    private $Photo = 'photo';
    private $PhoPublication = 'phopublication';
    private $Tchat = 'tchat';
    private $OTchat = array();
    private $Pays = 'pays';
    private $OPays = array();
    private $Canalcommunication = 'canalcommunication';
    private $OCanalcommunication = array();
    private $AdhCanalcommunication = 'adhcanalcommunication';
    private $dbconnnexion;

    //constructeur de la classe 
    public function __construct() {
        $this->dbconnnexion = DoConnexionPDO(Parameters::$host, Parameters::$user, Parameters::$pass, Parameters::$db, Parameters::$port);
    }

    //gestion des adhérents
    //creation d'un adhérent
    public function createAdherent($str_adhfirstlastname, $str_adhphone, $str_adhmail, $str_adhlogin, $str_adhpassword, $str_adhpic, $lg_payid, $str_adhville, $bool_adhadmin, $dt_adhentree, $dt_adhsortie, $dt_adhobtentionbac, $str_adhsitpro, $str_adhprofession, $str_adhnationality) {
        $validation = "";
        $lg_adhid = generateRandomString(20);

        try {
            $params_condition = array("lg_payid" => $lg_payid, "str_paydescription" => $lg_payid);
            $this->OPays = Find($this->Pays, $params_condition, $this->dbconnnexion, "OR");

            if ($this->OPays == null) {
                Parameters::buildErrorMessage("Pays inexistant. Vérifiez votre sélection");
                return $validation;
            }

            $params = array("lg_adhid" => $lg_adhid, "str_adhfirstlastname" => $str_adhfirstlastname, "str_adhphone" => $str_adhphone, "str_adhmail" => $str_adhmail, "str_adhlogin" => $str_adhlogin,
                "str_adhpassword" => sha1($str_adhpassword), "str_adhstatut" => Parameters::$statut_process, "str_adhpic" => $str_adhpic, "lg_adhcreatedid" => $lg_adhid, "dt_adhcreated" => get_now(),
                "lg_payid" => $this->OPays[0][0], "str_adhville" => $str_adhville, "bool_adhadmin" => $bool_adhadmin, "dt_adhentree" => $dt_adhentree, "dt_adhsortie" => $dt_adhsortie,
                "dt_adhobtentionbac" => $dt_adhobtentionbac, "str_adhsitpro" => $str_adhsitpro, "str_adhprofession" => $str_adhprofession, "str_adhnationality" => $str_adhnationality, "str_adhuuid" => generate_uuid());
            
            if ($this->dbconnnexion != null) {
                if (Persist($this->Adherent, $params, $this->dbconnnexion)) {
                    $validation = $lg_adhid;
                    Parameters::buildSuccessMessage("Pré-inscription de " . $str_adhfirstlastname . " effectuée avec succès. Vous recevrez une notification après la validation");
                } else {
                    Parameters::buildErrorMessage("Echec d'enregistrement de l'adhérent");
                }
            }
        } catch (Exception $exc) {
            $exc->getTraceAsString();
            Parameters::buildErrorMessage("Echec d'enregistrement de l'adhérent. Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    //fin creation d'un adhérent
    //update d'un adhérent
    public function updateAdherent($lg_adhid, $str_adhfirstlastname, $str_adhphone, $str_adhmail, $str_adhlogin, $str_adhpic, $lg_payid, $str_adhville, $dt_adhentree, $dt_adhsortie, $dt_adhobtentionbac, $str_adhsitpro, $str_adhprofession, $str_adhnationality, $lg_adhupdatedid) {
        $validation = false;

        try {
            $params_condition = array("lg_adhid" => $lg_adhid);
            $this->OAdherent = Find($this->Adherent, $params_condition, $this->dbconnnexion);

            if ($this->OAdherent == null) {
                Parameters::buildErrorMessage("Echec de mise à jour. Adhérent inexistant");
                return $validation;
            }

            $params_condition = array("lg_payid" => $lg_payid, "str_paydescription" => $lg_payid);
            $this->OPays = Find($this->Pays, $params_condition, $this->dbconnnexion, "OR");

            if ($this->OPays == null) {
                Parameters::buildErrorMessage("Pays inexistant. Vérifiez votre sélection");
                return $validation;
            }


            $params_condition = array("lg_adhid" => $lg_adhid);
            $params_to_update = array("str_adhfirstlastname" => $str_adhfirstlastname, "str_adhphone" => $str_adhphone, "str_adhmail" => $str_adhmail, "str_adhlogin" => $str_adhlogin,
                "str_adhpic" => $str_adhpic, "dt_adhupdated" => get_now(), "lg_adhupdatedid" => $lg_adhupdatedid,
                "lg_payid" => $this->OPays[0][0], "str_adhville" => $str_adhville, "dt_adhentree" => $dt_adhentree, "dt_adhsortie" => $dt_adhsortie,
                "dt_adhobtentionbac" => $dt_adhobtentionbac, "str_adhsitpro" => $str_adhsitpro, "str_adhprofession" => $str_adhprofession, "str_adhnationality" => $str_adhnationality);


            if ($this->dbconnnexion != null) {
                if (Merge($this->Adherent, $params_to_update, $params_condition, $this->dbconnnexion)) {
                    $validation = true;
                    Parameters::buildSuccessMessage("Adhérent " . $str_adhfirstlastname . " mis à jour avec succès");
                } else {
                    Parameters::buildErrorMessage("Echec de mise à jour de l'adhérent");
                }
            }
        } catch (Exception $exc) {
            $exc->getTraceAsString();
            Parameters::buildErrorMessage("Echec de mise à jour de l'adhérent. Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    //fin updade d'un adhérent
    //activation ou désactivation d'un adhérent
    public function activateAdherent($lg_adhid, $lg_adhupdatedid, $str_adhstatut) {
        $validation = false;

        try {
            $params_condition = array("lg_adhid" => $lg_adhid);
            $this->OAdherent = Find($this->Adherent, $params_condition, $this->dbconnnexion);

            if ($this->OAdherent == null) {
                Parameters::buildErrorMessage("Adhérent inexistant. Vérifiez votre sélection");
                return $validation;
            }

            $params_condition = array("lg_adhid" => $this->OAdherent[0][0]);
            $params_to_update = array("str_adhstatut" => $str_adhstatut, "lg_adhupdatedid" => $lg_adhupdatedid, "dt_adhupdated" => get_now());

            if (Merge($this->Adherent, $params_to_update, $params_condition, $this->dbconnnexion)) {
                $validation = true;
                Parameters::buildSuccessMessage(($str_adhstatut == Parameters::$statut_enable ? "Activation " : "Désactivation ") . "de " . $this->OAdherent[0][3] . " effectuée avec succès");
            } else {
                Parameters::buildErrorMessage("Echec " . ($str_adhstatut == Parameters::$statut_enable ? "d'activation " : "de désactivation ") . " de l'adhérent");
            }
        } catch (Exception $exc) {
            $exc->getTraceAsString();
            Parameters::buildErrorMessage("Echec de l'opération. Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    //fin activation ou désactivation d'un adhérent
    
    //recherche d'un adhérent
    public function getAdherent($lg_adhid) {
        $validation = null;
        $params_condition = array("lg_adhid" => $lg_adhid);
        Parameters::buildErrorMessage("Adhérent inexistant");
        try {
            $this->OAdherent = Find($this->Adherent, $params_condition, $this->dbconnnexion);
            if($this->OAdherent != null) {
                $validation = $this->OAdherent;
                Parameters::buildSuccessMessage("Adhérent " . $validation[0][3] . " trouvé");
            }
        } catch (Exception $ex) {
            $exc->getTraceAsString();
        }
        return $validation;
    }
    //fin recherche d'un adhérent
    
    //liste des adhérents
    public function showAllOrOneAdherent($search_value) {
        $arraySql = array();
        try {
            $query = "SELECT t.*, p.str_paydescription FROM " . $this->Adherent . " t, " . $this->Pays . " p WHERE t.lg_payid = p.lg_payid and (t.str_adhfirstlastname LIKE :search_value OR t.str_adhphone LIKE :search_value OR t.str_adhlogin LIKE :search_value) AND t.str_adhstatut NOT LIKE :str_adhstatut ORDER BY t.str_adhfirstlastname";
            $res = $this->dbconnnexion->prepare($query);
            //exécution de la requête
            $res->execute(array('search_value' => $search_value . "%", 'str_adhstatut' => Parameters::$statut_delete));
            while ($rowObj = $res->fetch()) {
                $arraySql[] = $rowObj;
            }
            $res->closeCursor();
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $arraySql;
    }

    //fin liste des adhérents
    //connexion d'un adhérent
    public function doConnexion($str_adhlogin, $str_adhpassword) {
        $validation = array();
        Parameters::buildErrorMessage("Echec de connexion. Identifiant ou mot de passe incorrecte");
        try {
            $params_condition = array('str_adhlogin' => $str_adhlogin, 'str_adhpassword' => sha1($str_adhpassword), 'str_adhstatut' => Parameters::$statut_enable);
            $this->OAdherent = Find($this->Adherent, $params_condition, $this->dbconnnexion);

            if ($this->OAdherent == null) {
                return $validation;
            }
            $this->updateTokenUtilisateur($this->OAdherent, generateRandomString(20));
            Parameters::buildSuccessMessage("Bienvenu " . $this->OAdherent[0][1]);
            $validation = $this->OAdherent;
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $validation;
    }

    //fin connexion d'un adhérent
    //déconnexion d'un adhérent
    public function doDisConnexion($lg_adhid) {
        $validation = false;
        Parameters::buildErrorMessage("Echec de déconnexion. Veuillez réessayer svp!");
        try {
            $params_condition = array('lg_adhid' => $lg_adhid);
            $this->OAdherent = Find($this->Adherent, $params_condition, $this->dbconnnexion);

            if ($this->OAdherent == null) {
                return $validation;
            }
            $this->updateTokenUtilisateur($this->OAdherent, "");
            Parameters::buildSuccessMessage("Déconnexion de " . $this->OAdherent[0][1] . " effectuée avec succès");
            $validation = true;
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $validation;
    }

    //fin déconnexion d'un adhérent

    public function updateTokenUtilisateur($Adherent, $str_adhtoken) {
        $validation = false;
        try {
            $params_condition = array("lg_adhid" => $this->OAdherent[0][0]);
            $params_to_update = array("str_adhtoken" => $str_adhtoken, "dt_adhlastconnected" => get_now());

            if (Merge($this->Adherent, $params_to_update, $params_condition, $this->dbconnnexion)) {
                $validation = true;
            }
        } catch (Exception $ex) {
            $exc->getTraceAsString();
        }
    }

    //gestion des types de publications
    public function createTypepublication($str_tpuname, $str_tpudescription, $str_tputype) {
        $validation = false;
        $lg_tpuid = generateRandomString(20);

        $params = array("lg_tpuid" => $lg_tpuid, "str_tpuname" => $str_tpuname, "str_tpudescription" => $str_tpudescription,
            "str_tputype" => $str_tputype, "str_tpustatut" => Parameters::$statut_enable, "dt_tpucreated" => get_now());
        try {

            if ($this->dbconnnexion != null) {
                if (Persist($this->Typepublication, $params, $this->dbconnnexion)) {
                    $validation = true;
                    Parameters::buildSuccessMessage("Type de publication " . $str_tpudescription . " créé avec succès.");
                } else {
                    Parameters::buildErrorMessage("Echec d'enregistrement du type de publication");
                }
            }
        } catch (Exception $exc) {
            $exc->getTraceAsString();
            Parameters::buildErrorMessage("Echec d'enregistrement du type de publication. Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    public function updateTypepublication($lg_tpuid, $str_tpuname, $str_tpudescription, $str_tputype) {
        $validation = false;
        try {
            $params_condition = array("lg_tpuid" => $lg_tpuid);
            $params_to_update = array("str_tpuname" => $str_tpuname, "str_tpudescription" => $str_tpudescription,
                "str_tputype" => $str_tputype, "lg_utiupdatedid" => $lg_utiupdatedid, "str_tpustatut" => $str_tpustatut, "dt_tpuupdated" => get_now());

            if (Merge($this->Typepublication, $params_to_update, $params_condition, $this->dbconnnexion)) {
                $validation = true;
                Parameters::buildSuccessMessage("Type de publication " . $str_tpudescription . " mis à jour avec succès");
            } else {
                Parameters::buildErrorMessage("Echec de mise à jour du type de publication");
            }
        } catch (Exception $exc) {
            $exc->getTraceAsString();
            Parameters::buildErrorMessage("Echec de mise à jour du type de publication. Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    public function deleteTypepublication($lg_tpuid) {
        $validation = false;
        try {
            $params_condition = array("lg_tpuid" => $lg_tpuid);
            $params_to_update = array("lg_utiupdatedid" => $lg_utiupdatedid, "str_tpustatut" => Parameters::$statut_delete, "dt_tpuupdated" => get_now());

            if (Merge($this->Typepublication, $params_to_update, $params_condition, $this->dbconnnexion)) {
                $validation = true;
                Parameters::buildSuccessMessage("Type de publicication supprimé avec succès");
            } else {
                Parameters::buildErrorMessage("Echec de suppression du type de publication sélectionné");
            }
        } catch (Exception $exc) {
            $exc->getTraceAsString();
            Parameters::buildErrorMessage("Echec de suppression du type de publication. Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    public function showAllOrOneTypepublication($search_value, $str_tputype) {
        $arraySql = array();
        try {
            $query = "SELECT * FROM " . $this->Typepublication . " t WHERE (t.str_tpuname LIKE :search_value OR t.str_tpudescription LIKE :search_value) AND t.str_tpustatut = :str_tpustatut AND t.str_tputype LIKE :str_tputype ORDER BY t.str_tpudescription";
            $res = $this->dbconnnexion->prepare($query);
            //exécution de la requête
            $res->execute(array('search_value' => $search_value . "%", 'str_tpustatut' => Parameters::$statut_enable, 'str_tputype' => $str_tputype));
            while ($rowObj = $res->fetch()) {
                $arraySql[] = $rowObj;
            }
            $res->closeCursor();
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $arraySql;
    }

    //fin gestion des types de publications
    //gestion des publications
    public function createPublication($str_pubname, $str_pubdescription, $lg_adhid, $lg_tpuid, $str_publieu, $dt_begin, $dt_end) {
        $validation = "";

        try {
            $lg_pubid = generateRandomString(20);
            $params_condition = array("lg_tpuid" => $lg_tpuid, "str_tpuname" => $lg_tpuid);
            $this->OTypepublication = Find($this->Typepublication, $params_condition, $this->dbconnnexion, "OR");

            if ($this->OTypepublication == null) {
                Parameters::buildErrorMessage("Type de publication inexistante. Vérifiez votre sélection");
                return $validation;
            }

            $params = array("lg_pubid" => $lg_pubid, "str_pubname" => $str_pubname, "str_pubdescription" => $str_pubdescription,
                "lg_tpuid" => $this->OTypepublication[0][0], "str_publieu" => $str_publieu, "dt_begin" => $dt_begin, "dt_end" => $dt_end, "str_pubstatut" => Parameters::$statut_process,
                "dt_pubcreated" => get_now(), "lg_adhid" => $lg_adhid);

            if ($this->dbconnnexion != null) {
             //   $validation = $lg_pubid;
                if (Persist($this->Publication, $params, $this->dbconnnexion)) {
                    $validation = $lg_pubid;
                    Parameters::buildSuccessMessage("Publication " . $str_pubname . " créée avec succès.");
                } else {
                    Parameters::buildErrorMessage("Echec d'enregistrement de publication");
                }
            }
        } catch (Exception $exc) {
            $exc->getTraceAsString();
            Parameters::buildErrorMessage("Echec d'enregistrement de publication. Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    public function updatePublication($lg_pubid, $str_pubname, $str_pubdescription, $lg_adhid, $lg_tpuid, $str_publieu, $dt_begin, $dt_end) {
        $validation = false;
        try {
            $params_condition = array('lg_pubid' => $lg_pubid);
            $this->OPublication = Find($this->Publication, $params_condition, $this->dbconnnexion);

            if ($this->OPublication == null) {
                Parameters::buildErrorMessage("Echec de la mise à jour de la publication. Référence inexistante");
                return $validation;
            }

            $params_condition = array("lg_tpuid" => $lg_tpuid);
            $this->OTypepublication = Find($this->Typepublication, $params_condition, $this->dbconnnexion);

            if ($this->OTypepublication == null) {
                Parameters::buildErrorMessage("Echec de la mise à jour de la publication. Type de publication inexistante");
                return $validation;
            }

            $params_condition = array("lg_pubid" => $this->OPublication[0][0]);
            $params_to_update = array("str_pubname" => $str_pubname, "str_pubdescription" => $str_pubdescription, "lg_adhupdatedid" => $lg_adhid,
                "lg_tpuid" => $this->OTypepublication[0][0], "str_publieu" => $str_publieu, "dt_begin" => $dt_begin, "dt_end" => $dt_end,
                "dt_pubupdated" => get_now());

            if (Merge($this->Publication, $params_to_update, $params_condition, $this->dbconnnexion)) {
                $validation = true;
                Parameters::buildSuccessMessage("Publication " . $str_pubname . " mise à jour avec succès");
            } else {
                Parameters::buildErrorMessage("Echec de mise à jour de publication. Veuillez réessayer svp.");
            }
        } catch (Exception $exc) {
            $exc->getTraceAsString();
            Parameters::buildErrorMessage("Echec de mise à jour de publication. Veuillez contacter votre administrateur");
        }
        return $validation;
    }
    
     public function activatePublication($lg_pubid, $lg_adhid, $str_pubstatut) {
        $validation = false;

        try {
            $params_condition = array("lg_pubid" => $lg_pubid);
            $this->OPublication = Find($this->Publication, $params_condition, $this->dbconnnexion);

            if ($this->OPublication == null) {
                Parameters::buildErrorMessage("Publication inexistante. Vérifiez votre sélection");
                return $validation;
            }
            
            $params_condition = array("lg_pubid" => $this->OPublication[0][0]);
            $params_to_update = array("str_pubstatut" => $str_pubstatut, "lg_adhupdatedid" => $lg_adhid, "dt_pubupdated" => get_now());
            
            if (Merge($this->Publication, $params_to_update, $params_condition, $this->dbconnnexion)) {
                $validation = true;
                Parameters::buildSuccessMessage(($str_pubstatut == Parameters::$statut_enable ? "Activation " : "Désactivation ") . "de " . $this->OPublication[0][1] . " effectuée avec succès");
            } else {
                Parameters::buildErrorMessage("Echec " . ($str_pubstatut == Parameters::$statut_enable ? "d'activation " : "de désactivation ") . " de la publication");
            }
        } catch (Exception $exc) {
            $exc->getTraceAsString();
            Parameters::buildErrorMessage("Echec de l'opération. Veuillez contacter votre administrateur");
        }
        return $validation;
    }
    
    public function getPublication($lg_pubid) {
        $validation = null;
        $params_condition = array("lg_pubid" => $lg_pubid);
        Parameters::buildErrorMessage("Publication inexistante");
        try {
            $this->OPublication = Find($this->Publication, $params_condition, $this->dbconnnexion);
            if($this->OPublication != null) {
                $validation = $this->OPublication;
                Parameters::buildSuccessMessage("Publication " . $validation[0][2] . " trouvée");
            }
        } catch (Exception $ex) {
            $exc->getTraceAsString();
        }
        return $validation;
    }
    //fin recherche d'un adhérent

    public function deletePublication($lg_pubid) {
        $validation = false;
        try {
            $params_condition = array('lg_pubid' => $lg_pubid);
            $this->OPublication = Find($this->Publication, $params_condition, $this->dbconnnexion);

            if ($this->OPublication == null) {
                Parameters::buildErrorMessage("Echec de suppression de la publication. Référence inexistante");
                return $validation;
            }
            $params_condition = array('lg_pubid' => $lg_pubid);
            $params_to_update = array("str_pubstatut" => Parameters::$statut_delete, "dt_pubupdated" => get_now());

            if (Merge($this->Publication, $params_to_update, $params_condition, $this->dbconnnexion)) {
                $validation = true;
                Parameters::buildSuccessMessage("Publicication supprimée avec succès");
            } else {
                Parameters::buildErrorMessage("Echec de suppression de publication sélectionnée");
            }
        } catch (Exception $exc) {
            $exc->getTraceAsString();
            Parameters::buildErrorMessage("Echec de suppression de publication. Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    public function showAllOrOnePublication($search_value, $lg_tpuid) {
        $arraySql = array();
        try {
            $query = "SELECT * FROM " . $this->Publication . " t WHERE (t.str_pubname LIKE :search_value OR t.str_pubdescription LIKE :search_value OR t.str_publieu LIKE :search_value) AND t.lg_tpuid LIKE :lg_tpuid AND t.str_pubstatut NOT LIKE :str_pubstatut ORDER BY t.dt_begin desc";
            $res = $this->dbconnnexion->prepare($query);
            //exécution de la requête
            $res->execute(array('search_value' => $search_value . "%", 'lg_tpuid' => $lg_tpuid, 'str_pubstatut' => Parameters::$statut_delete));
            while ($rowObj = $res->fetch()) {
                $arraySql[] = $rowObj;
            }
            $res->closeCursor();
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $arraySql;
    }

    //fin gestion des publications
    //gestion des photos des publications
    public function createPhoto($lg_phoid, $str_phoname, $str_phodescription) {
        $validation = "";

        try {
            //$lg_phoid = generateRandomString(20);
            $params = array("lg_phoid" => $lg_phoid, "str_phoname" => $str_phoname, "str_phodescription" => $str_phodescription,
                "str_phostatut" => Parameters::$statut_enable, "dt_phocreated" => get_now());

            if ($this->dbconnnexion != null) {
                if (Persist($this->Photo, $params, $this->dbconnnexion)) {
                    $validation = $lg_phoid;
                   // Parameters::buildSuccessMessage("Photo ajoutée avec succès.");
                } else {
                    Parameters::buildErrorMessage("Echec d'ajout de la photo à la publication");
                }
            }
        } catch (Exception $exc) {
            $exc->getTraceAsString();
            Parameters::buildErrorMessage("Echec d'ajout de la photo à la publication. Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    public function deletePhoto($lg_phoid) {
        $validation = false;
        try {
            $params = array('lg_phoid' => $lg_phoid);

            if (Remove($this->Photo, $params, $dbconnexion)) {
                $validation = true;
                Parameters::buildSuccessMessage("Photo supprimée avec succès");
            } else {
                Parameters::buildErrorMessage("Echec de suppression de la photo");
            }
        } catch (Exception $exc) {
            $exc->getTraceAsString();
            Parameters::buildErrorMessage("Echec de suppression de la photo. Veuillez contacter votre administrateur");
        }
        return $validation;
    }
    
    public function createPhotopublication($lg_pubid, $str_phoname, $str_phodescription = null) {
        $validation = "";
        $lg_phoid = "";
        $lg_ppuid = "";
        try {
            $params_condition = array('lg_pubid' => $lg_pubid);
            $this->OPublication = Find($this->Publication, $params_condition, $this->dbconnnexion);

            if ($this->OPublication == null) {
                Parameters::buildErrorMessage("Echec d'ajout de photo à la publication. Référence inexistante");
                return $validation;
            }
            $lg_phoid = $this->createPhoto(generateRandomString(20), $str_phoname, ($str_phodescription == null || $str_phodescription == "" ? $str_phoname : $str_phodescription));
            if ($lg_phoid == "") {
                Parameters::buildErrorMessage("Echec d'ajout de photo à la publication. Photo non chargé");
                return $validation;
            }
            
            $params = array("lg_ppuid" => generateRandomString(20), "lg_phoid" => $lg_phoid, 'lg_pubid' => $this->OPublication[0][0],
                "str_ppustatut" => Parameters::$statut_enable, "dt_ppucreated" => get_now());

            if ($this->dbconnnexion != null) {
                if (Persist($this->PhoPublication, $params, $this->dbconnnexion)) {
                    $validation = $lg_phoid;
                    //Parameters::buildSuccessMessage("Photo ajoutée avec succès.");
                } else {
                    Parameters::buildErrorMessage("Echec d'ajout de la photo à la publication");
                }
            }
        } catch (Exception $exc) {
            $exc->getTraceAsString();
            Parameters::buildErrorMessage("Echec d'ajout de la photo à la publication. Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    public function showAllOrOnePhotopublication($search_value, $lg_pubid) {
        $arraySql = array();
        try {
            $query = "SELECT t.lg_ppuid, p.str_pubname, p.str_pubdescription, p.str_publieu, ph.str_phoname, ph.str_phodescription FROM " . $this->PhoPublication . " t, " . $this->Publication . " p, " . $this->Photo . " ph WHERE t.lg_pubid = p.lg_pubid AND t.lg_phoid = ph.lg_phoid AND (p.str_pubname LIKE :search_value OR p.str_pubdescription LIKE :search_value) AND t.lg_pubid LIKE :lg_pubid AND p.str_pubstatut = :str_pubstatut ORDER BY t.dt_ppucreated";
            $res = $this->dbconnnexion->prepare($query);
            //exécution de la requête
            $res->execute(array('search_value' => $search_value . "%", 'lg_pubid' => $lg_pubid, 'str_pubstatut' => Parameters::$statut_enable));
            while ($rowObj = $res->fetch()) {
                $arraySql[] = $rowObj;
            }
            $res->closeCursor();
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $arraySql;
    }

    //gestion du tchat
    public function createTchat($str_tchcontent, $lg_adhsendid, $lg_adhreceiverid) {
        $validation = "";
        try {
            $lg_tchid = generateRandomString(20);
            $params = array("lg_tchid" => $lg_tchid, "str_tchcontent" => $str_tchcontent, "lg_adhsendid" => $lg_adhsendid,
                "lg_adhreceiverid" => $lg_adhreceiverid, "str_tchstatut" => Parameters::$statut_enable, "dt_tchcreated" => get_now());

            if ($this->dbconnnexion != null) {
                if (Persist($this->Tchat, $params, $this->dbconnnexion)) {
                    $validation = $lg_tchid;
                    Parameters::buildSuccessMessage("Opération effectuée avec succès.");
                } else {
                    Parameters::buildErrorMessage("Echec de l'opération");
                }
            }
        } catch (Exception $exc) {
            $exc->getTraceAsString();
            Parameters::buildErrorMessage("Echec de l'opération. Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    public function deleteTchat($lg_tchid) {
        $validation = false;
        try {
            $params_condition = array('lg_tchid' => $lg_tchid);
            $this->OTchat = Find($this->Tchat, $params_condition, $this->dbconnnexion);

            if ($this->OTchat == null) {
                Parameters::buildErrorMessage("Echec de suppression de la ligne. Référence inexistante");
                return $validation;
            }

            $params_condition = array("lg_tchid" => $this->OTchat[0][0]);
            $params_to_update = array("str_tchstatut" => Parameters::$statut_delete, "dt_tchupdated" => get_now());

            if (Merge($this->Tchat, $params_to_update, $params_condition, $this->dbconnnexion)) {
                $validation = true;
                Parameters::buildSuccessMessage("Ligne de tchat supprimée avec succès");
            } else {
                Parameters::buildErrorMessage("Echec de suppression de la ligne de tchat. Veuillez réessayer svp.");
            }
        } catch (Exception $exc) {
            $exc->getTraceAsString();
            Parameters::buildErrorMessage("Echec de suppression de la ligne de tchat. Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    public function showAllOrOneTchat($search_value, $lg_adhsendid, $lg_adhreceiverid) {
        $arraySql = array();
        try {
            $query = "SELECT * FROM tchat t WHERE t.str_tchcontent LIKE :search_value AND (t.lg_adhsendid LIKE :lg_adhsendid OR t.lg_adhreceiverid LIKE :lg_adhsendid) AND (t.lg_adhreceiverid LIKE :lg_adhreceiverid OR t.lg_adhsendid LIKE :lg_adhreceiverid) AND t.str_tchstatut = :str_tchstatut ORDER BY t.dt_tchcreated DESC";
            $res = $this->dbconnnexion->prepare($query);
            //exécution de la requête
            $res->execute(array('search_value' => "%" . $search_value . "%", 'lg_adhsendid' => $lg_adhsendid, 'lg_adhreceiverid' => $lg_adhreceiverid, 'str_tchstatut' => Parameters::$statut_enable));
            while ($rowObj = $res->fetch()) {
                $arraySql[] = $rowObj;
            }
            $res->closeCursor();
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $arraySql;
    }

    //fin gestion du tchat

    
    public function showAllOrOneCanalcommunication($search_value) {
        $arraySql = array();
        try {
            $query = "SELECT * FROM " . $this->Canalcommunication . " t WHERE (t.str_cconame LIKE :search_value OR t.str_ccodescription LIKE :search_value) AND t.str_ccostatut = :str_ccostatut ORDER BY t.str_ccodescription";
            $res = $this->dbconnnexion->prepare($query);
            //exécution de la requête
            $res->execute(array('search_value' => $search_value . "%", 'str_ccostatut' => Parameters::$statut_enable));
            while ($rowObj = $res->fetch()) {
                $arraySql[] = $rowObj;
            }
            $res->closeCursor();
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $arraySql;
    }

    public function createAdhCanalcommunication($lg_adhid, $lg_ccoid, $displayMessage) {
        $validation = false;
        $lg_accid = generateRandomString(20);
        try {
            $params_condition = array("lg_ccoid" => $lg_ccoid, "str_ccodescription" => $lg_ccoid);
            $this->OCanalcommunication = Find($this->Canalcommunication, $params_condition, $this->dbconnnexion, "OR");

            if ($this->OCanalcommunication == null) {
                Parameters::buildErrorMessage("Canal de communication inexistant. Vérifiez votre sélection");
                return $validation;
            }
            $params = array("lg_accid" => $lg_accid, "lg_adhid" => $lg_adhid, "lg_ccoid" => $this->OCanalcommunication[0][0],
                "str_accstatut" => Parameters::$statut_enable, "dt_acccreated" => get_now());

            if ($this->dbconnnexion != null) {
                if (Persist($this->AdhCanalcommunication, $params, $this->dbconnnexion)) {
                    $validation = true;
                    if ($displayMessage) {
                        Parameters::buildSuccessMessage("Opération effectuée avec succès.");
                    }
                } else {
                    Parameters::buildErrorMessage("Echec de prise en compte du canal de communication de l'adhérent.");
                }
            }
        } catch (Exception $exc) {
            $exc->getTraceAsString();
            Parameters::buildErrorMessage("Echec de prise en compte du canal de communication de l'adhérent. Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    public function getTchat($lg_tchid) {
        $validation = null;
        $params_condition = array("lg_tchid" => $lg_tchid);
        try {
            $this->OTchat = Find($this->Tchat, $params_condition, $this->dbconnnexion);
            if($this->OTchat != null) {
                $validation = $this->OTchat;
            }
        } catch (Exception $ex) {
            $exc->getTraceAsString();
        }
        return $validation;
    }

   
}
