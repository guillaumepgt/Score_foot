<?php
require("request_foot.php");
require("update_db.php");

$competitionsCodes = ['FL1', 'PL'];

foreach ($competitionsCodes as $code) {
    echo "<hr><h2>--- Traitement : $code ---</h2>";

    // 1. On récupère les infos de la compétition elle-même
    // L'URL API : https://api.football-data.org/v4/competitions/FL1
    $compInfo = request("competitions/$code");

    if (isset($compInfo['id'])) {
        // Sauvegarde de la compétition
        update_competition_db($compInfo);

        // On garde l'ID API (ex: 2015) pour le passer aux matchs
        $apiCompetitionId = $compInfo['id'];
    } else {
        echo "❌ Impossible de récupérer les infos de la compétition $code.<br>";
        continue; // On passe à la suivante
    }

    // 2. Récupération des Équipes
    echo "<em>Récupération des équipes...</em><br>";
    $teamResult = request("competitions/$code/teams");
    if (isset($teamResult['teams'])) {
        update_teams_db($teamResult['teams']);
    }

    // 3. Récupération des Matchs
    echo "<em>Récupération des matchs...</em><br>";
    $matchResult = request("competitions/$code/matches");
    if (isset($matchResult['matches'])) {
        // IMPORTANT : On passe l'ID de la compétition à la fonction
        update_matches_db($matchResult['matches'], $apiCompetitionId);
    }

    sleep(1); // Pause API
}

echo "<br><strong>Terminé !</strong>";
?>