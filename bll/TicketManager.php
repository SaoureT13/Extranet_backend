<?php

interface TicketInterface {

    public function createEvenement($LG_LSTID, $STR_EVENAME, $STR_EVEDESCRIPTION, $LG_LSTPLACEID, $DT_EVEBEGIN, $DT_EVEEND, $HR_EVEBEGIN, $HR_EVEEND, $STR_EVEPIC, $STR_EVEBANNER, $STR_EVEANNONCEUR, $LG_AGEID, $STR_EVEDISPLAYROOM, $STR_EVESTATUTFREE, $LG_LSTCATEGORIEPLACEID, $OUtilisateur);

    public function updateEvenement($LG_EVEID, $LG_LSTID, $STR_EVENAME, $STR_EVEDESCRIPTION, $LG_LSTPLACEID, $DT_EVEBEGIN, $DT_EVEEND, $HR_EVEBEGIN, $HR_EVEEND, $STR_EVEPIC, $STR_EVEBANNER, $STR_EVEANNONCEUR, $LG_AGEID, $STR_EVEDISPLAYROOM, $STR_EVESTATUTFREE, $LG_LSTCATEGORIEPLACEID, $OUtilisateur);

    public function getEvenement($LG_EVEID);

    public function deleteEvenement($LG_EVEID, $STR_EVESTATUT, $OUtilisateur);

    public function showAllOrOneEvenement($search_value, $LG_LSTID, $DT_BEGIN, $DT_END, $start, $limit);

    public function showAllOrOneEvenementFront($search_value, $LG_LSTID, $DT_BEGIN, $DT_END, $start, $limit);

    public function totalEvenement($search_value, $LG_LSTID, $DT_BEGIN, $DT_END);

    public function createTicket($LG_EVEID, $LG_LSTID, $STR_TICPHONE, $STR_TICMAIL, $DBL_TICAMOUNT, $STR_CURRENCY, $STR_PROVIDER);

    public function getTicket($LG_TICID);

    public function showAllOrOneTicket($search_value, $LG_EVEID, $LG_LSTID, $LG_AGEID, $LG_CLIID, $DT_BEGIN, $DT_END, $start, $limit);

    public function totalTicket($search_value, $LG_EVEID, $LG_LSTID, $LG_AGEID, $LG_CLIID, $DT_BEGIN, $DT_END);

    public function createCategorieplaceEvenement($LG_EVEID, $LG_LSTID, $INT_ELINUMBER, $INT_ELINUMBERMAX, $DBL_ELIAMOUNT, $OUtilisateur);

    public function deleteCategorieplaceEvenement($LG_EVEID, $OUtilisateur);

    public function deleteGlobalCategorieplaceEvenement($LG_EVEID, $OUtilisateur);

    public function showAllOrOneCategorieplaceEvenement($search_value, $LG_EVEID);
    //fin code ajouté
}

class TicketManager implements TicketInterface {

    private $Ticket = 'ticket';
    private $Evenement = 'evenement';
    private $Liste = 'liste';
    private $Eveliste = 'eveliste';
    private $OTicket = array();
    private $OEvenement = array();
    private $OListe = array();
    private $OEveliste = array();
    private $dbconnnexion;

    //constructeur de la classe 
    public function __construct() {
        $this->dbconnnexion = DoConnexionPDO(Parameters::$host, Parameters::$user, Parameters::$pass, Parameters::$db, Parameters::$port);
    }

