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

function update_matches_db($data, $compId)
{
    $pdo = get_pdo_connection();

    // On ajoute competition_id dans la requête
    $sql = "INSERT INTO matches (api_match_id, competition_id, home_team, away_team, score_home, score_away, status, match_date) 
            VALUES (:api_id, :comp_id, :home_id, :away_id, :s_home, :s_away, :status, :m_date)
            ON DUPLICATE KEY UPDATE 
                competition_id = VALUES(competition_id), -- Mise à jour de la liaison
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
            ':comp_id'  => $compId,
            ':home_id'  => $match['homeTeam']['id'],
            ':away_id'  => $match['awayTeam']['id'],
            ':s_home'   => isset($match['score']['fullTime']['home']) ? $match['score']['fullTime']['home'] : null,
            ':s_away'   => isset($match['score']['fullTime']['away']) ? $match['score']['fullTime']['away'] : null,
            ':status'   => $match['status'],
            ':m_date'   => $formattedDate
        ]);
        $count++;
    }
    echo "✅ Succès : $count matchs traités.<br>";
}

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
            ':short'  => isset($team['shortName']) ? $team['shortName'] : $team['name'],
            ':tla'    => isset($team['tla']) ? $team['tla'] : '',
            ':crest'  => isset($team['crest']) ? $team['crest'] : ''
        ]);
        $count++;
    }
    echo "✅ Succès : $count équipes mises à jour dans 'teams'.<br>";
}

function update_competition_db($compData)
{
    $pdo = get_pdo_connection();

    $sql = "INSERT INTO competitions (api_competition_id, code, name, emblem_url, area_name) 
            VALUES (:api_id, :code, :name, :emblem, :area)
            ON DUPLICATE KEY UPDATE 
                name = VALUES(name), 
                emblem_url = VALUES(emblem_url),
                updated_at = NOW()";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':api_id' => $compData['id'],
        ':code'   => $compData['code'],
        ':name'   => $compData['name'],
        ':emblem' => isset($compData['emblem']) ? $compData['emblem'] : '',
        ':area'   => isset($compData['area']['name']) ? $compData['area']['name'] : ''
    ]);

    echo "🏆 Compétition <strong>" . $compData['name'] . "</strong> mise à jour.<br>";
}
?>