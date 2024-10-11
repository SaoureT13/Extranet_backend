<?php

interface ConfigurationInterface
{

    //code ajouté
    public function doConnexion($STR_UTILOGIN, $STR_UTIPASSWORD, $IS_ADMIN = 0);

    public function doDisConnexion($STR_UTITOKEN);

    public function updateTokenUtilisateur($OUtilisateur, $STR_UTITOKEN);

    public function getUtilisateur($LG_UTIID);

    public function getProfile($LG_PROID);

    public function showAllOrOneProfile($search_value, $STR_PROTYPE);

    public function getProfilePrivilege($LG_PROID, $LG_PRIID);

    public function getTypetransaction($LG_TTRID);

    public function showAllOrOneTypetransaction($search_value);

    public function getOperateur($LG_OPEID);

    public function showAllOrOneOperateur($search_value);

    //Moi
    public function createSociete($STR_SOCNAME, $STR_SOCDESCRIPTION, $STR_SOCLOGO, $STR_SOCMAIL, $STR_SOCPHONE, $STR_SOCSIRET, $LG_LSTTYPESOCID, $LG_LSTPAYID, $STR_SOCCODE, $OUtilisateur);

    public function updateSociete($LG_SOCID, $LG_SOCEXTID, $STR_SOCSTATUT, $STR_SOCDESCRIPTION, $STR_SOCNAME, $STR_SOCLOGO, $STR_SOCMAIL, $STR_SOCPHONE, $STR_SOCSIRET, $LG_LSTTYPESOCID, $LG_LSTPAYID, $STR_SOCCODE, $OUtilisateur);

    public function getSociete($LG_SOCID);

    public function deleteSociete($LG_SOCID, $OUtilisateur);

    public function showAllOrOneSociete($search_value, $statut);

    public function showAllOrOneSocieteLimit($search_value, $start, $limit);

    public function totalSociete($search_value);

    public function createSocieteOperateur($LG_SOCID, $LG_OPEID, $STR_SOPPHONE, $OUtilisateur);

    public function updateSocieteOperateur($LG_SOPID, $STR_SOPPHONE, $OUtilisateur);

    public function getSocieteOperateur($LG_SOCID, $LG_OPEID);

    public function getSocieteOperateurUnique($LG_SOPID);

    public function deleteSocieteOperateur($LG_SOPID, $STR_SOPSTATUT, $OUtilisateur);

    public function showAllOrOneSocieteOperateur($search_value, $LG_SOCID, $LG_OPEID);

    public function createSocieteUtilisateur($LG_SOCID, $LG_UTIID, $OUtilisateur);

    public function getSocieteUtilisateur($LG_SOCID, $LG_UTIID);

    public function deleteSocieteUtilisateur($LG_SUTID, $OUtilisateur);

    public function showAllOrOneSocieteUtilisateur($search_value, $LG_SOCID, $LG_UTIID);

    public function showAllOrOneSocieteUtilisateurLimit($search_value, $LG_SOCID, $LG_UTIID, $start, $limit);

    public function totalSocieteUtilisateur($search_value, $LG_SOCID, $LG_UTIID);

    public function generateToken();

    public function getClient($LG_CLIID, $token = null);

    public function getAgence($LG_AGEID);
    //fin code ajouté

    //moi
    public function getListe($LG_LSTID);

    //Moi
    public function createAgence($STR_AGENAME, $STR_AGEDESCRIPTION, $STR_AGELOCALISATION,
                                 $STR_AGEPHONE, $LG_SOCID, $OUtilisateur);

    //moi
    public function getTrueAgence($LG_AGEID);

    //moi
    public function createUtilisateur($STR_UTIFIRSTLASTNAME, $STR_UTIPHONE, $STR_UTIMAIL, $STR_UTILOGIN, $STR_UTIPASSWORD, $LG_AGEID, $STR_UTIPIC, $LG_PROID, $OUtilisateur);

    public function updateUtilisateur($LG_UTIID, $STR_UTISTATUT, $STR_UTIFIRSTLASTNAME, $STR_UTIPHONE, $STR_UTIMAIL, $STR_UTILOGIN, $STR_UTIPASSWORD, $LG_AGEID, $STR_UTIPIC, $LG_PROID, $OUtilisateur);

    //moi
    public function createDocument($P_KEY, $STR_DOCPATH, $LG_LSTID, $OUtilisateur);

    //moi
    public function getDocument($LG_DOCID);

    //moi
    public function getClientDemandes($statut);

    //moi
    public function createClientExternal($LG_SOCID, $OUtilisateur);

    //moi
    public function createDemande($STR_SOCNAME, $STR_SOCDESCRIPTION, $STR_SOCLOGO = null, $STR_SOCMAIL, $STR_SOCPHONE, $STR_SOCSIRET, $LG_LSTTYPESOCID, $LG_LSTPAYID, $STR_SOCCODE,
                                  $STR_UTIFIRSTLASTNAME, $STR_UTIMAIL, $STR_UTILOGIN, $STR_UTIPASSWORD, $STR_UTIPIC = null, $STR_UTIPHONE, $LG_PROID, $DOCUMENTS, $OUtilisateur);

    //moi
    public function dowloadDocuments($Documents, $LG_SOCID, $OUtilisateur);

    //moi
    public function getClientDemande($LG_SOCID);

    //moi
    public function rejectRegistration($LG_SOCID, $OUtilisateur);

    public function getAllUtilisateurs();

    //moi
    public function markProductAsViewed($LG_PROID, $LG_UTIID);

    //moi
    public function uploadProductPicture($PICTURES, $SUBSTITUTION_PRODUCTS, $LG_PROID, $OUtilisateur);

    //moi
    public function createProduitSubstitution($LG_PROPARENTID, $LG_PROKIDID, $OUtilisateur);

    public function deleteProduitSubstitution($LG_PROSUBID);

    public function getProduitSubstitution($LG_PROSUBID);

    public function deleteProductImage($LG_PROID);

}

class ConfigurationManager implements ConfigurationInterface
{

    private $Typetransaction = 'TYPETRANSACTION';
    private $OTypetransaction = array();
    private $Operateur = 'OPERATEUR';
    private $OOperateur = array();
    private $Societe = 'societe';
    private $OSociete = array();
    private $Utilisateur = 'UTILISATEUR';
    private $OUtilisateur = array();
    private $SocieteOperateur = 'SOCIETE_OPERATEUR';
    private $OSocieteOperateur = array();
    private $SocieteUtilisateur = 'SOCIETE_UTILISATEUR';
    private $OSocieteUtilisateur = array();
    private $ProfilePrivilege = 'profile_privilege';
    private $OProfilePrivilege = array();
    private $Profile = 'profile';
    private $OAgence = array();
    private $Agence = 'agence';
    private $OProfile = array();

    private $dbconnnexion;

    //constructeur de la classe
    private $Liste = "LISTE";
    private $OListe = array();

    private $Document = "DOCUMENT";
    private $ODocument = array();
    private $ODemandes = array();

    private $Piste_audit = "PISTE_AUDIT";
    private $Produit = "produit";

    private $ProduitSubstitution = "produit_substitution";
    private $OProduitSubstitution = array();

    public function __construct()
    {
        $this->dbconnnexion = DoConnexionPDO(Parameters::$host, Parameters::$user, Parameters::$pass, Parameters::$db, Parameters::$port);
    }

