<?php

/*
 *  @author AliceIw
 */
namespace AiwPatchSystem;

class PatcherParserTest extends \TestDbAcle\PhpUnit\AbstractTestCase {

    protected $patcherParser;
    protected $dbConfig;
    protected $pdo;

    function __construct() {
        $this->dbConfig = include __DIR__ . "/config/local.php";
    }

    public function providePdo() {
        if (!isset($this->pdo)) {
            $this->pdo = new \PDO($this->dbConfig['dsn'], $this->dbConfig['username'], $this->dbConfig['password'], $this->dbConfig['options']);
        }
        return $this->pdo;
    }

    public function setUp() {
        parent::Setup();
        $pdo = $this->providePdo();
        $pdo->query("TRUNCATE TABLE db_patches");
        $pdo->query("DROP TABLE patch_table_1");
        $pdo->query("DROP TABLE patch_table_2");

        $this->patcherParser = new PatcherParser($pdo);
    }

    public function test_setPatcherPath() {
        $this->patcherParser->setPatcherPath('/something');

        $this->assertEquals('/something', $this->patcherParser->getPatcherPath());
    }

    public function test_executePatches() {
        $this->setupTables("
            [db_patches]
            id              |date_created   |name
            1               |2014-10-10     |000_testPatch
            ");
        $this->patcherParser->setPatcherPath(__DIR__ . '/patches')
                ->executePatches();
    
        $dateObj = new \DateTime();
        $curDate = $dateObj->format('Y-m-d');
        $this->assertTableStateContains("
            [db_patches]
            id              |date_created   |name
            1               |2014-10-10     |000_testPatch
            2               |{$curDate}     |001_testPatch
            3               |{$curDate}     |002_testPatch
            4               |{$curDate}     |003_testPatch
            [patch_table_1]
            patch_table_1_id    |patch_table_1_name
            1                   |test
            [patch_table_2]
            patch_table_2_id    |patch_table_2_name
            2                   |test
            3                   |testwww
        ");
    }

    public function test_executePatches_skipPatches() {
        $this->setupTables("
            [db_patches]
            id              |date_created   |name
            1               |2011-10-10     |000_testPatch
            2               |2011-10-10     |003_testPatch
            ");

        $this->patcherParser->setPatcherPath(__DIR__ . '/patches')
                ->executePatches();

        $dateObj = new \DateTime();
        $curDate = $dateObj->format('Y-m-d');
        $this->assertTableStateContains("
            [db_patches]
            id              |date_created   |name
            1               |2011-10-10     |000_testPatch
            2               |2011-10-10     |003_testPatch
            3               |{$curDate}     |001_testPatch
            4               |{$curDate}     |002_testPatch
            [patch_table_1]
            patch_table_1_id    |patch_table_1_name
            1                   |test
            [patch_table_2]
            patch_table_2_id    |patch_table_2_name
            2                   |test
        ");
    }
    

    public function test_executePatches_ExceptionPatch() {

        $this->setExpectedException("Exception","PDO Exception: SQLSTATE[42000]: Syntax error or access violation: 1064 You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '`patch_table_0` (
                `patch_table_1_id` int(11) NOT NULL AUTO_INCRE' at line 1");
        
        $this->patcherParser->setPatcherPath(__DIR__ . '/patches')
                ->executePatches();

    }
}
