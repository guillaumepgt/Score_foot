<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/DB/db.php';

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <script src="DB/query.js" defer></script>
</head>
<body>
<button onclick="searchTeam(idequipe)">Rechercher l’équipe</button>
<div id="root"></div>
</body>
</html>