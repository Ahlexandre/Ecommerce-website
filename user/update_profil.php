<?php
#DU Alexandre et JELASSI Bader

include 'connexion.php'; // Inclusion du fichier de connexion
session_start(); // Démarrage de la session

$user_id = $_SESSION['user_id']; // Récupération de l'ID de l'utilisateur depuis la session

if (isset($_GET['logout'])){ // Vérifie si la demande de déconnexion est effectuée via GET
    unset($user_id); // Suppression de l'ID de l'utilisateur
    session_destroy(); // Destruction de la session
    header('location:index.php'); // Redirection vers la page d'accueil
};

$select_user = mysqli_query($conn, "SELECT * FROM `user_form` WHERE ID = '$user_id'") or die("Erreur de requête"); // Requête pour sélectionner les informations de l'utilisateur en fonction de son ID
if (mysqli_num_rows($select_user) > 0){ // Vérifie si des lignes ont été renvoyées par la requête
    $fetch_user = mysqli_fetch_assoc($select_user); // Récupération des données de l'utilisateur
};

if(isset($_POST['update_profil'])){ // Vérifie si le formulaire de mise à jour du profil a été soumis
    $update_name = mysqli_real_escape_string($conn, $_POST['update_name']); // Échappement des caractères spéciaux et récupération du nouveau nom
    $update_email = mysqli_real_escape_string($conn, $_POST['update_email']); // Échappement des caractères spéciaux et récupération du nouvel email
    $update_numrue = mysqli_real_escape_string($conn, $_POST['update_numrue']); // Échappement des caractères spéciaux et récupération du nouveau numéro de rue
    $update_nomrue = mysqli_real_escape_string($conn, $_POST['update_nomrue']); // Échappement des caractères spéciaux et récupération du nouveau nom de rue
    $update_ville = mysqli_real_escape_string($conn, $_POST['update_ville']); // Échappement des caractères spéciaux et récupération de la nouvelle ville
    $update_codepostal = mysqli_real_escape_string($conn, $_POST['update_codepostal']); // Échappement des caractères spéciaux et récupération du nouveau code postal

    mysqli_query($conn, "UPDATE `user_form` SET name = '$update_name', email = '$update_email', numrue = '$update_numrue', nomrue = '$update_nomrue', ville ='$update_ville', codepostal = '$update_codepostal' WHERE id = '$user_id'") or die('Erreur de requête'); // Requête pour mettre à jour les informations de l'utilisateur dans la base de données

    $message[] = "Votre compte a été modifié avec vos nouvelles données !"; // Ajout d'un message de succès
    
    $old_pass = $_POST['old_pass']; // Récupération de l'ancien mot de passe depuis le formulaire
    $update_pass = mysqli_real_escape_string($conn, $_POST['update_pass']); // Échappement des caractères spéciaux et récupération du mot de passe actuel
    $new_pass = mysqli_real_escape_string($conn, $_POST['new_pass']); // Échappement des caractères spéciaux et récupération du nouveau mot de passe
    $cnew_pass = mysqli_real_escape_string($conn, $_POST['cnew_pass']); // Échappement des caractères spéciaux et récupération de la confirmation du nouveau mot de passe
    
    // Vérifie si un nouveau mot de passe est soumis
    if (!empty($update_pass) || !empty($new_pass) || !empty($cnew_pass)) {
        // Vérifie si l'ancien mot de passe est correct
        if (!password_verify($update_pass, $fetch_user['password'])) {
            $message[] = "L'ancien mot de passe est incorrect"; // Message d'erreur si l'ancien mot de passe est incorrect
        } elseif ($new_pass != $cnew_pass) {
            $message[] = "Le mot de passe de confirmation est incorrect"; // Message d'erreur si la confirmation du nouveau mot de passe ne correspond pas
        } else {
            // Hasher le nouveau mot de passe
            $hashed_new_pass = password_hash($new_pass, PASSWORD_DEFAULT); // Hashage du nouveau mot de passe
            // Mettre à jour le mot de passe dans la base de données
            mysqli_query($conn, "UPDATE `user_form` SET password = '$hashed_new_pass' WHERE id = '$user_id'") or die('Erreur de requête'); // Requête pour mettre à jour le mot de passe dans la base de données
            $message[] = "Le mot de passe a été modifié !"; // Message de succès pour la modification du mot de passe
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href = "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" type = "text/css" href = "../styles/style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="../img/logo.png" type="image/x-icon">
    <title>AS Shop - Modifier Votre Profil</title>
</head>
<body>

<?php

if(isset($message)){ // Vérifie si des messages sont disponibles
   foreach($message as $message){ // Parcourt tous les messages
      echo '<div class="message" onclick="this.remove();">'.$message.'</div>'; // Affichage des messages dans une boîte d'alerte qui disparaît au clic
   }
}

?>

    <header class="header">
            <nav class="nav container">

                <div class ="navigation d-flex">
                    <div class="icon1">
                        <i class='bx bx-menu'></i>
                    </div>
                    <div class="logo">
                        <a href ="#"><span>AS</span> Shop</a>
                    </div>
                    <div class="menu">
                        <div class="top">
                            <span class = "fermer">Fermer <i class='bx bx-x'></i></span>
                        </div>
                        <ul class ="nav-list d-flex">
                            <li class="nav-item">

                            </li>
                            <li class="nav-item">
                                <a href="profil.php" class ="nav-link">Retour</a>
                            </li>
                        </ul>
                    </div>
                    <div class="icons d-flex">
                        <div>
                                <a href="card.php"><i class='bx bx-shopping-bag'></i></a>
                                <!-- <span class = "align-center">0</span> -->
                        </div>
                        <div>
                        <a class = "delete-btn" href ="../index.php?logout=<?php echo $user_id; ?>" onclick="return confirm('Es-tu sûr de te déconnecter ?');">Déconnexion</a>
                        </div>
                    </div>
                </div>

            </nav>
    </header>

    <div class = "update-profil">
    <?php
            $select = mysqli_query($conn, "SELECT * FROM `user_form` WHERE id = '$user_id'") or die('Erreur de requête'); // Requête pour sélectionner les informations de l'utilisateur
            if(mysqli_num_rows($select) > 0){ // Vérifie si des lignes ont été renvoyées par la requête
                $fetch = mysqli_fetch_assoc($select); // Récupération des données de l'utilisateur
            }

    ?>

    <form action = "" method = "POST"> <!-- Formulaire de mise à jour du profil -->
        <div class="flex">
            <div class="inputBox">
                <label for="update_name">Nom</label>
                <input class = "box" type="text" name = "update_name" value = "<?php echo $fetch["name"];?>"> <!-- Champ de saisie pour le nom avec la valeur actuelle affichée -->
                <label for="update_email">Email</label>
                <input class = "box" type="text" name = "update_email" value = "<?php echo $fetch["email"];?>"> <!-- Champ de saisie pour l'email avec la valeur actuelle affichée -->
                <label for="update_numrue">Numéro de rue</label>
                <input class = "box" type="number" name = "update_numrue" value = "<?php echo $fetch["numrue"];?>"> <!-- Champ de saisie pour le numéro de rue avec la valeur actuelle affichée -->
                <label for="update_nomrue">Nom de la rue</label>
                <input class = "box" type="text" name = "update_nomrue" value = "<?php echo $fetch["nomrue"];?>"> <!-- Champ de saisie pour le nom de la rue avec la valeur actuelle affichée -->
                <label for="update_ville">Ville</label>
                <input class = "box" type="text" name = "update_ville" value = "<?php echo $fetch["ville"];?>"> <!-- Champ de saisie pour la ville avec la valeur actuelle affichée -->
                <label for="update_codepostal">Code Postal</label>
                <input class = "box" type="text" name = "update_codepostal" value = "<?php echo $fetch["codepostal"];?>"> <!-- Champ de saisie pour le code postal avec la valeur actuelle affichée -->
            </div>
            <div class="inputBox">
                <input type="hidden" name = "old_pass" value = "<?php echo $fetch['password']; ?>"> <!-- Champ caché pour stocker l'ancien mot de passe haché -->
                <label for="update_pass">Ancien Mot De Passe</label>
                <input class = "box" type="password" name = "update_pass" placeholder = "Entrez votre ancien mot de passe"> <!-- Champ de saisie pour l'ancien mot de passe -->
                <label for="new_pass">Nouveau Mot De Passe</label>
                <input class = "box" type="password" name = "new_pass" placeholder = "Entrez votre nouveau mot de passe"> <!-- Champ de saisie pour le nouveau mot de passe -->
                <label for="cnew_pass">Confirmer le Nouveau Mot De Passe</label>
                <input class = "box" type="password" name = "cnew_pass" placeholder = "Confirmez votre nouveau mot de passe"> <!-- Champ de saisie pour la confirmation du nouveau mot de passe -->
            </div>
        </div>
            <input class = "btn3" type = "submit" value ="Modifier votre compte" name = "update_profil"> <!-- Bouton de soumission du formulaire de mise à jour du profil -->
    </form>
    </div>

</body>
</html>

<?php
    // Fermeture de la connexion à la base de données
    mysqli_close($conn);
?>