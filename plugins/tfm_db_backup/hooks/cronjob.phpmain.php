<?php

defined('_VALID_CALL') or die('Direct Access is not allowed.');

if (defined('_SYSTEM_SECURITY_KEY')) {
    if($_GET['seckey'] != _SYSTEM_SECURITY_KEY){
        die('Access denied!');
    }
}

if (isset($_GET['create_backup'])){
    $tfm_bk_up = new tfm_db_backup;

    $tfm_bk_up->perform_backup();
}

