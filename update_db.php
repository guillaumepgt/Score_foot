<?php

function update_db($data, $table)
{
    $host = 'lamp_db';
    $db   = 'mabdd';
    $user = 'irdw';
    $pass = 'network';

    $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
    } catch (\PDOException $e) {
        die("Erreur de connexion BDD : " . $e->getMessage());
    }

    $sql = "INSERT INTO matches (api_match_id, home_team, away_team, score_home, score_away, status, match_date) 
        VALUES (:api_id, :home, :away, :s_home, :s_away, :status, :m_date)
        ON DUPLICATE KEY UPDATE 
            score_home = VALUES(score_home), 
            score_away = VALUES(score_away), 
            status = VALUES(status),
            updated_at = NOW()";

    $stmt = $pdo->prepare($sql);

    foreach ($matchesFromApi as $match) {
        $dateObj = new DateTime($match['utcDate']);
        $formattedDate = $dateObj->format('Y-m-d H:i:s');

        $stmt->execute([
            ':api_id' => $match['id'],
            ':home'   => $match['homeTeam']['name'],
            ':away'   => $match['awayTeam']['name'],
            ':s_home' => $match['score']['fullTime']['home'],
            ':s_away' => $match['score']['fullTime']['away'],
            ':status' => $match['status'],
            ':m_date' => $formattedDate
        ]);

        echo "Match " . $match['homeTeam']['name'] . " traité.<br>";
    }

    echo "Mise à jour de la base de données terminée !";

}
?>
