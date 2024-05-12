<!-- #Projet réalisé par DU Alexandre -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href = "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" type = "text/css" href = "styles/stylecontact.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="img/logo.png" type="image/x-icon">
    <title>AS Shop</title>
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
                            <a href="index.php" class ="nav-link">Accueil</a>
                        </li>
                        <li class="nav-item">
                            <a href="index.php#products" class ="nav-link">Boutique</a>
                        </li>
                        <li class="nav-item">
                            <a href="faq.php" class ="nav-link" target='_BLANK'>FAQ</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class ="nav-link">Contact</a>
                        </li>
                    </ul>
                </div>
            </div>

        </nav>
</header>

<section id = "Contact" class = "section">
        <div class ="container">
            <div class="contact-box">
                <div class="contact-left">
                    <h3>Besoin de plus d'informations ? Veuillez me contactez !</h3>

                    <form action="https://formspree.io/f/mwkdgrkv" method="POST">
                        <div class="input-row">
                            <div class="input_group">
                                <label>Name*</label>
                                <input type="text" name="Nom" placeholder="Nom"required>
                            </div>
                            <div class="input_group">
                                <label>E-mail*</label>
                                <input type="email" name="E-mail" placeholder="nom@email.com"required>
                            </div>
                        </div>
                        <div class="input-row">
                            <div class="input_group">
                                <label>Objet*</label>
                                <input type="text" name="Objet" placeholder="Objet" required>
                            </div>
                        </div>

                        <label>Message*</label>
                        <textarea name="Message" row="15" placeholder="Votre Message"></textarea>

                        <button type="submit">ENVOYER</button>
                    </form>

                </div>


                <div class="contact-right">
                    <h3>Nos coordonnées</h3>
                    <table>
                        <tr>
                            <td>Email: </td>
                            <td>as-shop@gmail.com</td>
                        </tr>
                        <tr>
                            <td>Tel:</td>
                            <td>01.23.45.67.78</td>
                        </tr>
                    </table>
                </div>
            </div>
            <i>Utilise l'API Formspree.io</i>
        </div>
</section>

</body>
</html>