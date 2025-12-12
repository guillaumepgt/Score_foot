<?php

// Fonction interne pour se connecter à la BDD (évite de répéter le code)
function get_pdo_connection() {
    $host = 'lamp_db';
    $db   = 'mabdd';
    $user = 'irdw';
    $pass = 'network';
    $charset = 'utf8mb4';
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    try {
        return new PDO($dsn, $user, $pass, $options);
    } catch (\PDOException $e) {
        die("Erreur de connexion BDD : " . $e->getMessage());
    }
}

/**
 * Sauvegarde les MATCHS (Scores)
 * Enregistre les IDs des équipes au lieu des noms
 */
function update_db($data, $table)
{
    $pdo = get_pdo_connection();

    // Whitelist des tables autorisées
    $allowed_tables = ['matches', 'live_scores'];
    if (!in_array($table, $allowed_tables)) {
        die("Erreur : Table '$table' non autorisée.");
    }

    $sql = "INSERT INTO `$table` (api_match_id, home_team, away_team, score_home, score_away, status, match_date) 
            VALUES (:api_id, :home_id, :away_id, :s_home, :s_away, :status, :m_date)
            ON DUPLICATE KEY UPDATE 
                home_team  = VALUES(home_team),
                away_team  = VALUES(away_team),
                score_home = VALUES(score_home), 
                score_away = VALUES(score_away), 
                status     = VALUES(status),
                updated_at = NOW()";

    $stmt = $pdo->prepare($sql);
    $count = 0;

    foreach ($data as $match) {
        try {
            $dateObj = new DateTime($match['utcDate']);
            $formattedDate = $dateObj->format('Y-m-d H:i:s');
        } catch (Exception $e) {
            $formattedDate = date('Y-m-d H:i:s');
        }

        $stmt->execute([
            ':api_id'   => $match['id'],
            ':home_id'  => $match['homeTeam']['id'], // On stocke l'ID
            ':away_id'  => $match['awayTeam']['id'], // On stocke l'ID
            ':s_home'   => $match['score']['fullTime']['home'] ?? null,
            ':s_away'   => $match['score']['fullTime']['away'] ?? null,
            ':status'   => $match['status'],
            ':m_date'   => $formattedDate
        ]);
        $count++;
    }
    echo "✅ Succès : $count matchs traités dans '$table'.<br>";
}

/**
 * Sauvegarde les ÉQUIPES (Noms, Logos, IDs)
 */
function update_teams_db($data)
{
    $pdo = get_pdo_connection();

    // Table 'teams' en dur car la structure est fixe
    $sql = "INSERT INTO teams (api_team_id, name, short_name, tla, crest_url) 
            VALUES (:api_id, :name, :short, :tla, :crest)
            ON DUPLICATE KEY UPDATE 
                name = VALUES(name), 
                short_name = VALUES(short_name), 
                crest_url = VALUES(crest_url),
                updated_at = NOW()";

    $stmt = $pdo->prepare($sql);
    $count = 0;

    foreach ($data as $team) {
        $stmt->execute([
            ':api_id' => $team['id'],
            ':name'   => $team['name'],
            ':short'  => $team['shortName'] ?? $team['name'],
            ':tla'    => $team['tla'] ?? '',
            ':crest'  => $team['crest'] ?? ''
        ]);
        $count++;
    }
    echo "✅ Succès : $count équipes mises à jour dans 'teams'.<br>";
}
?>