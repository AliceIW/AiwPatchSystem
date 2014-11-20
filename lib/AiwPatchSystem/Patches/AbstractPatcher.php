<?php

/*
 *  @author AliceIw
 */

namespace AiwPatchSystem\Patches;

abstract class AbstractPatcher {

    protected $pdo;    

    function setPdo(\PDO $pdo) {
        $this->pdo = $pdo;
    }

    abstract function execute();
   
}
