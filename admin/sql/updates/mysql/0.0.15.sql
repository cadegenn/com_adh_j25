ALTER TABLE  `#__adh_adherents` CHANGE  `cp`  `cp` VARCHAR( 16 ) NULL DEFAULT NULL;
ALTER TABLE  `#__adh_cotisations` ADD  `payee` TINYINT( 1 ) UNSIGNED NULL AFTER  `date_fin_cotiz`;