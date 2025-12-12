<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$bdd = array(
    'host'     => 'localhost',
    'username' => 'football_user',
    'password' => 'foot1234',
    'database' => 'football_db'
);

$mysqli = new mysqli(
    $bdd['host'],
    $bdd['username'],
    $bdd['password'],
    $bdd['database']
);

if ($mysqli->connect_errno) {
    die("Erreur de connexion MySQL : (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
}
?>
