<?php

interface CommandeInterface
{

    public function createOrderExternal($LG_CLIID, $STR_COMMNAME, $STR_COMMADRESSE, $STR_LIVADRESSE, $token);

    public function initCommande($LG_COMMID, $LG_AGEID, $STR_COMMNAME, $OUtilisateur);

    public function createCommande($LG_AGEID, $STR_COMMNAME, $STR_COMMADRESSE, $STR_LIVADRESSE, $OUtilisateur, $token);

    public function getLastCommandeByAgence($LG_AGEID, $STR_COMMSTATUT);

    public function getCommande($LG_COMMID);

    public function showAllOrOneCommande($search_value, $LG_CLIID, $start, $limit);

    public function totalCommande($search_value, $LG_CLIID);

    public function createOrderProduitExternal($LG_COMMID, $LG_CLIID, $LG_PROID, $INT_CPRQUANTITY, $token);

    public function initCommandeProduit($LG_CPRID, $LG_COMMID, $LG_PROID, $INT_CPRQUANTITY, $OUtilisateur);

    public function createCommandeProduit($LG_COMMID, $LG_CLIID, $LG_PROID, $INT_CPRQUANTITY, $OUtilisateur, $token);

    public function updateCommandeProduit($LG_CPRID, $INT_CPRQUANTITY, $OUtilisateur, $token);

    public function updateOrderProduitExternal($LG_CPRID, $LG_COMMID, $LG_CLIID, $INT_CPRQUANTITY, $token);

    public function getCommandeProduit($LG_COMMID, $LG_PROID);

    public function getCommandeProduitLight($LG_CPRID);

    public function deleteOrderProduitExternal($LG_CPRID, $LG_COMMID, $LG_CLIID, $token);

    public function deleteCommandeProduit($LG_CPRID, $token);

    public function showAllOrOneCommandeproduit($LG_CLIID, $LG_COMMID, $token);

    public function showAllCommandeproduit();

    public function getClientSolde($LG_CLIID);

    //moi
    public function handleCommande($LG_AGEID, $token, $OUtilisateur);

    public function getClientPlafond($LG_CLIID, $token = null);

    public function getExternalClientPanier($LG_CLIID, $LG_COMMID, $token = null);

    public function updateCommande($LG_COMMID, $DBL_COMMMTHT, $DBL_COMMMTTTC);

    public function getClientPanier($LG_AGEID);
}

class CommandeManager implements CommandeInterface
{

    private $Commande = 'commande';
    private $Commproduit = 'commproduit';
    private $Agence = 'agence';
    private $Societe = 'societe';
    private $OCommande = array();
    private $OCommproduit = array();
    private $OAgence = array();

    private $Produit = "produit";

    private $dbconnexion;

    //constructeur de la classe

    public function __construct()
    {
        $this->dbconnexion = DoConnexionPDO(Parameters::$host, Parameters::$user, Parameters::$pass, Parameters::$db, Parameters::$port);
    }

