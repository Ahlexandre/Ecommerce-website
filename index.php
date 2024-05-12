<?php
#Projet réalisé par DU Alexandre

include 'connexion.php'; // Inclusion du fichier de connexion à la base de données
session_start(); // Démarrage de la session
?>

<!DOCTYPE html>
<html lang = "fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href = "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" type = "text/css" href = "styles/style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="img/logo.png" type="image/x-icon">
    <title>AS Shop</title>
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

                    <div><a href="login.php"><i class='bx bx-user'></i></a></div>
                    <div>
                            <i class='bx bx-shopping-bag'></i>
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
                    <img src="img/setup.png" alt="Setup présentation">
                </div>
            </div>
        </div>
    </header>

    <section id="products" class="products"> <!-- Section pour afficher les produits -->
    <h1 class="title">Dernier arrivage</h1> <!-- Titre de la section -->
    <div class="box-container"> <!-- Conteneur pour les boîtes de produits -->

        <?php
        $select_product = mysqli_query($conn, "SELECT * FROM `products` ") or die("Erreur de requête"); // Requête pour récupérer les produits depuis la base de données
        if (mysqli_num_rows($select_product) > 0) { // Vérifie s'il y a des produits dans la base de données
            while ($fetch_product = mysqli_fetch_assoc($select_product)) { // Boucle pour parcourir tous les produits
                ?>
                <a href="page.php?id=<?php echo $fetch_product['id']; ?>"><div class="boite"> <!-- lien vers la page du produit avec l'id -->
                <form class="box" action="" method="POST"> <!-- Formulaire pour ajouter le produit au panier -->
                    <img src="img/products/<?php echo $fetch_product['image']; ?>" alt=""> <!-- Image du produit -->
                    <div class="name"><?php echo $fetch_product['name']; ?></div> <!-- Nom du produit -->
                    <div class="price"><?php echo $fetch_product['price']; ?>€</div> <!-- Prix du produit -->
                    <div class="stock">
                        <?php
                        if ($fetch_product['quantity'] > 0) { // Vérifie si le produit est en stock
                            echo "Stock : " . $fetch_product['quantity']; // Affiche le stock disponible
                        } else {
                            echo "Rupture de Stock"; // Affiche "Rupture de Stock" si le produit est en rupture de stock
                        }
                        ?>
                    </div>
                    <div class="description"><ul>
                        <?php
                        $fetch_product['description']; // Récupère la description du produit
                        $liste = explode(";", $fetch_product['description']); // Sépare la description en éléments
                        foreach ($liste as $element) { // Boucle pour afficher chaque élément de la description
                            echo $element . "<br>"; // Affiche chaque élément de la description avec un saut de ligne
                        }
                        ?>
                    </ul>
                    </div>
                    <input type="number" name="product_quantity" min="1" value="1"> <!-- Champ pour saisir la quantité du produit -->
                    <input type="hidden" name="product_image" value="<?php echo $fetch_product['image']; ?>"> <!-- Champ caché pour l'image du produit -->
                    <input type="hidden" name="product_name" value="<?php echo $fetch_product['name']; ?>"> <!-- Champ caché pour le nom du produit -->
                    <input type="hidden" name="product_price" value="<?php echo $fetch_product['price']; ?>"> <!-- Champ caché pour le prix du produit -->
                    <input type="submit" value="Ajouter au Panier" name="add_panier" class="btn2"> <!-- Bouton pour ajouter le produit au panier -->
                </form>
                </div></a>
                <?php
            };
        };
        ?>


    </div>
</section>

<script>
    if (window.location.pathname.includes('index.php')) { // Vérifie si la page actuelle est index.php
        let button = document.querySelector(".bx-shopping-bag"); // Sélectionne le bouton du panier

        button.addEventListener('click', function(){ // Ajoute un écouteur d'événement pour le clic sur le bouton du panier
            alert("Il faut d'abord vous connecter !"); // Affiche une alerte pour demander de se connecter
        });
    }
    document.addEventListener('DOMContentLoaded', function() { // Attend que le DOM soit chargé
        if (window.location.pathname.includes('index.php')) { // Vérifie si la page actuelle est index.php
            let buttons = document.querySelectorAll(".btn2"); // Sélectionne tous les boutons "Ajouter au Panier"

            buttons.forEach(function(button) { // Boucle pour chaque bouton "Ajouter au Panier"
                button.addEventListener('click', function(){ // Ajoute un écouteur d'événement pour le clic sur chaque bouton
                    alert("Il faut d'abord vous connecter !"); // Affiche une alerte pour demander de se connecter
                });
            });
        }
    });
</script>
</body>
</html>
