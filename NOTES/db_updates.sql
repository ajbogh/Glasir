/* 
   Date added: 2011-07-18 11:01:17 
   Added by Allan Bogh
   Required for updating file information
*/
ALTER TABLE `Songs` ADD COLUMN `LastUpdated` DATETIME AFTER `BPM`;
