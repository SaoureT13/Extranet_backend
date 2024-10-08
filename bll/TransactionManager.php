<?php

interface TransactionInterface {

    //code ajouté
    public function createTransaction($LG_OPEID, $LG_TTRID, $STR_TRAREFERENCE, $STR_TRAPHONE, $LG_SUTID, $DBL_TRAAMOUNT, $STR_TRAOTHERVALUE, $OUtilisateur);

    public function getTransaction($LG_TRAID);

    public function deleteTransaction($LG_TRAID, $OUtilisateur);

    public function showAllOrOneTransaction($search_value, $LG_OPEID, $LG_TTRID, $LG_SOCID, $DT_BEGIN, $DT_END, $start, $limit);

    public function totalTransaction($search_value, $LG_OPEID, $LG_TTRID, $LG_SOCID, $DT_BEGIN, $DT_END);
    //fin code ajouté
}

class TransactionManager implements TransactionInterface {

    private $Transaction = 'TRANSACTION';
    private $Typetransaction = 'TYPETRANSACTION';
    private $Operateur = 'OPERATEUR';
    private $SocieteUtilisateur = 'SOCIETE_UTILISATEUR';
    private $OTransaction = array();
    private $OOperateur = array();
    private $OTypetransaction = array();
    private $OSocieteOperateur = array();
    private $OSocieteUtilisateur = array();
    private $dbconnnexion;

    //constructeur de la classe 
    public function __construct() {
        $this->dbconnnexion = DoConnexionPDO(Parameters::$host, Parameters::$user, Parameters::$pass, Parameters::$db, Parameters::$port);
    }

