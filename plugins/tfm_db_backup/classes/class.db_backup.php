<?php

defined('_VALID_CALL') or die('Direct Access is not allowed.');


class tfm_db_backup {


    function get_tables($tables='all'){
        global $db;
        //get all of the tables
        if($tables == 'all')
        {
            $tables = array();
            $rs = $db->execute('SHOW TABLES');
			
            while (!$rs->EOF) {
                $tables[] = $rs->fields['Tables_in_'._SYSTEM_DATABASE_DATABASE];
                $rs->MoveNext();
            }$rs->Close();
        }
        else
        {
        // eventually implement single table backups...
        }
        return $tables;

    }
	
	function write_backup_exec(){
																																																										
		passthru('/usr/bin/mysqldump --opt --user='._SYSTEM_DATABASE_USER.' --password='._SYSTEM_DATABASE_PWD.' --host='._SYSTEM_DATABASE_HOST.' '._SYSTEM_DATABASE_DATABASE.' > '._SRV_WEBROOT. 'plugin_cache/db_backups/db-backup-'.time().'-.sql');   
	}

	
	// this function creates a backup on low permission environments. 
    function write_backup_php ($tables=null){
        global $db;

        $content = '';
        foreach($tables as $table)
        {

           # $content .= 'DROP TABLE '.$table.';';
            $row = $db->execute('SHOW CREATE TABLE '.$table);
            $content .= "\n\n".$row->fields['Create Table'].";\n\n";

            $rs = $db->execute('SELECT * FROM '.$table);

            while (!$rs->EOF) {
                $content .= 'INSERT INTO '.$table.' ';
                $keys = '';
                $values = '';
                foreach ($rs->fields as $key => $value) {

                    $keys .= '`'.addslashes($key) .'`,';

                    if ( is_null($value)){
                        $values .= 'NULL,';
                    }else{
                        $values .= '"'.addslashes($value) .'",';
                    }
                    $row = '('.substr($keys, 0, -1).') VALUES ('.substr($values, 0, -1).')';


                }
                $content .= $row;
                $content = substr($content, 0, -1);
                $content .= ");\n";

            $rs->MoveNext();
        }$rs->Close();
            $content.="\n\n\n";
        }
        return $content;

    }

    function write_file($contents){
        if(!file_exists(_SRV_WEBROOT. '/plugin_cache/db_backups/')){
          echo mkdir(_SRV_WEBROOT. '/plugin_cache/db_backups/', 0777, true);
          echo chmod(_SRV_WEBROOT. '/plugin_cache/db_backups/', 0777);
        }
        $handle = fopen(_SRV_WEBROOT. '/plugin_cache/db_backups/db-backup-'.time().'-.sql','w+');
        fwrite($handle,$contents);
        fclose($handle);
        return 'done';

    }

    function perform_backup(){
        $tables = $this->get_tables('all');

		if (TFM_DB_BACKUP_MODE == 'fallback'){
			
			// write_backup_php() performs a simple (more time consuming backup) 
			// Functionality is BETA!!
			$content = $this->write_backup_php($tables);
			$this->write_file($content);
		}else{
			
			// write_backup_exec() performs a classic mysqldump
			$content = $this->write_backup_exec($tables);
		}
		$this->notify_admin();
    }
	
	function notify_admin(){
		
		$content = "Backup successfully executed!";
		$body_html = "<p>Backup successfully executed!</p>";
		$mail = new xtMailer('none');
		$mail->_addReceiver(_CORE_DEBUG_MAIL_ADDRESS,_STORE_NAME);
		$mail->_setSubject('Backup ausgeführt');
		$mail->_setContent($content,$body_html);
		$mail->_setFrom(_STORE_CONTACT_EMAIL,_STORE_NAME);
		$mail->_sendMail()	;
		
	}


}
