<?php 
$installer = $this;

$installer->startSetup();


$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('ifuturz_searchbysize')};
CREATE TABLE {$this->getTable('ifuturz_searchbysize')} (
  `searchsize_id` int(11) unsigned NOT NULL auto_increment,
	`width` varchar(255),
	`depth` varchar(255),
	`height` varchar(255),
  PRIMARY KEY (`searchsize_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");

$installer->endSetup(); 
