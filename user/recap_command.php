<?php

// Inclusion du fichier de connexion à la base de données
include 'connexion.php';

// Démarrage de la session
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
};

// Vérification si le formulaire de paiement a été soumis
if (isset($_POST['submit'])){
    
    // Échappement des caractères spéciaux pour les données du formulaire
    $numCarte = mysqli_real_escape_string($conn, $_POST['numCarte']);
    $dateexpiration = mysqli_real_escape_string($conn, $_POST['dateexpiration']);
    $nomCarte = mysqli_real_escape_string($conn, $_POST['nomCarte']);
    // Hachage du code secret de la carte de crédit
    $codeSecret_Hash = password_hash($_POST['codeSecret'], PASSWORD_DEFAULT);

    // Insertion des informations de la carte de crédit dans la base de données
    mysqli_query($conn, "INSERT INTO `credit_card` (user_id, numcarte, dateexpiration, nomCarte, codeSecret) VALUES('$user_id','$numCarte','$dateexpiration','$nomCarte','$codeSecret_Hash') ") or die('Erreur de requête');

    // Récupération des informations de livraison depuis le formulaire
    $numrue = mysqli_real_escape_string($conn, $_POST['numrue']);
    $nomrue = mysqli_real_escape_string($conn, $_POST['nomrue']);
    $ville = mysqli_real_escape_string($conn, $_POST['ville']);
    $codepostal = mysqli_real_escape_string($conn, $_POST['codepostal']);
    

    // Récupération des produits dans le panier de l'utilisateur
    $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('Erreur de requête');
    
    // Parcours des produits du panier
    while ($row = mysqli_fetch_assoc($cart_query)) {
        $product_id = $row['product_id'];
        $price = $row['price'];
        $image = $row['image'];
        $quantity = $row['quantity'];
        $codepromo = $row['codepromo'];

        // Insertion des détails de la commande dans la table des commandes
        mysqli_query($conn, "INSERT INTO `orders` (user_id, product_id, image, price, quantity, date, heure, codepromo, numrue, nomrue, ville, codepostal) VALUES('$user_id','$product_id', '$image', '$price', '$quantity', CURDATE(), CURTIME(), '$codepromo', '$numrue','$nomrue','$ville','$codepostal') ") or die('Erreur de requête');
    }
}

// Vérification si le formulaire de paiement a été soumis
if (isset($_POST['submit'])) {
    // Début de la transaction
    mysqli_begin_transaction($conn);

    try {
        // Vérification de la disponibilité des produits dans le panier
        $cart_query = mysqli_query($conn, "SELECT product_id, quantity FROM `cart` WHERE user_id = '$user_id' FOR UPDATE") or die('Erreur de requête');

        while ($row = mysqli_fetch_assoc($cart_query)) {
            $product_id = $row['product_id'];
            $quantity = $row['quantity'];

            // Vérification du stock
            $check_stock_query = mysqli_query($conn, "SELECT quantity FROM `products` WHERE id = '$product_id' FOR UPDATE");
            $stock_row = mysqli_fetch_assoc($check_stock_query);
            $stock_quantity = $stock_row['quantity'];

            // Vérifcation de la quantité commandé sur la quantité disponible
            if ($stock_quantity < $quantity) {
                // Message d'erreur si le stock est insuffisant
                $message[] = 'La quantité commandée est supérieure à la quantité stockée';
                // Annulation de la transaction
                mysqli_rollback($conn);
                // Redirection vers la page d'accueil
                header('location:index2.php');
                // Arrêt de l'exécution après la redirection
                exit();
            }

            // Mise à jour du stock après la commande
            $update_stock_query = mysqli_query($conn, "UPDATE `products` SET quantity = quantity - $quantity  WHERE id = '$product_id'");

            if (!$update_stock_query) {
                throw new Exception('Erreur lors de la mise à jour du stock');
            }
        }

        // Suppression des produits du panier après la commande
        $delete_cart_query = mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'");

        if (!$delete_cart_query) {
            throw new Exception('Erreur lors de la suppression des produits du panier');
        }

        // Validation de la transaction
        mysqli_commit($conn);

    } catch (Exception $e) {
        // En cas d'erreur, on annule la transaction
        mysqli_rollback($conn);
        $message[] = $e->getMessage();
    }
}

