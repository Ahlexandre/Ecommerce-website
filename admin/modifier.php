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
if(!isset($_GET['pdt']) || empty($_GET['pdt']) || !is_numeric($_GET['pdt'])){ // Vérifie si le paramètre 'pdt' n'est pas défini dans l'URL, s'il est vide ou s'il n'est pas numérique
    header("Location:admin.php"); // Redirige vers la page d'administration
    exit; // Arrête l'exécution du script après la redirection
}
$id = $_GET['pdt']; // Récupère l'ID du produit à modifier depuis l'URL

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
$produits = produit($id); // Appelle la fonction 'produit' pour récupérer les détails du produit à modifier

if($produits){ // Vérifie si des détails de produit ont été récupérés avec succès
    $idpdt = $produits[0]['id']; // Récupère l'ID du produit
    $nom = $produits[0]['name']; // Récupère le nom du produit
    $prix = $produits[0]['price']; // Récupère le prix du produit
    $image = $produits[0]['image']; // Récupère le nom de l'image du produit
    $description = $produits[0]['description']; // Récupère la description du produit
    $stock = $produits[0]['quantity']; // Récupère la quantité en stock du produit
}
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
    <title>AS Shop - Ajouter un produit</title>
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
                    <ul class ="nav-list d-flex">
                        <li class="nav-item">

                        </li>
                        <li class="nav-item">
                            <a href="afficher.php" class ="nav-link">Retour</a> <!-- Lien pour revenir à la page d'affichage -->
                        </li>
                    </ul>
                </div>
                <div class="icons d-flex">
                    <div class = "username"><?php echo $fetch_admin['email']; ?></a></div>
                    <div>
                    <a class = "delete-btn" href ="../index.php?logout=<?php echo $fetch_admin['id']; ?>" onclick="return confirm('Es-tu sûr de te déconnecter ?');">Déconnexion</a>
                    </div>
                </div>
            </div>

        </nav>
    <div class = "form-container">
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $idpdt; ?>"> <!-- Champ caché pour stocker l'ID du produit -->
            <label for="nom">Nom du produit :</label>
            <input class = "box2" type="text" name="nom" value="<?php echo $nom; ?>" required> <!-- Champ pour modifier le nom du produit -->
            <label for="description">Description :</label>
            <textarea class = "box3" type="text" name="description" required><?php echo $description; ?></textarea> <!-- Champ pour modifier la description du produit -->
            <label for="prix">Prix :</label>
            <input class = "box2" type="text" name="prix" value="<?php echo $prix; ?>" required> <!-- Champ pour modifier le prix du produit -->
            <label for="image">Image :</label>
            <input class = "box2" type="text" name="image" value="<?php echo $image; ?>" required> <!-- Champ pour modifier le nom de l'image du produit -->
            <img class = "imgproduct" src = "../img/products/<?php echo $image; ?>"> <!-- Affiche l'image du produit -->
            <br>
            <label for="stock">Stock :</label>
            <input class = "box2" type="number" name="stock" value="<?php echo $stock; ?>" required> <!-- Champ pour modifier la quantité en stock du produit -->
            <input class = "btn2" type="submit" name="Modifier" value="Enregistrer"> <!-- Bouton pour enregistrer les modifications -->
        </form>
    </div>
</body>
</html>
<?php
if(isset($_POST["Modifier"])){ // Vérifie si le formulaire de modification a été soumis

    if(isset($_POST["image"]) && isset($_POST["prix"]) && isset($_POST["description"]) && isset($_POST["nom"]) && isset($_POST["stock"])){ // Vérifie si toutes les données nécessaires sont présentes dans le formulaire

        if(!empty($_POST["image"]) && !empty($_POST["prix"]) && !empty($_POST["description"]) && !empty($_POST["nom"]) && !empty($_POST["stock"])){ // Vérifie si aucun champ n'est vide dans le formulaire

            $id = htmlspecialchars(strip_tags($_POST["id"])); // Récupère l'ID du produit à partir du formulaire
            $image = htmlspecialchars(strip_tags($_POST["image"])); // Récupère le nom de l'image du produit à partir du formulaire
            $prix = htmlspecialchars(strip_tags($_POST["prix"])); // Récupère le prix du produit à partir du formulaire
            $description = htmlspecialchars(strip_tags($_POST["description"])); // Récupère la description du produit à partir du formulaire
            $nom = htmlspecialchars(strip_tags($_POST["nom"])); // Récupère le nom du produit à partir du formulaire
            $stock = htmlspecialchars(strip_tags($_POST["stock"])); // Récupère la quantité en stock du produit à partir du formulaire

            modifier($nom, $image, $description, $prix, $stock, $id); // Appelle la fonction pour modifier le produit dans la base de données
            header("Location: afficher.php"); // Redirige vers la page d'affichage des produits
            
            exit; // Arrête l'exécution du script après la redirection
        }
    }
}
?>

<?php
    // Fermeture de la connexion à la base de données
    mysqli_close($conn);
?>
