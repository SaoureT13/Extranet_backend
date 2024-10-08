<?php

interface StatistiqueInterface {
    public function topOperateur($LG_SOCID, $DT_BEGIN, $DT_END, $limit);
    public function topTransaction($LG_SOCID, $DT_BEGIN, $DT_END, $limit);
    public function getProductStatViewed();
}

class StatistiqueManager implements StatistiqueInterface {

    private $Transaction = 'TRANSACTION';
    private $Operateur = 'OPERATEUR';
    private $SocieteUtilisateur = 'SOCIETE_UTILISATEUR';
    private $Typetransaction = 'TYPETRANSACTION';
    private $dbconnnexion;

    //constructeur de la classe 
    public function __construct() {
        $this->dbconnnexion = DoConnexionPDO(Parameters::$host, Parameters::$user, Parameters::$pass, Parameters::$db, Parameters::$port);
    }

    //liste des tops X du classement des opérateurs
    public function topOperateur($LG_SOCID, $DT_BEGIN, $DT_END, $limit) {
        $arraySql = array();
        try {
            $query = "SELECT COUNT(t.LG_TRAID) NOMBRE, sum(t.DBL_TRAAMOUNT) MONTANT, o.LG_OPEID, o.STR_OPENAME, o.STR_OPEDESCRIPTION, o.STR_OPEPIC, su.LG_SOCID FROM ".$this->Transaction." t, ".$this->Operateur." o, ".$this->SocieteUtilisateur." su
            WHERE t.LG_OPEID = o.LG_OPEID AND t.LG_SUTID = su.LG_SUTID and t.STR_TRASTATUT = :STR_STATUT AND su.LG_SOCID LIKE :LG_SOCID AND date(t.DT_TRACREATED) BETWEEN :DT_BEGIN AND :DT_END GROUP BY o.LG_OPEID, o.STR_OPENAME, o.STR_OPEDESCRIPTION, o.STR_OPEPIC, su.LG_SOCID ORDER BY o.STR_OPEDESCRIPTION LIMIT " . $limit;
            $res = $this->dbconnnexion->prepare($query);
            //exécution de la requête
            $res->execute(array("LG_SOCID" => $LG_SOCID, "DT_BEGIN" => $DT_BEGIN, "DT_END" => $DT_END, 'STR_STATUT' => Parameters::$statut_enable));
            while ($rowObj = $res->fetch()) {
                $arraySql[] = $rowObj;
            }
            $res->closeCursor();
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $arraySql;
    }
    
    //liste des tops X du classement des transactions
    public function topTransaction($LG_SOCID, $DT_BEGIN, $DT_END, $limit) {
        $arraySql = array();
        try {
            $query = "SELECT t.LG_TRAID, t.STR_TRAREFERENCE, t.STR_TRAPHONE, t.STR_OPEPHONE, t.DBL_TRAAMOUNT, t.STR_TRAOTHERVALUE, t.DT_TRACREATED, o.STR_OPENAME, o.STR_OPEDESCRIPTION, o.STR_OPEPIC, su.LG_SOCID, tt.STR_TTRDESCRIPTION FROM ".$this->Transaction." t, ".$this->Operateur." o, ".$this->SocieteUtilisateur." su, ".$this->Typetransaction." tt 
                WHERE t.LG_OPEID = o.LG_OPEID AND t.LG_SUTID = su.LG_SUTID AND t.LG_TTRID = tt.LG_TTRID AND su.LG_SOCID LIKE :LG_SOCID AND date(t.DT_TRACREATED) BETWEEN :DT_BEGIN AND :DT_END and t.STR_TRASTATUT = :STR_STATUT ORDER BY t.DBL_TRAAMOUNT DESC LIMIT " . $limit;
            $res = $this->dbconnnexion->prepare($query);
            //exécution de la requête
            $res->execute(array("LG_SOCID" => $LG_SOCID, "DT_BEGIN" => $DT_BEGIN, "DT_END" => $DT_END, 'STR_STATUT' => Parameters::$statut_enable));
            while ($rowObj = $res->fetch()) {
                $arraySql[] = $rowObj;
            }
            $res->closeCursor();
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $arraySql;
    }

    public function getProductStatViewed()
    {
        $validation = [];
        try {
            $query = "SELECT c.str_proname, COUNT(pist.lg_proid) AS count
                    FROM " . $this->Produit . " pro 
                     LEFT JOIN " . $this->PisteAudit . " pist ON pist.lg_proid = pro.lg_proid
                    GROUP BY pro.str_proname, pist.lg_proid";
            $res = $this->dbconnnexion->prepare($query);
            //exécution de la requête
            $res->execute();
            while ($rowObj = $res->fetch()) {
                $validation[] = $rowObj;
            }
            $res->closeCursor();
        } catch (Exception $exc) {
            error_log($exc->getTraceAsString());
        }

        return $validation;
    }
}
