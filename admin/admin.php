<?php
#Projet réalisé par DU Alexandre

session_start(); // Démarre la session PHP
if(!isset($_SESSION['admin_email'])){ // Vérifie si l'email de l'administrateur n'est pas défini dans la session
    header("Location:login.php"); // Redirige vers la page de connexion
    exit; // Termine l'exécution du script
}
if(empty($_SESSION['admin_email'])){ // Vérifie si l'email de l'administrateur est vide dans la session
    header("Location:login.php"); // Redirige vers la page de connexion
    exit; // Termine l'exécution du script
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

if(isset($_POST["Ajouter"])){ // Vérifie si le formulaire d'ajout de produit est soumis
    if(isset($_POST["image"]) && isset($_POST["prix"]) && isset($_POST["description"]) && isset($_POST["nom"])){ // Vérifie si les champs requis sont définis dans la requête POST
        if(!empty($_POST["image"]) && !empty($_POST["prix"]) && !empty($_POST["description"]) && !empty($_POST["nom"])){ // Vérifie si les champs requis ne sont pas vides
            $image = $_POST["image"]; // Récupère l'URL de l'image du produit
            $prix = htmlspecialchars($_POST["prix"]); // Récupère et convertit le prix du produit en entité HTML spéciales pour éviter les attaques XSS
            $description = htmlspecialchars($_POST["description"]); // Récupère et convertit la description du produit en entité HTML spéciales pour éviter les attaques XSS
            $nom = htmlspecialchars($_POST["nom"]); // Récupère et convertit le nom du produit en entité HTML spéciales pour éviter les attaques XSS
            $stock = htmlspecialchars($_POST["stock"]); // Récupère et convertit la quantité de stock du produit en entité HTML spéciales pour éviter les attaques XSS
            
            try {
                ajouter($nom, $image, $description, $prix, $stock); // Appelle la fonction 'ajouter' pour ajouter un nouveau produit
                $message[] = "Produit ajouté avec succès !"; // Ajoute un message de succès au tableau des messages
            } catch(Exception $e) {
                $message[] = "Erreur lors de l'ajout du produit : " . $e->getMessage(); // Ajoute un message d'erreur avec le message d'exception au tableau des messages
            }
        }
    }
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
                <span class="icon"><a href="supp.php"><div><i class='bx bx-trash' ></i></div></a></span>
                <a href="supp.php"><h4>Supprimer un produit</h4></a>
            </div>

            <div class="service">
                <span class="icon"><a href="afficher.php"><div><i class='bx bx-check-square' ></i></div></a></span>
                <a href="afficher.php"><h4>Modifier un produit</h4></a>
            </div>

        </div>
    </header>

    <div class = "form-container">
        <form method="POST" enctype="multipart/form-data">
            <label for="nom">Nom du produit :</label>
            <input class = "box2" type="text" name="nom" required>

            <label for="description">Description :</label>
            <textarea class = "box3" name="description" required></textarea>

            <label for="prix">Prix :</label>
            <input class = "box2" type="text" name="prix" required>

            <label for="image">Image :</label>
            <input class = "box2" type="text" name="image" required>
            <label for="stock">Stock :</label>
            <input class = "box2" type="number" name="stock" required>

            <input class = "btn2" type="submit" name="Ajouter" value="Ajouter">
        </form>
    </div>
</body>
</html>

<?php
    // Fermeture de la connexion à la base de données
    mysqli_close($conn);
?>
