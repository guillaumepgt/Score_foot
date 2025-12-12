<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Notifications</title>
    <style>
        #contenuNotifications {
            width: 400px;
            border: 1px solid #333;
            border-radius: 8px;
            padding: 10px;
            background-color: #f9f9f9;
            margin: 20px auto;
            font-family: Arial, sans-serif;
        }
        h2 { text-align: center; }
    </style>
</head>
<body>

<h2>Centre de Notifications</h2>

<div id="contenuNotifications">
    Chargement...
</div>

<script>
	function updateNotifications() {
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {

				document.getElementById("contenuNotifications").innerHTML = this.responseText;
	              console.log("Mise à jour effectuée via XMLHttpRequest");
			}
		};

		// --- Configuration de l'envoi (Nécessaire pour que ça marche) ---

		// On prépare une requête POST vers le fichier PHP
		xhttp.open("POST", "get_notifications.php", true);

		xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

		xhttp.send("idEtudiant=1");
	}

	updateNotifications();

	setInterval(updateNotifications, 10000);
</script>

</body>
</html>