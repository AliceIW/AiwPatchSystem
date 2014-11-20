<?php

namespace AiwPatchSystem;

class PatcherParser {

    protected $pdo;
    protected $patcherPath;
    protected $dbPatchesTable;
    protected $loadedPatches = [];

    function __construct(\PDO $pdo, $dbPatchesTable = 'db_patches') {
        $this->pdo = $pdo;
        $this->dbPatchesTable = $dbPatchesTable;
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->initPatchesTable();
    }

    protected function initPatchesTable() {
        $this->pdo->query("
            CREATE TABLE IF NOT EXISTS `{$this->dbPatchesTable}` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `date_created` date DEFAULT NULL,
              `name` varchar(255) DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            ");
    }

    public function executePatches() {
        chdir($this->getPatcherPath());
        $patchesFiles = glob('*Patch.php', GLOB_BRACE);
        $this->loadPatches($patchesFiles);

        foreach ($this->loadedPatches as $patchName => $patch) {
            try {
                if (!$this->patchHasBeenExecuted($patchName)) {
                    $patch->execute();
                    $this->updateDbPatchesTable($patchName);
                }
            } catch (\PDOException $e) {
                throw new \Exception('PDO Exception: ' . $e->getMessage());
            }
        }
    }

    protected function patchHasBeenExecuted($patchName) {
        $result = $this->pdo->query("SELECT id FROM {$this->dbPatchesTable} WHERE `name` LIKE '{$patchName}'")->fetch(\PDO::FETCH_ASSOC);
        if (isset($result['id'])) {
            return true;
        }
        return false;
    }

    protected function updateDbPatchesTable($name) {
        $dateObj = new \DateTime();
        $curDate = $dateObj->format('y-m-d');
        $this->pdo->query("INSERT INTO {$this->dbPatchesTable} (`date_created`,`name`) VALUES('{$curDate}','{$name}')");
    }

    protected function loadPatches($patchesFiles) {
        foreach ($patchesFiles as $patchFile) {
            $this->loadedPatches[substr($patchFile, 0, -4)] = $this->loadPatch($patchFile, $this->getClassName($patchFile));
        }
    }

    protected function getClassName($patchFile) {
        return 'Patch_' . substr($patchFile, 0, -4);
    }

    protected function loadPatch($file, $class) {
        if (!class_exists($class)) {
            include("$file");
        }
        $patch = new $class();
        $patch->setPdo($this->pdo);
        return $patch;
    }

    public function setPatcherPath($path) {
        $this->patcherPath = $path;
        return $this;
    }

    public function getPatcherPath() {
        return $this->patcherPath;
    }

}
