<?php
require("request_foot.php");
require("update_db.php");

$matchesFromApi = request("matches");
$update = update_db($matchesFromApi, "matches");

?>
