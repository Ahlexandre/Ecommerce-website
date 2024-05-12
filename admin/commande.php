<?php

#Projet réalisé par DU Alexandre

function ajouter($nom, $image, $desc, $prix, $stock) {
    include("connexion.php"); // Inclut le fichier de connexion à la base de données
    if($conn) { // Vérifie si la connexion à la base de données est établie avec succès
        $stmt = $conn->prepare("INSERT INTO products (name, price, description, image, quantity) VALUES (?, ?, ?, ?, ?)"); // Prépare une instruction SQL pour l'ajout d'un produit
        $stmt->bind_param('sssss', $nom, $prix, $desc, $image, $stock); // Lie les paramètres à l'instruction SQL préparée
        $stmt->execute(); // Exécute la requête préparée
        $stmt->close(); // Ferme l'instruction préparée
        $conn->close(); // Ferme la connexion à la base de données
    }
}

function afficher(){
    include("connexion.php"); // Inclut le fichier de connexion à la base de données
    $donnees = array(); // Initialise un tableau vide pour stocker les données des produits
    if($conn){ // Vérifie si la connexion à la base de données est établie avec succès
        $result = $conn->query("SELECT * FROM products ORDER BY id DESC"); // Exécute une requête pour sélectionner tous les produits
        if ($result->num_rows > 0) { // Vérifie s'il y a des lignes retournées par la requête
            while($row = $result->fetch_assoc()) { // Boucle à travers les résultats de la requête
                $donnees[] = $row; // Ajoute chaque ligne de résultat au tableau de données
            }
        }
        $conn->close(); // Ferme la connexion à la base de données
    }
    return $donnees; // Retourne le tableau de données des produits
}

function supprimer($id){
    include("connexion.php"); // Inclut le fichier de connexion à la base de données
    if($conn){ // Vérifie si la connexion à la base de données est établie avec succès
        $stmt = $conn->prepare("DELETE FROM products WHERE id=?"); // Prépare une instruction SQL pour supprimer un produit
        $stmt->bind_param('i', $id); // Lie le paramètre à l'instruction SQL préparée
        $stmt->execute(); // Exécute la requête préparée
        $stmt->close(); // Ferme l'instruction préparée
        $conn->close(); // Ferme la connexion à la base de données
    }
}

function Admin($email,$mdp){
    include("connexion.php"); // Inclut le fichier de connexion à la base de données
    if($conn){ // Vérifie si la connexion à la base de données est établie avec succès
        $stmt = $conn->prepare("SELECT * FROM admin WHERE email=? AND mdp=?"); // Prépare une instruction SQL pour sélectionner un administrateur par son email et mot de passe
        $stmt->bind_param('ss', $email, $mdp); // Lie les paramètres à l'instruction SQL préparée
        $stmt->execute(); // Exécute la requête préparée
        $result = $stmt->get_result(); // Récupère le résultat de la requête
        if($result->num_rows == 1){ // Vérifie s'il y a exactement une ligne retournée par la requête
            $donnee = $result->fetch_assoc(); // Récupère les données de l'administrateur sous forme de tableau associatif
            $stmt->close(); // Ferme l'instruction préparée
            $conn->close(); // Ferme la connexion à la base de données
            return $donnee; // Retourne les données de l'administrateur
        } else {
            $stmt->close(); // Ferme l'instruction préparée
            $conn->close(); // Ferme la connexion à la base de données
            return false; // Retourne false s'il n'y a pas de correspondance d'administrateur
        }
    }
}

function produit($id){
    include("connexion.php"); // Inclut le fichier de connexion à la base de données
    if($conn){ // Vérifie si la connexion à la base de données est établie avec succès
        $donnees = array(); // Initialise un tableau vide pour stocker les données du produit
        $stmt = $conn->prepare("SELECT * FROM `products` WHERE id=?"); // Prépare une instruction SQL pour sélectionner un produit par son ID
        $stmt->bind_param('i', $id); // Lie le paramètre à l'instruction SQL préparée
        $stmt->execute(); // Exécute la requête préparée
        $result = $stmt->get_result(); // Récupère le résultat de la requête
        if($result->num_rows == 1){ // Vérifie s'il y a exactement une ligne retournée par la requête
            while($row = $result->fetch_assoc()) { // Boucle à travers les résultats de la requête
                $donnees[] = $row; // Ajoute chaque ligne de résultat au tableau de données
            }
        }
        $stmt->close(); // Ferme l'instruction préparée
        $conn->close(); // Ferme la connexion à la base de données
        return $donnees; // Retourne le tableau de données du produit
    }
}

function modifier($nom, $image, $desc, $prix,$stock,$id) {
    include("connexion.php"); // Inclut le fichier de connexion à la base de données
    if($conn) { // Vérifie si la connexion à la base de données est établie avec succès
        $stmt = $conn->prepare("UPDATE products SET name=?, price=?, description=?, image=?, quantity=? WHERE id=?"); // Prépare une instruction SQL pour modifier un produit
        $stmt->bind_param('sssssi', $nom, $prix, $desc,$image,$stock,$id); // Lie les paramètres à l'instruction SQL préparée
        $stmt->execute(); // Exécute la requête préparée
        $stmt->close(); // Ferme l'instruction préparée
        $conn->close(); // Ferme la connexion à la base de données
    }
}

?>


