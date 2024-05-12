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
<html lang = "fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href = "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" type = "text/css" href = "../styles/style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="../img/logo.png" type="image/x-icon">
    <title>AS Shop - FAQ</title>
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
                        <span class = "fermer">Fermer <i class='bx bx-x'></i></span>
                    </div>
                    <ul class ="nav-list d-flex">
                        <li class="nav-item">

                        </li>
                        <li class="nav-item">
                            <a href="index2.php" class ="nav-link">Retour</a>
                        </li>
                    </ul>
                </div>
                <div class="icons d-flex">
                    <div class = "username"><a href="profil.php" target='_BLANK'><?php echo $fetch_user['name']; ?></a></div>
                    <div>
                            <a href="card.php"><i class='bx bx-shopping-bag'></i></a>
                            <!-- <span class = "align-center">0</span> -->
                    </div>
                    <div>
                    <a class = "delete-btn" href ="../index.php?logout=<?php echo $user_id; ?>" onclick="return confirm('Es-tu sûr de te déconnecter ?');">Déconnexion</a>
                    </div>
                </div>
            </div>

        </nav>


    <header class = "section service">
        <div class="service-center container">

            <div class="service">
                <span class="icon"><div><i class='bx bx-purchase-tag'></i></div></span>
                <h4>Livraison gratuite</h4>
                <span class="text">Commande supérieur à partir de 1000€</span>
            </div>

            <div class="service">
                <span class="icon"><div><i class='bx bx-lock'></i></div></span>
                <h4>Paiement sécurisé</h4>
                <span class="text">Moyens de paiement populaires</span>
            </div>

            <div class="service">
                <span class="icon"><div><i class='bx bxs-left-arrow-circle'></i></div></span>
                <h4>30 Jours pour retour</h4>
                <span class="text">Produit 100% garantie</span>
            </div>

            <div class="service">
                <span class="icon"><div><i class='bx bx-headphone'></i></div></span>
                <h4>24/7 Support</h4>
                <span class="text">Assistance Client</span>
            </div>
            

        </div>
    </header>

    <div class = "propos">
        <h1>A propos de nous</h1>
        <p>

Bonjour à tous ! Nous sommes <b class = "Prenom">Alexandre et Bader</b>, deux passionnés de l'informatique et des nouvelles technologies. Aujourd'hui, nous sommes ravis de vous présenter notre projet qui s'inscrit dans <b class = "Prenom">le monde en constante évolution des ordinateurs et de la technologie</b>.<br/><br/>

Nous avons choisi d'explorer ce domaine fascinant en raison de sa prédominance croissante dans <b class = "Prenom">notre quotidien</b>. En effet, l'impact des ordinateurs sur nos vies est de plus en plus marqué, que ce soit dans nos foyers, nos lieux de travail ou même dans nos loisirs. Avec l'avènement de <b class = "Prenom">l'Internet des objets</b>, de <b class = "Prenom">l'intelligence artificielle et de la réalité virtuelle</b>, l'importance des ordinateurs et des dispositifs informatiques <b class = "Prenom">ne cesse de croître</b>.<br/><br/>

Dans les années à venir, nous anticipons une valorisation croissante des plateformes de commerce électronique spécialisées dans la vente d'ordinateurs. La demande pour ces produits ne cesse d'augmenter, que ce soit pour les particuliers à la recherche d'équipements performants ou pour les entreprises souhaitant renouveler leur parc informatique.<br/><br/>

De plus, étant tous deux engagés dans des études et des carrières en informatique, nous nous sentons particulièrement concernés par cette thématique. <b class = "Prenom">Notre passion</b> pour ce domaine nous pousse à explorer les dernières avancées technologiques et à les rendre <b class = "Prenom">accessibles à un large public.</b><br/><br/>

Ainsi, à travers ce projet, nous aspirons à <b class = "Prenom">créer une plateforme de commerce électronique</b> qui offre non seulement <b class = "Prenom">une large gamme d'ordinateurs et d'accessoires</b>, mais aussi une <b class = "Prenom">expérience utilisateur exceptionnelle</b>. Notre objectif est de fournir des produits de <b class = "Prenom">qualité</b>, <b class = "Prenom">des conseils personnalisés</b> et <b class = "Prenom">un service clientèle attentif</b> pour répondre aux besoins divers et variés de notre clientèle.<br/><br/>

Nous sommes <b class = "Prenom">impatients</b> de partager cette aventure avec vous et de vous accompagner dans l'univers passionnant des ordinateurs et de la technologie. Restez <b class = "Prenom">connectés</b> pour découvrir toutes les nouveautés que nous avons en réserve !


    </p>
    </div>
</body>
</html>

<?php
    // Fermeture de la connexion à la base de données
    mysqli_close($conn);
?>