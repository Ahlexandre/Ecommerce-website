<?php
#Projet réalisé par DU Alexandre

// Établit la connexion à la base de données 'as_shop' sur le serveur local avec l'utilisateur 'root' et un mot de passe vide. En cas d'échec, affiche un message d'erreur.
$conn = mysqli_connect('localhost','root','', 'as_shop') or die ('Erreur de Connexion'); 
?>
