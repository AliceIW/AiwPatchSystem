<?php

/**
 * Description of run_dump
 *
 * @author AliceIW
 */
ini_set('display_errors', 'On');
error_reporting(E_ALL);

$databaseConfig = include '../tests/config/local.php';

try {
    $db = new PDO($databaseConfig['dsn'], $databaseConfig['username'], $databaseConfig['password'], $databaseConfig['options']);
} catch (PDOException $e) {
    throw new Exception($e->getMessage(), $e->getCode());
}
$dumpFile = file_get_contents('../data/dump.sql');

echo "Executing Dump.sql ...";
$db->query($dumpFile);
echo "Done!";

