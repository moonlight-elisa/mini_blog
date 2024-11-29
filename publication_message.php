<?php
    include "connexion_bdd.php";
    session_start();

    // J'insère un nouveau message dans ma table pour l'utilisateur
    $stmt = $conn->prepare("INSERT INTO messages (utilisateurs_id, contenu_text) VALUES (?, ?)");
    $id = $_SESSION['id'];
    $message = $_POST['message'];

    $stmt->bindParam(':utilisateurs_id', $id);
    $stmt->bindParam(':contenu_text', $message);
    $stmt->execute([$id, $message]);

    header("Location: index.php");
?>