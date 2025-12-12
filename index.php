<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once "DB/db.php";
require_once "Backend/request_foot.php";
require_once "Backend/update_db.php";

if (isset($_GET['update']) && $_GET['update'] == 1) {

    $competitionsCodes = ['FL1', 'PL'];

    foreach ($competitionsCodes as $code) {
        $compInfo = request("competitions/$code");
        if (isset($compInfo['id'])) {
            update_competition_db($compInfo);
            $apiCompId = $compInfo['id']; // ex: 2015

            $teamResult = request("competitions/$code/teams");
            if (isset($teamResult['teams'])) update_teams_db($teamResult['teams']);

            $matchResult = request("competitions/$code/matches");
            if (isset($matchResult['matches'])) update_matches_db($matchResult['matches'], $apiCompId);
        }
    }
    echo "<div style='background:green;color:white;padding:5px;text-align:center;'>Mise à jour effectuée !</div>";
}

$sql = "SELECT 
            c.name as competition_name,
            m.match_date, m.status, m.score_home, m.score_away,
            t_home.name as home_name, t_home.crest_url as home_logo,
            t_away.name as away_name, t_away.crest_url as away_logo
        FROM matches m
        JOIN competitions c ON m.competition_id = c.api_competition_id
        LEFT JOIN teams t_home ON m.home_team = t_home.api_team_id
        LEFT JOIN teams t_away ON m.away_team = t_away.api_team_id
        ORDER BY c.id, m.match_date ASC";

$donnees = [];
if (isset($pdo)) {
    $stmt = $pdo->query($sql);
    $allMatches = $stmt->fetchAll();

    foreach ($allMatches as $row) {
        $ligue = $row['competition_name'];

        $dateObj = new DateTime($row['match_date']);
        $heure = $dateObj->format('H:i');
        $dateFr = $dateObj->format('d/m');

        if ($row['status'] === 'FINISHED') {
            $scoreDisplay = $row['score_home'] . ' - ' . $row['score_away'];
            $statusDisplay = "Terminé ($dateFr)";
        } elseif ($row['status'] === 'IN_PLAY' || $row['status'] === 'PAUSED') {
            $scoreDisplay = $row['score_home'] . ' - ' . $row['score_away'];
            $statusDisplay = "En cours";
        } else {
            $scoreDisplay = "vs";
            $statusDisplay = "$dateFr à $heure";
        }

        // On remplit le tableau
        $donnees[$ligue][] = [
            'home_name' => $row['home_name'],
            'home_logo' => $row['home_logo'],
            'away_name' => $row['away_name'],
            'away_logo' => $row['away_logo'],
            'score'     => $scoreDisplay,
            'status'    => $statusDisplay
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kickoff - Matchs en direct</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { background-color: rgb(196, 181, 163); margin: 0; font-family: 'Inter', sans-serif; }

        /* --- Header --- */
        header { background-color: rgb(196, 181, 163); padding: 10px 40px; display: flex; justify-content: space-between; align-items: center; }
        .logo { height: auto; } /* Ajusté pour éviter la déformation */
        .main-nav { display: flex; gap: 30px; }
        .nav-link { text-decoration: none; color: #000000; font-weight: 600; font-size: 1.1rem; text-transform: uppercase; letter-spacing: 1px; transition: opacity 0.3s ease; }
        .nav-link:hover { opacity: 0.7; }
        .nav-link.active { border-bottom: 2px solid #E65100; }
        hr { border: 0; height: 1px; background-color: #000000; margin: 0; opacity: 0.2; }

        /* --- Layout Principal --- */
        .container { max-width: 1200px; margin: 40px auto; padding: 0 20px; }

        /* --- Bouton Update --- */
        .btn-update { display: inline-block; margin-bottom: 20px; padding: 10px 20px; background: #E65100; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; }
        .btn-update:hover { background: #cf4900; }

        /* --- Styles des Matchs --- */
        .league-section { margin-bottom: 50px; }
        .league-title { font-size: 1.5rem; font-weight: 700; margin-bottom: 20px; color: #422B02; border-left: 5px solid #E65100; padding-left: 15px; }
        .matches-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px; }

        .match-card { background-color: #FDFBF7; border-radius: 12px; padding: 20px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 6px rgba(0,0,0,0.05); transition: transform 0.2s; }
        .match-card:hover { transform: translateY(-5px); }

        .team { display: flex; flex-direction: column; align-items: center; width: 30%; text-align: center; }
        .team-logo { width: 50px; height: 50px; object-fit: contain; margin-bottom: 8px; }
        .team-name { font-weight: 600; font-size: 0.9rem; color: #333; }

        .match-info { display: flex; flex-direction: column; align-items: center; width: 35%; }
        .score { font-size: 1.4rem; font-weight: 800; color: #E65100; background: #eee; padding: 5px 15px; border-radius: 20px; margin-bottom: 5px; white-space: nowrap; }
        .match-time { font-size: 0.8rem; color: #666; font-weight: 500; }

        /* --- Footer --- */
        footer { background-color: rgb(66, 43, 2); font-weight: bold; color: antiquewhite; padding: 20px; text-align: center; margin-top: 50px; }
        .empty-msg { text-align: center; color: #666; font-style: italic; margin-top: 50px; }
    </style>
</head>

<body>

<header>
    <img src="Frontend/images/kickoff_logo.png" class="logo" width="200" alt="Kickoff Logo"/>
    <nav class="main-nav">
        <a href="index.php" class="nav-link active">Matchs</a>
        <a href="classement.php" class="nav-link">Classements</a>
    </nav>
</header>
<hr>

<main class="container">

    <div style="text-align: right;">
        <a href="index.php?update=1" class="btn-update">🔄 Mettre à jour les scores</a>
    </div>

    <?php if (empty($donnees)): ?>
        <div class="empty-msg">
            <h2>Aucun match trouvé dans la base de données.</h2>
            <p>Cliquez sur le bouton "Mettre à jour" ci-dessus pour charger les données depuis l'API.</p>
        </div>
    <?php endif; ?>

    <?php foreach($donnees as $nom_ligue => $matchs): ?>

        <section class="league-section">
            <h2 class="league-title"><?php echo htmlspecialchars($nom_ligue); ?></h2>

            <div class="matches-grid">
                <?php foreach($matchs as $match): ?>
                    <div class="match-card">

                        <div class="team">
                            <?php if(!empty($match['home_logo'])): ?>
                                <img src="<?php echo $match['home_logo']; ?>" class="team-logo" alt="Logo">
                            <?php endif; ?>
                            <span class="team-name"><?php echo htmlspecialchars($match['home_name']); ?></span>
                        </div>

                        <div class="match-info">
                            <span class="score"><?php echo $match['score']; ?></span>
                            <span class="match-time"><?php echo $match['status']; ?></span>
                        </div>

                        <div class="team">
                            <?php if(!empty($match['away_logo'])): ?>
                                <img src="<?php echo $match['away_logo']; ?>" class="team-logo" alt="Logo">
                            <?php endif; ?>
                            <span class="team-name"><?php echo htmlspecialchars($match['away_name']); ?></span>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>
        </section>

    <?php endforeach; ?>

</main>

<footer>
    <p>&copy; Par Basile, Guillaume et Titouan</p>
</footer>

</body>
</html>