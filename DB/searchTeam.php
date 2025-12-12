<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once "db.php";

if (isset($_POST['idEquipe'])) {
    $idEquipe = (int)$_POST['idEquipe'];

    $sql = "SELECT m.home_team, m.away_team, m.score_home, m.score_away, m.status, m.match_date 
            FROM matches m
            WHERE m.home_team = $idEquipe OR m.away_team = $idEquipe
            ORDER BY m.match_date DESC";

    $result = $mysqli->query($sql);

    if ($result) {
        echo '<div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px; display: flex; align-items: center;">';

        while ($row = $result->fetch_assoc()) {

            echo '<div>';
            echo '<strong>' . htmlspecialchars($row['home_team']) . '</strong> vs <strong>' . htmlspecialchars($row['away_team']) . '</strong><br>';
            echo 'Score: ' . htmlspecialchars($row['score_home']) . ' - ' . htmlspecialchars($row['score_away']) . '<br>';
            echo 'Status: ' . htmlspecialchars($row['status']) . '<br>';
            echo 'Date: ' . htmlspecialchars($row['match_date']) . '<br>';
            echo '</div>';        
        }
        echo '</div>';

    } else {
        echo "Erreur dans la requête SQL : " . $mysqli->error;
    }

} else {
    echo "Aucun ID équipe fourni.";
}
?>