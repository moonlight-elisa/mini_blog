<!DOCTYPE html>
<html>
<head>
    <title>Mini Chat</title>
    <style>
        /* Un peu de CSS pour l'apparence */
    </style>
</head>
<body>

    <?php 
    
        include "connexion_bdd.php";
        session_start();

        $photo = null;

        $stmt = $conn->prepare("SELECT photo_profil FROM utilisateurs WHERE id=:id");
        $id = $_SESSION["id"];
        $stmt->bindParam(":id", $id);
        $stmt->execute();
    
        if ($result = $stmt->fetch()) {
            $photo = $result["photo_profil"];
        }
    ?>

    <?php if (isset($photo)) { echo "<img src='uploads/" . $photo . "' alt='Photo de profil'>"; } ?>
    <h1>Bonjour <?php if (isset($_SESSION["nom"])) { echo $_SESSION["nom"]; } ?></h1>
    <p>Pseudo : <?php if (isset($_SESSION["nom"])) { echo $_SESSION["nom"]; } ?></p>
    <p>Email : <?php if (isset($_SESSION["email"])) { echo $_SESSION["email"]; } ?></p>
    <a href="edition_photo_profil.php">Modifier ma photo de profil</a>
    <a href="deconnexion.php">Se déconnecter</a>

</body>
</html>

