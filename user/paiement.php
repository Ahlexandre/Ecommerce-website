<?php
# DU Alexandre et JELASSI Bader

// Inclusion du fichier de connexion et démarrage de la session
include 'connexion.php';
session_start();

// Récupération de l'identifiant de l'utilisateur depuis la session
$user_id = $_SESSION['user_id'];

// Vérification si l'utilisateur demande à se déconnecter
if (isset($_GET['logout'])){
    // Suppression de l'identifiant de l'utilisateur et destruction de la session
    unset($user_id);
    session_destroy();
    // Redirection vers la page d'accueil
    header('location:index.php');
}

// Sélection des informations de l'utilisateur connecté
$select_user = mysqli_query($conn, "SELECT * FROM `user_form` WHERE ID = '$user_id'") or die("Erreur de requête");
if (mysqli_num_rows($select_user) > 0){
    // Récupération des données de l'utilisateur
    $fetch_user = mysqli_fetch_assoc($select_user);
}

?>




<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href = "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" type = "text/css" href = "../styles/stylepaiement.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="../img/logo.png" type="image/x-icon">
    <title>AS Shop - Paiement</title>
</head>
<body>

<?php

if(isset($message)){
   foreach($message as $message){
      echo '<div class="message" onclick="this.remove();">'.$message.'</div>';
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
                            <a href="card.php" class ="nav-link">Retour</a>
                        </li>
                    </ul>
                </div>
                <div class="icons d-flex">
                    <div class = "username"><a href="profil.php" target='_BLANK'><?php echo $fetch_user['name']; ?></a></div>
                    <div>
                            <a href="card.php"><i class='bx bx-shopping-bag'></i></a>
                            <!-- <span class = "align-center">0</span> -->
                    </div>
                    <div>
                    <a class = "delete-btn" href ="../index.php?logout=<?php echo $user_id; ?>" onclick="return confirm('Es-tu sûr de te déconnecter ?');">Déconnexion</a>
                    </div>
                </div>
            </div>
    </header>

<div class="form-container">
    <form action = "recap_command.php" method = "POST">
        <h3>Paiement</h3>
        <div class = "paiement">
        <h4>Carte Bancaire</h4>
            <label for = "numCarte">Numéro de Carte</label>
            <input class="box" type="text" name="numCarte" placeholder="XXXX XXXX XXXX XXXX" minlength="16" maxlength="16" required>
            <label for = "dateexpiration">Date d'expiration</label>
            <input class = "box" type="month" name = "dateexpiration" placeholder ="XX/XX" required>
            <label for = "nomCarte">Nom sur la Carte</label>
            <input class = "box" type="text" name = "nomCarte" placeholder ="Nom Carte" required>
            <label for = "codeSecret">Code Secret</label>
            <input class = "box" type="password" name = "codeSecret" placeholder ="Code" minlength="3" maxlength="3" required>
        </div>
        </br>
        <div class = "facturation">
            <h4>Adresse de Livraison</h4>
            <label for = "numrue">Numéro de rue</label>
            <input class = "box" type="number" name = "numrue" placeholder ="Numéro de rue"  min = 1 required>
            <label for = "nomrue">Nom de la rue</label>
            <input class = "box" type="text" name = "nomrue" placeholder ="Nom de rue" >
            <label for = "ville">Ville</label>
            <input class = "box" type="text" name = "ville" placeholder ="Ville" required>
            <label for = "codepostal">Code Postal</label>
            <input class = "box" type="text" name = "codepostal" placeholder ="Code Postal" required>
            <input type ="submit" name ="submit" class = "btn2" value ="Confirmer l'achat">
        </div>
    </form>
</div>

</body>
</html>

<?php
    // Fermeture de la connexion à la base de données
    mysqli_close($conn);
?>