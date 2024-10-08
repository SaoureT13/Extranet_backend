<?php
//fonction d'auto chargement des classes
function my_autoload($class) { //ex $class = 'UserManager.php'
    include '../../backoffice/bll/' . $class . '.php';
}

spl_autoload_register('my_autoload');

//fin fonction d'auto chargement des classes
//fonction de deboogage
function debug($tableau) {
    echo '<pre>';
    print_r($tableau);
    echo '</pre>';
}

//fin fonction de deboogage
//ouverture de session
//session_start();
