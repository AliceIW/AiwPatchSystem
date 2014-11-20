<?php

class Patch_001_testPatch extends \AiwPatchSystem\Patches\AbstractPatcher {

    public function execute() {
        $this->pdo->query("
            CREATE TABLE IF NOT EXISTS `patch_table_1` (
                `patch_table_1_id` int(11) NOT NULL AUTO_INCREMENT,
                `patch_table_1_name` varchar(255) NOT NULL,
                PRIMARY KEY (`patch_table_1_id`)
              ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
            ");
        
        $this->pdo->query("INSERT INTO patch_table_1 VALUES(1,'test')");
        
    }

    public function undo() {
        
    }

}
