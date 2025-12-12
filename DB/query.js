function searchTeam() {
	const idEquipe = "Réal Madrid";

    if (!idEquipe) {
        alert("Veuillez sélectionner une équipe");
        return;
    }
	
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {

            document.getElementById("root").innerHTML = this.responseText;
        }
    };


	xhttp.open("POST", "/DB/searchTeam.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("idEquipe=" + encodeURIComponent(idEquipe));
}
