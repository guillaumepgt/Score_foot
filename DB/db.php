<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$bdd = array(
    'host'     => 'lamp_db',
    'username' => 'irdw',
    'password' => 'network',
    'database' => 'mabdd'
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
