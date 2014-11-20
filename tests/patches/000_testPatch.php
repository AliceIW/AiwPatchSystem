<?php

class Patch_000_testPatch extends \AiwPatchSystem\Patches\AbstractPatcher {

    public function execute() {
        $this->pdo->query("
            CREATE TABLE IF NOT EXISTS FAILTABLE `patch_table_0` (
                `patch_table_1_id` int(11) NOT NULL AUTO_INCREMENT,
                `patch_table_1_name` varchar(255) NOT NULL,
                PRIMARY KEY (`patch_table_1_id`)
              ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
            ");
        
        $this->pdo->query("INSERT INTO patch_table_0 VALUES(1,'test')");
        
    }

    public function undo() {
        
    }

}
