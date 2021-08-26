<?php

ini_set('max_execution_time', 1200);

include_once('conf/dbconf.php');
include_once('lib/Mysqldump.php');
include_once('controller/BackupController.php');

$selectedComponent = isset($_POST['chooseBackup']) ? $_POST['chooseBackup'] : false;

if ($selectedComponent) {
    $backup = new BackupController($selectedComponent);

    try {
        $backup->createMysqlDump();
        header('Location: http://localhost:8888/backuptool/public/view/success.html');
        header("Refresh:0");
    }
    catch (Exception $e) {
        echo $e->getMessage();
    }
}
else {
    echo "Keine Komponente ausgewählt";
    exit;
}




