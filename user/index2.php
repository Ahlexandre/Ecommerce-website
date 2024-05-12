<?php
#DU Alexandre et JELASSI Bader

include 'connexion.php'; // Inclusion du fichier de connexion à la base de données
session_start(); // Démarrage de la session

$user_id = $_SESSION['user_id']; // Récupération de l'ID de l'utilisateur depuis la session

if (isset($_GET['logout'])){ // Vérification si la requête GET contient 'logout'
    unset($user_id); // Suppression de l'ID de l'utilisateur
    session_destroy(); // Destruction de la session
    header('location:index.php'); // Redirection vers la page d'accueil
};

if(isset($_POST['add_panier'])){ // Vérification si le formulaire d'ajout au panier a été soumis
    $product_id = $_POST['product_id']; // Récupération de l'ID du produit depuis le formulaire
    $product_name = $_POST['product_name']; // Récupération du nom du produit depuis le formulaire
    $product_price = $_POST['product_price']; // Récupération du prix du produit depuis le formulaire
    $product_image = $_POST['product_image']; // Récupération de l'image du produit depuis le formulaire
    $product_quantity = $_POST['product_quantity']; // Récupération de la quantité du produit depuis le formulaire

    $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('Erreur de requête'); // Requête pour vérifier si le produit est déjà dans le panier

    $select_stock = mysqli_query($conn, "SELECT * FROM `products` WHERE id = $product_id") or die('Erreur de requête');
    $fetch_stock = mysqli_fetch_assoc($select_stock );

    if ($fetch_stock["quantity"] <= 0){ // Vérifcation si la quantité en stock
        $message[] = 'Vous ne pouvez pas ajouter un produit en rupture de stock !';
    }else{
        if(mysqli_num_rows($select_cart)>0){ // Vérification si le produit est déjà dans le panier
            $message[] = 'Le produit a déjà été ajouté dans le panier !'; // Message d'erreur
        }else{
            mysqli_query($conn, "INSERT INTO `cart`(user_id, product_id, name, price, image, quantity) VALUES('$user_id','$product_id', '$product_name', '$product_price', '$product_image','$product_quantity')") or die('Erreur de requête'); // Insertion du produit dans le panier
            $message[] = 'Le produit a été ajouté dans le panier'; // Message de succès
        }
    }

    
}

?>

<?php

if(isset($message)){ // Vérification si des messages sont présents
   foreach($message as $message){ // Parcours de chaque message
      echo '<div class="message" onclick="this.remove();">'.$message.'</div>'; // Affichage des messages
   }
}

?>

<?php
    $select_user = mysqli_query($conn, "SELECT * FROM `user_form` WHERE ID = '$user_id'") or die("Erreur de requête"); // Requête pour récupérer les informations de l'utilisateur
    if (mysqli_num_rows($select_user) > 0){ // Vérification si l'utilisateur existe
    $fetch_user = mysqli_fetch_assoc($select_user); // Récupération des données de l'utilisateur
};

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
    <title>AS Shop - Accueil</title>
</head>
<body>

    <div class="promo">
        <span>Promo de 15% de Réduction avec le code : PARIS1</span>
    </div>

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
                            <a href="#" class ="nav-link">Accueil</a>
                        </li>
                        <li class="nav-item">
                            <a href="#products" class ="nav-link">Boutique</a>
                        </li>
                        <li class="nav-item">
                            <a href="faq.php" class ="nav-link" target='_BLANK'>FAQ</a>
                        </li>
                        <li class="nav-item">
                            <a href="contact.php" class ="nav-link" target='_BLANK'>Contact</a>
                        </li>
                    </ul>
                </div>
                <div class="icons d-flex">
                    <div class = "username"><a href="profil.php" target='_BLANK'><?php echo $fetch_user['name']; ?></a></div>
                    <div>
                            <a href="card.php"><i class='bx bx-shopping-bag'></i></a>
                    </div>
                    <div>
                    <a href="historique_command.php"><i class='bx bxs-book-content'></i></a>
                    </div>
                    <div>
                        <a class = "delete-btn" href ="../index.php?logout=<?php echo $user_id; ?>" onclick="return confirm('Es-tu sûr de te déconnecter ?');">Déconnexion</a>
                    </div>
                </div>
            </div>

        </nav>

        <div class="banniere">
            <div class="banniere-contenu d-flex container">
                <div class="gauche">
                    <span class = "Sous-titre">Nouveautés</span>
                    <h1 class = "titre">
                        Jusqu'à
                        <span class ="couleur">15%<br>
                        de réduction</span>
                        sur<br>
                        nos offre de la semaine
                    </h1>
                    <h5>Du 01 avril au 30 avril</h5>
                    <a href="#products" class ="btn">Découvrir</a>
                </div>
                <div class="droite">
                    <img src="../img/setup.png" alt="">
                </div>
            </div>
        </div>
    </header>

    <section id="products" class="products">
    <h1 class="title">Dernier arrivage</h1>
    <div class="box-container">

        <?php
        $select_product = mysqli_query($conn, "SELECT * FROM `products` ") or die("Erreur de requête"); // Requête pour récupérer les produits
        if (mysqli_num_rows($select_product) > 0) { // Vérification si des produits sont présents
            while ($fetch_product = mysqli_fetch_assoc($select_product)) { // Parcours des produits
                ?>
                <a href="page.php?id=<?php echo $fetch_product['id']; ?>"><div class="boite">
                <form class="box" action="" method="POST">
                    <img src="../img/products/<?php echo $fetch_product['image']; ?>" alt="">
                    <div class="name"><?php echo $fetch_product['name']; ?></div>
                    <div class="price"><?php echo $fetch_product['price']; ?>€</div>
                    <div class="stock">
                        <?php
                        if ($fetch_product['quantity'] > 0) { // Vérification si le produit est en stock
                            echo "Stock : " . $fetch_product['quantity'];
                        } else {
                            echo "Rupture de Stock"; // Affichage en cas de rupture de stock
                        }
                        ?>
                    </div>
                    <div class="description"><ul>
                        <?php
                        $fetch_product['description']; // Récupération de la description du produit
                        $liste = explode(";", $fetch_product['description']); // Séparation de la description en éléments
                        foreach ($liste as $element) { // Parcours des éléments de la description
                            echo $element . "<br>"; // Affichage de chaque élément de la description
                        }
                        ?>
                    </ul>
                    </div>
                    <input type="number" name="product_quantity" min="1" value="1">
                    <input type="hidden" name="product_image" value="<?php echo $fetch_product['image']; ?>">
                    <input type="hidden" name="product_id" value="<?php echo $fetch_product['id']; ?>">
                    <input type="hidden" name="product_name" value="<?php echo $fetch_product['name']; ?>">
                    <input type="hidden" name="product_price" value="<?php echo $fetch_product['price']; ?>">
                    <input type="submit" value="Ajouter au Panier" name="add_panier" class="btn2">
                </form>
                </div></a>
                <?php
            };
        };
        ?>


    </div>
</section>

<script src="script/script.js"></script>
</body>
</html>

<?php
    // Fermeture de la connexion à la base de données
    mysqli_close($conn);
?>
