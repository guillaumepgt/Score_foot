<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once "DB/db.php";

function getClassement($pdo, $codeCompetition) {
    $stmt = $pdo->prepare("SELECT id, name FROM competitions WHERE code = :code");
    $stmt->execute([':code' => $codeCompetition]);
    $compet = $stmt->fetch();

    if (!$compet) return [];

    $compId = $compet['id'];
    $compName = $compet['name'];

    $sql = "SELECT 
                m.score_home, m.score_away,
                t_home.api_team_id as id_home, t_home.name as nom_home, t_home.crest_url as logo_home,
                t_away.api_team_id as id_away, t_away.name as nom_away, t_away.crest_url as logo_away
            FROM matches m
            LEFT JOIN teams t_home ON m.home_team = t_home.api_team_id
            LEFT JOIN teams t_away ON m.away_team = t_away.api_team_id
            WHERE m.competition_id = :comp_id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':comp_id' => $compId]);
    $matchs = $stmt->fetchAll();

    $classement = [];

    $initEquipe = function($id, $nom, $logo) use (&$classement) {
        if (!isset($classement[$id])) {
            $classement[$id] = [
                'nom' => $nom,
                'logo' => $logo,
                'pts' => 0, 'j' => 0, 'g' => 0, 'n' => 0, 'p' => 0, 'bp' => 0, 'bc' => 0, 'diff' => 0
            ];
        }
    };

    foreach ($matchs as $m) {
        $idH = $m['id_home'];
        $idA = $m['id_away'];
        $sH = (int)$m['score_home'];
        $sA = (int)$m['score_away'];

        $initEquipe($idH, $m['nom_home'], $m['logo_home']);
        $initEquipe($idA, $m['nom_away'], $m['logo_away']);

        $classement[$idH]['j']++;
        $classement[$idA]['j']++;
        $classement[$idH]['diff'] += ($sH - $sA);
        $classement[$idA]['diff'] += ($sA - $sH);

        if ($sH > $sA) {
            $classement[$idH]['pts'] += 3;
            $classement[$idH]['g']++;
            $classement[$idA]['p']++;
        } elseif ($sA > $sH) {
            $classement[$idA]['pts'] += 3;
            $classement[$idA]['g']++;
            $classement[$idH]['p']++;
        } else {
            $classement[$idH]['pts'] += 1;
            $classement[$idA]['pts'] += 1;
            $classement[$idH]['n']++;
            $classement[$idA]['n']++;
        }
    }

    usort($classement, function($a, $b) {
        if ($a['pts'] != $b['pts']) return $b['pts'] - $a['pts'];
        return $b['diff'] - $a['diff'];
    });

    return ['titre' => $compName, 'data' => $classement];
}

$codesALister = ['FL1', 'PL'];
$donnees_classement = [];

