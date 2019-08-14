<?php 
$installer = $this;

$installer->startSetup();


$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('searchbysize_lck')};
CREATE TABLE {$this->getTable('searchbysize_lck')} ( 	
	`flag` varchar(4),
	`value` ENUM('0','1') DEFAULT '0' NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `{$installer->getTable('searchbysize_lck')}` VALUES ('LCK','1');
");

$installer->endSetup(); 
