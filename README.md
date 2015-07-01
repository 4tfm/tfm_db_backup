# tfm_db_backup
Simple Backup automation(Database) Plugin for xt:Commerce 4

## Installation:
Download & install the plugin in you xt:Commerce 4 Onlineshop
( General installation manual: http://wiki.4tfm.de/pages/viewpage.action?pageId=1081399 )

## How to user:
After the plugin is activated, you can start a backup with following URL:
http://your-shop.de/cronjob.php?create_backup=1&seckey=here-your-sec-key

The backup files will be stored in plugin_cache/db_backups/ 

## Options:
you can choose between two modes:
"exec" will create a backup with MySql's "mysqldump"-command.
( Very fast an reliable, but doesn't work on crappy servers... )

"fallback" will read the Database and create a backup-file.
( This functionality has BETA status )

Every time when the plugin performs a Backup you'll receive a mail confirmation. 

## FAQ

### Can you Install this plugin in my Shop?
Sure, contact us: http://4tfm.de/

### No files are stored in plugin_cache/db_backups/. What can cause this?
- Make sure the folder is writabel
- enable error-reporting and run the backup. 
 
### How to enable error reporting for xt:Commerce 4?
See: https://xtcommerce.atlassian.net/wiki/pages/viewpage.action?pageId=22151181

### When I start the backup the message "Access denied!" is displayed, what to do?
Make sure you have set the "seckey" Parameter in the URL correctly.
( Use "SELECT * FROM `xt_config` WHERE `config_key` LIKE '_SYSTEM_SECURITY_KEY'; " in your Database to see your security key )