    //gestion des transactions
    //creation d'une transaction
    public function createTransaction($LG_OPEID, $LG_TTRID, $STR_TRAREFERENCE, $STR_TRAPHONE, $LG_SOCID, $DBL_TRAAMOUNT, $STR_TRAOTHERVALUE, $OUtilisateur) {
        $validation = false;
        $LG_TRAID = generateRandomString(20);
        $ConfigurationManager = new ConfigurationManager();
        try {
            $this->OOperateur = $ConfigurationManager->getOperateur($LG_OPEID);
            if ($this->OOperateur == null) {
                Parameters::buildErrorMessage("Echec de l'opération. Opérateur inexistant");
                return $validation;
            }
            
            $this->OTypetransaction = $ConfigurationManager->getTypetransaction($LG_TTRID);
            if ($this->OTypetransaction == null) {
                Parameters::buildErrorMessage("Echec de l'opération. Type de transaction inexistant");
                return $validation;
            }
            
            $this->OSocieteOperateur = $ConfigurationManager->getSocieteOperateur($LG_SOCID, $LG_OPEID);
            if ($this->OSocieteOperateur == null) {
                Parameters::buildErrorMessage("Echec de l'opération. Opérateur non commercialisé pour cette société");
                return $validation;
            }
            
            $this->OSocieteUtilisateur = $ConfigurationManager->getSocieteUtilisateur($LG_SOCID, $OUtilisateur[0][0]);
            if ($this->OSocieteUtilisateur == null) {
                Parameters::buildErrorMessage("Echec de l'opération. Utilisateur non rattaché à cette société");
                return $validation;
            }
            
            $params = array("LG_TRAID" => $LG_TRAID, "LG_OPEID" => $LG_OPEID, "LG_TTRID" => $LG_TTRID, "STR_TRAREFERENCE" => $STR_TRAREFERENCE, "STR_TRAPHONE" => $STR_TRAPHONE, "STR_OPEPHONE" => $this->OSocieteOperateur[0]["STR_SOPPHONE"],
                "LG_SUTID" => $this->OSocieteUtilisateur[0][0], "DBL_TRAAMOUNT" => $DBL_TRAAMOUNT, "STR_TRAOTHERVALUE" => $STR_TRAOTHERVALUE, "STR_TRASTATUT" => Parameters::$statut_enable, "DT_TRACREATED" => get_now());
            
            if ($this->dbconnnexion != null) {
                if (Persist($this->Transaction, $params, $this->dbconnnexion)) {
                    $validation = true;
                    Parameters::buildSuccessMessage("Opération N°" . $STR_TRAREFERENCE . " effectuée avec succès.");
                } else {
                    Parameters::buildErrorMessage("Echec d'enregistrement de l'opération");
                }
            }
        } catch (Exception $exc) {
            $exc->getTraceAsString();
            Parameters::buildErrorMessage("Echec d'enregistrement de l'opération. Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    //fin creation d'une transaction
    
    //suppression de transaction
    public function deleteTransaction($LG_TRAID, $OUtilisateur) {
        $validation = false;
        try {
            $this->OTransaction = $this->getTransaction($LG_TRAID);

            if ($this->OTransaction == null) {
                Parameters::buildErrorMessage("Echec d'annulation de l'opération. Référence inexistante");
                return $validation;
            }

            $params_condition = array("LG_TRAID" => $this->OTransaction[0][0]);
            $params_to_update = array("STR_TRASTATUT" => Parameters::$statut_delete, "DT_TRAUPDATED" => get_now(), "LG_UTIUPDATEDID" => $OUtilisateur[0][0]);

            if ($this->dbconnnexion != null) {
                if (Merge($this->Transaction, $params_to_update, $params_condition, $this->dbconnnexion)) {
                    $validation = true;
                    Parameters::buildSuccessMessage("Opération N°" . $this->OTransaction[0][3] . " supprimée avec succès");
                } else {
                    Parameters::buildErrorMessage("Echec de suppression de l'opération");
                }
            }
        } catch (Exception $exc) {
            $exc->getTraceAsString();
            Parameters::buildErrorMessage("Echec de suppression de l'opération. Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    //fin suppression de transaction
    //recherche de transaction
    public function getTransaction($LG_TRAID) {
        $validation = null;
        Parameters::buildErrorMessage("Opération inexistante");
        try {
            $params_condition = array("LG_TRAID" => $LG_TRAID, "STR_TRAREFERENCE" => $LG_TRAID);
            $validation = $this->OTransaction = Find($this->Transaction, $params_condition, $this->dbconnnexion, "OR");

            if ($this->OTransaction == null) {
                return $validation;
            }
            Parameters::buildSuccessMessage("Opération N°" . $this->OTransaction[0][3] . " trouvée");
            $validation = $this->OTransaction;
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $validation;
    }

    //fin recherche de transaction
    //liste des transactions
    public function showAllOrOneTransaction($search_value, $LG_OPEID, $LG_TTRID, $LG_SOCID, $DT_BEGIN, $DT_END, $start, $limit) {
        $arraySql = array();
        try {
            $query = "SELECT t.*, tt.STR_TTRDESCRIPTION, o.STR_OPENAME, o.STR_OPEDESCRIPTION, o.STR_OPEPIC, su.LG_SOCID FROM ".$this->Transaction." t, ".$this->Typetransaction." tt, ".$this->Operateur." o, ".$this->SocieteUtilisateur." su WHERE t.LG_TTRID = tt.LG_TTRID AND t.LG_OPEID = o.LG_OPEID AND t.LG_SUTID = su.LG_SUTID AND (t.STR_TRAREFERENCE LIKE :search_value OR t.STR_TRAPHONE LIKE :search_value OR t.STR_OPEPHONE LIKE :search_value OR o.STR_OPENAME LIKE :search_value OR o.STR_OPEDESCRIPTION LIKE :search_value)
            AND t.LG_OPEID LIKE :LG_OPEID AND t.LG_TTRID LIKE :LG_TTRID AND su.LG_SOCID LIKE :LG_SOCID AND t.STR_TRASTATUT = :STR_STATUT AND DATE(t.DT_TRACREATED) BETWEEN :DT_BEGIN AND :DT_END ORDER BY t.DT_TRACREATED DESC LIMIT " . $start . "," . $limit;
            $res = $this->dbconnnexion->prepare($query);
            //exécution de la requête
            $res->execute(array('search_value' => $search_value . "%", "LG_OPEID" => $LG_OPEID, "LG_TTRID" => $LG_TTRID, "LG_SOCID" => $LG_SOCID, "DT_BEGIN" => $DT_BEGIN, "DT_END" => $DT_END, 'STR_STATUT' => Parameters::$statut_enable));
            while ($rowObj = $res->fetch()) {
                $arraySql[] = $rowObj;
            }
            $res->closeCursor();
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $arraySql;
    }

    public function totalTransaction($search_value, $LG_OPEID, $LG_TTRID, $LG_SOCID, $DT_BEGIN, $DT_END) {
        $result = 0;
        try {
            $query = "SELECT COUNT(t.LG_TRAID) NOMBRE FROM ".$this->Transaction." t, ".$this->Typetransaction." tt, ".$this->Operateur." o, ".$this->SocieteUtilisateur." su WHERE t.LG_TTRID = tt.LG_TTRID AND t.LG_OPEID = o.LG_OPEID AND t.LG_SUTID = su.LG_SUTID AND (t.STR_TRAREFERENCE LIKE :search_value OR t.STR_TRAPHONE LIKE :search_value OR t.STR_OPEPHONE LIKE :search_value OR o.STR_OPENAME LIKE :search_value OR o.STR_OPEDESCRIPTION LIKE :search_value)
            AND t.LG_OPEID LIKE :LG_OPEID AND t.LG_TTRID LIKE :LG_TTRID AND su.LG_SOCID LIKE :LG_SOCID AND t.STR_TRASTATUT = :STR_STATUT AND DATE(t.DT_TRACREATED) BETWEEN :DT_BEGIN AND :DT_END";
            $res = $this->dbconnnexion->prepare($query);
            //exécution de la requête
            $res->execute(array('search_value' => $search_value . "%", "LG_OPEID" => $LG_OPEID, "LG_TTRID" => $LG_TTRID, "LG_SOCID" => $LG_SOCID, "DT_BEGIN" => $DT_BEGIN, "DT_END" => $DT_END, 'STR_STATUT' => Parameters::$statut_enable));
            while ($rowObj = $res->fetch()) {
                $result = $rowObj["NOMBRE"];
            }
            $res->closeCursor();
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $result;
    }
    //fin gestion des transactions
}
