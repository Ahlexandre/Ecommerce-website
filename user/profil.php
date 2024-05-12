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
}

// Sélection des informations de l'utilisateur connecté
$select_user = mysqli_query($conn, "SELECT * FROM `user_form` WHERE ID = '$user_id'") or die("Erreur de requête");
if (mysqli_num_rows($select_user) > 0){
    // Récupération des données de l'utilisateur
    $fetch_user = mysqli_fetch_assoc($select_user);
}

// Traitement de la demande de suppression de compte
if(isset($_GET['delete_account'])){
    // Suppression du compte de l'utilisateur
    mysqli_query($conn, "DELETE FROM `user_form` WHERE id = '$user_id'") or die('Erreur de requête');
    // Redirection vers la page d'accueil
    header('location:../index.php');
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
    <title>AS Shop - Profil</title>
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
                    <div>
                        <a href="card.php"><i class='bx bx-shopping-bag'></i></a>
                    </div>
                    <div>
                        <a class="delete-btn" href="../index.php?logout=<?php echo $user_id; ?>" onclick="return confirm('Es-tu sûr de te déconnecter ?');">Déconnexion</a>
                    </div>
                </div>
            </div>
        </nav>
</header>


<div class="container-profil">
    <div class="profil">
        <?php
            // Sélection des informations de l'utilisateur connecté
            $select = mysqli_query($conn, "SELECT * FROM `user_form` WHERE id = '$user_id'") or die('Erreur de requête');
            if(mysqli_num_rows($select) > 0){
                $fetch = mysqli_fetch_assoc($select);
            }
        ?>
        <h3><?php echo $fetch['name']; ?></h3>
        <a href="update_profil.php" class="btn"> Modifier votre compte</a>
        <a class="delete-btn" href="profil.php?delete_account" onclick="return confirm('Es-tu sûr de supprimer ton compte ?')">Supprimer votre compte</a>
    </div>
</div>

</body>
</html>

<?php
    // Fermeture de la connexion à la base de données
    mysqli_close($conn);
?>