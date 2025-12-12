<?php

$host = 'localhost';
$db   = 'votre_base_de_donnees';
$user = 'votre_user';
$pass = 'votre_password';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
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

$matchesFromApi = [
    [
        'id' => 45678,
        'homeTeam' => ['name' => 'PSG'],
        'awayTeam' => ['name' => 'OM'],
        'score' => ['fullTime' => ['home' => 2, 'away' => 1]],
        'status' => 'IN_PLAY',
        'utcDate' => '2023-10-24T20:00:00Z'
    ],
    [
        'id' => 45679,
        'homeTeam' => ['name' => 'Lyon'],
        'awayTeam' => ['name' => 'Lille'],
        'score' => ['fullTime' => ['home' => 0, 'away' => 0]], 
        'status' => 'SCHEDULED',
        'utcDate' => '2023-10-24T18:00:00Z'
    ]
];


$sql = "INSERT INTO matches (api_match_id, home_team, away_team, score_home, score_away, status, match_date) 
        VALUES (:api_id, :home, :away, :s_home, :s_away, :status, :m_date)
        ON DUPLICATE KEY UPDATE 
            score_home = VALUES(score_home), 
            score_away = VALUES(score_away), 
            status = VALUES(status),
            updated_at = NOW()";

$stmt = $pdo->prepare($sql);

foreach ($matchesFromApi as $match) {
    // On formate la date pour MySQL
    $dateObj = new DateTime($match['utcDate']);
    $formattedDate = $dateObj->format('Y-m-d H:i:s');

    // On exécute la requête pour chaque match
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

?>
