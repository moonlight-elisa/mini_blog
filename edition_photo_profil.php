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

        $error = null;
    
        if (isset($_POST["submit"])) {
            // Je définis le chemin vers le dossier où sera uploadée l'image
            $content_dir = "uploads/";
            // Je récupère le nom du fichier
            $tmp_file = $_FILES["photo_profil"]["tmp_name"]; 

            if (is_uploaded_file($tmp_file) ) {
                // Je définis le nouveau du fichier en reprenant l'id de l'utilisateur
                $file_name = $_SESSION["id"] . ".png";
                if (move_uploaded_file($tmp_file, $content_dir . $file_name)) {
                    // Je mets à jour la nouvelle photo de profil
                    $stmt = $conn->prepare("UPDATE utilisateurs SET photo_profil=:photo_profil WHERE id=:id");

                    $stmt->bindParam(":photo_profil", $file_name);
                    $stmt->bindParam(":id", $_SESSION["id"]);
                    $stmt->execute();

                    // Redirection vers la page profil
                    header("Location: profil.php");
                }
                else {
                    $error = "Une erreur est survenue lors de l'importation de l'image. Veuillez réessayer.";
                }
            } 
            else {
                $error = "Veuillez choisir un fichier.";
            }
        }
        else {
            $error = "Veuillez choisir un fichier.";
        }
    ?>

    <section>
        <div class="container">
            <h1>Modifier ma photo de profil 🖊️</h1>
        
            <form action="edition_photo_profil.php" method="POST" enctype="multipart/form-data">   
                <input type="file" name="photo_profil" accept=".png">
                <button class="button button__green" type="submit" name="submit">Valider</button>
            </form>

            <?php
                if (isset($error)) {
                    echo "<p class='p-error'>$error</p>";
                }
            ?>
        </div>
    </section>

</body>
</html>

