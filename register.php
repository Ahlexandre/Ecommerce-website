<?php
#Projet réalisé par DU Alexandre

//Ce fichier permet de s'enregistrer

include 'connexion.php'; // Inclut le fichier de connexion à la base de données

if(isset($_POST['submit'])){ // Vérifie si le formulaire a été soumis
    // Sécurisation des données en échappant les caractères spéciaux
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $cpassword = mysqli_real_escape_string($conn, $_POST['cpassword']);
    $numrue = mysqli_real_escape_string($conn, $_POST['numrue']);
    $nomrue = mysqli_real_escape_string($conn, $_POST['nomrue']);
    $ville = mysqli_real_escape_string($conn, $_POST['ville']);
    $codepostal = mysqli_real_escape_string($conn, $_POST['codepostal']);

    // Vérification du mot de passe de confirmation
    if ($password != $cpassword){
        $message[] = "Le mot de passe de confirmation ne correspond pas !";
    } else {
        // Hachage du mot de passe
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Vérification si l'utilisateur existe déjà
        $select = mysqli_query($conn, "SELECT * FROM `user_form` WHERE email = '$email'") or die('Erreur de requête');
        if(mysqli_num_rows($select) > 0){
            $message[] = "L'utilisateur existe déjà !"; // Affichage d'un message
        } else {
            // Insertion de l'utilisateur dans la base de données
            mysqli_query($conn, "INSERT INTO `user_form` (name, email, password, numrue, nomrue, ville, codepostal) VALUES('$name','$email','$hashed_password','$numrue','$nomrue','$ville','$codepostal') ") or die('Erreur de requête');
            $message[] = "Inscription réussie !"; // Affichage d'un message
            // Redirection vers la page de connexion avec un message de confirmation
            header('location:login.php?success=1');
        }
    }
}

?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Inscription</title>
    <link rel="stylesheet" type = "text/css" href = "styles/styleregister.css">
    <link rel="icon" href="img/logo.png" type="image/x-icon">
</head>
<body>

<?php

// Affichage des messages s'il y en a
if(isset($message)){
   foreach($message as $message){
      echo '<div class="message" onclick="this.remove();">'.$message.'</div>';
   }
}

?>

<div class="logo">
    <a href="index.php"><img src = "img/logo.png"></a>
</div>
<div class="form-container">
    <form action="" method = "POST">
        <h3>Inscription</h3>
        <input class = "box" type="text" name = "name" placeholder ="Entrez votre nom" required>
        <input class = "box" type="email" name = "email" placeholder ="Entrez votre e-mail" required>
        <input class = "box" type="password" name = "password" placeholder ="Entrez votre mot de passe" required>
        <input class = "box" type="password" name = "cpassword" placeholder ="Confirmez votre mot de passe" required>
        <input class = "box" type="number" name = "numrue" placeholder ="Numéro de rue" required min = 1>
        <input class = "box" type="text" name = "nomrue" placeholder ="Nom de rue" required>
        <input class = "box" type="text" name = "ville" placeholder ="Ville" required>
        <input class = "box" type="text" name = "codepostal" placeholder ="CodePostal" required>
        <input class = "btn" type = "submit" name = "submit" value ="S'inscrire">  
        <p>Vous avez déjà un compte ? <a href = "login.php">Connectez vous</a><p>
    </form>
</div>

<footer>
        <div class="footer">
            <h3>
                &copy; AS SHOP<br> 2023-2024
            </h3>
        </div>
        
        <div class = "footer-links">
            <a href="https://www.linkedin.com/in/" target="_blank"><img class = "icons" src="img/icons/linkedin-icon.png" alt="LinkedIn"></a>
            <a href="https://www.instagram.com/" target="_blank"><img class = "icons" src="img/icons/Instagram-Icon.png" alt="Instagram"></a>
            <a href="https://www.twitter.com/" target="_blank"><img class = "icons" src="img/icons/twitter-icon.png" alt="Twitter"></a>
        </div>
</footer>

</body>
</html>

<?php
    // Fermeture de la connexion à la base de données
    mysqli_close($conn);
?>