<?php

function request($uri)
{
    $uri = 'https://api.football-data.org/v4/' . $uri;
    $apiKey = '602d56c08d634a588654e3977b6db8d9';

    $reqPrefs['http']['method'] = 'GET';
    $reqPrefs['http']['header'] = 'X-Auth-Token: ' . $apiKey;
    $stream_context = stream_context_create($reqPrefs);

    $response = file_get_contents($uri, false, $stream_context);
    $matches = json_decode($response, true);

    if (isset($matches['matches'])) {
        print_r($matches['matches']);
        return $matches['matches'];
    } else {
        return "Impossible de récupérer les scores.";
    }
}