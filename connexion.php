<!DOCTYPE html>
<html>
<head>
    <title>Mini Chat</title>
    <style>
        /* Un peu de CSS pour l'apparence */
    </style>
</head>
<body>

    <?php include "connexion_bdd.php" ?>

    <?php 

    $error = null;
    
    // Connexion
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["email"], $_POST["mot_de_passe"])) {
        $email = $_POST["email"];
        $mot_de_passe = $_POST["mot_de_passe"]; 

        // Vérifier si l'utilisateur existe déjà dans la base de données
        $stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE email=:email");
        $stmt->bindParam(":email", $email);

        $stmt->execute();

        if ($result = $stmt->fetch()) { 
            if (password_verify($mot_de_passe, $result["mot_de_passe"])) {
                // Démarrer la session
                session_start();
                $_SESSION["nom"] = $result["nom"];
                $_SESSION["email"] = $result["email"];
                $_SESSION["id"] = $result["id"]; 
                header("Location: profil.php");
            } 
            else {
                $error = "Votre mot de passe est incorrect.";
            } 
        } 
        else {
            $error = "Votre adresse email est incorrecte.";
        }
    }

    ?>

    <h1>Connexion</h1>
    <form action="connexion.php" method="POST" enctype="multipart/form-data">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="mot_de_passe" placeholder="Mot de passe" required>            
        <button type="submit">Se connecter</button>
    </form>

    <?php
        if (isset($error)) {
            echo "<p>$error</p>";
        }
    ?>

</body>
</html>

