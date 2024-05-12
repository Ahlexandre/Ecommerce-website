<?php
#Projet réalisé par DU Alexandre

//Ce fichier permet d'afficher une page dédiée pour chaque produit à l'aide de la récupération du product_id


// Inclusion du fichier de connexion à la base de données et démarrage de la session PHP
include 'connexion.php';
session_start();

// Récupération de l'ID du produit depuis la requête GET
$product_id = $_GET["id"];
?>

<!DOCTYPE html>
<html lang = "fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="styles/style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="img/logo.png" type="image/x-icon">
    <title>AS Shop - <?php 
    // Récupération du nom du produit à partir de la base de données en fonction de son ID
    $select_product = mysqli_query($conn, "SELECT name FROM `products` WHERE id = $product_id ") or die("Erreur de requête");
    if(mysqli_num_rows($select_product) > 0){
        $fetch_name = mysqli_fetch_assoc($select_product);
        echo $fetch_name['name'];
    }
    ?></title>
</head>
<body>

<?php
// Affichage des messages s'il y en a
if(isset($message)){
   foreach($message as $message){
      echo '<div class="message" onclick="this.remove();">'.$message.'</div>'; // Affichage des messages dans une boîte de message avec possibilité de suppression
   }
}
?>

<header class="header">
    <nav class="nav container">
        <div class="navigation d-flex">
            <div class="icon1">
                <i class='bx bx-menu'></i> <!-- Icône du menu -->
            </div>
            <div class="logo">
                <a href="#"><span>AS</span> Shop</a> <!-- Logo de la boutique -->
            </div>
            <div class="menu">
                <ul class="nav-list d-flex">
                    <li class="nav-item"></li>
                    <li class="nav-item">
                        <a href="index.php" class="nav-link">Retour</a> <!-- Lien pour retourner à la page d'accueil -->
                    </li>
                </ul>
            </div>
            <div class="icons d-flex">
                <div><a href="login.php"><i class='bx bx-user'></i></a></div> <!-- Icône pour se connecter -->
                <div>
                    <a href="#"><i class='bx bx-shopping-bag'></i></a> <!-- Icône du panier -->
                </div>
            </div>
        </div>
    </nav>
</header>

<section class="products2">
    <div class="box-container2">
        <?php
        // Récupération et affichage des détails du produit à partir de la base de données en fonction de son ID
        $select_product = mysqli_query($conn, "SELECT * FROM `products` WHERE id = $product_id ") or die("Erreur de requête");
        if(mysqli_num_rows($select_product) > 0){
            while($fetch_product = mysqli_fetch_assoc($select_product)){
        ?>
        <div class="container2">
            <div class="name2"><h1><?php echo $fetch_product['name']; ?></h1></div> <!-- Affichage du nom du produit -->
            <div class="price2">Prix : <?php echo $fetch_product['price']; ?>€</div> <!-- Affichage du prix du produit -->
            <div class="stock2">
            <?php
                // Vérification et affichage du stock du produit
                if ($fetch_product['quantity'] > 0){
                    echo "Stock : ". $fetch_product['quantity']; // Affichage du stock disponible
                } else {
                    echo "Rupture de Stock"; // Message de rupture de stock
                }
             ?>
            </div>
            <br>
            <div class="description2">
                <ul>
                <?php 
                // Affichage de la description du produit
                $fetch_product['description']; // Récupération de la description du produit
                $liste = explode(";", $fetch_product['description']); // Séparation de la description en éléments distincts
                foreach ($liste as $element) {
                    echo $element."<br>"; // Affichage de chaque élément de la description
                }
                ?>
                </ul>
            </div>
            <div class="form-container2">
                <!-- Formulaire pour ajouter le produit au panier -->
                <form action="" method="POST">
                    <input class="box" type="number" name="product_quantity" min="1" value="1"> <!-- Champ pour la quantité -->
                    <input type="hidden"  name="product_image" value ="<?php echo $fetch_product['image']; ?>"> <!-- Champ caché pour l'image du produit -->
                    <input type="hidden"  name="product_name" value ="<?php echo $fetch_product['name']; ?>"> <!-- Champ caché pour le nom du produit -->
                    <input type="hidden"  name="product_price" value ="<?php echo $fetch_product['price']; ?>"> <!-- Champ caché pour le prix du produit -->
                    <input type="submit" value="Ajouter au Panier" name="add_panier" class="btn2"> <!-- Bouton pour ajouter au panier -->
                </form>
            </div>
        </div>
        <div class="container-img2">
            <img class="img2" src="img/products/<?php echo $fetch_product['image']; ?>" alt=""> <!-- Affichage de l'image du produit -->
        </div>
        <?php
             }
        }
        ?>
    </div>
</section>

<section class="container-commentaire">
    <?php
    // Récupération et affichage des commentaires sur le produit
    $select_commentaire = mysqli_query($conn, "SELECT * FROM commentaires WHERE product_id = $product_id");
    // Si le nombre total de commentaire est supérieur à 0 : on affiche tous les commentaires
    if(mysqli_num_rows($select_commentaire) > 0){
        echo "<h2>Avis des Utilisateurs</h2>";
        echo "<div class='box5'>";
        while ($ligne = mysqli_fetch_assoc($select_commentaire)){
            echo "<b>".$ligne["name"]. "</b>" . "<br>"; // Affichage du nom de l'utilisateur qui a commenté
            echo "<span class='commentaire'>Posté le " . date("d/m/Y à H:i", strtotime($ligne["date_heure"])) . "</span>"."<br><br>"; // Affichage de la date du commentaire
            echo $ligne["commentaire"] . "<br><br>"; // Affichage du commentaire
        }
    // Sinon on affiche un autre message
    } else {
        echo "Aucun utilisateur n'a posté de commentaire. <a href='login.php'>Connectez-vous pour en ajouter !</a>"; // Message si aucun commentaire n'est trouvé
    }
    mysqli_close($conn);
    ?>
</section>

<script>
// Vérifie si l'utilisateur est sur la page 'page.php' et affiche une alerte s'il tente d'ajouter un produit au panier

if (window.location.pathname.includes('page.php')) {
    let button = document.querySelector(".bx-shopping-bag");
    button.addEventListener('click', function(){
        alert("Vous devez d'abord vous connecter !"); // Alerte si l'utilisateur non connecté tente d'accéder au panier
    });
}
document.addEventListener('DOMContentLoaded', function() {
    if (window.location.pathname.includes('page.php')) {
        let buttons = document.querySelectorAll(".btn2");
        buttons.forEach(function(button) {
            button.addEventListener('click', function(){
                alert("Vous devez d'abord vous connecter !"); // Alerte si l'utilisateur non connecté tente d'ajouter au panier
            });
        });
    }
});
</script>
</body>
</html>
