RENAME TABLE `#__adh_adherents_origines` TO  `#__adh_origines` ;
RENAME TABLE `#__adh_adherents_professions` TO `#__adh_professions` ;
ALTER TABLE  `#__adh_origines` CHANGE  `creation_date`  `creation_date` DATETIME NULL DEFAULT NULL ;
ALTER TABLE  `#__adh_origines` CHANGE  `modification_date`  `modification_date` DATETIME NULL DEFAULT NULL ;