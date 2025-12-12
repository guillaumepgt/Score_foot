<?php
require("request_foot.php");
require("update_db.php");

$competitionsCodes = ['FL1', 'PL'];

foreach ($competitionsCodes as $code) {

    $compInfo = request("competitions/$code");

    if (isset($compInfo['id'])) {
        update_competition_db($compInfo);

        $apiCompetitionId = $compInfo['id'];
    } else {
        continue;
    }

    $teamResult = request("competitions/$code/teams");
    if (isset($teamResult['teams'])) {
        update_teams_db($teamResult['teams']);
    }

    $matchResult = request("competitions/$code/matches");
    if (isset($matchResult['matches'])) {
        update_matches_db($matchResult['matches'], $apiCompetitionId);
    }

}
?>