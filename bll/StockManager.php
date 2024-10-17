<?php

interface StockInterface
{

    public function createProduct($LG_PROID, $STR_PRONAME, $STR_PRODESCRIPTION, $INT_PROPRICEVENTE, $INT_PROPRICEACHAT, $STR_PROCATEG, $STR_PROFAMILLE, $STR_PROGAMME);

    public function getProduct($LG_PROID, $token = null);

    public function showAllOrOneProduct($search_value);

    public function showAllOrOneProductRemote($search_value, $start, $limit);

    public function totalProduct($search_value);

    public function loadExternalProduct();


}

class StockManager implements StockInterface
{

    private $Produit = 'produit';
    private $OProduit = array();

    private $Document = "document";

    private $ProduitSubstitution = "produit_substitution";
    private $dbconnexion;

    //constructeur de la classe
    public function __construct()
    {
        $this->dbconnexion = DoConnexionPDO(Parameters::$host, Parameters::$user, Parameters::$pass, Parameters::$db, Parameters::$port);
    }

    public function createProduct($LG_PROID, $STR_PRONAME, $STR_PRODESCRIPTION, $INT_PROPRICEACHAT, $INT_PROPRICEVENTE, $STR_PROCATEG, $STR_PROFAMILLE, $STR_PROGAMME)
    {
        $validation = false;
        try {
            $params = array("lg_proid" => $LG_PROID, "str_proname" => $STR_PRONAME, "dt_procreated" => get_now(), "str_prostatut" => Parameters::$statut_enable,
                "str_prodescription" => $STR_PRODESCRIPTION, "int_propriceachat" => $INT_PROPRICEACHAT, "int_propricevente" => $INT_PROPRICEVENTE, "str_procateg" => $STR_PROCATEG, "str_profamille" => $STR_PROFAMILLE, "str_progamme" => $STR_PROGAMME);
            //var_dump($params);
            if ($this->dbconnexion != null) {
                if (Persist($this->Produit, $params, $this->dbconnexion)) {
                    $validation = true;
                }
            }
        } catch (Exception $exc) {
            var_dump($exc->getTraceAsString());
        }
        return $validation;
    }

