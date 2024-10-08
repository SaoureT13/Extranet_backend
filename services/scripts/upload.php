<?php

$targetDir = '../../reports/import/';

if (!empty($_FILES)) {
    $targetFile = $targetDir.pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION)."/".time().'-'.$_FILES['file']['name'];
    move_uploaded_file($_FILES['file']['tmp_name'], $targetFile);
} 
