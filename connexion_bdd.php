<?php

// Informations de connexion à la base de données
$host = 'localhost'; // Nom de l'hôte (généralement 'localhost' si la base est en local)
$dbname = 'tp_miniblog'; // Remplacez par le nom de votre base de données
$username = 'root'; // Remplacez par votre nom d'utilisateur MySQL
$password = ''; // Remplacez par votre mot de passe MySQL

// Création de la connexion avec PDO
$conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

?>