if (isset($pdo)) {
    foreach ($codesALister as $code) {
        $res = getClassement($pdo, $code);
        if (!empty($res['data'])) {
            $donnees_classement[$res['titre']] = $res['data'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kickoff - Classements Dynamiques</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* --- TON CSS --- */
        body { background-color: rgb(196, 181, 163); margin: 0; font-family: 'Inter', sans-serif; }
        footer { background-color: rgb(66, 43, 2); font-weight: bold; color: antiquewhite; padding: 20px; text-align: center; margin-top: 50px; }
        header { display: flex; justify-content: space-between; align-items: center; padding: 10px 40px; background-color: rgb(196, 181, 163); }
        .logo { height: auto; }
        .main-nav { display: flex; gap: 30px; }
        .nav-link { text-decoration: none; color: #000000; font-weight: 600; font-size: 1.1rem; text-transform: uppercase; letter-spacing: 1px; transition: opacity 0.3s ease; }
        .nav-link:hover { opacity: 0.7; }
        .nav-link.active { border-bottom: 2px solid #E65100; }
        hr { border: 0; height: 1px; background-color: #000000; margin: 0; opacity: 0.2; }

        .container { max-width: 1000px; margin: 40px auto; padding: 0 20px; }
        .league-title { font-size: 1.5rem; font-weight: 700; margin-top: 40px; margin-bottom: 20px; color: #422B02; border-left: 5px solid #E65100; padding-left: 15px; }
        .table-responsive { overflow-x: auto; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        table { width: 100%; border-collapse: collapse; background-color: #FDFBF7; min-width: 600px; }
        th { background-color: #e8dfd6; color: #422B02; padding: 15px 10px; text-align: center; font-size: 0.85rem; text-transform: uppercase; }
        th.text-left, td.text-left { text-align: left; padding-left: 20px; }
        td { padding: 12px 10px; border-bottom: 1px solid #eee; text-align: center; color: #333; font-weight: 500; }
        .team-cell { display: flex; align-items: center; gap: 15px; }
        .table-logo { width: 30px; height: 30px; object-fit: contain; }
        .points { font-weight: 800; color: #E65100; font-size: 1.1rem; }
        .top-3 { border-left: 4px solid #2ECC71; background-color: rgba(46, 204, 113, 0.05); }
        .empty-msg { text-align: center; padding: 20px; color: #666; font-style: italic; }
    </style>
</head>

<body>

<header>
    <img src="./images/kickoff_logo.png" class="logo" width="200" alt="Kickoff Logo"/>
    <nav class="main-nav">
        <a href="index.php" class="nav-link">Matchs</a>
        <a href="classement.php" class="nav-link active">Classements</a>
    </nav>
</header>

<hr>

<main class="container">

    <?php if (empty($donnees_classement)): ?>
        <div class="empty-msg">
            <p>Aucune donnée de classement disponible pour le moment.<br>Veuillez lancer la mise à jour des scores.</p>
        </div>
    <?php endif; ?>

    <?php foreach($donnees_classement as $nom_ligue => $equipes): ?>

        <h2 class="league-title"><?php echo htmlspecialchars($nom_ligue); ?></h2>

        <div class="table-responsive">
            <table>
                <thead>
                <tr>
                    <th width="50">Pos</th>
                    <th class="text-left">Équipe</th>
                    <th title="Points">Pts</th>
                    <th title="Joués">J</th>
                    <th title="Gagnés">G</th>
                    <th title="Nuls">N</th>
                    <th title="Perdus">P</th>
                    <th title="Différence">Diff</th>
                </tr>
                </thead>
                <tbody>

                <?php
                $position = 1;
                foreach($equipes as $equipe):
                    ?>
                    <tr class="<?php echo ($position <= 3) ? 'top-3' : ''; ?>">

                        <td><?php echo $position; ?></td>

                        <td class="text-left">
                            <div class="team-cell">
                                <?php if(!empty($equipe['logo'])): ?>
                                    <img src="<?php echo $equipe['logo']; ?>" class="table-logo" alt="logo">
                                <?php endif; ?>
                                <span><?php echo htmlspecialchars($equipe['nom']); ?></span>
                            </div>
                        </td>

                        <td class="points"><?php echo $equipe['pts']; ?></td>
                        <td><?php echo $equipe['j']; ?></td>
                        <td><?php echo $equipe['g']; ?></td>
                        <td><?php echo $equipe['n']; ?></td>
                        <td><?php echo $equipe['p']; ?></td>

                        <td style="color: <?php echo ($equipe['diff'] >= 0) ? 'green' : 'red'; ?>">
                            <?php echo ($equipe['diff'] > 0 ? '+' : '') . $equipe['diff']; ?>
                        </td>

                    </tr>
                    <?php
                    $position++;
                endforeach;
                ?>

                </tbody>
            </table>
        </div>

    <?php endforeach; ?>

</main>

<footer>
    <p>&copy; Par Basile, Guillaume et Titouan</p>
</footer>

</body>
</html>