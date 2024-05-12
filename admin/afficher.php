<?php
#Projet réalisé par DU Alexandre

session_start(); // Démarre la session PHP
if(!isset($_SESSION['admin_email'])){ // Vérifie si l'email de l'administrateur n'est pas défini dans la session
    header("Location:login.php"); // Redirige vers la page de connexion
    exit; // Arrêter l'exécution du script après la redirection
}
if(empty($_SESSION['admin_email'])){ // Vérifie si l'email de l'administrateur est vide dans la session
    header("Location:login.php"); // Redirige vers la page de connexion
    exit; // Arrêter l'exécution du script après la redirection
}

include 'connexion.php'; // Inclut le fichier de connexion à la base de données
$admin_id = $_SESSION['admin_email']; // Récupère l'email de l'administrateur depuis la session
$select_admin = mysqli_query($conn, "SELECT * FROM `admin` WHERE email = '$admin_id'") or die("Erreur de requête"); // Exécute une requête pour sélectionner l'administrateur à partir de l'email
if (mysqli_num_rows($select_admin) > 0){ // Vérifie s'il y a des lignes retournées par la requête
    $fetch_admin = mysqli_fetch_assoc($select_admin); // Récupère les données de l'administrateur sous forme de tableau associatif
};

if (isset($_GET['logout'])){ // Vérifie si le paramètre 'logout' est présent dans l'URL
    unset($fetch_admin['id']); // Supprime la clé 'id' des données de l'administrateur
    session_destroy(); // Détruit toutes les données de session
    header('Location:index.php'); // Redirige vers la page d'accueil
}

require("commande.php"); // Inclut le fichier de fonction 'commande.php'
$produits = afficher(); // Appelle la fonction 'afficher' pour obtenir la liste des produits
?>

<!DOCTYPE html>
<html lang = "fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href = "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" type = "text/css" href = "../styles/style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="../img/logo.png" type="image/x-icon">
    <title>AS Shop - Modifier un produit</title>
</head>
<body>
    
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
                    <div class = "username"><?php echo $fetch_admin['email']; ?></a></div>
                    <div>
                    <a class = "delete-btn" href ="../index.php?logout=<?php echo $fetch_admin['id']; ?>" onclick="return confirm('Es-tu sûr de te déconnecter ?');">Déconnexion</a>
                    </div>
                </div>
            </div>

        </nav>


    <header class = "section service">
        <div class="service-center container">

            <div class="service">
                <span class="icon"><a href="admin.php"><div><i class='bx bx-cart-add'></i></i></div></a></span>
                <a href="admin.php"><h4>Ajouter un produit</h4></a>
            </div>

            <div class="service">
                <span class="icon"><a href="supp.php"><div><i class='bx bx-trash' ></i></div></a></span>
                <a href="supp.php"><h4>Supprimer un produit</h4></a>
            </div>

        </div>
    </header>   

    <div class="shopping-cart">
    <table>
        <tr>
            <thead>
            <th>ID</th>
            <th>Image</th>
            <th>Nom</th>
            <th>Quantity</th>
            <th>Prix</th>
            <th>Description</th>
            <th>Modifier</th>
</thead>
        </tr>
        <?php foreach ($produits as $product): ?> <!-- Boucle à travers tous les produits -->
        <tr>
            <td><?php echo $product['id']; ?></td>
            <td><img src="../img/products/<?= $product['image'] ?>" style="width:60px;"></td> <!-- Affiche l'image du produit -->
            <td><?php echo $product['name']; ?></td> <!-- Affiche le nom du produit -->
            <td><?php echo $product['quantity']; ?></td> <!-- Affiche la quantité du produit -->
            <td><?php echo $product['price']; ?>€</td> <!-- Affiche le prix du produit -->
            <td><?php echo $product['description']; ?></td> <!-- Affiche la description du produit -->
            <td><a class = "option-btn" href="modifier.php?pdt=<?= $product['id'] ?>">Modifier</a></td> <!-- Affiche un lien pour modifier le produit -->
        </tr>
        <?php endforeach; ?>
    </table>
    
    </div> 

</body>
</html>

<?php
    // Fermeture de la connexion à la base de données
    mysqli_close($conn);
?>