// Sélection des informations de l'utilisateur actuel
$select_user = mysqli_query($conn, "SELECT * FROM `user_form` WHERE ID = '$user_id'") or die("Erreur de requête");
if (mysqli_num_rows($select_user) > 0){
    $fetch_user = mysqli_fetch_assoc($select_user);
}

// Message de remerciement après la commande
$message[]= "Merci d'avoir effectué une commande sur notre site !";

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

// Affichage des messages
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
                            <a href="index2.php" class ="nav-link">Retour à l'accueil</a>
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
    

    <div>
<?php 
    // Sélection des informations de la carte de crédit de l'utilisateur
    $select_credit_cart = mysqli_query($conn, "SELECT * FROM `credit_card` WHERE user_id = '$user_id'") or die("Erreur de requête");
    if (mysqli_num_rows($select_credit_cart) > 0){
        $fetch_credit_cart = mysqli_fetch_assoc($select_credit_cart);
    }

    // Sélection de la dernière commande de l'utilisateur
    $select_order = mysqli_query($conn, "SELECT * FROM `orders` WHERE user_id = '$user_id' AND date = (SELECT MAX(date) FROM `orders` WHERE user_id = '$user_id' ) AND heure = (SELECT MAX(heure) FROM `orders` WHERE user_id = '$user_id' ) ;") or die("Erreur de requête");
    if (mysqli_num_rows($select_order) > 0){
        $fetch_order = mysqli_fetch_assoc($select_order);
    }
?>

<div class="container2">
    <div class="left-div">
       
        <div class = "infolivraison">
        <h3>Adresse de Livraison</h3>
        Adresse : <?php echo $fetch_order['numrue']. " " .$fetch_order['nomrue'].", ".$fetch_order['ville'].", ".$fetch_order['codepostal']; ?>
        </div>
        <br>
        <div class = "infocarte">
        <h3>Informations Bancaire</h3>
        Numéro de carte : <?php echo $carteBancaire = "XXXX XXXX XXXX " . substr($numCarte, -4); ?><br>
        Nom sur la carte : <?php echo $nomCarte; ?>
        
        </div>
    </div>
    <div class="right-div">
        <h3>Information Commande</h3>   
        <?php
        // Calcul du total de la commande
        $total  = 0;
            $select_order2 = mysqli_query($conn, "SELECT * FROM `orders` WHERE user_id = '$user_id' AND date = (SELECT MAX(date) FROM `orders` WHERE user_id = '$user_id' ) AND heure = (SELECT MAX(heure) FROM `orders` WHERE user_id = '$user_id' ) ;") or die("Erreur de requête");
            while ($row2 = mysqli_fetch_assoc($select_order2)) {
                $product_id = $row2['product_id'];   
                $select_product = mysqli_query($conn, "SELECT * FROM `products` WHERE id = '$product_id'") or die('Erreur de requête');
                while ($row = mysqli_fetch_assoc($select_product)){
                    echo $row['name'];
                    echo "<br>";
                    echo "<img class = 'img' src='../img/products/".$row['image']."'>";

                    echo "<br>";
                    echo "Prix : " .$row['price'];
                    echo "<br>";
                    $total += $row['price'] * $row2['quantity'];
                }
                echo "Quantité : ".$row2['quantity']. "<br>";
                echo "<br><br>";
            }
            
            // Vérification et application du code promo
            $select_promo = mysqli_query($conn, "SELECT codepromo FROM `orders` WHERE user_id = '$user_id' AND date = (SELECT MAX(date) FROM `orders` WHERE user_id = '$user_id' ) AND heure = (SELECT MAX(heure) FROM `orders` WHERE user_id = '$user_id' ) ;");
            if (mysqli_num_rows($select_promo) > 0){
                $fetch_promo = mysqli_fetch_assoc($select_promo);
                if($fetch_promo['codepromo']){
                    echo "Code Promo appliqué ! <br>";
                    echo "Sous-total : ".$total."<br>";
                    echo "Total : ". $total*0.85;
                }
                else{
                    echo "Pas de Code Promo ! <br>";
                    echo "Total : ".$total;
                }
            }


        ?>
    </div>
</div>

<div class = "text">Les conditions et délais de livraison sont sur notre page <a href = "faq.php" target = "_BLANK">FAQ</a></div>

</body>
</html>

<?php
    // Fermeture de la connexion à la base de données
    mysqli_close($conn);
?>