    public function getProduct($LG_PROID, $token = null)
    {

        $ConfigurationManager = new ConfigurationManager();
        $arraySql = array();
        try {
            $token = $token ?: $ConfigurationManager->generateToken();

            $url = Parameters::$urlRootAPI . "/products/" . $LG_PROID . "?ColSuppl=ArtCategEnu,ArtFamilleEnu,ArtGammeEnu,ARTFREE0,ARTFREE1,ARTFREE2,ARTFREE3,ARTFREE4,ARTFREE5";

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


            $query = "SELECT * 
                    FROM " . $this->Produit . " p
                    LEFT JOIN " . $this->Document . " d ON p.lg_proid = d.p_key
                    WHERE p.lg_proid = :LG_PROID ";
            $res = $this->dbconnexion->prepare($query);
            //exécution de la requête
            $res->execute(array('LG_PROID' => $LG_PROID));;
            while ($rowObj = $res->fetch()) {
                if (!property_exists($obj, 'str_propic')) {
                    $obj->products[0]->str_propic = $rowObj['str_propic'];

                }
                if (!property_exists($obj, 'str_prospecifications')) {
                    $obj->products[0]->str_prospecifications = $rowObj['str_prospecifications'];
                }
                if (!property_exists($obj, 'str_prodetails')) {
                    $obj->products[0]->str_prodetails = $rowObj['str_prodetails'];
                }

                $src = Parameters::$rootFolderRelative . "produits/" . "$LG_PROID/" . $rowObj['str_docpath'];
                $exists = false;
                if (property_exists($obj->products[0], 'gallerie')) {
                    foreach ($obj->products[0]->gallerie as $item) {
                        if ($item['src'] === $src) {
                            $exists = true;
                            break;
                        }
                    }
                    if (!$exists) {
                        $obj->products[0]->gallerie[] = array(
                            "src" => $src,
                            "lg_docid" => $rowObj['lg_docid']
                        );
                    }
                } else {
                    $obj->products[0]->gallerie[] = array(
                        "src" => $src,
                        "lg_docid" => $rowObj['lg_docid']
                    );
                }
            }
            $query = "SELECT *
                    FROM " . $this->ProduitSubstitution . " ps
                    LEFT JOIN " . $this->Produit . " p ON ps.lg_prokidid = p.lg_proid
                    LEFT JOIN document d ON ps.lg_prokidid = d.p_key
                    WHERE ps.lg_proparentid = :LG_PROID AND ps.str_prosubstatut = :STR_STATUT";
            $res = $this->dbconnexion->prepare($query);
            //exécution de la requête
            $res->execute(array('LG_PROID' => $LG_PROID, 'STR_STATUT' => Parameters::$statut_enable));
            if ($res->fetch() > 0) {
                while ($rowObj = $res->fetch()) {
                    $obj->products[0]->products[] = array(
                        "ArtLib" => $rowObj['str_prodescription'] ?: null,
                        "lg_prosubid" => $rowObj['lg_prosubid'] ?: null,
                        "ArtID" => $rowObj['lg_prokidid'] ?: null,
                        "ArtCode" => $rowObj['str_proname'] ?: null,
                        "ArtCategEnu" => $rowObj['str_procateg'] ?: null,
                        "ArtFamilleEnu" => $rowObj['str_profamille'] ?: null,
                        "ArtGammeEnu" => $rowObj['str_progamme'] ?: null,
                        "ArtLastPA" => $rowObj['int_propriceachat'] ?: null,
                        "ArtGPicID" => Parameters::$rootFolderRelative . "produits/" . $rowObj['lg_prokidid'] . "/" . $rowObj['str_propic']
                    );
                }
            } else {
                $obj->products[0]->products = [];
            }

            $arraySql = $obj;
        } catch (Exception $exc) {
            var_dump($exc->getTraceAsString());
        }
        return $arraySql;
    }

    public function showAllOrOneProduct($search_value)
    {
        $arraySql = array();
        try {
            $query = "SELECT * FROM produit t WHERE (t.str_prodescription LIKE :search_value OR t.str_proname LIKE :search_value) AND t.str_prostatut = :STR_STATUT ORDER BY t.str_prodescription";
            $res = $this->dbconnexion->prepare($query);
            //exécution de la requête
            $res->execute(array('search_value' => "%" . $search_value . "%", 'STR_STATUT' => Parameters::$statut_enable));
            while ($rowObj = $res->fetch()) {
                $arraySql[] = $rowObj;
            }
            $res->closeCursor();
        } catch (Exception $exc) {
            var_dump($exc->getTraceAsString());
        }
        return $arraySql;
    }

    public function totalProduct($search_value)
    {
        $result = 0;
        try {
            $query = "SELECT COUNT(t.lg_proid) NOMBRE FROM produit t WHERE (t.str_prodescription LIKE :search_value OR t.str_proname LIKE :search_value) AND t.str_prostatut = :STR_STATUT";
            $res = $this->dbconnnexion->prepare($query);
            //exécution de la requête
            $res->execute(array('search_value' => "%" . $search_value . "%", 'STR_STATUT' => Parameters::$statut_enable));
            while ($rowObj = $res->fetch()) {
                $result = $rowObj["NOMBRE"];
            }
            $res->closeCursor();
        } catch (Exception $exc) {
            var_dump($exc->getTraceAsString());
        }
        return $result;
    }

