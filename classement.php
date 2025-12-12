<?php
// --- 1. SIMULATION DE LA BASE DE DONNÉES ---
// On crée un tableau avec des équipes en désordre pour tester le tri automatique
$donnees_classement = [
    'Ligue 1 Uber Eats' => [
        [
            'nom' => 'Marseille',
            'logo' => 'https://upload.wikimedia.org/wikipedia/fr/4/43/Logo_Olympique_de_Marseille.svg',
            'pts' => 27, 'j' => 17, 'g' => 7, 'n' => 6, 'p' => 4, 'diff' => 6
        ],
        [
            'nom' => 'PSG',
            'logo' => 'https://upload.wikimedia.org/wikipedia/fr/8/86/Paris_Saint-Germain_Logo.svg',
            'pts' => 42, 'j' => 17, 'g' => 13, 'n' => 3, 'p' => 1, 'diff' => 30
        ],
        [
            'nom' => 'Nice',
            'logo' => 'https://upload.wikimedia.org/wikipedia/fr/b/b1/Logo_OGC_Nice_2013.svg',
            'pts' => 35, 'j' => 17, 'g' => 10, 'n' => 5, 'p' => 2, 'diff' => 10
        ],
        [
            'nom' => 'Monaco',
            'logo' => 'https://upload.wikimedia.org/wikipedia/fr/5/50/Logo_AS_Monaco_FC_2021.svg',
            'pts' => 33, 'j' => 17, 'g' => 10, 'n' => 3, 'p' => 4, 'diff' => 12
        ]
    ],
    'Premier League' => [
        [
            'nom' => 'Arsenal',
            'logo' => 'https://upload.wikimedia.org/wikipedia/en/5/53/Arsenal_FC.svg',
            'pts' => 40, 'j' => 19, 'g' => 12, 'n' => 4, 'p' => 3, 'diff' => 18
        ],
        [
            'nom' => 'Liverpool',
            'logo' => 'https://upload.wikimedia.org/wikipedia/en/c/c2/Liverpool_FC_Logo.svg',
            'pts' => 42, 'j' => 19, 'g' => 12, 'n' => 6, 'p' => 1, 'diff' => 25
        ],
        [
            'nom' => 'Man City',
            'logo' => 'https://upload.wikimedia.org/wikipedia/en/7/7a/Manchester_City_FC_badge.svg',
            'pts' => 37, 'j' => 18, 'g' => 11, 'n' => 4, 'p' => 3, 'diff' => 24
        ]
    ]
];

// --- 2. SYSTÈME DE TRI AUTOMATIQUE ---
// Cette boucle parcourt chaque ligue et remet les équipes dans l'ordre
foreach ($donnees_classement as $ligue => $equipes) {
    usort($donnees_classement[$ligue], function($a, $b) {
        // Règle 1 : Celui qui a le plus de points est devant
        if ($a['pts'] != $b['pts']) {
            return $b['pts'] - $a['pts'];
        }
        // Règle 2 : Si égalité de points, celui qui a la meilleure diff est devant
        return $b['diff'] - $a['diff'];
    });
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kickoff - Classements</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        /* --- TON CSS DE BASE (Corrigé) --- */
        body {
            background-color: rgb(196, 181, 163);
            margin: 0;
            font-family: 'Inter', sans-serif;
        }

        footer {
            background-color: rgb(66, 43, 2);
            font-weight: bold; /* Corrigé: font-style c'est pour l'italique */
            color: antiquewhite;
            padding: 20px;
            text-align: center;
            margin-top: 50px;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 40px; /* Ajout d'un peu d'espace */
            background-color: rgb(196, 181, 163);
        }

        /* Correction: ajout du point devant logo */
        .logo { 
            height: auto; 
            /* Tu pourras ajuster la largeur max ici si besoin */
        }

        .main-nav {
            display: flex;
            gap: 30px;
        }

        .nav-link {
            text-decoration: none;
            color: #000000;
            font-weight: 600;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: opacity 0.3s ease;
        }

        .nav-link:hover { opacity: 0.7; }
        .nav-link.active { border-bottom: 2px solid #E65100; }

        hr {
            border: 0;
            height: 1px;
            background-color: #000000;
            margin: 0;
            opacity: 0.2;
        }

        /* --- NOUVEAU CSS POUR LE CLASSEMENT --- */
        .container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .league-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-top: 40px;
            margin-bottom: 20px;
            color: #422B02;
            border-left: 5px solid #E65100;
            padding-left: 15px;
        }

        /* Style du Tableau */
        .table-responsive {
            overflow-x: auto; /* Permet de scroller sur mobile */
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #FDFBF7; /* Un blanc cassé qui va bien avec le beige */
            min-width: 600px;
        }

        th {
            background-color: #e8dfd6; /* Beige un peu plus foncé pour l'entête */
            color: #422B02;
            padding: 15px 10px;
            text-align: center;
            font-size: 0.85rem;
            text-transform: uppercase;
        }

        /* Aligner les noms d'équipes à gauche */
        th.text-left, td.text-left { text-align: left; padding-left: 20px; }

        td {
            padding: 12px 10px;
            border-bottom: 1px solid #eee; /* Ligne grise fine entre les équipes */
            text-align: center;
            color: #333;
            font-weight: 500;
        }

        /* Style spécifique pour la cellule Équipe (Logo + Nom) */
        .team-cell {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .table-logo {
            width: 30px;
            height: 30px;
            object-fit: contain;
        }

        /* Mise en avant des Points */
        .points {
            font-weight: 800;
            color: #E65100; /* Orange Kickoff */
            font-size: 1.1rem;
        }

        /* Couleur pour les places européennes (Top 3) */
        .top-3 {
            border-left: 4px solid #2ECC71; /* Petite barre verte */
        }

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

        <?php foreach($donnees_classement as $nom_ligue => $equipes): ?>
            
            <h2 class="league-title"><?php echo $nom_ligue; ?></h2>

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
                        $position = 1; // On initialise la position à 1
                        foreach($equipes as $equipe): 
                        ?>
                            <tr class="<?php echo ($position <= 3) ? 'top-3' : ''; ?>">
                                
                                <td><?php echo $position; ?></td>
                                
                                <td class="text-left">
                                    <div class="team-cell">
                                        <img src="<?php echo $equipe['logo']; ?>" class="table-logo" alt="logo">
                                        <span><?php echo $equipe['nom']; ?></span>
                                    </div>
                                </td>
                                
                                <td class="points"><?php echo $equipe['pts']; ?></td>
                                <td><?php echo $equipe['j']; ?></td>
                                <td><?php echo $equipe['g']; ?></td>
                                <td><?php echo $equipe['n']; ?></td>
                                <td><?php echo $equipe['p']; ?></td>
                                
                                <td><?php echo ($equipe['diff'] > 0 ? '+' : '') . $equipe['diff']; ?></td>
                            
                            </tr>
                        <?php 
                        $position++; // On passe à la position suivante (1, puis 2, puis 3...)
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