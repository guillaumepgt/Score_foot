<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');
ini_set('display_startup_errors', 'On');

session_start();

$infoBdd = ['server' => 'db',
    'login' => 'user',
    'password' => 'password',
    'db_name' => 'mabdd', ];

$mysqli = new mysqli($infoBdd['server'], $infoBdd['login'], $infoBdd['password'], $infoBdd['db_name']);
if ($mysqli->connect_errno) {
    exit('Problème de connexion à la BDD');
}

if (!empty($_GET['logout']) && $_GET['logout'] == 1) {
    unset($_SESSION['compte']);
    header('Location: ./');
}