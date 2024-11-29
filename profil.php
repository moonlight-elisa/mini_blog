<!DOCTYPE html>
<html>
<head>
    <?php include "head.php"; ?>
</head>
<body>

    <?php 
        // Connexion BDD
        include "connexion_bdd.php";
        session_start();

        // Affichage du menu
        include "navigation_logue.php";
        
        // Si l'utilisateur n'est pas connecté, je le rediriges vers la page de connexion
        if (!isset($_SESSION["id"])) {
            header("Location: connexion.php");
        }

        // Je récupère la photo de profil de l'utilisateur
        $photo = null;
        $stmt = $conn->prepare("SELECT photo_profil FROM utilisateurs WHERE id=:id");
        $id = $_SESSION["id"];
        $stmt->bindParam(":id", $id);
        $stmt->execute();
    
        if ($result = $stmt->fetch()) {
            $photo = $result["photo_profil"];
        }
    ?>

    <section>
        <div class="container container-small">
            <?php if (isset($photo)) { echo "<div class='pp'><img src='uploads/" . $photo . "' alt='Photo de profil'></div>"; } ?>
            <h1>Bonjour <?php if (isset($_SESSION["nom"])) { echo $_SESSION["nom"]; } ?>👋</h1>
            <p><span class="p-bold">Pseudo :</span> <?php if (isset($_SESSION["nom"])) { echo $_SESSION["nom"]; } ?></p>
            <p><span class="p-bold">Email :</span> <?php if (isset($_SESSION["email"])) { echo $_SESSION["email"]; } ?></p>
            <div class="button__container">
                <a class="button" href="edition_photo_profil.php">Modifier ma photo de profil</a>
                <a class="button button__red" href="deconnexion.php">Se déconnecter</a>
            </div>
        </div>
    </section>
</body>
</html>

