<?php
#DU Alexandre et JELASSI Bader

include 'connexion.php'; // Inclut le fichier de connexion à la base de données
session_start(); // Démarre la session PHP pour maintenir l'état de l'utilisateur

$user_id = $_SESSION['user_id']; // Récupère l'ID de l'utilisateur à partir de la session

if (isset($_GET['logout'])){ // Vérifie si le paramètre GET 'logout' est défini
    unset($user_id); // Supprime l'ID de l'utilisateur
    session_destroy(); // Détruit la session
    header('location:index.php'); // Redirige vers la page d'accueil
};

$select_user = mysqli_query($conn, "SELECT * FROM `user_form` WHERE ID = '$user_id'") or die("Erreur de requête"); // Exécute la requête SQL pour sélectionner l'utilisateur en fonction de son ID
if (mysqli_num_rows($select_user) > 0){ // Vérifie s'il y a des lignes dans le résultat de la requête
    $fetch_user = mysqli_fetch_assoc($select_user); // Récupère les données de l'utilisateur
};

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href = "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"> <!-- Inclut la bibliothèque Font Awesome -->
    <link rel="stylesheet" type = "text/css" href = "../styles/stylecontact.css"> <!-- Inclut la feuille de style CSS -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'> <!-- Inclut la bibliothèque Boxicons -->
    <link rel="icon" href="../img/logo.png" type="image/x-icon"> <!-- Définit l'icône du site -->
    <title>AS Shop - Contact</title> <!-- Définit le titre de la page -->
</head>
<body>

<header class="header">
        <nav class="nav container">

            <div class ="navigation d-flex">
                <div class="icon1">
                    <i class='bx bx-menu'></i> <!-- Affiche l'icône du menu -->
                </div>
                <div class="logo">
                    <a href ="#"><span>AS</span> Shop</a> <!-- Affiche le logo et le nom du site -->
                </div>
                <div class="menu">
                    <div class="top">
                        <span class = "fermer">Fermer <i class='bx bx-x'></i></span> <!-- Affiche l'option de fermeture du menu -->
                    </div>
                    <ul class ="nav-list d-flex">
                        <li class="nav-item">

                        </li>
                        <li class="nav-item">
                            <a href="index2.php" class ="nav-link">Retour</a> <!-- Affiche le lien pour revenir à la page précédente -->
                        </li>
                    </ul>
                </div>
                <div class="icons d-flex">
                    <div class = "username"><a href="profil.php" target='_BLANK'><?php echo $fetch_user['name']; ?></a></div> <!-- Affiche le nom de l'utilisateur connecté -->
                    <div>
                            <a href="card.php"><i class='bx bx-shopping-bag'></i></a> <!-- Affiche l'icône du panier -->
                            <!-- <span class = "align-center">0</span> -->
                    </div>
                    <div>
                    <a class = "delete-btn" href ="../index.php?logout=<?php echo $user_id; ?>" onclick="return confirm('Es-tu sûr de te déconnecter ?');">Déconnexion</a> <!-- Affiche le lien de déconnexion -->
                    </div>
                </div>
            </div>

        </nav>
</header>

<section id = "Contact" class = "section">
        <div class ="container">
            <div class="contact-box">
                <div class="contact-left">
                    <h3>Besoin de plus d'informations ? Veuillez me contactez !</h3> <!-- Affiche le titre de la section -->
                    <!-- Formulaire de contact -->
                    <form action="https://formspree.io/f/mwkdgrkv" method="POST">
                        <div class="input-row">
                            <div class="input_group">
                                <label>Name*</label>
                                <input type="text" name="Nom" placeholder="Nom" value = "<?php echo $fetch_user['name']; ?>" readonly > <!-- Affiche le nom de l'utilisateur connecté -->
                            </div>
                            <div class="input_group">
                                <label>E-mail*</label>
                                <input type="email" name="E-mail" placeholder="nom@email.com" value = "<?php echo $fetch_user['email']; ?>" readonly > <!-- Affiche l'email de l'utilisateur connecté -->
                            </div>
                        </div>
                        <div class="input-row">
                            <div class="input_group">
                                <label>Objet*</label>
                                <input type="text" name="Objet" placeholder="Objet" required> <!-- Champ pour l'objet du message -->
                            </div>
                        </div>

                        <label>Message*</label>
                        <textarea name="Message" row="15" placeholder="Votre Message"></textarea> <!-- Champ pour le message -->

                        <button type="submit">ENVOYER</button> <!-- Bouton pour envoyer le formulaire -->
                    </form>

                </div>


                <div class="contact-right">
                    <h3>Nos coordonnées</h3> <!-- Affiche le titre des coordonnées -->
                    <!-- Tableau des coordonnées -->
                    <table>
                        <tr>
                            <td>Email: </td>
                            <td>as-shop@gmail.com</td> <!-- Adresse e-mail -->
                        </tr>
                        <tr>
                            <td>Tel:</td>
                            <td>01.23.45.67.78</td> <!-- Numéro de téléphone -->
                        </tr>
                    </table>
                </div>
            </div>
            <i>Utilise l'API Formspree.io</i> <!-- Informations sur l'utilisation de l'API Formspree.io -->
        </div>
</section>

</body>
</html>

<?php
    // Fermeture de la connexion à la base de données
    mysqli_close($conn);
?>