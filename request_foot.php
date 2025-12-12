<?php

$uri = 'https://api.football-data.org/v4/matches';
$apiKey = '602d56c08d634a588654e3977b6db8d9';

$reqPrefs['http']['method'] = 'GET';
$reqPrefs['http']['header'] = 'X-Auth-Token: ' . $apiKey;
$stream_context = stream_context_create($reqPrefs);

$response = file_get_contents($uri, false, $stream_context);
$matches = json_decode($response, true);

if (isset($matches['matches'])) {
    echo "<h2>Matchs du jour :</h2>";
    echo "<ul>";

    print_r($matches['matches']);
    foreach ($matches['matches'] as $match) {
        $homeTeam = $match['homeTeam']['name'];
        $awayTeam = $match['awayTeam']['name'];
        $scoreHome = $match['score']['fullTime']['home'];
        $scoreAway = $match['score']['fullTime']['away'];
        $status = $match['status'];

        $displayScore = ($status === 'SCHEDULED') ? 'vs' : "$scoreHome - $scoreAway";

        echo "<li><strong>$homeTeam</strong> $displayScore <strong>$awayTeam</strong> ($status)</li>";
    }
    echo "</ul>";
} else {
    echo "Impossible de récupérer les scores.";
}
?>