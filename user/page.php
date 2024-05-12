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
};

// Récupération de l'identifiant du produit depuis l'URL
$product_id = $_GET["id"];

// Traitement de l'ajout d'un produit au panier
if(isset($_POST['add_panier'])){ // Vérification si le formulaire d'ajout au panier a été soumis
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

// Sélection des informations de l'utilisateur connecté
$select_user = mysqli_query($conn, "SELECT * FROM `user_form` WHERE ID = '$user_id'") or die("Erreur de requête");
if (mysqli_num_rows($select_user) > 0){
    // Récupération des données de l'utilisateur
    $fetch_user = mysqli_fetch_assoc($select_user);
}



// Configuration du fuseau horaire
date_default_timezone_set('Europe/Paris');

// Traitement de la soumission d'un commentaire
if(isset($_POST['submit'])){
    // Récupération des données du formulaire de commentaire
    $nom = $_POST['nom'];
    $commentaire  = mysqli_real_escape_string($conn, $_POST['commentaire']);
    $date_heure = date("Y-m-d H:i:s");

    // Insertion du commentaire dans la base de données
    mysqli_query($conn, "INSERT INTO commentaires(product_id, user_id, name, commentaire, date_heure) VALUES('$product_id', '$user_id','$nom','$commentaire', '$date_heure')");
    // Message de succès pour la publication du commentaire
    $message[] = "Votre commentaire a été posté !";
}

// Traitement de la suppression d'un commentaire
if(isset($_GET['commentaireid'])) {
    // Récupération de l'identifiant du commentaire à supprimer depuis l'URL
    $delete_comment_id = $_GET['commentaireid'];
    // Suppression du commentaire de la base de données
    mysqli_query($conn, "DELETE FROM commentaires WHERE id = $delete_comment_id AND user_id = $user_id");
    // Redirection vers la page d'accueil
    header("Location: index2.php");
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="../styles/style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="../img/logo.png" type="image/x-icon">
    <title>AS Shop - <?php 
    // Récupération du nom du produit pour affichage dans le titre de la page
    $select_product = mysqli_query($conn, "SELECT name FROM `products` WHERE id = $product_id ") or die("Erreur de requête");
    if(mysqli_num_rows($select_product)>0){
        $fetch_name = mysqli_fetch_assoc($select_product);
        echo $fetch_name['name'];
    }
    ?>
     </title>
</head>
<body>

<?php
// Affichage des messages éventuels
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
                    <span class="fermer">Fermer <i class='bx bx-x'></i></span>
                </div>
                <ul class="nav-list d-flex">
                    <li class="nav-item">

                    </li>
                    <li class="nav-item">
                        <a href="index2.php" class="nav-link">Retour</a>
                    </li>
                </ul>
            </div>
            <div class="icons d-flex">
                <div class="username"><a href="profil.php" target='_BLANK'><?php echo $fetch_user['name']; ?></a></div>
                <div>
                    <a href="card.php"><i class='bx bx-shopping-bag'></i></a>
                    <!-- <span class = "align-center">0</span> -->
                </div>
                <div>
                    <a class="delete-btn" href ="../index.php?logout=<?php echo $user_id; ?>" onclick="return confirm('Es-tu sûr de te déconnecter ?');">Déconnexion</a>
                </div>
            </div>
        </div>
    </nav>
</header>

<section class="products2">
    <div class="box-container2">
        <?php
        // Sélection des informations du produit spécifié
        $select_product = mysqli_query($conn, "SELECT * FROM `products` WHERE id = $product_id ") or die("Erreur de requête");
        if (mysqli_num_rows($select_product) > 0){
            while($fetch_product = mysqli_fetch_assoc($select_product)){
        ?>
        <div class="container2">
            <div class="name2"><h1><?php echo $fetch_product['name']; ?></h1></div>
            <div class="price2">Prix : <?php echo $fetch_product['price']; ?>€</div>
            <div class="stock2">
            <?php
                // Vérification de la disponibilité du produit
                if ($fetch_product['quantity']>0){
                    echo "Stock : ". $fetch_product['quantity'];
                }else{
                    echo "Rupture de Stock";
                }
             ?>
            </div>
            <br>
            <div class="description2"><ul>
                <?php 
                // Affichage de la description du produit
                $fetch_product['description'];
                $liste = explode(";", $fetch_product['description']);
                foreach ($liste as $element) {
                    echo $element."<br>";
                }
            ?>
            </ul>
            </div>
            <div class="form-container2">
                <form action="" method="POST">
                    <input class="box" type="number" name="product_quantity" min="1" value="1">
                    <input type="hidden"  name="product_image" value ="<?php echo $fetch_product['image']; ?>">
                    <input type="hidden"  name="product_name" value ="<?php echo $fetch_product['name']; ?>">
                    <input type="hidden"  name="product_price" value ="<?php echo $fetch_product['price']; ?>">
                    <input type="submit" value="  Ajoute au Panier  " name="add_panier" class="btn2">
                </form>
            </div>
        </div>
        <div class="container-img2">
            <img class="img2" src="../img/products/<?php echo $fetch_product['image']; ?>" alt="">
        </div>
        <?php
             };
        };
        ?>
    </div>
</section>

<section class="container-commentaire">
    <h2>Commentaires</h2>
    <form action="" method="POST">
        <input type="hidden" name="nom" value="<?php echo $fetch_user['name']; ?>"><br>
        <textarea class="box4" name="commentaire" placeholder="Saisissez votre commentaire" required></textarea><br>
        <input type="submit" name="submit" value="Envoyer" class="btn2">
</section>

<section class="container-commentaire">
    <?php
    // Sélection des commentaires pour le produit spécifié
    $select_commentaire = mysqli_query($conn, "SELECT * FROM commentaires WHERE product_id = $product_id");
    $select_commentaire_user = mysqli_query($conn, "SELECT commentaire FROM commentaires WHERE  user_id = $user_id");
    if (mysqli_num_rows($select_commentaire)>0){
        // Affichage des commentaires des utilisateurs
        echo "<h2>Avis des Utilisateurs</h2>";
        echo "<div class='box5'>";
        while ($ligne = mysqli_fetch_assoc($select_commentaire)){
            echo "<b>".$ligne["name"]. "</b>" . "<br>";
            echo "<span class='commentaire'>Posté le " . date("d/m/Y à H:i", strtotime($ligne["date_heure"])) . "</span>"."<br><br>";
            echo $ligne["commentaire"] . "<br><br>";
            // Vérification si l'utilisateur connecté est l'auteur du commentaire
            if ($ligne['user_id'] == $user_id) {
                echo "<div class='delete-btn'><a href='page.php?commentaireid=".$ligne['id']."' onclick=\"return confirm('Es-tu sûr de vouloir supprimer ce commentaire ?');\"> Supprimer</a></div><br><br>";           
            }
        }
    }else{
        // Message indiquant qu'aucun utilisateur n'a posté de commentaire
        echo "Aucun utilisateur n'a posté de commentaire, soyez le premier !";
    }

    // Fermeture de la connexion à la base de données
    mysqli_close($conn);
    ?>
</div>
</section>
</body>
</html>