    public function showAllOrOneProductRemote($search_value, $start, $limit)
    {
        $ConfigurationManager = new ConfigurationManager();
        $arraySql = array();
        $token = "";
        try {
            $token = $ConfigurationManager->generateToken();

            $url = Parameters::$urlRootAPI . "/products?nb_by_page=" . $limit . "&page=" . $start . "&ColSuppl=ArtCategEnu,ArtFamilleEnu,ArtGammeEnu,ARTFREE0,ARTFREE1,ARTFREE2,ARTFREE3,ARTFREE4,ARTFREE5";

            $headers = array(
                'Accept: application/json',
                'Content-Type: application/x-www-form-urlencoded',
                "api_key: " . Parameters::$apikey,
                "token: " . $token
            );

            //echo $data->orderId;
//            var_dump($headers);
//            echo json_encode($dataSend);
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
//            var_dump($arraySql);
            /* foreach ($obj as $value) { //a decommenter en cas de probleme
              $arrayJson_chidren = array();
              $arrayJson_chidren["LG_PROID"] = $value['lg_proid'];
              $arrayJson_chidren["STR_PROPIC"] = $value['str_propic'];
              $arrayJson_chidren["STR_PRONAME"] = $value['str_proname'];
              $arrayJson_chidren["STR_PRODESCRIPTION"] = $value['str_prodescription'];
              //        $arrayJson_chidren["OPEPIC"] = Parameters::$rootFolderRelative . "logos/" . $value['STR_OPEPIC'];
              //        $arrayJson_chidren["TRACREATED"] = DateToString($value['DT_TRACREATED'], 'd/m/Y H:i:s');
              $arrayJson_chidren["STR_PROEAN13"] = $value['str_proean13'];
              //$arrayJson_chidren["STR_PROPIC_DATA_TABLE"] = $value['str_propic'] != null ? "<img class='img-30' src='images/product/".$value['str_propic']."' alt='' style='width:30%;border-radius:5px;'>" : "<img class='img-30' src='images/product/profile.png' alt='' style='width:30%;border-radius:5px;'>";
              $arrayJson_chidren["INT_PROPRICEACHAT"] = $value['int_propriceachat'];
              $arrayJson_chidren["INT_PROPRICEVENTE"] = $value['int_propricevente'];
              $arrayJson_chidren["str_ACTION"] = "<div class='d-flex'><a href='javascript:void(0);' class='btn btn-primary shadow btn-xs sharp mr-1' title='Modification des informations de " . $value['str_proname'] . "'><i class='fa fa-pencil'></i></a><a href='javascript:void(0);' class='btn btn-warning shadow btn-xs sharp' title='Consultation des informations de " . $value['str_proname'] . "'><i class='fa fa-folder-o'></i></a></div>";
              $OJson[] = $arrayJson_chidren;
              } */

            /* $query = "SELECT * FROM ".$this->Produit." t WHERE (t.str_prodescription LIKE :search_value OR t.str_proname LIKE :search_value) AND t.str_prostatut = :STR_STATUT ORDER BY t.str_proname";
              $res = $this->dbconnnexion->prepare($query);
              //exécution de la requête
              $res->execute(array('search_value' => "%" . $search_value . "%", 'STR_STATUT' => Parameters::$statut_enable));
              while ($rowObj = $res->fetch()) {
              $arraySql[] = $rowObj;
              }
              $res->closeCursor(); */
        } catch (Exception $exc) {
            var_dump($exc->getTraceAsString());
        }
        return $arraySql;
    }

    public function loadExternalProduct()
    {
        $arrayJson = array();
        try {
            $arrayJson = $this->showAllOrOneProductRemote("", 1, 500);
//            var_dump($arrayJson->products);
//            echo count($arrayJson->products);
            foreach ($arrayJson->products as $value) {
                //echo $value->ArtID."<br/>";
                $this->createProduct($value->ArtID, $value->ArtCode, $value->ArtLib, $value->ArtLastPA, $value->ArtPrixBase,
                    $value->ArtCategEnu, $value->ArtFamilleEnu, $value->ArtGammeEnu);
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }


}
