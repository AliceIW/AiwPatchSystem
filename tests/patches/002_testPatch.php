<?php

class Patch_002_testPatch extends \AiwPatchSystem\Patches\AbstractPatcher {

    public function execute() {
        $this->pdo->query("
            CREATE TABLE IF NOT EXISTS `patch_table_2` (
                `patch_table_2_id` int(11) NOT NULL AUTO_INCREMENT,
                `patch_table_2_name` varchar(255) NOT NULL,
                PRIMARY KEY (`patch_table_2_id`)
              ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
            ");
        $this->pdo->query("INSERT INTO patch_table_2 VALUES(2,'test')");
    }

    public function undo() {
        
    }

}
