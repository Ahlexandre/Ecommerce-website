<?php
#Projet réalisé par DU Alexandre

session_start(); // Démarre la session PHP
if(!isset($_SESSION['admin_email'])){ // Vérifie si l'email de l'administrateur n'est pas défini dans la session
    header("Location:login.php"); // Redirige vers la page de connexion
    exit; // Arrête l'exécution du script après la redirection
}
if(empty($_SESSION['admin_email'])){ // Vérifie si l'email de l'administrateur est vide dans la session
    header("Location:login.php"); // Redirige vers la page de connexion
    exit; // Arrête l'exécution du script après la redirection
}

include 'connexion.php'; // Inclut le fichier de connexion à la base de données
$admin_id = $_SESSION['admin_email'];  
$select_admin = mysqli_query($conn, "SELECT * FROM `admin` WHERE email = '$admin_id'") or die("Erreur de requête"); // Exécute une requête pour sélectionner l'administrateur par son email
if (mysqli_num_rows($select_admin) > 0){ // Vérifie s'il y a des résultats retournés par la requête
    $fetch_admin = mysqli_fetch_assoc($select_admin); // Récupère les données de l'administrateur sous forme de tableau associatif
};

if (isset($_GET['logout'])){ // Vérifie si le paramètre 'logout' est présent dans l'URL
    unset($fetch_admin['id']); // Supprime la clé 'id' des données de l'administrateur
    session_destroy(); // Détruit toutes les données de session
    header('Location:index.php'); // Redirige vers la page d'accueil
}

require("commande.php"); // Inclut le fichier de fonction 'commande.php'

if(isset($_POST['Supprimer']) && isset($_POST['id'])) { // Vérifie si le bouton de suppression a été cliqué et si l'ID du produit est défini
    supprimer($_POST['id']); // Appelle la fonction pour supprimer le produit avec l'ID spécifié
    $message[] = "Produit supprimé avec succès !"; // Ajoute un message de confirmation de suppression
}

$produits = afficher(); // Appelle la fonction pour afficher tous les produits de la base de données
?>

<!DOCTYPE html>
<html lang = "fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="../styles/style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="../img/logo.png" type="image/x-icon">
    <title>AS Shop - Supprimer un produit</title>
</head>
<body>

<?php

if(isset($message)){ // Vérifie si des messages sont présents
   foreach($message as $message){ // Parcourt tous les messages
      echo '<div class="message" onclick="this.remove();">'.$message.'</div>'; // Affiche chaque message dans une boîte de message avec la possibilité de le supprimer en cliquant dessus
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
                <div class="icons d-flex">
                    <div class = "username"><?php echo $fetch_admin['email']; ?></div> <!-- Affiche l'email de l'administrateur -->
                    <div>
                    <a class = "delete-btn" href ="../index.php?logout=<?php echo $fetch_admin['id']; ?>" onclick="return confirm('Es-tu sûr de te déconnecter ?');">Déconnexion</a> <!-- Lien pour se déconnecter -->
                    </div>
                </div>
            </div>

        </nav>


    <header class = "section service">
        <div class="service-center container">

            <div class="service">
                <span class="icon"><a href="admin.php"><div><i class='bx bx-cart-add'></i></div></a></span>
                <a href="admin.php"><h4>Ajouter un produit</h4></a>
            </div>

            <div class="service">
                <span class="icon"><a href="afficher.php"><div><i class='bx bx-check-square' ></i></div></a></span>
                <a href="afficher.php"><h4>Modifier un produit</h4></a>
            </div>

        </div>
    </header>

<main>
<div class="shopping-cart">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Image</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Supprimer</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($produits as $product): ?> <!-- Boucle sur tous les produits -->
                <tr>
                    <td><?php echo $product['id']; ?></td> <!-- Affiche l'ID du produit -->
                    <td><?php echo strtoupper($product['name']); ?></td> <!-- Affiche le nom du produit en majuscules -->
                    <td><img src="../img/products/<?= $product['image'] ?>" alt="<?= $product['name'] ?>" style="width:140px;"></td> <!-- Affiche l'image du produit avec un lien vers l'image et un texte alternatif du nom du produit -->
                    <td><?php echo $product['price']; ?>€</td> <!-- Affiche le prix du produit -->
                    <td><?php echo $product['quantity']; ?></td> <!-- Affiche la quantité en stock du produit -->
                    <td>
                    <form method="POST"> <!-- Formulaire pour supprimer un produit -->
                    <input type="hidden" name="id" value="<?= $product['id'] ?>"> <!-- Champ caché contenant l'ID du produit -->
                    <input type="submit" class="delete-btn" name="Supprimer" value="Supprimer"> <!-- Bouton pour soumettre le formulaire de suppression -->
                    </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</main>
</body>
</html>

<?php
    // Fermeture de la connexion à la base de données
    mysqli_close($conn);
?>