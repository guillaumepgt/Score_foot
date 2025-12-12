<?php
// --- SIMULATION DE LA BASE DE DONNÉES ---
// Plus tard, ces données viendront d'une requête SQL (ex: SELECT * FROM matchs...)
$donnees = [
    'Ligue 1 Uber Eats' => [
        [
            'home_name' => 'PSG',
            'home_logo' => 'https://upload.wikimedia.org/wikipedia/fr/8/86/Paris_Saint-Germain_Logo.svg',
            'away_name' => 'Marseille',
            'away_logo' => 'https://upload.wikimedia.org/wikipedia/fr/4/43/Logo_Olympique_de_Marseille.svg',
            'score'     => '3 - 1',
            'status'    => 'Terminé'
        ],
        [
            'home_name' => 'Rennes',
            'home_logo' => 'https://upload.wikimedia.org/wikipedia/fr/b/b1/Logo_Stade_Rennais_FC.svg',
            'away_name' => 'Nantes',
            'away_logo' => 'https://upload.wikimedia.org/wikipedia/fr/6/62/Logo_FC_Nantes_2019.svg',
            'score'     => '20:45',
            'status'    => 'Ce soir'
        ],
        [
            'home_name' => 'Lens',
            'home_logo' => 'https://upload.wikimedia.org/wikipedia/fr/archive/1/1e/20210712122604%21Racing_Club_de_Lens_logo.svg',
            'away_name' => 'Lyon',
            'away_logo' => 'https://upload.wikimedia.org/wikipedia/fr/e/e2/Olympique_lyonnais_%28logo%29.svg',
            'score'     => '0 - 0',
            'status'    => '45\' (Mi-temps)'
        ]
    ],
    'Premier League' => [
        [
            'home_name' => 'Man City',
            'home_logo' => 'https://upload.wikimedia.org/wikipedia/en/7/7a/Manchester_City_FC_badge.svg',
            'away_name' => 'Liverpool',
            'away_logo' => 'https://upload.wikimedia.org/wikipedia/en/c/c2/Liverpool_FC_Logo.svg',
            'score'     => '2 - 2',
            'status'    => '88\' (En cours)'
        ],
        [
            'home_name' => 'Arsenal',
            'home_logo' => 'https://upload.wikimedia.org/wikipedia/en/5/53/Arsenal_FC.svg',
            'away_name' => 'Chelsea',
            'away_logo' => 'https://upload.wikimedia.org/wikipedia/en/c/cc/Chelsea_FC.svg',
            'score'     => '- : -',
            'status'    => 'Demain 16h'
        ]
    ]
];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<<<<<<< HEAD
    <script src="DB/query.js" defer></script>
=======
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kickoff</title>
<style>
body {
            background-color: rgb(196, 181, 163);
            margin: 0;
            font-family: 'Inter', sans-serif;
        }

        /* --- Header --- */
        header {
            background-color: rgb(196, 181, 163);
            padding: 10px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo { height: auto; }

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

        /* --- Layout Principal --- */
        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        /* --- Styles des Matchs --- */
        .league-section { margin-bottom: 50px; }

        .league-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            color: #422B02;
            border-left: 5px solid #E65100;
            padding-left: 15px;
        }

        .matches-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 20px;
        }

        .match-card {
            background-color: #FDFBF7;
            border-radius: 12px;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            transition: transform 0.2s;
        }

        .match-card:hover { transform: translateY(-5px); }

        .team {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 30%;
            text-align: center;
        }

        .team-logo {
            width: 50px;
            height: 50px;
            object-fit: contain;
            margin-bottom: 8px;
        }

        .team-name {
            font-weight: 600;
            font-size: 0.9rem;
            color: #333;
        }

        .match-info {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 30%;
        }

        .score {
            font-size: 1.4rem;
            font-weight: 800;
            color: #E65100;
            background: #eee;
            padding: 5px 15px;
            border-radius: 20px;
            margin-bottom: 5px;
            white-space: nowrap;
        }

        .match-time {
            font-size: 0.8rem;
            color: #666;
            font-weight: 500;
        }

        /* --- Footer --- */
        footer {
            background-color: rgb(66, 43, 2);
            font-weight: bold;
            color: antiquewhite;
            padding: 20px;
            text-align: center;
            margin-top: 50px;
        }
</style>
>>>>>>> origin/Basile
</head>

<body>
<<<<<<< HEAD
<button onclick="searchTeam(idequipe)">Rechercher l’équipe</button>
<div id="root"></div>
=======
<header>
    <img src="/Frontend/images/kickoff_logo.png" class="logo" width="400" height="200"/>
    <nav class="main-nav">
            <a href="index.php" class="nav-link active">Matchs</a>
            <a href="classement.html" class="nav-link">Classements</a>
        </nav>
</header>
    <hr>
    <br>
<main class="container">

        <?php 
        // BOUCLE 1 : On parcourt chaque championnat
        // $nom_ligue prendra "Ligue 1", puis "Premier League"
        // $matchs prendra la liste des matchs de cette ligue
        foreach($donnees as $nom_ligue => $matchs): 
        ?>
            
            <section class="league-section">
                <h2 class="league-title"><?php echo $nom_ligue; ?></h2>
                
                <div class="matches-grid">
                    
                    <?php 
                    // BOUCLE 2 : On parcourt chaque match à l'intérieur du championnat
                    foreach($matchs as $match): 
                    ?>
                        <div class="match-card">
                            
                            <div class="team">
                                <img src="<?php echo $match['home_logo']; ?>" class="team-logo" alt="Logo Home">
                                <span class="team-name"><?php echo $match['home_name']; ?></span>
                            </div>

                            <div class="match-info">
                                <span class="score"><?php echo $match['score']; ?></span>
                                <span class="match-time"><?php echo $match['status']; ?></span>
                            </div>

                            <div class="team">
                                <img src="<?php echo $match['away_logo']; ?>" class="team-logo" alt="Logo Away">
                                <span class="team-name"><?php echo $match['away_name']; ?></span>
                            </div>

                        </div>
                        <?php endforeach; // Fin de la boucle des matchs ?>

                </div>
            </section>

        <?php endforeach; // Fin de la boucle des championnats ?>

    </main>

   



















    <footer>
        <p>&copy; Par Basile, Guillaume et Titouan</p>
    </footer>

>>>>>>> origin/Basile
</body>
</html>