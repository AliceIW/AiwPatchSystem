<?php

class Patch_003_testPatch extends \AiwPatchSystem\Patches\AbstractPatcher {

    public function execute() {
        $this->pdo->query("INSERT INTO patch_table_2 VALUES(3,'testwww')");
    }

}
