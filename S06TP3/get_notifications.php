<?php
require_once('core.php');

if (isset($_POST['idEtudiant'])) {
    $idEtudiant = (int)$_POST['idEtudiant'];

    $sql = "SELECT notification.type, etudiant.nom, etudiant.prenom, etudiant.photo 
            FROM notification 
            INNER JOIN etudiant ON notification.idEtudiant = etudiant.id 
            WHERE notification.idEtudiant = $idEtudiant";

    $result = $mysqli->query($sql);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            echo '<div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px; display: flex; align-items: center;">';

            if (!empty($row['image'])) {
                echo '<img src="' . $row['image'] . '" alt="Avatar" style="width: 50px; height: 50px; border-radius: 50%; margin-right: 15px;">';
            }

            echo '<div>';
            echo '<h3>' . htmlspecialchars($row['type']) . '</h3>';

            echo '<p>Envoyé par : <strong>' . htmlspecialchars($row['prenom']) . ' ' . htmlspecialchars($row['nom']) . '</strong></p>';
            echo '</div>';

            echo '</div>';
            echo '<hr>';
        }
    } else {
        echo "Erreur dans la requête SQL : " . $mysqli->error;
    }

} else {
    echo "Aucun ID étudiant fourni.";
}
?>