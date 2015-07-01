<?php

defined('_VALID_CALL') or die('Direct Access is not allowed.');

if ($request['get'] =='plg_tfm_db_backup_mode'){
    $result = array(
        array('id' => 'exec', 'name' => 'exec'),
        array('id' => 'fallback', 'name' => 'fallback'),
    );
}