<?php
// On inclut le moteur de requête API et les fonctions de sauvegarde
require("request_foot.php");
require("update_db.php");

// Liste des compétitions à mettre à jour (FL1 = Ligue 1, FL2 = Ligue 2)
$competitions = ['FL1'];

foreach ($competitions as $code) {
    echo "<h2>--- Traitement de la compétition : $code ---</h2>";

    // ------------------------------------------------------
    // ÉTAPE 1 : Récupérer et sauvegarder les ÉQUIPES (Teams)
    // ------------------------------------------------------
    echo "Récupération des équipes...<br>";
    $teamResult = request("competitions/$code/teams");

    if (isset($teamResult['teams'])) {
        // On appelle la fonction spécifique aux équipes
        update_teams_db($teamResult['teams']);
    } else {
        echo "⚠️ Erreur ou aucune équipe trouvée pour $code.<br>";
    }

    // ------------------------------------------------------
    // ÉTAPE 2 : Récupérer et sauvegarder les MATCHS (Scores)
    // ------------------------------------------------------
    echo "Récupération des matchs...<br>";
    $matchResult = request("competitions/$code/matches");

    if (isset($matchResult['matches'])) {
        // On appelle la fonction spécifique aux matchs
        // On précise la table 'matches'
        update_db($matchResult['matches'], 'matches');
    } else {
        echo "⚠️ Erreur ou aucun match trouvé pour $code.<br>";
    }

    // Petit temps de pause pour éviter de bloquer l'API (Rate Limiting)
    sleep(1);
    echo "<hr>";
}

echo "<strong>Mise à jour globale terminée !</strong>";
?>