    public function createOrderExternal($LG_CLIID, $STR_COMMNAME, $STR_COMMADRESSE, $STR_LIVADRESSE, $token)
    {
        $ConfigurationManager = new ConfigurationManager();
        $validation = "";
        try {
            $OClient = $ConfigurationManager->getClient($LG_CLIID, $token);
            if ($OClient == null) {
                return $validation;
            }

            // URL de l'API
            $url = Parameters::$urlRootAPI . "/clients/" . $OClient->CliID . "/carts";

            // Headers de la requête
            $headers = array(
                'Accept: application/json',
                'Content-Type: application/x-www-form-urlencoded',
                "api_key: " . Parameters::$apikey,
                "token: " . $token
            );

            // Données à envoyer
            $data = array(
                "ref" => $STR_COMMNAME,
                "adrfac_id" => $STR_COMMADRESSE,
                "adrliv_id" => $STR_LIVADRESSE
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

            $validation = $obj->PcvID;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return $validation;
    }

    public function initCommande($LG_COMMID, $LG_AGEID, $STR_COMMNAME, $OUtilisateur)
    {
        $validation = "";
        //$LG_COMMID = generateRandomString(20);
        try {
            //echo $LG_COMMID . "===" . $LG_AGEID . "+++" . $STR_COMMNAME . "---" . $OUtilisateur[0][0];
            $params = array("lg_commid" => $LG_COMMID, "str_commname" => $STR_COMMNAME, "dt_commcreated" => get_now(), "str_commstatut" => Parameters::$statut_process,
                "lg_ageid" => $LG_AGEID, "lg_uticreatedid" => $OUtilisateur[0]['lg_utiid'], "lg_ageoriginid" => $LG_AGEID);
            //var_dump($params);
            if ($this->dbconnexion != null) {
                if (Persist($this->Commande, $params, $this->dbconnexion)) {
                    $validation = $LG_COMMID;
//                    Parameters::buildSuccessMessage("Société " . $STR_SOCDESCRIPTION . " effectuée avec succès.");
                } else {
                    Parameters::buildErrorMessage("Echec de création de la commande");
                }
            }
        } catch (Exception $exc) {
            error_log($exc->getTraceAsString());
            Parameters::buildErrorMessage("Echec de création de la commande. Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    public function createCommande($LG_AGEID, $STR_COMMNAME, $STR_COMMADRESSE, $STR_LIVADRESSE, $OUtilisateur, $token)
    {
        $ConfigurationManager = new ConfigurationManager();
        $validation = array();
        try {

            $OAgence = $ConfigurationManager->getAgence($LG_AGEID);
            if ($OAgence == null) {
                return $validation;
            }

            $this->OCommande = $this->getLastCommandeByAgence($OAgence[0]["lg_ageid"], Parameters::$statut_process);
            if ($this->OCommande == null) {
                $LG_COMMID = $this->createOrderExternal($OAgence[0]["lg_socextid"], $STR_COMMNAME, $STR_COMMADRESSE, $STR_LIVADRESSE, $token);

                $LG_COMMID = $this->initCommande($LG_COMMID, $OAgence[0]["lg_ageid"], $STR_COMMNAME, $OUtilisateur);
            } else {
                $LG_COMMID = $this->OCommande[0][0];
            }

            $validation["LG_COMMID"] = $LG_COMMID;
            $validation["LG_CLIID"] = $OAgence[0]["lg_socextid"];
//            var_dump($validation);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return $validation;
    }

    public function getLastCommandeByAgence($LG_AGEID, $STR_COMMSTATUT)
    {
        $arraySql = array();
        try {
            $query = "SELECT t.*, s.lg_socextid, s.lg_socid, s.dbl_socplafond FROM commande t, agence a, societe s WHERE t.lg_ageid = a.lg_ageid and a.lg_socid = s.lg_socid and t.lg_ageid = :LG_AGEID and t.str_commstatut = :STR_STATUT order by t.dt_commupdated DESC LIMIT " . Parameters::$PROCESS_SUCCESS;
            $res = $this->dbconnexion->prepare($query);
            //exécution de la requête
            $res->execute(array("LG_AGEID" => $LG_AGEID, "STR_STATUT" => $STR_COMMSTATUT));
            while ($rowObj = $res->fetch()) {
                $arraySql[] = $rowObj;
            }
            $res->closeCursor();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return $arraySql;
    }

    public function getCommande($LG_COMMID)
    {
        $validation = null;
        try {
            $params_condition = array("lg_commid" => $LG_COMMID, "str_commname" => $LG_COMMID);
            $validation = $this->OCommande = Find($this->Commande, $params_condition, $this->dbconnexion);
            if ($this->OCommande == null) {
                return $validation;
            }
            $validation = $this->OCommande;
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $validation;
    }

    public function showAllOrOneCommande($search_value, $LG_CLIID, $start, $limit)
    {
        $ConfigurationManager = new ConfigurationManager();
        $arraySql = array();
        $token = "";
        try {
            $token = $ConfigurationManager->generateToken();

            $url = Parameters::$urlRootAPI . "/clients/" . $LG_CLIID . "/carts";

            $headers = array(
                'Accept: application/json',
                'Content-Type: application/x-www-form-urlencoded',
                "api_key: " . Parameters::$apikey,
                "token: " . $token
            );

            // Initialisation de cURL
            $ch = curl_init($url);

            // Configuration de cURL
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $response = curl_exec($ch);
            curl_close($ch);

            $obj = json_decode($response);
            //var_dump($obj);
            // Vérifier si la conversion a réussi
            if ($obj === null && json_last_error() !== JSON_ERROR_NONE) {
                die('Erreur lors du décodage JSON');
            }

            $arraySql = $obj;
        } catch (Exception $exc) {
            error_log($exc->getTraceAsString());
        }
        return $arraySql;
    }

    public function totalCommande($search_value, $LG_AGEID)
    {

    }

    public function createCommandeProduit($LG_COMMID, $LG_CLIID, $LG_PROID, $INT_CPRQUANTITY, $OUtilisateur, $token)
    {
        //$ConfigurationManager = new ConfigurationManager();
        $validation = "";
        $LG_CPRID = "";
        $StockManager = new StockManager();
        try {
            $this->OCommproduit = $this->getCommandeProduit($LG_COMMID, $LG_PROID);

//            echo "+++".$LG_COMMID ."=====". $LG_PROID."----";
            if ($this->OCommproduit == null) {
                $ArtStk = (float)$StockManager->getProduct($LG_PROID, $token)->products[0]->ArtStk;
                if ($INT_CPRQUANTITY > $ArtStk) {
                    Parameters::buildErrorMessage("Echec d'ajout du produit a la commande. La quantité demandé dépasse le stock");
                    return $validation;
                }
                $LG_CPRID = $this->createOrderProduitExternal($LG_COMMID, $LG_CLIID, $LG_PROID, $INT_CPRQUANTITY, $token);
                //echo "====".$LG_CPRID."++++";
                if ($LG_CPRID == "") {
                    Parameters::buildErrorMessage("Echec d'ajout du produit a la commande. Une erreur est survenu sur votre commande");
                    return $validation;
                }
                $validation = $this->initCommandeProduit($LG_CPRID, $LG_COMMID, $LG_PROID, $INT_CPRQUANTITY, $OUtilisateur);
            } else {
                $LG_CPRID = $this->OCommproduit[0][0];
//                echo "=====".$validation;
                $this->updateCommandeProduit($LG_CPRID, (int)$this->OCommproduit[0]["int_cprquantity"] + (int)$INT_CPRQUANTITY, $OUtilisateur, $token);
                //TODO: A faire
//                $PanierClient = $this->getClientPanier($LG_CLIID, $LG_COMMID, $token);
//                $this->updateCommande($LG_COMMID, $PanierClient->pieces[0]->PcvMtHT, $PanierClient->pieces[0]->PcvMtTTC);
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return $validation;
    }

    public function createOrderProduitExternal($LG_COMMID, $LG_CLIID, $LG_PROID, $INT_CPRQUANTITY, $token)
    {
        $validation = "";
        try {
            // URL de l'API
            $url = Parameters::$urlRootAPI . "/clients/" . $LG_CLIID . "/carts/" . $LG_COMMID . "/lines";

            // Headers de la requête
            $headers = array(
                'Accept: application/json',
                'Content-Type: application/x-www-form-urlencoded',
                "api_key: " . Parameters::$apikey,
                "token: " . $token
            );

            // Données à envoyer
            $data = array(
                "art_id" => $LG_PROID,
                "qty" => $INT_CPRQUANTITY
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

            $validation = $obj->PlvID != null ? $obj->PlvID : "";
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return $validation;
    }

    public function getCommandeProduit($LG_COMMID, $LG_PROID)
    {
        $validation = null;
        try {
            $params_condition = array("lg_commid" => $LG_COMMID, "lg_proid" => $LG_PROID);
            $validation = $this->OCommproduit = Find($this->Commproduit, $params_condition, $this->dbconnexion);
            if ($this->OCommproduit == null) {
                return $validation;
            }
            $validation = $this->OCommproduit;
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $validation;
    }

    public function getCommandeProduitLight($LG_CPRID)
    {
        $arraySql = array();
        try {
            $query = "SELECT t.*, s.lg_socextid FROM " . $this->Commproduit . " t, " . $this->Commande . " c, " . $this->Agence . " a, " . $this->Societe . " s WHERE t.lg_commid = c.lg_commid and c.lg_ageid = a.lg_ageid and a.lg_socid = s.lg_socid and t.lg_cprid = :LG_CPRID";
            $res = $this->dbconnexion->prepare($query);
            //exécution de la requête
            $res->execute(array("LG_CPRID" => $LG_CPRID));
            while ($rowObj = $res->fetch()) {
                $arraySql[] = $rowObj;
            }
            $res->closeCursor();
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
        return $arraySql;
    }

    public function initCommandeProduit($LG_CPRID, $LG_COMMID, $LG_PROID, $INT_CPRQUANTITY, $OUtilisateur)
    {
        $validation = false;
        try {
            $params = array("lg_cprid" => $LG_CPRID, "lg_commid" => $LG_COMMID, "lg_proid" => $LG_PROID, "dt_cprcreated" => get_now(), "str_cprstatut" => Parameters::$statut_process,
                "int_cprquantity" => $INT_CPRQUANTITY, "lg_uticreatedid" => $OUtilisateur[0][0]);
//            var_dump($params);
            if ($this->dbconnexion != null) {
                if (Persist($this->Commproduit, $params, $this->dbconnexion)) {
                    $validation = true;
                    Parameters::buildSuccessMessage("Produit ajouté avec succès.");
                } else {
                    Parameters::buildErrorMessage("Echec d'ajout du produit à la commande");
                }
            }
        } catch (Exception $exc) {
            error_log($exc->getTraceAsString());
            Parameters::buildErrorMessage("Echec d'ajout du produit à la commande. Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    public function updateCommandeProduit($LG_CPRID, $INT_CPRQUANTITY, $OUtilisateur, $token)
    {
        $validation = "";
        $StockManager = new StockManager();
        try {
            $this->OCommproduit = $this->getCommandeProduitLight($LG_CPRID);
            if ($this->OCommproduit == null) {
                Parameters::buildErrorMessage("Echec de mise à jour du produit. Référence inexistante sur la commande");
                return $validation;
            }
            $ArtStk = (float)$StockManager->getProduct($this->OCommproduit[0]['lg_proid'], $token)->products[0]->ArtStk;
            if ($INT_CPRQUANTITY > $ArtStk) {
                Parameters::buildErrorMessage("Echec d'augmentation de la quantité du produit. La quantité voulue dépasse le stock");
                return $validation;
            }
            $this->updateOrderProduitExternal($LG_CPRID, $this->OCommproduit[0]["lg_commid"], $this->OCommproduit[0]["lg_socextid"], $INT_CPRQUANTITY, $token);
            //Mise à jour de la commande chez nous
            $PanierClient = $this->getExternalClientPanier($this->OCommproduit[0]["lg_socextid"], $this->OCommproduit[0]["lg_commid"], $token);
            $this->updateCommande($this->OCommproduit[0]["lg_commid"], $PanierClient->pieces[0]->PcvMtHT, $PanierClient->pieces[0]->PcvMtTTC);

            $params_condition = array("lg_cprid" => $this->OCommproduit[0][0]);
            $params_to_update = array("int_cprquantity" => $INT_CPRQUANTITY, "dt_cprupdated" => get_now(), "lg_utiupdateid" => $OUtilisateur[0][0]);

            if ($this->dbconnexion != null) {
                if (Merge($this->Commproduit, $params_to_update, $params_condition, $this->dbconnexion)) {
                    $validation = $this->OCommproduit[0]["lg_commid"];
                    Parameters::buildSuccessMessage("Mise à jour avec succès");
                } else {
                    Parameters::buildErrorMessage("Echec de mise à jour du produit");
                }
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
            Parameters::buildErrorMessage("Echec de mise à jour du produit. Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    public function updateOrderProduitExternal($LG_CPRID, $LG_COMMID, $LG_CLIID, $INT_CPRQUANTITY, $token)
    {
        $validation = "";
        try {
            // URL de l'API
            $url = Parameters::$urlRootAPI . "/clients/" . $LG_CLIID . "/carts/" . $LG_COMMID . "/lines/" . $LG_CPRID;

            // Headers de la requête
            $headers = array(
                'Accept: application/json',
                'Content-Type: application/x-www-form-urlencoded',
                "api_key: " . Parameters::$apikey,
                "token: " . $token
            );

            // Données à envoyer
            $data = array(
                "qty" => $INT_CPRQUANTITY
            );

            // Initialisation de cURL
            $ch = curl_init();

            // Configuration de cURL
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT'); // Méthode HTTP PUT
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

            $validation = $obj->qty;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return $validation;
    }

    public function deleteCommandeProduit($LG_CPRID, $token)
    {
        $validation = "";
        try {
            $this->OCommproduit = $this->getCommandeProduitLight($LG_CPRID);
            if ($this->OCommproduit == null) {
                Parameters::buildErrorMessage("Echec de suppression du produit. Référence inexistante sur la commande");
                return $validation;
            }
            // echo $this->OCommproduit[0]["lg_commid"] . "====" . $this->OCommproduit[0]["lg_socextid"];
            $this->deleteOrderProduitExternal($this->OCommproduit[0][0], $this->OCommproduit[0]["lg_commid"], $this->OCommproduit[0]["lg_socextid"], $token);

            //Mise à jour de la commande chez nous
            $PanierClient = $this->getExternalClientPanier($this->OCommproduit[0]["lg_socextid"], $this->OCommproduit[0]["lg_commid"], $token);
            $this->updateCommande($this->OCommproduit[0]["lg_commid"], $PanierClient->pieces[0]->PcvMtHT, $PanierClient->pieces[0]->PcvMtTTC);

            $params = array("lg_cprid" => $this->OCommproduit[0][0]);
            if (Remove($this->Commproduit, $params, $this->dbconnexion)) {
                $validation = $this->OCommproduit[0]["lg_commid"];
                Parameters::buildSuccessMessage("Suppression du produit avec succès");
            } else {
                Parameters::buildErrorMessage("Echec de suppression du produit");
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
            Parameters::buildErrorMessage("Echec de suppression du produit. Veuillez contacter votre administrateur");
        }
        return $validation;
    }

    public function deleteOrderProduitExternal($LG_CPRID, $LG_COMMID, $LG_CLIID, $token)
    {
        $validation = "";
        try {
            // URL de l'API
            $url = Parameters::$urlRootAPI . "/clients/" . $LG_CLIID . "/carts/" . $LG_COMMID . "/lines/" . $LG_CPRID;

            // Headers de la requête
            $headers = array(
                'Accept: application/json',
                'Content-Type: application/x-www-form-urlencoded',
                "api_key: " . Parameters::$apikey,
                "token: " . $token
            );

            // Données à envoyer
            $data = array();

            // Initialisation de cURL
            $ch = curl_init();

            // Configuration de cURL
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE'); // Méthode HTTP PUT
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

            $validation = $obj->PlvID;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return $validation;
    }

    public function showAllOrOneCommandeproduit($LG_CLIID, $LG_COMMID, $token)
    {
        $arraySql = array();
        try {
            $url = Parameters::$urlRootAPI . "/clients/" . $LG_CLIID . "/carts/" . $LG_COMMID . "/lines";

            $headers = array(
                'Accept: application/json',
                'Content-Type: application/x-www-form-urlencoded',
                "api_key: " . Parameters::$apikey,
                "token: " . $token
            );

            // Initialisation de cURL
            $ch = curl_init($url);

            // Configuration de cURL
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $response = curl_exec($ch);
            curl_close($ch);

            $obj = json_decode($response);
            //var_dump($obj);
            // Vérifier si la conversion a réussi
            if ($obj === null && json_last_error() !== JSON_ERROR_NONE) {
                die('Erreur lors du décodage JSON');
            }

            $arraySql = $obj;
        } catch (Exception $exc) {
            error_log($exc->getTraceAsString());
        }
        return $arraySql;
    }

    public function showAllCommandeproduit()
    {
        $arraySql = array();
        try {
            $query = "SELECT * 
                    FROM " . $this->Commande . " com 
                    INNER JOIN " . $this->Agence . " age ON com.lg_ageid = age.lg_ageid
                    INNER JOIN " . $this->Societe . " as soc ON soc.lg_socid = com.lg_cliid 
                    WHERE str_commstatut = 'process' 
                    ORDER BY dt_commcreated DESC";
            $res = $this->dbconnexion->prepare($query);
            $res->execute();
            while ($rowObj = $res->fetch(PDO::FETCH_ASSOC)) {
                $cliid = $rowObj['str_socname'];
                if (!isset($arraySql[$cliid])) {
                    $arraySql[$cliid] = array();
                }
                $arraySql[$cliid][] = $rowObj;
            }
            $res->closeCursor();

            // Requête externe pour récupérer une information supplémentaire
            foreach ($arraySql as $cliid => &$commandes) {
                $externalInfo = $this->getClientSolde(883)->clisolde;
                $commandes['clientEncours'] = $externalInfo;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return $arraySql;
    }

    public function getClientSolde($LG_CLIID)
    {
        $ConfigurationManager = new ConfigurationManager();
        $arraySql = array();
        $token = "";
        try {
            $token = $ConfigurationManager->generateToken();

            $url = Parameters::$urlRootAPI . "/clients/" . $LG_CLIID . "/encours";

            $headers = array(
                'Accept: application/json',
                'Content-Type: application/x-www-form-urlencoded',
                "api_key: " . Parameters::$apikey,
                "token: " . $token
            );

            // Initialisation de cURL
            $ch = curl_init($url);

            // Configuration de cURL
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $response = curl_exec($ch);
            curl_close($ch);

            $obj = json_decode($response);
            //var_dump($obj);
            // Vérifier si la conversion a réussi
            if ($obj === null && json_last_error() !== JSON_ERROR_NONE) {
                die('Erreur lors du décodage JSON');
            }

            $arraySql = $obj;
        } catch (Exception $exc) {
            error_log($exc->getTraceAsString());
        }
        return $arraySql;
    }

    public function handleCommande($LG_AGEID, $token, $OUtilisateur)
    {
        $validation = false;
        $mTTC = 0;
        $encours = 0;
        $plafond = 0;

        try {
            $this->OCommande = $this->getLastCommandeByAgence($LG_AGEID, Parameters::$statut_process);
            if ($this->OCommande == null) {
                Parameters::buildErrorMessage("Echec de validation de la commande. Commande innexistante");
                return $validation;
            }
            $LG_CLIID = $this->OCommande[0]["lg_socextid"];
            $mTTC = $this->getClientPanier($LG_AGEID)['dbl_commmtttc'];
//            var_dump($mTTC);
            $encours = $this->getClientSolde($LG_CLIID)->clisolde;
            $plafond = $this->OCommande[0]["dbl_socplafond"];

            if ($mTTC != null && $encours != null && ($mTTC + $encours > $plafond)) {
                Parameters::buildErrorMessage("Echec de validation de la commande. Le montant total de la commande dépasse le plafond autorisé");
                return $validation;
            } else {

                $params_condition = array("lg_commid" => $this->OCommande[0][0]);
                $params_to_update = array("str_commstatut" => Parameters::$statut_closed, "dt_commupdated" => get_now(), "lg_utiupdatedid" => $OUtilisateur[0][0]);

                if ($this->dbconnexion != null) {
                    if (Merge($this->Commande, $params_to_update, $params_condition, $this->dbconnexion)) {
                        $validation = true;
                        Parameters::buildSuccessMessage("Mise à jour de la commande effectuée avec succès.");
                    } else {
                        Parameters::buildErrorMessage("Echec de l'opération");
                    }
                }

            }

        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return $validation;
    }


    public function getClientPlafond($LG_CLIID, $token = null)
    {
        $ConfigurationManager = new ConfigurationManager();
        $arraySql = array();
        Parameters::buildSuccessMessage("Plafond obtenu avec succès.");
        try {
            $url = Parameters::$urlRootAPI . "/clients/" . $LG_CLIID . "?nb_by_page=10&page=1&ColSuppl=CliPlaf";
            $token = $token ?: $ConfigurationManager->generateToken();
            // Headers de la requête
            $headers = array(
                'Accept: application/json',
                'Content-Type: application/x-www-form-urlencoded',
                "api_key: " . Parameters::$apikey,
                "token: " . $token
            );

            // Initialisation de cURL
            $ch = curl_init();

            // Configuration de cURL
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Exécution de la requête
            $response = curl_exec($ch);

// Vérification des erreurs
            if (curl_errno($ch)) {
                echo 'Erreur cURL : ' . curl_error($ch);
            }

// Fermeture de la session cURL
            curl_close($ch);

            // Convertir le JSON en objet PHP
            $obj = json_decode($response);
//            var_dump($obj);
            // Vérifier si la conversion a réussi
            if ($obj === null && json_last_error() !== JSON_ERROR_NONE) {
                die('Erreur lors du décodage JSON');
            }
            $arraySql = $obj;
        } catch (Exception $exc) {
            error_log($exc->getTraceAsString());
        }

        return $arraySql;
    }

    public function getExternalClientPanier($LG_CLIID, $LG_COMMID, $token = null)
    {
        $ConfigurationManager = new ConfigurationManager();
        $arraySql = array();
        Parameters::buildSuccessMessage("Panier obtenu avec succès.");
        try {
            $url = Parameters::$urlRootAPI . "/clients/" . $LG_CLIID . "/carts/" . $LG_COMMID;
//            var_dump($url);
            $token = $token ?: $ConfigurationManager->generateToken();
            // Headers de la requête
            $headers = array(
                'Accept: application/json',
                'Content-Type: application/x-www-form-urlencoded',
                "api_key: " . Parameters::$apikey,
                "token: " . $token
            );

            // Initialisation de cURL
            $ch = curl_init();

            // Configuration de cURL
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Exécution de la requête
            $response = curl_exec($ch);

// Vérification des erreurs
            if (curl_errno($ch)) {
                echo 'Erreur cURL : ' . curl_error($ch);
            }

// Fermeture de la session cURL
            curl_close($ch);

            // Convertir le JSON en objet PHP
            $obj = json_decode($response);
//            var_dump($obj);
            // Vérifier si la conversion a réussi
            if ($obj === null && json_last_error() !== JSON_ERROR_NONE) {
                die('Erreur lors du décodage JSON');
            }
            $arraySql = $obj;
//            var_dump((int)$arraySql->pieces[0]->PcvMtHT);
        } catch (Exception $exc) {
            error_log($exc->getTraceAsString());
        }

        return $arraySql;
    }


    public function updateCommande($LG_COMMID, $DBL_COMMMTHT, $DBL_COMMMTTTC)
    {
        $validation = "";
        try {
            $params_condition = array("lg_commid" => $LG_COMMID);
            $params_to_update = array("dbl_commmtttc" => $DBL_COMMMTTTC, "dbl_commmtht" => $DBL_COMMMTHT, "dt_commupdated" => get_now());

            if ($this->dbconnexion != null) {
                if (Merge($this->Commande, $params_to_update, $params_condition, $this->dbconnexion)) {
                    $validation = $LG_COMMID;
                    Parameters::buildSuccessMessage("Mise à jour de la commande effectuée avec succès.");
                } else {
                    Parameters::buildErrorMessage("Echec de l'opération");
                }
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return $validation;
    }

    public function getClientPanier($LG_AGEID)
    {
        $validation = array();
        try {
            $value = $this->getLastCommandeByAgence($LG_AGEID, Parameters::$statut_process);
            if(empty($array)){
                Parameters::buildErrorMessage("Aucun panier ouvert");
                return $validation;
            }

            $query = "SELECT * FROM " . $this->Commande . " c INNER JOIN " . $this->Commproduit . " cp ON c.lg_commid = cp.lg_commid INNER JOIN " . $this->Produit . " p ON cp.lg_proid = p.lg_proid  WHERE c.lg_commid = :LG_COMMID";
            $res = $this->dbconnexion->prepare($query);
            $res->execute(array("LG_COMMID" => $value[0]['lg_commid']));
            $panier = array();
            while ($rowObj = $res->fetch(PDO::FETCH_ASSOC)) {
                if (empty($panier)) {
                    $panier = [
                        'lg_commid' => $rowObj['lg_commid'],
                        'str_commname' => $rowObj['str_commname'],
                        'dt_commcreated' => $rowObj['dt_commcreated'],
                        'dt_commupdated' => $rowObj['dt_commupdated'],
                        'str_commstatut' => $rowObj['str_commstatut'],
                        'lg_ageid' => $rowObj['lg_ageid'],
                        'lg_uticreatedid' => $rowObj['lg_uticreatedid'],
                        'lg_ageoriginid' => $rowObj['lg_ageoriginid'],
                        'dbl_commmtht' => $rowObj['dbl_commmtht'],
                        'dbl_commmtttc' => $rowObj['dbl_commmtttc'],
                    ];
                }
                $panier['produits'][] = [
                    'lg_cprid' => $rowObj['lg_cprid'],
                    'lg_proid' => $rowObj['lg_proid'],
                    'int_cprquantity' => $rowObj['int_cprquantity'],
                    'str_proname' => $rowObj['str_proname'],
                    'str_prodescription' => $rowObj['str_prodescription'],
                    'int_propricevente' => $rowObj['int_propricevente'],
                    'str_procateg' => $rowObj['str_procateg'],
                    'str_profamille' => $rowObj['str_profamille'],
                    'str_progamme' => $rowObj['str_progamme'],
                    'str_propic' => $rowObj['str_propic'],
                ];
            }
            $validation = $panier;
            $res->closeCursor();

        } catch (Exception $exc) {
            var_dump($exc->getTraceAsString());
        }
        return $validation;
    }

}
