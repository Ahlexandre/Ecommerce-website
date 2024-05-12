<?php

include 'connexion.php'; // Inclusion du fichier de connexion
session_start(); // Démarrage de la session

$user_id = $_SESSION['user_id']; // Récupération de l'ID de l'utilisateur depuis la session

if (isset($_GET['logout'])){ // Vérifie si la demande de déconnexion est effectuée via GET
    unset($user_id); // Suppression de l'ID de l'utilisateur
    session_destroy(); // Destruction de la session
    header('location:index.php'); // Redirection vers la page d'accueil
};

$select_user = mysqli_query($conn, "SELECT * FROM `user_form` WHERE ID = '$user_id'") or die("Erreur de requête"); // Sélection des informations de l'utilisateur
    if (mysqli_num_rows($select_user) > 0){
    $fetch_user = mysqli_fetch_assoc($select_user); // Récupération des données de l'utilisateur
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
                            <a href="index2.php" class ="nav-link">Retour à l'accueil</a>
                        </li>
                    </ul>
                </div>
                <div class="icons d-flex">
                    <div class = "username"><a href="profil.php" target='_BLANK'><?php echo $fetch_user['name']; ?></a></div> <!-- Affichage du nom de l'utilisateur avec un lien vers son profil -->
                    <div>
                            <a href="card.php"><i class='bx bx-shopping-bag'></i></a>
                            <!-- <span class = "align-center">0</span> -->
                    </div>
                    <div>
                    <a class = "delete-btn" href ="../index.php?logout=<?php echo $user_id; ?>" onclick="return confirm('Es-tu sûr de te déconnecter ?');">Déconnexion</a> <!-- Bouton de déconnexion avec confirmation -->
                    </div>
                </div>
            </div>
    </header>
    

    <div>

<div class="container3">
    <div class="center-div">
        <h3>Historique Commande</h3>   
        <?php
        $total  = 0;
            $select_order2 = mysqli_query($conn, "SELECT * FROM `orders` WHERE user_id = '$user_id'") or die('Erreur de requête'); // Sélection de toutes les commandes de l'utilisateur
            if (mysqli_num_rows($select_order2)>0){
                while ($row2 = mysqli_fetch_assoc($select_order2)) {
                    $product_id = $row2['product_id'];   
                    $select_product = mysqli_query($conn, "SELECT * FROM `products` WHERE id = '$product_id'") or die('Erreur de requête'); // Sélection des détails du produit
                    while ($row = mysqli_fetch_assoc($select_product)){
                        echo $row['name']; // Affichage du nom du produit
                        echo "<br>";
                        echo "<img class = 'img' src='../img/products/".$row['image']."'>"; // Affichage de l'image du produit

                        echo "<br>";
                        echo "Prix : " .$row['price']; // Affichage du prix du produit
                        echo "<br>";
                        $total += $row['price'] * $row2['quantity']; // Calcul du total
                    }
                    echo "Quantité : ".$row2['quantity']. "<br>"; // Affichage de la quantité commandée
                    echo "Date : " .$row2['date']; // Affichage de la date de la commande
                    echo "<br><br>";
                }
            }else{
                echo "Vous n'avez effectué aucune commande ! "; // Message si aucune commande n'a été effectuée
            }
        ?>
    </div>
</div>

</body>
</html>

<?php
    // Fermeture de la connexion à la base de données
    mysqli_close($conn);
?>