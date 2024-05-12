<?php
#Projet réalisé par DU Alexandre

// Inclusion du fichier de connexion à la base de données et démarrage de la session PHP
include 'connexion.php';
session_start();

// Vérification si le formulaire de connexion a été soumis
if(isset($_POST['submit'])){
    // Récupération et échappement des valeurs saisies dans le formulaire
    $email = mysqli_real_escape_string($conn, $_POST['email']); // Sécurisation de l'e-mail saisi dans le formulaire
    $password = mysqli_real_escape_string($conn, $_POST['password']); // Sécurisation du mot de passe saisi dans le formulaire

    // Hachage du mot de passe pour la comparaison ultérieure
    $password_hash = password_hash($password, PASSWORD_DEFAULT); // Hachage du mot de passe

    // Vérification dans la table admin
    $stmt_admin = $conn->prepare("SELECT * FROM admin WHERE email = ?"); // Préparation de la requête SQL pour récupérer les informations de l'administrateur
    $stmt_admin->bind_param('s', $email); // Liaison du paramètre de la requête
    $stmt_admin->execute(); // Exécution de la requête
    $result_admin = $stmt_admin->get_result(); // Récupération des résultats

    if($result_admin->num_rows > 0){ // Vérification s'il y a des résultats
        // L'utilisateur est un administrateur
        $row = $result_admin->fetch_assoc(); // Récupération des données de l'administrateur
        if(password_verify($password, $row['mdp'])){ // Vérification du mot de passe haché
            $_SESSION['admin_email'] = $email; // Attribution de la session pour l'administrateur
            // Redirection vers la page d'administration
            header('location: admin/admin.php'); // Redirection vers la page d'administration
            exit; // Arrêt du script
        } else {
            $message[] = "Mot de passe ou email incorrect !"; // Message d'erreur en cas de mot de passe incorrect
        }
    } else {
        // Vérification dans la table user_form
        $stmt_user = $conn->prepare("SELECT * FROM user_form WHERE email = ?"); // Préparation de la requête SQL pour récupérer les informations de l'utilisateur
        $stmt_user->bind_param('s', $email); // Liaison du paramètre de la requête
        $stmt_user->execute(); // Exécution de la requête
        $result_user = $stmt_user->get_result(); // Récupération des résultats

        if($result_user->num_rows > 0){ // Vérification s'il y a des résultats
            // L'utilisateur est un utilisateur normal
            $row = $result_user->fetch_assoc(); // Récupération des données de l'utilisateur
            if(password_verify($password, $row['password'])){ // Vérification du mot de passe haché
                $_SESSION['user_id'] = $row['id']; // Attribution de la session pour l'utilisateur
                // Redirection vers la page utilisateur
                header('location: user/index2.php'); // Redirection vers la page utilisateur
                exit; // Arrêt du script
            } else {
                $message[] = "Mot de passe ou email incorrect !"; // Message d'erreur en cas de mot de passe incorrect
            }
        } else {
            $message[] = "Mot de passe ou email incorrect !"; // Message d'erreur en cas d'absence d'utilisateur avec cet e-mail
        }
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Connexion</title>
    <link rel="stylesheet" type="text/css" href="styles/styleregister.css">
    <link rel="icon" href="img/logo.png" type="image/x-icon">
</head>
<body>

<?php
// Affichage des messages d'erreur
if(isset($message)){
   foreach($message as $message){
      echo '<div class="message" onclick="this.remove();">'.$message.'</div>'; // Affichage des messages d'erreur dans une boîte de message
   }
}
?>

<div class="logo">
    <a href="index.php"><img src="img/logo.png"></a>
</div>
<div class="form-container">
    <form action="" method="POST">
        <h3>Connexion</h3>
        <input class="box" type="email" name="email" placeholder="Entrez votre e-mail" required>
        <input class="box" type="password" name="password" placeholder="Entrez votre mot de passe" required>
        <input class="btn" type="submit" name="submit" value="Se connecter">
        <p>Vous n'avez pas de compte ? <a href="register.php">Inscrivez-vous</a></p>
    </form>
</div>

<footer>
    <div class="footer">
        <h3>
            &copy; AS SHOP<br> 2023-2024
        </h3>
    </div>
    
    <div class="footer-links">
        <a href="https://www.linkedin.com/in/" target="_blank"><img class="icons" src="img/icons/linkedin-icon.png" alt="LinkedIn"></a>
        <a href="https://www.instagram.com/" target="_blank"><img class="icons" src="img/icons/Instagram-Icon.png" alt="Instagram"></a>
        <a href="https://www.twitter.com/" target="_blank"><img class="icons" src="img/icons/twitter-icon.png" alt="Twitter"></a>
    </div>
</footer>

</body>
</html>


<?php
    // Fermeture de la connexion à la base de données
    mysqli_close($conn);
?>