    //gestion des evenements
    public function createEvenement($LG_LSTID, $STR_EVENAME, $STR_EVEDESCRIPTION, $LG_LSTPLACEID, $DT_EVEBEGIN, $DT_EVEEND, $HR_EVEBEGIN, $HR_EVEEND, $STR_EVEPIC, $STR_EVEBANNER, $STR_EVEANNONCEUR, $LG_AGEID, $STR_EVEDISPLAYROOM, $STR_EVESTATUTFREE, $LG_LSTCATEGORIEPLACEID, $OUtilisateur) {
        $validation = false;
        $LG_EVEID = generateRandomString(40);
        $ConfigurationManager = new ConfigurationManager();
        try {
            $this->OListe = $ConfigurationManager->getListe($LG_LSTID);
            if ($this->OListe == null) {
                Parameters::buildErrorMessage("Echec d'enregistrement de l'evenement. Categorie inexistante");
                return $validation;
            }
            $params = array("lg_eveid" => $LG_EVEID, "lg_lstid" => $this->OListe[0][0], "str_evename" => $STR_EVENAME, "str_evedescription" => $STR_EVEDESCRIPTION, "lg_lstplaceid" => $LG_LSTPLACEID, "dt_evebegin" => $DT_EVEBEGIN, "dt_eveend" => $DT_EVEEND, "hr_evebegin" => $HR_EVEBEGIN,
                "hr_eveend" => $HR_EVEEND, "str_evepic" => $STR_EVEPIC, "str_evebanner" => $STR_EVEBANNER,
                "str_eveannonceur" => $STR_EVEANNONCEUR, "lg_ageid" => $LG_AGEID, "str_evedisplayroom" => $STR_EVEDISPLAYROOM, "str_evestatutfree" => $STR_EVESTATUTFREE,
                "lg_uticreatedid" => $OUtilisateur[0][0], "str_evestatut" => Parameters::$statut_enable, "dt_evecreated" => get_now());

            if ($this->dbconnnexion != null) {
                if (Persist($this->Evenement, $params, $this->dbconnnexion)) {
                    if ($LG_LSTCATEGORIEPLACEID != "") {//A REVOIR EN URGENCE
//                        foreach ($fruits as $fruit) {
//                            echo "Name: " . $fruit['name'] . "<br>";
//                            echo "Color: " . $fruit['color'] . "<br>";
//                            echo "Taste: " . $fruit['taste'] . "<br>";
//                            echo "<br>";
//                        }

                        foreach ($LG_LSTCATEGORIEPLACEID as $obj) {
                            $this->createCategorieplaceEvenement($LG_EVEID, $obj->LG_LSTID, $obj->INT_ELINUMBER, $obj->INT_ELINUMBERMAX, $obj->DBL_ELIAMOUNT, $OUtilisateur);
                        }
                    }
                    $validation = true;
                    Parameters::buildSuccessMessage("Evenement " . $STR_EVENAME . " enregistre avec succès.");
                } else {
                    Parameters::buildErrorMessage("Echec d'enregistrement de l'evenement");
                }
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
            $exc->getTraceAsString();
            Parameters::buildErrorMessage("Echec d'enregistrement de l'evenement. Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    public function updateEvenement($LG_EVEID, $LG_LSTID, $STR_EVENAME, $STR_EVEDESCRIPTION, $LG_LSTPLACEID, $DT_EVEBEGIN, $DT_EVEEND, $HR_EVEBEGIN, $HR_EVEEND, $STR_EVEPIC, $STR_EVEBANNER, $STR_EVEANNONCEUR, $LG_AGEID, $STR_EVEDISPLAYROOM, $STR_EVESTATUTFREE, $LG_LSTCATEGORIEPLACEID, $OUtilisateur) {
        $validation = false;
        $ConfigurationManager = new ConfigurationManager();
        try {
            $this->OEvenement = $this->getEvenement($LG_EVEID);

            if ($this->OEvenement == null) {
                Parameters::buildErrorMessage("Echec de mise à jour. Evenement inexistant");
                return $validation;
            }

            $this->OListe = $ConfigurationManager->getListe($LG_LSTID);
            if ($this->OListe == null) {
                Parameters::buildErrorMessage("Echec d'enregistrement de l'evenement. Categorie inexistante");
                return $validation;
            }

            $params_condition = array("lg_eveid" => $this->OEvenement[0][0]);
            $params_to_update = array("lg_lstid" => $this->OListe[0][0], "str_evename" => $STR_EVENAME, "str_evedescription" => $STR_EVEDESCRIPTION, "lg_lstplaceid" => $LG_LSTPLACEID, "dt_evebegin" => $DT_EVEBEGIN, "dt_eveend" => $DT_EVEEND, "hr_evebegin" => $HR_EVEBEGIN,
                "hr_eveend" => $HR_EVEEND, "str_evepic" => $STR_EVEPIC, "str_evebanner" => $STR_EVEBANNER,
                "str_eveannonceur" => $STR_EVEANNONCEUR, "lg_ageid" => $LG_AGEID, "str_evedisplayroom" => $STR_EVEDISPLAYROOM, "str_evestatutfree" => $STR_EVESTATUTFREE,
                "lg_utiupdatedid" => $OUtilisateur[0][0], "dt_eveupdated" => get_now());

            if ($this->dbconnnexion != null) {
                if (Merge($this->Evenement, $params_to_update, $params_condition, $this->dbconnnexion)) {
                    if ($LG_LSTCATEGORIEPLACEID != "") {//A REVOIR EN URGENCE
//                        foreach ($fruits as $fruit) {
//                            echo "Name: " . $fruit['name'] . "<br>";
//                            echo "Color: " . $fruit['color'] . "<br>";
//                            echo "Taste: " . $fruit['taste'] . "<br>";
//                            echo "<br>";
//                        }

                        foreach ($LG_LSTCATEGORIEPLACEID as $obj) {
                            $this->createCategorieplaceEvenement($this->OEvenement[0][0], $obj->LG_LSTID, $obj->INT_ELINUMBER, $obj->INT_ELINUMBERMAX, $obj->DBL_ELIAMOUNT, $OUtilisateur);
                        }
                    }
                    $validation = true;
                    Parameters::buildSuccessMessage("Evenement " . $STR_EVENAME . " mise a jour avec succès.");
                } else {
                    Parameters::buildErrorMessage("Echec de mise a jour de l'evenement");
                }
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
            $exc->getTraceAsString();
            Parameters::buildErrorMessage("Echec de mise a jour de l'evenement. Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    public function getEvenement($LG_EVEID) {
        $validation = null;
        Parameters::buildErrorMessage("Evenement inexistant");
        try {
            $params_condition = array("LG_EVEID" => $LG_EVEID, "STR_EVENAME" => $LG_EVEID);
            $validation = $this->OEvenement = Find($this->Evenement, $params_condition, $this->dbconnnexion, "OR");

            if ($this->OEvenement == null) {
                return $validation;
            }
            Parameters::buildSuccessMessage("Evenement N°" . $this->OEvenement[0][2] . " trouvé");
            $validation = $this->OEvenement;
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $validation;
    }

    public function deleteEvenement($LG_EVEID, $STR_EVESTATUT, $OUtilisateur) {
        $validation = false;
        try {
            $this->OEvenement = $this->getEvenement($LG_EVEID);

            if ($this->OEvenement == null) {
                Parameters::buildErrorMessage("Echec de l'operation. Evenement inexistant");
                return $validation;
            }

            $params_condition = array("lg_eveid" => $this->OEvenement[0][0]);
            $params_to_update = array("lg_utiupdatedid" => $OUtilisateur[0][0], "str_evestatut" => $STR_EVESTATUT, "dt_eveupdated" => get_now());

            if ($this->dbconnnexion != null) {
                if (Merge($this->Evenement, $params_to_update, $params_condition, $this->dbconnnexion)) {
                    $validation = true;
                    Parameters::buildSuccessMessage("Operation effectuee avec succès.");
                } else {
                    Parameters::buildErrorMessage("Echec de l'opération. Veuillez réessayer svp");
                }
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
            $exc->getTraceAsString();
            Parameters::buildErrorMessage("Echec de l'opération. Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    public function showAllOrOneEvenement($search_value, $LG_LSTID, $DT_BEGIN, $DT_END, $start, $limit) {
        $arraySql = array();
        try {
            $query = "SELECT t.*, p.str_lstdescription lg_lstplaceid FROM " . $this->Evenement . " t, (select * from " . $this->Liste . " li where li.lg_tylid = :LG_TYLID) p WHERE p.lg_lstid = t.lg_lstplaceid AND (t.str_evename like :search_value or t.str_evedescription like :search_value or t.str_eveannonceur like :search_value) and t.lg_lstid like :LG_LSTID and t.str_evestatut = :STR_STATUT and (t.dt_evebegin BETWEEN :DT_BEGIN AND :DT_END) LIMIT " . $start . "," . $limit;
            $res = $this->dbconnnexion->prepare($query);
            //exécution de la requête
            $res->execute(array('search_value' => "%" . $search_value . "%", "LG_LSTID" => $LG_LSTID, "DT_BEGIN" => $DT_BEGIN, "DT_END" => $DT_END, 'STR_STATUT' => Parameters::$statut_enable, 'LG_TYLID' => Parameters::$typelisteKey[2]));
            while ($rowObj = $res->fetch()) {
                $arraySql[] = $rowObj;
            }
            $res->closeCursor();
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $arraySql;
    }

    public function showAllOrOneEvenementFront($search_value, $LG_LSTID, $DT_BEGIN, $DT_END, $start, $limit) {
        $arraySql = array();
        try {
            //echo $search_value . "===" . $LG_LSTID . "+++" . $DT_BEGIN . "---" . $DT_END . "===" . $start . "***" . $limit;
            $query = "SELECT t.*, l.lg_lstid, l.str_lstdescription, p.str_lstdescription lg_lstplaceid FROM " . $this->Evenement . " t, " . $this->Liste . " l, (select * from " . $this->Liste . " li where li.lg_tylid = :LG_TYLID) p WHERE t.lg_lstid = l.lg_lstid and p.lg_lstid = t.lg_lstplaceid and (t.str_evename like :search_value or t.str_evedescription like :search_value or t.str_eveannonceur like :search_value) and l.str_lstothervalue like :LG_LSTID and t.str_evestatut = :STR_STATUT and (t.dt_evebegin BETWEEN :DT_BEGIN AND :DT_END) LIMIT " . $start . "," . $limit;
            $res = $this->dbconnnexion->prepare($query);
            //exécution de la requête
            $res->execute(array('search_value' => "%" . $search_value . "%", "LG_LSTID" => $LG_LSTID, "DT_BEGIN" => $DT_BEGIN, "DT_END" => $DT_END, 'STR_STATUT' => Parameters::$statut_enable, 'LG_TYLID' => Parameters::$typelisteKey[2]));
            while ($rowObj = $res->fetch()) {
                $arraySql[] = $rowObj;
            }
            $res->closeCursor();
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $arraySql;
    }

    public function totalEvenement($search_value, $LG_LSTID, $DT_BEGIN, $DT_END) {
        $result = 0;
        try {
            $query = "SELECT COUNT(t.lg_eveid) NOMBRE FROM " . $this->Evenement . " t WHERE (t.str_evename like :search_value or t.str_evedescription like :search_value or t.str_eveannonceur like :search_value) and t.lg_lstid like :LG_LSTID and t.str_evestatut = :STR_STATUT and (t.dt_evebegin BETWEEN :DT_BEGIN AND :DT_END)";
            $res = $this->dbconnnexion->prepare($query);
            //exécution de la requête
            $res->execute(array('search_value' => "%" . $search_value . "%", "LG_LSTID" => $LG_LSTID, "DT_BEGIN" => $DT_BEGIN, "DT_END" => $DT_END, 'STR_STATUT' => Parameters::$statut_enable));
            while ($rowObj = $res->fetch()) {
                $result = $rowObj["NOMBRE"];
            }
            $res->closeCursor();
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $result;
    }

    public function createTicket($LG_EVEID, $LG_LSTID, $STR_TICPHONE, $STR_TICMAIL, $DBL_TICAMOUNT, $STR_CURRENCY, $STR_PROVIDER) {
        $validation = false;
        $LG_TICID = generateRandomString(20);
        $STR_TICNAME = generateRandomString(5);
        $STR_TICBARECODE = "";
        $STR_TICUUID = "";
        $ConfigurationManager = new ConfigurationManager();
        $PaymentManager = new PaymentManager();
        try {
            $this->OEvenement = $this->getEvenement($LG_EVEID);
            if ($this->OEvenement == null) {
                Parameters::buildErrorMessage("Echec de l'operation. Evenement inexistant");
                return $validation;
            }

            $this->OListe = $ConfigurationManager->getListe($LG_LSTID);
            if ($this->OListe == null) {
                Parameters::buildErrorMessage("Echec de l'operation. Categorie de siege inexistante");
                return $validation;
            }

            $STR_TICUUID = generate_uuid() . "\n" . $STR_TICNAME;
            $STR_TICBARECODE = $LG_TICID . ".png";
            generate_qr_code($text, Parameters::$path_import . $LG_TICID . ".png"); //generation de code barre

            $params = array("lg_ticid" => $LG_TICID, "lg_eveid" => $this->OEvenement[0][0], "lg_lstid" => $this->OListe[0][0], "str_ticname" => $STR_TICNAME, "str_ticphone" => $STR_TICPHONE, "str_ticmail" => $STR_TICMAIL, "str_ticbarecode" => $STR_TICBARECODE, "dt_ticcreated" => get_now(),
                "dbl_ticamount" => (float) $DBL_TICAMOUNT, "str_ticuuid" => $STR_TICUUID, "str_ticstatut" => Parameters::$statut_enable);
            if ($this->dbconnnexion != null) {
                if (Persist($this->Ticket, $params, $this->dbconnnexion)) {
                    //$validation = true;

                    $validation = $PaymentManager->doPayment($STR_PROVIDER, $params, $STR_CURRENCY);
                    if ($validation) {
                        Parameters::buildSuccessMessage("Paiement effectue avec succès.");
                    } else {
                        Parameters::buildErrorMessage("Echec de paiement. Veuillez reessayer svp");
                    }
                } else {
                    Parameters::buildErrorMessage("Echec de paiement. Veuillez reessayer svp");
                }
            }
        } catch (Exception $exc) {
            var_dump($exc);
            $exc->getTraceAsString();
            Parameters::buildErrorMessage("Echec de paiement. Veuillez reessayer ulterieurement");
        }
        return $validation;
    }

    public function getTicket($LG_TICID) {
        $validation = null;
        Parameters::buildErrorMessage("Ticket inexistant");
        try {
            $params_condition = array("lg_ticid" => $LG_TICID, "str_ticuuid" => $LG_TICID);
            $validation = $this->OTicket = Find($this->Ticket, $params_condition, $this->dbconnnexion, "OR");

            if ($this->OTicket == null) {
                return $validation;
            }
            Parameters::buildSuccessMessage("Ticket " . $this->OTicket[0][4] . " trouvée");
            $validation = $this->OTicket;
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $validation;
    }

    public function showAllOrOneTicket($search_value, $LG_EVEID, $LG_LSTID, $LG_AGEID, $LG_CLIID, $DT_BEGIN, $DT_END, $start, $limit) {
        $arraySql = array();
        try {
            $query = "SELECT t.*, e.*, l.str_lstdescription FROM " . $this->Ticket . " t, " . $this->Evenement . " e, " . $this->Liste . " l WHERE t.lg_eveid = e.lg_eveid and t.lg_lstid = l.lg_lstid and (t.str_ticname like :search_value or t.str_ticphone like :search_value or t.str_ticmail like :search_value or e.str_evename like :search_value) "
                    . "and t.lg_eveid like :LG_EVEID and t.lg_lstid like :LG_LSTID and e.lg_ageid LIKE :LG_AGEID "//and t.lg_cliid like :LG_CLIID "
                    . "and t.str_ticstatut like :STR_STATUT and (t.dt_ticupdated BETWEEN :DT_BEGIN AND :DT_END) order by t.dt_ticupdated DESC LIMIT " . $start . "," . $limit;
            $res = $this->dbconnnexion->prepare($query);
            //exécution de la requête
            $res->execute(array('search_value' => "%" . $search_value . "%", "LG_EVEID" => $LG_EVEID, "LG_LSTID" => $LG_LSTID, "LG_AGEID" => $LG_AGEID, // "LG_CLIID" => $LG_CLIID, 
                "DT_BEGIN" => $DT_BEGIN, "DT_END" => $DT_END, 'STR_STATUT' => Parameters::$statut_enable));
            while ($rowObj = $res->fetch()) {
                $arraySql[] = $rowObj;
            }
            $res->closeCursor();
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $arraySql;
    }

    public function totalTicket($search_value, $LG_EVEID, $LG_LSTID, $LG_AGEID, $LG_CLIID, $DT_BEGIN, $DT_END) {
        $result = 0;
        try {
            $query = "SELECT COUNT(t.lg_ticid) NOMBRE FROM " . $this->Ticket . " t, " . $this->Evenement . " e, " . $this->Liste . " l WHERE t.lg_eveid = e.lg_eveid and t.lg_lstid = l.lg_lstid and (t.str_ticname like :search_value or t.str_ticphone like :search_value or t.str_ticmail like :search_value or e.str_evename like :search_value) and t.lg_eveid like :LG_EVEID and t.lg_lstid like :LG_LSTID and e.lg_ageid LIKE :LG_AGEID and t.lg_cliid like :LG_CLIID and t.str_ticstatut like :STR_STATUT and (t.dt_ticupdated BETWEEN :DT_BEGIN AND :DT_END)";
            $res = $this->dbconnnexion->prepare($query);
            //exécution de la requête
            $res->execute(array('search_value' => "%" . $search_value . "%", "LG_EVEID" => $LG_EVEID, "LG_LSTID" => $LG_LSTID, "LG_AGEID" => $LG_AGEID, "LG_CLIID" => $LG_CLIID, "DT_BEGIN" => $DT_BEGIN, "DT_END" => $DT_END, 'STR_STATUT' => Parameters::$statut_enable));
            while ($rowObj = $res->fetch()) {
                $result = $rowObj["NOMBRE"];
            }
            $res->closeCursor();
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $result;
    }

    public function createCategorieplaceEvenement($LG_EVEID, $LG_LSTID, $INT_ELINUMBER, $INT_ELINUMBERMAX, $DBL_ELIAMOUNT, $OUtilisateur) {
        $validation = false;
        $LG_ELIID = generateRandomString(40);
        $ConfigurationManager = new ConfigurationManager();
        try {
            $this->OEvenement = $this->getEvenement($LG_EVEID);
            if ($this->OEvenement == null) {
                Parameters::buildErrorMessage("Echec de l'operation. Evenement inexistant");
                return $validation;
            }

            $this->OListe = $ConfigurationManager->getListe($LG_LSTID);
            if ($this->OListe == null) {
                Parameters::buildErrorMessage("Echec de l'operation. Categorie de siege inexistante");
                return $validation;
            }

            $params = array("lg_eliid" => $LG_ELIID, "lg_eveid" => $this->OEvenement[0][0], "lg_lstid" => $this->OListe[0][0], "int_elinumber" => $INT_ELINUMBER, "int_elinumbermax" => $INT_ELINUMBERMAX, "dbl_eliamount" => $DBL_ELIAMOUNT, "dt_elicreated" => get_now(),
                "dbl_eliamount" => (float) $DBL_ELIAMOUNT, "str_elistatut" => Parameters::$statut_enable, "lg_uticreatedid" => $OUtilisateur[0][0]);

            if ($this->dbconnnexion != null) {
                if (Persist($this->Eveliste, $params, $this->dbconnnexion)) {
                    $validation = true;
                    Parameters::buildSuccessMessage("Operation effectuee avec succès.");
                } else {
                    Parameters::buildErrorMessage("Echec de l'operation. Veuillez reessayer svp");
                }
            }
        } catch (Exception $exc) {
            var_dump($exc);
            $exc->getTraceAsString();
            Parameters::buildErrorMessage("Echec de l'operation. Veuillez reessayer ulterieurement");
        }
        return $validation;
    }

    public function deleteCategorieplaceEvenement($LG_EVEID, $OUtilisateur) {
        $validation = false;
        try {
            $params_condition = array("lg_eveid" => $LG_EVEID);
            $this->OEveliste = Find($this->Eveliste, $params_condition, $this->dbconnnexion);

            if ($this->OEveliste == null) {
                Parameters::buildErrorMessage("Echec de suppression. Evenement inexistant");
                return $validation;
            }

            $params_condition = array("LG_ELIID" => $this->OEveliste[0][0]);

            if ($this->dbconnnexion != null) {
                if (Remove($this->Eveliste, $params_condition, $this->dbconnnexion)) {
                    $validation = true;
                    Parameters::buildSuccessMessage("Suppression effectuee avec succès");
                } else {
                    Parameters::buildErrorMessage("Echec de l'operation. Veuillez reessayer svp");
                }
            }
        } catch (Exception $exc) {
            $exc->getTraceAsString();
            Parameters::buildErrorMessage("Echec de suppression. Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    public function deleteGlobalCategorieplaceEvenement($LG_EVEID, $OUtilisateur) {
        $validation = false;
        try {
            $listEveliste = $this->showAllOrOneCategorieplaceEvenement("", $LG_EVEID);
            foreach ($listEveliste as $value) {
                if (!$this->deleteCategorieplaceEvenement($value['lg_eliid'], $OUtilisateur)) {
                    return $validation;
                }
            }
            $validation = true;
        } catch (Exception $exc) {
            $exc->getTraceAsString();
            Parameters::buildErrorMessage("Echec de l'operation. Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    public function showAllOrOneCategorieplaceEvenement($search_value, $LG_EVEID) {
        $arraySql = array();
        try {
            $query = "SELECT t.*, l.str_lstvalue, l.str_lstdescription FROM eveliste t, liste l WHERE t.lg_lstid = l.lg_lstid and l.str_lstdescription LIKE :search_value and t.lg_eveid LIKE :LG_EVEID and t.str_elistatut = :STR_STATUT";
            $res = $this->dbconnnexion->prepare($query);
            //exécution de la requête
            $res->execute(array('search_value' => "%" . $search_value . "%", "LG_EVEID" => $LG_EVEID, "LG_LSTID" => $LG_LSTID, 'STR_STATUT' => Parameters::$statut_enable));
            while ($rowObj = $res->fetch()) {
                $arraySql[] = $rowObj;
            }
            $res->closeCursor();
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $arraySql;
    }

    //fin gestion des evenements
}