    public function createProduitSubstitution($LG_PROPARENTID, $LG_PROKIDID, $OUtilisateur): string
    {
        $validation = "";
        $LG_PROSUBID = generateRandomString(20);
        try {

            $params = array("lg_prosubid" => $LG_PROSUBID, "lg_proparentid" => $LG_PROPARENTID, "lg_prokidid" => $LG_PROKIDID, "dt_prosubcreated" => get_now(), "str_prosubstatut" => Parameters::$statut_enable, "lg_uticreatedid" => $OUtilisateur ? $OUtilisateur[0]['lg_utiid'] : Parameters::$defaultAdminId);

            if ($this->dbconnnexion != null) {
                if (Persist($this->ProduitSubstitution, $params, $this->dbconnnexion)) {
                    $validation = $params['lg_prosubid'];
                    Parameters::buildSuccessMessage("Produit de substitution lié avec succès");
                } else {
                    Parameters::buildErrorMessage("Echec de l'opération");
                }
            }
        } catch (Exception $exc) {
            error_log($exc->getTraceAsString());
            Parameters::buildErrorMessage("Echec de l'opération du document . Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    //connexion d'un utilisateur
    public function doConnexion($STR_UTILOGIN, $STR_UTIPASSWORD, $IS_ADMIN = 0)
    {
        $validation = array();
        $Object = null;
        $STR_UTITOKEN = generateRandomString(20);
        if ($IS_ADMIN == '1') {
            Parameters::buildErrorMessage("Echec de connexion. Identifiant ou mot de passe incorrecte de l'administrateur");
        } else {
            Parameters::buildErrorMessage("Echec de connexion. Identifiant ou mot de passe incorrecte");
        }

        try {
            if ($IS_ADMIN == '1') {
                $query = "SELECT t.*, p.str_prodescription, p.str_protype, s.lg_socid, s.str_socname, s.str_socdescription, s.str_soclogo, s.dbl_socplafond, s.lg_socextid  
                        FROM " . $this->Utilisateur . " t
                        JOIN " . $this->Profile . " p ON t.lg_proid = p.lg_proid
                        JOIN " . $this->Agence . " a ON a.lg_ageid = t.lg_ageid
                        JOIN " . $this->Societe . " s ON s.lg_socid = a.lg_socid
                        WHERE t.str_utilogin = :STR_UTILOGIN AND 
                            t.str_utipassword = :STR_UTIPASSWORD AND 
                            t.str_utistatut = :STR_UTISTATUT AND
                            s.lg_socid = :LG_SOCID AND
                            a.lg_ageid = :LG_AGEID AND
                            p.lg_proid = :LG_PROID
                            LIMIT 1";
                $res = $this->dbconnnexion->prepare($query);
                //exécution de la requête
                $res->execute(array('STR_UTILOGIN' => $STR_UTILOGIN, 'STR_UTIPASSWORD' => $STR_UTIPASSWORD, 'STR_UTISTATUT' => Parameters::$statut_enable, 'LG_SOCID' => Parameters::$SN_PROVECI_ID, 'LG_AGEID' => Parameters::$SN_PROVECI_ID, 'LG_PROID' => Parameters::$admin_profileID));
            } else {
                $query = "SELECT t.*, p.str_prodescription, p.str_protype, s.lg_socid, s.str_socname, s.str_socdescription, s.str_soclogo, s.dbl_socplafond, s.lg_socextid FROM " . $this->Utilisateur . " t INNER JOIN " . $this->Profile . " p ON t.lg_proid = p.lg_proid INNER JOIN " . $this->Agence . " a ON a.lg_ageid = t.lg_ageid INNER JOIN " . $this->Societe . " s ON s.lg_socid = a.lg_socid 
            WHERE t.str_utilogin = :STR_UTILOGIN AND 
                t.str_utipassword = :STR_UTIPASSWORD AND 
                t.STR_UTISTATUT = :STR_UTISTATUT
                ";
                $res = $this->dbconnnexion->prepare($query);
                $res->execute(array('STR_UTILOGIN' => $STR_UTILOGIN, 'STR_UTIPASSWORD' => $STR_UTIPASSWORD,
                    'STR_UTISTATUT' => Parameters::$statut_enable));
            }
            while ($rowObj = $res->fetch()) {
                $Object[] = $rowObj;
            }

            $this->OUtilisateur = $Object;

            if ($this->OUtilisateur == null) {
                return $validation;
            }

            $this->updateTokenUtilisateur($this->OUtilisateur, $STR_UTITOKEN);
            if ($IS_ADMIN == '1') {
                $this->OUtilisateur[0]["admin"] = true;
            }
            Parameters::buildSuccessMessage("Bienvenu " . $this->OUtilisateur[0]['str_utifirstlastname']);
            $this->OUtilisateur[0]['str_utitoken'] = $STR_UTITOKEN;
            $validation = $this->OUtilisateur;
        } catch (Exception $exc) {
            error_log($exc->getTraceAsString());
        }
        return $validation;
    }

    public function getProduitSubstitution($LG_PROSUBID)
    {
        $validation = null;
        Parameters::buildErrorMessage("Produit de substitution inexistant");
        try {
            $params_condition = array("lg_prosubid" => $LG_PROSUBID);
            $validation = $this->OProduitSubstitution = Find($this->ProduitSubstitution, $params_condition, $this->dbconnnexion);
            if ($this->OProduitSubstitution == null) {
                return $validation;
            }
            Parameters::buildSuccessMessage("Produit de substitution " . $this->OProduitSubstitution[0]['lg_prosubid'] . " trouvé");
            $validation = $this->OProduitSubstitution;
        } catch (Exception $exc) {
            error_log($exc->getTraceAsString());
        }

        return $validation;
    }

    //fin connexion d'un utilisateur
    //déconnexion d'un utilisateur
    public function doDisConnexion($STR_UTITOKEN)
    {
        $validation = false;
        Parameters::buildErrorMessage("Echec de déconnexion. Veuillez réessayer svp!");
        try {
            $params_condition = array('STR_UTITOKEN' => $STR_UTITOKEN);
            $this->OUtilisateur = Find($this->Utilisateur, $params_condition, $this->dbconnnexion);

            if ($this->OUtilisateur == null) {
                return $validation;
            }
            $this->updateTokenUtilisateur($this->OUtilisateur, "");
            Parameters::buildSuccessMessage("Déconnexion de " . $this->OUtilisateur[0]['str_utifirstlastname'] . " effectuée avec succès");
            $validation = true;
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $validation;
    }

    //fin déconnexion d'un utilisateur
    //mise à jour de token de l'utilisateur
    public function updateTokenUtilisateur($OUtilisateur, $STR_UTITOKEN)
    {
        $validation = false;
        try {
            $params_condition = array("lg_utiid" => $OUtilisateur[0]['lg_utiid']);
            $params_to_update = array("str_utitoken" => $STR_UTITOKEN, "dt_utilastconnected" => get_now());

            if (Merge($this->Utilisateur, $params_to_update, $params_condition, $this->dbconnnexion)) {
                $validation = true;
            }
        } catch (Exception $exc) {
            error_log($exc->getTraceAsString());
        }

        return $validation;
    }

    //recherche d'un utilisateur
    public function getUtilisateur($LG_UTIID)
    {
        $validation = null;
        Parameters::buildErrorMessage("Utilisateur inexistant");
        try {
            $params_condition = array("lg_utiid" => $LG_UTIID, "str_utitoken" => $LG_UTIID);
            $validation = $this->OUtilisateur = Find($this->Utilisateur, $params_condition, $this->dbconnnexion, "OR");
            if ($this->OUtilisateur == null) {
                return $validation;
            }
            Parameters::buildSuccessMessage("Utilisateur " . $this->OUtilisateur[0]['str_utifirstlastname'] . " trouvé");
            $validation = $this->OUtilisateur;
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $validation;
    }

    public function getAllUtilisateurs()
    {
        $validation = null;
        Parameters::buildErrorMessage("Aucun utilisateur trouvé");
        try {
            $query = "SELECT * FROM " . $this->Utilisateur . " WHERE str_utistatut != :str_utistatut";
            $this->OUtilisateur = Finds($query, $this->dbconnnexion, array('str_utistatut' => Parameters::$statut_delete));
            if ($this->OUtilisateur == null) {
                return $validation;
            }
            Parameters::buildSuccessMessage("Utilisateurs trouvés");
            $validation = $this->OUtilisateur;
        } catch (Exception $exc) {
            error_log($exc->getTraceAsString());
        }
        return $validation;
    }

    //fin gestion des utilisateurs
    //gestion des profils
    //recherche de profil
    public function getProfile($LG_PROID)
    {
        $validation = null;
        Parameters::buildErrorMessage("Profil inexistant");
        try {
            $params_condition = array("LG_PROID" => $LG_PROID, "STR_PRODESCRIPTION" => $LG_PROID);
            $validation = $this->OProfile = Find($this->Profile, $params_condition, $this->dbconnnexion, "OR");

            if ($this->OProfile == null) {
                return $validation;
            }
            Parameters::buildSuccessMessage("Profil " . $this->OProfile[0]['lg_proid'] . " trouvé");
            $validation = $this->OProfile;
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $validation;
    }

    //liste de profils
    public function showAllOrOneProfile($search_value, $STR_PROTYPE)
    {
        $arraySql = array();
        try {
            $query = "SELECT * FROM " . $this->Profile . " t WHERE (t.STR_PRONAME LIKE :search_value OR t.STR_PRODESCRIPTION LIKE :search_value) AND t.STR_PROTYPE LIKE :STR_PROTYPE AND t.STR_PROSTATUT = :STR_STATUT ORDER BY t.STR_PRODESCRIPTION";
            $res = $this->dbconnnexion->prepare($query);
            //exécution de la requête
            $res->execute(array('search_value' => $search_value . "%", 'STR_PROTYPE' => $STR_PROTYPE, 'STR_STATUT' => Parameters::$statut_enable));
            while ($rowObj = $res->fetch()) {
                $arraySql[] = $rowObj;
            }
            $res->closeCursor();
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $arraySql;
    }

    //fin gestion des profils
    //gestion des privileges
    public function getProfilePrivilege($LG_PROID, $LG_PRIID)
    {
        $validation = null;
        try {
            $params_condition = array("LG_PROID" => $LG_PROID, "LG_PRIID" => $LG_PRIID);
            $validation = $this->OProfilePrivilege = Find($this->ProfilePrivilege, $params_condition, $this->dbconnnexion);

            if ($this->OProfilePrivilege == null) {
                return $validation;
            }
            $validation = $this->OProfilePrivilege;
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $validation;
    }

    //fin gestion des privileges
    //Gestion des types de transactions
    public function getTypetransaction($LG_TTRID)
    {
        $validation = null;
        Parameters::buildErrorMessage("Type de transaction inexistante");
        try {
            $params_condition = array("LG_TTRID" => $LG_TTRID, "STR_TTRDESCRIPTION" => $LG_TTRID);
            $validation = $this->OTypetransaction = Find($this->Typetransaction, $params_condition, $this->dbconnnexion, "OR");

            if ($this->OTypetransaction == null) {
                return $validation;
            }
            Parameters::buildSuccessMessage("Type de transaction " . $this->OTypetransaction[0][2] . " trouvé");
            $validation = $this->OTypetransaction;
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $validation;
    }

    public function showAllOrOneTypetransaction($search_value)
    {
        $arraySql = array();
        try {
            $query = "SELECT * FROM " . $this->Typetransaction . " t WHERE (t.STR_TTRNAME LIKE :search_value OR t.STR_TTRDESCRIPTION LIKE :search_value) AND t.STR_TTRSTATUT = :STR_STATUT ORDER BY t.STR_TTRDESCRIPTION";
            $res = $this->dbconnnexion->prepare($query);
            //exécution de la requête
            $res->execute(array('search_value' => $search_value . "%", 'STR_STATUT' => Parameters::$statut_enable));
            while ($rowObj = $res->fetch()) {
                $arraySql[] = $rowObj;
            }
            $res->closeCursor();
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $arraySql;
    }

    //fin gestion des types de transaction
    //gestion des opérateurs
    public function getOperateur($LG_OPEID)
    {
        $validation = null;
        Parameters::buildErrorMessage("Opérateur inexistant");
        try {
            $params_condition = array("LG_OPEID" => $LG_OPEID, "STR_OPEDESCRIPTION" => $LG_OPEID);
            $validation = $this->OOperateur = Find($this->Operateur, $params_condition, $this->dbconnnexion, "OR");

            if ($this->OOperateur == null) {
                return $validation;
            }
            Parameters::buildSuccessMessage("Opérateur " . $this->OOperateur[0][2] . " trouvé");
            $validation = $this->OOperateur;
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $validation;
    }

    public function showAllOrOneOperateur($search_value)
    {
        $arraySql = array();
        try {
            $query = "SELECT * FROM " . $this->Operateur . " t WHERE (t.STR_OPENAME LIKE :search_value OR t.STR_OPEDESCRIPTION LIKE :search_value) AND t.STR_OPESTATUT = :STR_STATUT ORDER BY t.STR_OPEDESCRIPTION";
            $res = $this->dbconnnexion->prepare($query);
            //exécution de la requête
            $res->execute(array('search_value' => $search_value . "%", 'STR_STATUT' => Parameters::$statut_enable));
            while ($rowObj = $res->fetch()) {
                $arraySql[] = $rowObj;
            }
            $res->closeCursor();
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $arraySql;
    }

    //fin gestion des opérateurs
    //gestion des sociétés
    //creation d'une société
    //moi
    public function createSociete($STR_SOCNAME, $STR_SOCDESCRIPTION, $STR_SOCLOGO, $STR_SOCMAIL, $STR_SOCPHONE, $STR_SOCSIRET, $LG_LSTTYPESOCID, $LG_LSTPAYID, $STR_SOCCODE, $OUtilisateur): string
    {
        $validation = "";
        $LG_SOCID = generateRandomString(20);
        $LG_LSTTYPESOCID = $this->getListe($LG_LSTTYPESOCID);

        if ($LG_LSTTYPESOCID == null) {
            Parameters::buildErrorMessage("Type société inexistant");
            return "";
        }
        $LG_LSTPAYID = $this->getListe($LG_LSTPAYID);
        if ($LG_LSTPAYID == null) {
            Parameters::buildErrorMessage("Id du pays de facturation inexistant");
            return "";
        }
        try {
            if ($STR_SOCLOGO != null) {
//                $STR_SOCLOGO = uploadFile(Parameters::$rootFolderAbsolute . "logos/" . $LG_SOCID . "/", $_FILES['STR_SOCLOGO']);
                $STR_SOCLOGO = uploadFile(Parameters::$rootFolderAbsolute . "logos/", $_FILES['STR_SOCLOGO']);
            }
            $params = array("lg_socid" => $LG_SOCID, "str_socname" => $STR_SOCNAME, "str_socdescription" => $STR_SOCDESCRIPTION, "str_soclogo" => $STR_SOCLOGO, "str_soccode" => $STR_SOCCODE,
                "str_socstatut" => Parameters::$statut_process, "str_socmail" => $STR_SOCMAIL, "str_socphone" => $STR_SOCPHONE, "dt_soccreated" => get_now(),
                "lg_uticreatedid" => $OUtilisateur ? $OUtilisateur[0]['lg_utiid'] : Parameters::$defaultAdminId, "str_socsiret" => $STR_SOCSIRET, "lg_lsttypesocid" => $LG_LSTTYPESOCID[0]['lg_lstid'], "lg_lstpayid" => $LG_LSTPAYID[0]['lg_lstid']);

            if ($this->dbconnnexion != null) {//
                if (Persist($this->Societe, $params, $this->dbconnnexion)) {
                    $validation = $LG_SOCID;
                    Parameters::buildSuccessMessage("Société " . $STR_SOCDESCRIPTION . " effectuée avec succès.");
                } else {
                    Parameters::buildErrorMessage("Echec de création de la société");
                }
            }
        } catch (Exception $exc) {
            error_log($exc->getTraceAsString());
            Parameters::buildErrorMessage("Echec de création de la société. Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    //fin creation d'une société
    //mise à jour d'une société
    public function updateSociete($LG_SOCID, $LG_SOCEXTID, $STR_SOCSTATUT, $STR_SOCDESCRIPTION, $STR_SOCNAME, $STR_SOCLOGO, $STR_SOCMAIL, $STR_SOCPHONE, $STR_SOCSIRET, $LG_LSTTYPESOCID, $LG_LSTPAYID, $STR_SOCCODE, $OUtilisateur = null)
    {
        $validation = false;
        $LG_LSTTYPESOCID = $this->getListe($LG_LSTTYPESOCID);
        if ($LG_LSTTYPESOCID == null) {
            Parameters::buildErrorMessage("Type société inexistant");
            return "";
        }
        $LG_LSTPAYID = $this->getListe($LG_LSTPAYID);
        if ($LG_LSTPAYID == null) {
            Parameters::buildErrorMessage("Id du pays de facturation inexistant");
            return "";
        }
        try {
            $this->OSociete = $this->getSociete($LG_SOCID);

            if ($this->OSociete == null) {
                Parameters::buildErrorMessage("Echec de mise à jour. Société inexistante");
                return $validation;
            }

            $params_condition = array("lg_socid" => $this->OSociete[0]['lg_socid']);
            if ($STR_SOCLOGO) {
                $STR_SOCLOGO = uploadFile(Parameters::$rootFolderAbsolute . "logos/" . $LG_SOCID . "/", $_FILES['STR_SOCLOGO']);
            }
            $params_to_update = array("str_socname" => $STR_SOCNAME, "str_socdescription" => $STR_SOCDESCRIPTION, "lg_socextid" => $LG_SOCEXTID, "str_socstatut" => $STR_SOCSTATUT, "str_soclogo" => (!$STR_SOCLOGO ? $this->OSociete[0]["str_soclogo"] : $STR_SOCLOGO),
                "str_socmail" => $STR_SOCMAIL, "str_socphone" => $STR_SOCPHONE, "dt_socupdated" => get_now(),
                "lg_utiupdatedid" => $OUtilisateur ? $OUtilisateur[0]['lg_utiid'] : Parameters::$defaultAdminId, "lg_lsttypesocid" => $LG_LSTTYPESOCID[0]['lg_lstid'], "lg_lstpayid" => $LG_LSTPAYID[0]['lg_lstid'], 'str_soccode' => $STR_SOCCODE, 'str_socsiret' => $STR_SOCSIRET);

            if ($this->dbconnnexion != null) {
                if (Merge($this->Societe, $params_to_update, $params_condition, $this->dbconnnexion)) {
                    $validation = true;
                    Parameters::buildSuccessMessage("Société " . $STR_SOCDESCRIPTION . " mise à jour avec succès");
                } else {
                    Parameters::buildErrorMessage("Echec de mise à jour de la société");
                }
            }
        } catch (Exception $exc) {
            $exc->getTraceAsString();
            Parameters::buildErrorMessage("Echec de mise à jour de la société. Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    //fin mise à jour de société
    //suppression de société
    public function deleteSociete($LG_SOCID, $OUtilisateur)
    {
        $validation = false;
        try {
            $this->OSociete = $this->getSociete($LG_SOCID);

            if ($this->OSociete == null) {
                Parameters::buildErrorMessage("Echec de suppression. Société inexistante");
                return $validation;
            }

            $params_condition = array("LG_SOCID" => $this->OSociete[0][0]);
            $params_to_update = array("STR_SOCSTATUT" => Parameters::$statut_delete, "DT_SOCUPDATED" => get_now(), "LG_UTIUPDATEDID" => $OUtilisateur[0][0]);

            if ($this->dbconnnexion != null) {
                if (Merge($this->Societe, $params_to_update, $params_condition, $this->dbconnnexion)) {
                    $validation = true;
                    Parameters::buildSuccessMessage("Société " . $this->OSociete[0][2] . " supprimée avec succès");
                } else {
                    Parameters::buildErrorMessage("Echec de suppression de la société");
                }
            }
        } catch (Exception $exc) {
            $exc->getTraceAsString();
            Parameters::buildErrorMessage("Echec de suppression de la société. Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    //fin suppression de société
    //recherche de société
    public function getSociete($LG_SOCID)
    {
        $validation = null;
        Parameters::buildErrorMessage("Société inexistante");
        try {
            $params_condition = array("lg_socid" => $LG_SOCID, "str_socdescription" => $LG_SOCID);
            $validation = $this->OSociete = Find($this->Societe, $params_condition, $this->dbconnnexion, "OR");

            if ($this->OSociete == null) {
                return $validation;
            }
            Parameters::buildSuccessMessage("Société " . $this->OSociete[0]["lg_socid"] . " trouvée");
            $validation = $this->OSociete;
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $validation;
    }

    //fin recherche de société
    //liste des sociétés
    public function showAllOrOneSociete($search_value, $statut)
    {
        if ($statut != Parameters::$statut_process and $statut != Parameters::$statut_enable and $statut != Parameters::$statut_delete and $statut != Parameters::$statut_closed) {
            Parameters::buildErrorMessage("Statut incorrecte");
            return [];
        }
        $arraySql = array();
        try {
            $query = "SELECT * FROM " . $this->Societe . " t WHERE (t.STR_SOCNAME LIKE :search_value OR t.STR_SOCDESCRIPTION LIKE :search_value) AND t.STR_SOCSTATUT = :STR_STATUT ORDER BY t.STR_SOCDESCRIPTION";
            $res = $this->dbconnnexion->prepare($query);
            //exécution de la requête
            $res->execute(array('search_value' => $search_value . "%", 'STR_STATUT' => $statut));
            while ($rowObj = $res->fetchAll()) {
                $arraySql[] = $rowObj;
            }
            $res->closeCursor();
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $arraySql;
    }

    public function showAllOrOneSocieteLimit($search_value, $start, $limit)
    {
        $arraySql = array();
        try {
            $query = "SELECT * FROM " . $this->Societe . " t WHERE (t.STR_SOCNAME LIKE :search_value OR t.STR_SOCDESCRIPTION LIKE :search_value) AND t.STR_SOCSTATUT = :STR_STATUT ORDER BY t.STR_SOCDESCRIPTION LIMIT " . $start . "," . $limit;
            $res = $this->dbconnnexion->prepare($query);
            //exécution de la requête
            $res->execute(array('search_value' => $search_value . "%", 'STR_STATUT' => Parameters::$statut_enable));
            while ($rowObj = $res->fetch()) {
                $arraySql[] = $rowObj;
            }
            $res->closeCursor();
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $arraySql;
    }

    public function totalSociete($search_value)
    {
        $result = 0;
        try {
            $query = "SELECT COUNT(t.LG_SOCID) NOMBRE FROM " . $this->Societe . " t WHERE (t.STR_SOCNAME LIKE :search_value OR t.STR_SOCDESCRIPTION LIKE :search_value) AND t.STR_SOCSTATUT = :STR_STATUT";
            $res = $this->dbconnnexion->prepare($query);
            //exécution de la requête
            $res->execute(array('search_value' => $search_value . "%", 'STR_STATUT' => Parameters::$statut_enable));
            while ($rowObj = $res->fetch()) {
                $result = $rowObj["NOMBRE"];
            }
            $res->closeCursor();
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $result;
    }

    public function createSocieteOperateur($LG_SOCID, $LG_OPEID, $STR_SOPPHONE, $OUtilisateur)
    {
        $validation = false;
        $LG_SOPID = generateRandomString(20);
        try {
            $params_condition = array("LG_SOCID" => $LG_SOCID, "LG_OPEID" => $LG_OPEID, "STR_SOPSTATUT" => Parameters::$statut_enable);
            $this->OSocieteOperateur = Find($this->SocieteOperateur, $params_condition, $this->dbconnnexion);

            if ($this->OSocieteOperateur != null) {
                Parameters::buildErrorMessage("Echec d'ajout de l'opérateur. Celui existe déjà pour cette société");
                return $validation;
            }

            $params = array("LG_SOPID" => $LG_SOPID, "LG_SOCID" => $LG_SOCID, "LG_OPEID" => $LG_OPEID, "STR_SOPPHONE" => $STR_SOPPHONE, "STR_SOPSTATUT" => Parameters::$statut_enable,
                "DT_SOPCREATED" => get_now(), "LG_UTICREATEDID" => $OUtilisateur[0][0]);

            if ($this->dbconnnexion != null) {
                if (Persist($this->SocieteOperateur, $params, $this->dbconnnexion)) {
                    $validation = true;
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

    public function updateSocieteOperateur($LG_SOPID, $STR_SOPPHONE, $OUtilisateur)
    {
        $validation = false;
        try {
            $this->OSocieteOperateur = $this->getSocieteOperateurUnique($LG_SOPID);

            if ($this->OSocieteOperateur == null) {
                Parameters::buildErrorMessage("Echec de mise à jour. Référence inexistante");
                return $validation;
            }

            $params_condition = array("LG_SOPID" => $this->OSocieteOperateur[0][0]);
            $params_to_update = array("STR_SOPPHONE" => $STR_SOPPHONE, "DT_SOPUPDATED" => get_now(), "LG_UTIUPDATEDID" => $OUtilisateur[0][0]);

            if ($this->dbconnnexion != null) {
                if (Merge($this->SocieteOperateur, $params_to_update, $params_condition, $this->dbconnnexion)) {
                    $validation = true;
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

    public function getSocieteOperateur($LG_SOCID, $LG_OPEID)
    {
        $validation = null;
        Parameters::buildErrorMessage("Opérateur inexistante sur la société");
        try {
            $params_condition = array("LG_SOCID" => $LG_SOCID, "LG_OPEID" => $LG_OPEID);
            $validation = $this->OSocieteOperateur = Find($this->SocieteOperateur, $params_condition, $this->dbconnnexion);

            if ($this->OSocieteOperateur == null) {
                return $validation;
            }
            Parameters::buildSuccessMessage("Opérateur trouvé");
            $validation = $this->OSocieteOperateur;
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $validation;
    }

    public function getSocieteOperateurUnique($LG_SOPID)
    {
        $validation = null;
        Parameters::buildErrorMessage("Opérateur inexistante sur la société");
        try {
            $params_condition = array("LG_SOPID" => $LG_SOPID, "STR_SOPPHONE" => $LG_SOPID);
            $validation = $this->OSocieteOperateur = Find($this->SocieteOperateur, $params_condition, $this->dbconnnexion, "OR");

            if ($this->OSocieteOperateur == null) {
                return $validation;
            }
            Parameters::buildSuccessMessage("Opérateur trouvé");
            $validation = $this->OSocieteOperateur;
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $validation;
    }

    public function deleteSocieteOperateur($LG_SOPID, $STR_SOPSTATUT, $OUtilisateur)
    {
        $validation = false;
        try {
            $this->OSocieteOperateur = $this->getSocieteOperateurUnique($LG_SOPID);

            if ($this->OSocieteOperateur == null) {
                Parameters::buildErrorMessage("Echec de l'opération. Référence inexistante");
                return $validation;
            }

            $params_condition = array("LG_SOPID" => $this->OSocieteOperateur[0][0]);
            $params_to_update = array("STR_SOPSTATUT" => $STR_SOPSTATUT, "DT_SOPUPDATED" => get_now(), "LG_UTIUPDATEDID" => $OUtilisateur[0][0]);

            if ($this->dbconnnexion != null) {
                if (Merge($this->SocieteOperateur, $params_to_update, $params_condition, $this->dbconnnexion)) {
                    $validation = true;
                    Parameters::buildSuccessMessage("Opération effectuée avec succès");
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

    public function showAllOrOneSocieteOperateur($search_value, $LG_SOCID, $LG_OPEID)
    {
        $arraySql = array();
        try {
            $query = "SELECT t.LG_SOPID, t.STR_SOPPHONE, o.STR_OPENAME, o.STR_OPEDESCRIPTION FROM " . $this->SocieteOperateur . " t, " . $this->Operateur . " o WHERE t.LG_OPEID = o.LG_OPEID AND (t.STR_SOPPHONE LIKE :search_value OR o.STR_OPENAME LIKE :search_value OR o.STR_OPEDESCRIPTION LIKE :search_value) AND t.LG_SOCID LIKE :LG_SOCID AND t.LG_OPEID LIKE :LG_OPEID AND o.STR_OPESTATUT = :STR_STATUT AND t.STR_SOPSTATUT = :STR_STATUT ORDER BY o.STR_OPEDESCRIPTION";
            $res = $this->dbconnnexion->prepare($query);
            //exécution de la requête
            $res->execute(array('search_value' => $search_value . "%", 'LG_SOCID' => $LG_SOCID, 'LG_OPEID' => $LG_OPEID, 'STR_STATUT' => Parameters::$statut_enable));
            while ($rowObj = $res->fetch()) {
                $arraySql[] = $rowObj;
            }
            $res->closeCursor();
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $arraySql;
    }

    public function createSocieteUtilisateur($LG_SOCID, $LG_UTIID, $OUtilisateur)
    {
        $validation = false;
        $LG_SUTID = generateRandomString(20);
        try {
            $this->OSocieteUtilisateur = $this->getSocieteUtilisateur($LG_SOCID, $LG_UTIID);

            if ($this->OSocieteUtilisateur != null) {
                Parameters::buildErrorMessage("Echec d'ajout de l'ajout de la société. Celui existe déjà pour cet utilisateur");
                return $validation;
            }

            $params = array("LG_SUTID" => $LG_SUTID, "LG_SOCID" => $LG_SOCID, "LG_UTIID" => $LG_UTIID, "STR_SUTSTATUT" => Parameters::$statut_enable,
                "DT_SUTCREATED" => get_now(), "LG_UTICREATEDID" => $OUtilisateur[0][0]);
            if ($this->dbconnnexion != null) {
                if (Persist($this->SocieteUtilisateur, $params, $this->dbconnnexion)) {
                    $validation = true;
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

    public function getSocieteUtilisateur($LG_SOCID, $LG_UTIID)
    {
        $validation = null;
        try {
            $params_condition = array("LG_SOCID" => $LG_SOCID, "LG_UTIID" => $LG_UTIID);
            $validation = $this->OSocieteUtilisateur = Find($this->SocieteUtilisateur, $params_condition, $this->dbconnnexion);

            if ($this->OSocieteUtilisateur == null) {
                return $validation;
            }
            $validation = $this->OSocieteUtilisateur;
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $validation;
    }

    public function deleteSocieteUtilisateur($LG_SUTID, $OUtilisateur)
    {
        $validation = false;
        try {
            $params = array("LG_SUTID" => $LG_SUTID);
            if ($this->dbconnnexion != null) {
                if (Remove($this->SocieteUtilisateur, $params, $this->dbconnnexion)) {
                    $validation = true;
                    Parameters::buildSuccessMessage("Suppression effectuée avec succès");
                } else {
                    Parameters::buildErrorMessage("Echec de suppression");
                }
            }
        } catch (Exception $exc) {
            $exc->getTraceAsString();
            Parameters::buildErrorMessage("Echec de suppression. Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    public function showAllOrOneSocieteUtilisateur($search_value, $LG_SOCID, $LG_UTIID)
    {
        $arraySql = array();
        try {
            $query = "SELECT DISTINCT t.LG_SUTID, u.LG_UTIID, u.STR_UTIFIRSTLASTNAME, u.STR_UTIPHONE, u.STR_UTIMAIL, u.STR_UTILOGIN, u.STR_UTIPIC, s.STR_SOCNAME, s.STR_SOCDESCRIPTION, s.STR_SOCLOGO FROM " . $this->SocieteUtilisateur . " t, " . $this->Utilisateur . " u, " . $this->Societe . " s, " . $this->Profile . " p "
                . "WHERE t.LG_UTIID = u.LG_UTIID AND t.LG_SOCID = s.LG_SOCID AND u.LG_PROID = p.LG_PROID AND (u.STR_UTIFIRSTLASTNAME LIKE :search_value OR u.STR_UTIPHONE LIKE :search_value OR s.STR_SOCNAME LIKE :search_value OR s.STR_SOCDESCRIPTION LIKE :search_value) AND t.LG_SOCID LIKE :LG_SOCID AND t.LG_UTIID LIKE :LG_UTIID AND u.STR_UTISTATUT = :STR_STATUT AND t.STR_SUTSTATUT = :STR_STATUT ORDER BY s.STR_SOCDESCRIPTION, u.STR_UTIFIRSTLASTNAME";
            $res = $this->dbconnnexion->prepare($query);
            //exécution de la requête
            $res->execute(array('search_value' => $search_value . "%", 'LG_SOCID' => $LG_SOCID, 'LG_UTIID' => $LG_UTIID, 'STR_STATUT' => Parameters::$statut_enable));
            while ($rowObj = $res->fetch()) {
                $arraySql[] = $rowObj;
            }
            $res->closeCursor();
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $arraySql;
    }

    public function showAllOrOneSocieteUtilisateurLimit($search_value, $LG_SOCID, $LG_UTIID, $start, $limit)
    {
        $arraySql = array();
        try {
            $query = "SELECT DISTINCT t.LG_SUTID, u.LG_UTIID, u.STR_UTIFIRSTLASTNAME, u.STR_UTIPHONE, u.STR_UTIMAIL, u.STR_UTILOGIN, u.STR_UTIPIC, s.STR_SOCNAME, s.STR_SOCDESCRIPTION, s.STR_SOCLOGO FROM " . $this->SocieteUtilisateur . " t, " . $this->Utilisateur . " u, " . $this->Societe . " s, " . $this->Profile . " p "
                . "WHERE t.LG_UTIID = u.LG_UTIID AND t.LG_SOCID = s.LG_SOCID AND u.LG_PROID = p.LG_PROID AND (u.STR_UTIFIRSTLASTNAME LIKE :search_value OR u.STR_UTIPHONE LIKE :search_value OR s.STR_SOCNAME LIKE :search_value OR s.STR_SOCDESCRIPTION LIKE :search_value) AND t.LG_SOCID LIKE :LG_SOCID AND t.LG_UTIID LIKE :LG_UTIID AND u.STR_UTISTATUT = :STR_STATUT AND t.STR_SUTSTATUT = :STR_STATUT ORDER BY u.STR_UTIFIRSTLASTNAME LIMIT " . $start . "," . $limit;
            $res = $this->dbconnnexion->prepare($query);
            //exécution de la requête
            $res->execute(array('search_value' => $search_value . "%", 'LG_SOCID' => $LG_SOCID, 'LG_UTIID' => $LG_UTIID, 'STR_STATUT' => Parameters::$statut_enable));
            while ($rowObj = $res->fetch()) {
                $arraySql[] = $rowObj;
            }
            $res->closeCursor();
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $arraySql;
    }

    public function totalSocieteUtilisateur($search_value, $LG_SOCID, $LG_UTIID)
    {
        $result = 0;
        try {
            $query = "SELECT COUNT(DISTINCT(u.LG_UTIID)) NOMBRE FROM " . $this->SocieteUtilisateur . " t, " . $this->Utilisateur . " u, " . $this->Societe . " s, " . $this->Profile . " p "
                . "WHERE t.LG_UTIID = u.LG_UTIID AND t.LG_SOCID = s.LG_SOCID AND u.LG_PROID = p.LG_PROID AND (u.STR_UTIFIRSTLASTNAME LIKE :search_value OR u.STR_UTIPHONE LIKE :search_value OR s.STR_SOCNAME LIKE :search_value OR s.STR_SOCDESCRIPTION LIKE :search_value) AND t.LG_SOCID LIKE :LG_SOCID AND t.LG_UTIID LIKE :LG_UTIID AND u.STR_UTISTATUT = :STR_STATUT AND t.STR_SUTSTATUT = :STR_STATUT";
            $res = $this->dbconnnexion->prepare($query);
            //exécution de la requête
            $res->execute(array('search_value' => $search_value . "%", 'LG_SOCID' => $LG_SOCID, 'LG_UTIID' => $LG_UTIID, 'STR_STATUT' => Parameters::$statut_enable));
            while ($rowObj = $res->fetch()) {
                $result = $rowObj["NOMBRE"];
            }
            $res->closeCursor();
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $result;
    }

    //fin gestion des sociétés

    public function generateToken()
    {
        $validation = "";
        try {
            // URL de l'API
            $url = Parameters::$urlRootAPI . "/login/user";

// Headers de la requête
            $headers = array(
                'Accept: application/json',
                'Content-Type: application/x-www-form-urlencoded',
                "api_key: " . Parameters::$apikey
            );

            // Données à envoyer
            $data = array(
                'login' => Parameters::$apiusername,
                'password' => Parameters::$apipassword
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
//            var_dump($obj);

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

    public function getClient($LG_CLIID, $token = null)
    {
        $validation = null;
        try {
            $token = $token == null ? $this->generateToken() : $token;

            $url = Parameters::$urlRootAPI . "/clients/" . $LG_CLIID;

            $headers = array(
                'Accept: application/json',
                'Content-Type: application/x-www-form-urlencoded',
                "api_key: " . Parameters::$apikey,
                "token: " . $token
            );

            $ch = curl_init($url);

            // Configuration de cURL
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $response = curl_exec($ch);
            curl_close($ch);

            $obj = json_decode($response);
            // Vérifier si la conversion a réussi
            if ($obj === null && json_last_error() !== JSON_ERROR_NONE) {
                Parameters::buildErrorMessage("Client inexistant. Veuille vérifier votre sélection");
                return $validation;
                //die('Erreur lors du décodage JSON');
            }

            $obj = $obj->clients;
            if (is_object($obj) && empty((array)$obj)) {
                Parameters::buildErrorMessage("Client inexistant. Veuille vérifier votre sélection");
                return $validation;
            }

            $validation = $obj[0];
            Parameters::buildSuccessMessage("Client " . $validation->CliLib . " trouvé");
        } catch (Exception $exc) {
            error_log($exc->getTraceAsString());
        }
        return $validation;
    }

    public function getAgence($LG_AGEID)
    {
        $arraySql = array();
        try {
            $query = "SELECT t.*, s.lg_socid, s.lg_socextid FROM " . $this->Agence . " t, " . $this->Societe . " s WHERE t.lg_socid = s.lg_socid and (t.lg_ageid = :lg_ageid or t.str_agename = :lg_ageid) and t.str_agestatut != :str_agestatut";
            $res = $this->dbconnnexion->prepare($query);
            //exécution de la requête
            $res->execute(array("lg_ageid" => $LG_AGEID, 'str_agestatut' => Parameters::$statut_delete));
            while ($rowObj = $res->fetch()) {
                $arraySql[] = $rowObj;
            }
            $res->closeCursor();
        } catch (Exception $exc) {
            error_log($exc->getTraceAsString());
        }
        return $arraySql;
    }

    //Moi
    public function getListe($LG_LSTID): ?array
    {
        $validation = null;
        Parameters::buildErrorMessage("Item inexistant");

        try {
            $params_condition = array("lg_lstid" => $LG_LSTID);
            $validation = $this->OListe = Find($this->Liste, $params_condition, $this->dbconnnexion);
            if ($this->OListe == null) {
                return $validation;
            }
            Parameters::buildSuccessMessage("Item" . $this->OListe[0]['lg_lstid'] . "trouvée");
            $validation = $this->OListe;
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $validation;
    }


    //moi
    public function createAgence($STR_AGENAME, $STR_AGEDESCRIPTION, $STR_AGELOCALISATION,
                                 $STR_AGEPHONE, $LG_SOCID, $OUtilisateur): string
    {
        $validation = "";
        $LG_AGEID = generateRandomString(20);
        $LG_SOCID = $this->getSociete($LG_SOCID);
        if ($LG_SOCID == null) {
            Parameters::buildErrorMessage("Id de la société introuvable");
            return $validation;
        }

        try {
            $params = array("lg_ageid" => $LG_AGEID, "str_agename" => $STR_AGENAME, "str_agedescription" =>
                $STR_AGEDESCRIPTION, "str_agelocalisation" => $STR_AGELOCALISATION, "dt_agecreated" => get_now(), "lg_uticreatedid" => $OUtilisateur ? $OUtilisateur[0]['lg_utiid'] : Parameters::$defaultAdminId, "str_agestatut" => Parameters::$statut_enable, "str_agephone" => $STR_AGEPHONE,
                "lg_socid" => $LG_SOCID[0]['lg_socid'],
            );

            if ($this->dbconnnexion != null) {
                if (Persist($this->Agence, $params, $this->dbconnnexion)) {
                    $validation = $LG_AGEID;
                    Parameters::buildSuccessMessage(("Agence " . $STR_AGEDESCRIPTION . " créer avec succès"));
                } else {
                    Parameters::buildErrorMessage("Echec de création de l'agence");
                }
            }
        } catch (Exception $exc) {
            $exc->getTraceAsString();
            Parameters::buildErrorMessage("Echec de création de l'agence. Veuillez contacter votre administrateur");
        }

        return $validation;
    }

    //moi
    public function getTrueAgence($LG_AGEID): ?array
    {
        $validation = null;
        Parameters::buildErrorMessage("Agence inexistante");

        try {
            $params_condition = array("lg_ageid" => $LG_AGEID);
            $validation = $this->OAgence = Find($this->Agence, $params_condition, $this->dbconnnexion);
            if ($this->OAgence == null) {
                return $validation;
            }
            Parameters::buildSuccessMessage("Agence" . $this->OAgence[0]['str_agename'] . "trouvée");
            $validation = $this->OAgence;
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $validation;
    }

    //moi
    public function createUtilisateur($STR_UTIFIRSTLASTNAME, $STR_UTIPHONE, $STR_UTIMAIL, $STR_UTILOGIN, $STR_UTIPASSWORD, $LG_AGEID, $STR_UTIPIC, $LG_PROID, $OUtilisateur): string
    {
        $validation = "";
        $LG_UTIID = generateRandomString(20);
        $LG_AGEID = $this->getTrueAgence($LG_AGEID);
        $LG_PROID = $this->getProfile($LG_PROID);
        if ($LG_AGEID == null) {
            Parameters::buildErrorMessage("Id de l'agence incorrecte");
            return "";
        }
        if ($LG_PROID == null) {
            Parameters::buildErrorMessage("Profil inexistant");
            return "";
        }
        try {
            if ($STR_UTIPIC) {
                $STR_UTIPIC = uploadFile(Parameters::$rootFolderAbsolute . "avatars/" . $LG_UTIID . "/", $STR_UTIPIC);
            }
            $params = array("lg_utiid" => $LG_UTIID, "str_utifirstlastname" => $STR_UTIFIRSTLASTNAME, "str_utiphone" => $STR_UTIPHONE, "str_utimail" => $STR_UTIMAIL, "str_utilogin" => $STR_UTILOGIN, "str_utipassword" => $STR_UTIPASSWORD, "str_utipic" => $STR_UTIPIC, "str_utitoken" => generateRandomString(), "str_utionesignalid" => "", "dt_uticreated" => get_now(), "str_utistatut" => Parameters::$statut_process, "lg_ageid" => $LG_AGEID[0]['lg_ageid'],
                "lg_proid" => $LG_PROID[0]['lg_proid'], "lg_uticreatedid" => $OUtilisateur ? $OUtilisateur[0]['lg_utiid'] : Parameters::$defaultAdminId);

            if ($this->dbconnnexion != null) {
                if (Persist($this->Utilisateur, $params, $this->dbconnnexion)) {
                    $validation = $LG_UTIID;
                    Parameters::buildSuccessMessage("Utilisateur " . $STR_UTIFIRSTLASTNAME . " créé avec succès");
                } else {
                    Parameters::buildErrorMessage("Echec de création de l'utilisateur");
                }
            }
        } catch (Exception $exc) {
            error_log($exc->getTraceAsString());
            Parameters::buildErrorMessage("Echec de création de l'utilisateur. Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    //moi
    public function updateUtilisateur($LG_UTIID, $STR_UTISTATUT, $STR_UTIFIRSTLASTNAME, $STR_UTIPHONE, $STR_UTIMAIL, $STR_UTILOGIN, $STR_UTIPASSWORD, $LG_AGEID, $STR_UTIPIC, $LG_PROID, $OUtilisateur = null): bool
    {
        $validation = false;
        $LG_AGEID = $this->getTrueAgence($LG_AGEID);
        $LG_PROID = $this->getProfile($LG_PROID);
        if ($LG_AGEID == null) {
            Parameters::buildErrorMessage("Id de l'agence incorrecte");
            return "";
        }
        if ($LG_PROID == null) {
            Parameters::buildErrorMessage("Profil inexistant");
            return "";
        }
        try {
            $this->OUtilisateur = $this->getUtilisateur($LG_UTIID);

            if ($this->OUtilisateur == null) {
                Parameters::buildErrorMessage("Echec de mise à jour. Référence inexistante");
                return $validation;
            }

            $params_condition = array("LG_UTIID" => $this->OUtilisateur[0]['lg_utiid']);
            if ($STR_UTIPIC) {
                $STR_UTIPIC = uploadFile(Parameters::$rootFolderAbsolute . "avatars/" . $LG_UTIID . "/", $STR_UTIPIC);
            }
            $params_to_update = array("str_utifirstlastname" => $STR_UTIFIRSTLASTNAME, "str_utiphone" => $STR_UTIPHONE, "str_utimail" => $STR_UTIMAIL, "str_utilogin" => $STR_UTILOGIN, "str_utipassword" => sha1($STR_UTIPASSWORD), "str_utipic" => $STR_UTIPIC, "str_utionesignalid" => "", "dt_uticreated" => get_now(), "str_utistatut" => $STR_UTISTATUT, "lg_ageid" => $LG_AGEID[0]['lg_ageid'],
                "lg_proid" => $LG_PROID[0]['lg_proid'], "lg_utiupdatedid" => $OUtilisateur ? $OUtilisateur[0]['lg_utiid'] : Parameters::$defaultAdminId);

            if ($this->dbconnnexion != null) {
                if (Merge($this->Utilisateur, $params_to_update, $params_condition, $this->dbconnnexion)) {
                    $validation = true;
                    Parameters::buildSuccessMessage("Mise à jour des données l'utilisateur effectuée avec succès.");
                } else {
                    Parameters::buildErrorMessage("Echec de l'opération");
                }
            }
        } catch (Exception $exc) {
            error_log($exc->getTraceAsString());
            Parameters::buildErrorMessage("Echec de l'opération. Veuillez contacter votre administrateur");
        }

        return $validation;
    }

    //moi
    public function createDocument($P_KEY, $STR_DOCPATH, $LG_LSTID, $OUtilisateur): string
    {
        $validation = "";
        $LG_DOCID = generateRandomString(20);
        $LG_LSTID = $this->getListe($LG_LSTID);

        try {
            if ($LG_LSTID == null) {
                Parameters::buildErrorMessage("Type de document inexistant");
                return "";
            }
            $params = array("lg_docid" => $LG_DOCID, "p_key" => $P_KEY, "str_docpath" => $STR_DOCPATH, "dt_doccreated" => get_now(), "str_docstatut" => Parameters::$statut_enable, "lg_lstid" => $LG_LSTID[0]['lg_lstid'], "lg_uticreatedid" => $OUtilisateur ? $OUtilisateur[0]['lg_utiid'] : Parameters::$defaultAdminId);

            if ($this->dbconnnexion != null) {
                if (Persist($this->Document, $params, $this->dbconnnexion)) {
                    $validation = $params['lg_docid'];
                    Parameters::buildSuccessMessage("Document uploadé avec succès");
                } else {
                    Parameters::buildErrorMessage("Echec de création du document");
                }
            }
        } catch (Exception $exc) {
            error_log($exc->getTraceAsString());
            Parameters::buildErrorMessage("Echec de création du document. Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    //moi
    public function getDocument($LG_DOCID): ?array
    {
        $validation = null;
        Parameters::buildErrorMessage("Document inexistant");

        try {
            $params_condition = array("lg_docid" => $LG_DOCID);
            $validation = $this->ODocument = Find($this->Document, $params_condition, $this->dbconnnexion);
            if ($this->ODocument == null) {
                return $validation;
            }
            Parameters::buildSuccessMessage("Document " . $this->ODocument[0]['lg_docid'] . " trouvé");
            $validation = $this->ODocument;
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $validation;
    }

    //moi
    public function getClientDemandes($statut)
    {
        $validation = null;
        Parameters::buildErrorMessage("Impossible d'obtenir toutes les demandes");

        $query = "
            select distinct *,
                GROUP_CONCAT(CONCAT(lst.str_lstvalue, ':', doc.str_docpath) SEPARATOR ', ') as gallery,
                (select lst.str_lstdescription
                 from liste as lst
                 where soc.lg_lstpayid = lst.lg_lstid and lst.str_lststatut = 'enable')     as str_paysfacturation,
                (select lst.str_lstdescription
                 from liste as lst
                 where soc.lg_lsttypesocid = lst.lg_lstid and lst.str_lststatut = 'enable') as str_typesociete
            from utilisateur as uti
                     inner join agence as age on uti.lg_ageid = age.lg_ageid
                     inner join societe as soc on age.lg_socid = soc.lg_socid
                     inner join document as doc on soc.lg_socid = doc.p_key
                    inner join liste as lst on lst.lg_lstid = doc.lg_lstid
            where  uti.str_utistatut = :STR_STATUT 
                and age.str_agestatut = 'enable'
              and soc.str_socstatut = :STR_STATUT
                and uti.lg_proid = 3
            group by uti.str_utifirstlastname, soc.str_socname, soc.str_socsiret, soc.str_soccode, soc.str_socphone, soc.str_socmail, soc.str_socdescription, soc.str_socstatut, soc.lg_socid
            ";
        try {
//            var_dump($query);
            $validation = $this->ODemandes = Finds($query, $this->dbconnnexion, array("STR_STATUT" => $statut));
            if ($this->ODemandes == null) {
                return $validation;
            }

            Parameters::buildSuccessMessage("Demandes trouvées");
            $validation = $this->ODemandes;
        } catch (Exception $exc) {
            var_dump($exc->getTraceAsString());
            Parameters::buildErrorMessage("Impossible d'obtenir toutes les demandes");
        }
        return $validation;
    }

    public function getClientDemande($LG_SOCID)
    {
        $validation = "";
        Parameters::buildErrorMessage("Impossible d'obtenir la demande");
        try {
            $query = "select distinct *,
                GROUP_CONCAT(CONCAT(lst.str_lstvalue, ':', doc.str_docpath) SEPARATOR ', ') as gallery,
                (select lst.str_lstdescription
                 from liste as lst
                 where soc.lg_lstpayid = lst.lg_lstid and lst.str_lststatut = 'enable')     as str_paysfacturation,
                (select lst.str_lstdescription
                 from liste as lst
                 where soc.lg_lsttypesocid = lst.lg_lstid and lst.str_lststatut = 'enable') as str_typesociete
                from utilisateur as uti
                         inner join agence as age on uti.lg_ageid = age.lg_ageid
                         inner join societe as soc on age.lg_socid = soc.lg_socid
                         inner join document as doc on soc.lg_socid = doc.p_key
                        inner join liste as lst on lst.lg_lstid = doc.lg_lstid
                where soc.lg_socid = :LG_SOCID
group by uti.str_utifirstlastname, soc.str_socname, soc.str_socsiret, soc.str_soccode, soc.str_socphone, soc.str_socmail, soc.str_socdescription, soc.str_socstatut, soc.lg_socid
                ";

            $validation = $demandeData = Finds($query, $this->dbconnnexion, ['LG_SOCID' => $LG_SOCID]);
            if ($demandeData == null) {
                return $validation;
            }
            Parameters::buildSuccessMessage("Demande trouvée");
            $validation = $demandeData;
        } catch (Exception $exc) {
            error_log($exc->getTraceAsString());
            Parameters::buildErrorMessage("Impossible d'obtenir la demande");
        }

        return $validation;
    }

    public function createClientExternal($LG_SOCID, $OUtilisateur)
    {
        $validation = "";
        $error = "";
        Parameters::buildSuccessMessage("Création du client chez 8sens réussi . ");
        $url = "http://160.120.155.165:8082/v1/clients";

        try {
            $header = array(
                "Accept: application/json",
                "api_key: ZghY887665YhGH",
                "Content-Type: application/json",
                "token: " . $this->generateToken()
            );


            $demandeData = $this->getClientDemande($LG_SOCID);
//        var_dump($demandeData);
            if ($demandeData == null) {
                Parameters::buildErrorMessage("Echec de la création du client chez 8sens. Veuillez contacté votre administrateur");
                return $validation;
            }
            $data = array(
                "clilib" => $demandeData[0][0]['str_socname'],//STR_SOCNAME
                "clilogin" => $demandeData[0][0]['str_utilogin'],//str_utilogin
                "moctel" => $demandeData[0][0]['str_socphone'],
                "mocport" => $demandeData[0][0]['str_socphone'],
                "mocmail" => $demandeData[0][0]['str_socmail'],
                "clicategenu" => $demandeData[0][0]['str_typesociete'],
                "clisiret" => $demandeData[0][0]['str_socsiret'],
                "pyscode" => $demandeData[0][0]['str_paysfacturation'],
                "prsprenom" => $demandeData[0][0]['str_utifirstlastname'],
                "prsname" => $demandeData[0][0]['str_utifirstlastname'],
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

            $response = curl_exec($ch);
            $obj = json_decode($response);
            curl_close($ch);

            if (!property_exists($obj, 'error')) {
                $validation = $obj->CliID;
                //Obtenir les infos de l'utilisateur
                $uti_data = $this->getUtilisateur($demandeData[0][0]['lg_utiid']);
                //mise à jour
                $this->updateUtilisateur($uti_data[0]['lg_utiid'], Parameters::$statut_enable, $uti_data[0]['str_utifirstlastname'], $uti_data[0]['str_utiphone'], $uti_data[0]['str_utimail'], $uti_data[0]['str_utilogin'], $uti_data[0]['str_utipassword'], $uti_data[0]['lg_ageid'], null, $uti_data[0]['lg_proid'], $OUtilisateur ?: null);

                //Obtenir les infos de la societe
                $soc_data = $this->getSociete($demandeData[0][0]['lg_socid']);
                $this->updateSociete($soc_data[0]['lg_socid'], $obj->CliID, Parameters::$statut_enable, $soc_data[0]['str_socdescription'], $soc_data[0]['str_socname'], null, $soc_data[0]['str_socmail'], $soc_data[0]['str_socphone'], $soc_data[0]["str_socsiret"], $soc_data[0]['lg_lsttypesocid'], $soc_data[0]['lg_lstpayid'], $soc_data[0]['str_soccode'], $OUtilisateur ?: null);

                Parameters::buildSuccessMessage("Création du client réussi avec ID: " . $obj->CliID);

                $validation = $this->getClientDemande($demandeData[0][0]['lg_socid']);

            }
        } catch (Exception $exc) {
            error_log($exc->getTraceAsString());
        }

        return $validation;
    }


    public function createDemande($STR_SOCNAME, $STR_SOCDESCRIPTION, $STR_SOCLOGO = null, $STR_SOCMAIL, $STR_SOCPHONE, $STR_SOCSIRET, $LG_LSTTYPESOCID, $LG_LSTPAYID, $STR_SOCCODE, $STR_UTIFIRSTLASTNAME, $STR_UTIMAIL, $STR_UTILOGIN, $STR_UTIPASSWORD, $STR_UTIPIC = null, $STR_UTIPHONE, $LG_PROID, $DOCUMENTS, $OUtilisateur): bool
    {
        $validation = false;
        try {
            $LG_SOCID = $this->createSociete($STR_SOCNAME, $STR_SOCDESCRIPTION, $STR_SOCLOGO, $STR_SOCMAIL, $STR_SOCPHONE, $STR_SOCSIRET, $LG_LSTTYPESOCID, $LG_LSTPAYID, $STR_SOCCODE, $OUtilisateur);

            if ($LG_SOCID == null) {
                Parameters::buildErrorMessage("Echec de l'enregistrement du client. Erreur: La création de la société à echouer");
                return $validation;
            }
            $LG_AGEID = $this->createAgence($STR_SOCNAME, $STR_SOCNAME, null, $STR_SOCPHONE, $LG_SOCID, $OUtilisateur);
            if ($LG_AGEID == null) {
                Parameters::buildErrorMessage("Echec de l'enregistrement du client. Erreur: La création de l'agence à echouer");
                return $validation;
            }
            $LG_UTIID = $this->createUtilisateur($STR_UTIFIRSTLASTNAME, $STR_UTIPHONE, $STR_UTIMAIL, $STR_UTILOGIN, $STR_UTIPASSWORD, $LG_AGEID, $STR_UTIPIC, $LG_PROID, $OUtilisateur);
            $this->dowloadDocuments($DOCUMENTS, $LG_SOCID, $OUtilisateur);
            $validation = true;
            Parameters::buildSuccessMessage("Inscription réussi");
        } catch (Exception $exc) {
            error_log($exc->getTraceAsString());
        }

        return $validation;
    }

    public function dowloadDocuments($DOCUMENTS, $LG_SOCID, $OUtilisateur): string
    {
//        var_dump($Documents);
        $files = $DOCUMENTS[0];
        $filesCount = count($files['name']);
        $post = $DOCUMENTS[1];

        for ($i = 0; $i < $filesCount; $i++) {
            $fileTmpName = $files['tmp_name'][$i]['file'];
//            var_dump($fileTmpName);
            $fileName = $files['name'][$i]['file'];
//            var_dump($fileName);
            $fileSize = $files['size'][$i]['file'];
            $LG_LSTID = $post[$i]['LG_LSTID'];

            $imageTypes = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

            $is_IMG = in_array(strtolower($fileExtension), $imageTypes);

            $STR_DOCPATH = uploadFile(Parameters::$rootFolderAbsolute . "documents/" . $LG_SOCID . "/", ['tmp_name' => $fileTmpName, 'name' => $fileName, 'size' => $fileSize], $is_IMG);
            $this->createDocument($LG_SOCID, $STR_DOCPATH, $LG_LSTID, $OUtilisateur);

        }

        return "Files uploaded successfully";
    }

    //moi
    public function deleteProduitSubstitution($LG_PROSUBID)
    {
        $validation = "";
        try {
            $this->OProduitSubstitution = $this->getProduitSubstitution($LG_PROSUBID);

            if ($this->OProduitSubstitution == null) {
                Parameters::buildErrorMessage("Echec de la mise à jour du produit de substitution, ID inexistant");
                return $validation;
            }

            $params = array("lg_prosubid" => $this->OProduitSubstitution[0]['lg_prosubid']);
            if (Remove($this->ProduitSubstitution, $params, $this->dbconnnexion)) {
                $validation = $this->OProduitSubstitution[0]["lg_prosubid"];
                Parameters::buildSuccessMessage("Suppression du produit avec succès");
            } else {
                Parameters::buildErrorMessage("Echec de suppression du produit");
            }
        } catch (Exception $exc) {
            error_log($exc->getTraceAsString());
            Parameters::buildErrorMessage("Echec de la suppression du produit de substitution" . $this->OProduitSubstitution[0]['lg_prosubid'] . " Veuillez contacter votre administrateur");
        }

        return $validation;
    }

    //moi
    public function rejectRegistration($LG_SOCID, $OUtilisateur)
    {
        $validation = null;
        try {
            $this->OSociete = $this->getSociete($LG_SOCID);

            if ($this->OSociete == null) {
                Parameters::buildErrorMessage("Echec du rejet du client, ID inexistant");
                return $validation;
            }

            $params_condition = array("lg_socid" => $this->OSociete[0]['lg_socid']);
            $params_to_update = array("str_socstatut" => Parameters::$statut_canceled, "dt_socupdated" => get_now(),
                "lg_utiupdatedid" => $OUtilisateur ? $OUtilisateur[0]['lg_utiid'] : Parameters::$defaultAdminId);

            if ($this->dbconnnexion != null) {
                if (Merge($this->Societe, $params_to_update, $params_condition, $this->dbconnnexion)) {
                    $validation = $this->getClientDemande($LG_SOCID);
                    Parameters::buildSuccessMessage("Demande du" . $this->OSociete[0]['lg_socid'] . " rejetée avec succès");
                } else {
                    Parameters::buildErrorMessage("Echec du rejet du client" . $this->OSociete[0]['lg_socname']);
                }
            }
        } catch (Exception $exc) {
            error_log($exc->getTraceAsString());
            Parameters::buildErrorMessage("Echec du rejet du client" . $this->OSociete[0]['lg_socname'] . " Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    public function markProductAsViewed($LG_PROID, $LG_UTIID)
    {
        $validation = false;
        if ($LG_UTIID == null || $LG_UTIID == "") {
            Parameters::buildErrorMessage("Utilisateur inexistant");
            return $validation;
        }
        $StockManger = new StockManager();
        $LG_PROID = $StockManger->getProduct($LG_PROID);
        if ($LG_PROID == null) {
            Parameters::buildErrorMessage("Produit inexistant");
            return $validation;
        }
        $LG_UTIID = $this->getUtilisateur($LG_UTIID);
        if ($LG_UTIID == null) {
            Parameters::buildErrorMessage("Utilisateur inexistant");
            return $validation;
        }
        try {
            $params = array("lg_pistaudit" => generateRandomNumber(), "lg_proid" => $LG_PROID[0]['lg_proid'], "lg_lsttypeaudit" => Parameters::$lst_viewed_product, "lg_utiid" => $LG_UTIID[0]['lg_utiid'], "dt_pistauditcreated" => get_now(), "lg_uticreated" => $LG_UTIID[0]['lg_utiid'], "str_pistauditstatut" => Parameters::$statut_enable);
            if ($this->dbconnnexion != null) {
                if (Persist($this->Piste_audit, $params, $this->dbconnnexion)) {
                    $validation = true;
                    Parameters::buildSuccessMessage("Produit " . $LG_PROID[0]['str_proname'] . " marqué comme vu avec succès");
                } else {
                    Parameters::buildErrorMessage("Echec de marquage du produit comme vu");
                }
            }
        } catch (Exception $exc) {
            error_log($exc->getTraceAsString());
            Parameters::buildErrorMessage("Echec de marquage du produit comme vu. Veuillez contacter votre administrateur");
        }

        return $validation;
    }

    //moi
    public function uploadProductPicture($PICTURES, $SUBSTITUTION_PRODUCTS, $LG_PROID, $OUtilisateur)
    {
        $validation = false;
//        Parameters::buildErrorMessage("Échec de l'upload de l'image principale du produit " . $LG_PROID);
        try {
            if ($PICTURES != null) {
                // Traitement de l'image principale
                if (isset($PICTURES['name']['main'])) {
                    $mainImage = [
                        'tmp_name' => $PICTURES['tmp_name']['main'],
                        'name' => $PICTURES['name']['main'],
                        'size' => $PICTURES['size']['main'],
                        'error' => $PICTURES['error']['main']
                    ];

                    $mainFileExtension = pathinfo($mainImage['name'], PATHINFO_EXTENSION);
                    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];

                    if (in_array(strtolower($mainFileExtension), $imageExtensions)) {
                        // Upload de l'image principale
                        $STR_PROPIC_MAIN = uploadFile(Parameters::$rootFolderAbsolute . "produits/" . $LG_PROID . "/", $mainImage, true);

                        // Mise à jour de l'image principale dans la base de données
                        $params_condition = array("lg_proid" => $LG_PROID);
                        $params_to_update = array(
                            "str_propic" => $STR_PROPIC_MAIN,
                            "dt_proupdated" => get_now(),
                            "lg_utiupdatedid" => $OUtilisateur ? $OUtilisateur[0]['lg_utiid'] : Parameters::$defaultAdminId
                        );

                        if ($this->dbconnnexion != null) {
                            if (Merge($this->Produit, $params_to_update, $params_condition, $this->dbconnnexion)) {
                                Parameters::buildSuccessMessage("Image principale du produit " . $LG_PROID . " uploadée avec succès");
                                $validation = true;
                            } else {
                                Parameters::buildErrorMessage("Échec de l'upload de l'image principale du produit " . $LG_PROID);
                            }
                        }
                    }
                }

                // Traitement des images miniatures
                if (isset($PICTURES['name']['thumbnail']) && is_array($PICTURES['name']['thumbnail'])) {
                    foreach ($PICTURES['name']['thumbnail'] as $index => $thumbnailName) {
                        $thumbnailImage = [
                            'tmp_name' => $PICTURES['tmp_name']['thumbnail'][$index],
                            'name' => $thumbnailName,
                            'size' => $PICTURES['size']['thumbnail'][$index],
                            'error' => $PICTURES['error']['thumbnail'][$index]
                        ];

                        $thumbnailFileExtension = pathinfo($thumbnailImage['name'], PATHINFO_EXTENSION);
                        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
                        if (in_array(strtolower($thumbnailFileExtension), $imageExtensions)) {
                            // Upload de la miniature
                            $STR_PROPIC_THUMBNAIL = uploadFile(Parameters::$rootFolderAbsolute . "produits/" . $LG_PROID . "/", $thumbnailImage, true);

                            $LG_DOCID = generateRandomNumber(20);
                            $params = array("lg_docid" => $LG_DOCID, "p_key" => $LG_PROID, "str_docpath" => $STR_PROPIC_THUMBNAIL, "dt_doccreated" => get_now(), "str_docstatut" => Parameters::$statut_enable, "lg_lstid" => 5, "lg_uticreatedid" => $OUtilisateur ? $OUtilisateur[0]['lg_utiid'] : Parameters::$defaultAdminId);

                            if ($this->dbconnnexion != null) {
                                if (Persist($this->Document, $params, $this->dbconnnexion)) {
                                    $validation = $params['lg_docid'];
                                    Parameters::buildSuccessMessage("Document uploadé avec succès");
                                } else {
                                    Parameters::buildErrorMessage("Echec de création du document");
                                }
                            }
                        }
                    }
                    $validation = true; // Validation après traitement de toutes les miniatures
                }
            }

            // Traitement des produits de substitution
            if (isset($SUBSTITUTION_PRODUCTS)) {
                $SUBSTITUTION_PRODUCTS = explode(",", $SUBSTITUTION_PRODUCTS);
                foreach ($SUBSTITUTION_PRODUCTS as $substitutionProduct) {
                    $LG_PROSUBID = $this->createProduitSubstitution($LG_PROID, trim($substitutionProduct), $OUtilisateur);
                    if ($LG_PROSUBID == null) {
                        Parameters::buildErrorMessage("Echec de la création du produit de substitution");
                        return $validation;
                    }
                }
            }

        } catch (Exception $exc) {
            error_log($exc->getTraceAsString());
        }

        return $validation;
    }

    public function deleteProductImage($LG_DOCID)
    {
        $validation = "";
        try {
            $this->ODocument = $this->getDocument($LG_DOCID);

            if ($this->ODocument == null) {
                Parameters::buildErrorMessage("Echec de la suppression de l'image, ID inexistant");
                return $validation;
            }

            $params = array("lg_docid" => $this->ODocument[0]['lg_docid']);
            if (Remove($this->Document, $params, $this->dbconnnexion)) {
                $validation = $this->ODocument[0]["lg_docid"];
                Parameters::buildSuccessMessage("Suppression de l'image avec succès");
            } else {
                Parameters::buildErrorMessage("Echec de suppression de l'image");
            }
        } catch (Exception $exc) {
            error_log($exc->getTraceAsString());
            Parameters::buildErrorMessage("Echec de la suppression de l'image" . $this->ODocument[0]['lg_docid'] . " Veuillez contacter votre administrateur");
        }

        return $validation;
    }
}
