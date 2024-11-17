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

        $error = null;
    
        if (isset($_POST["submit"])) {
            $content_dir = "uploads/";
            $tmp_file = $_FILES["photo_profil"]["tmp_name"]; 

            if (is_uploaded_file($tmp_file) ) {
                $file_name = $_SESSION["id"] . ".png";
                if (move_uploaded_file($tmp_file, $content_dir . $file_name)) {
                    $stmt = $conn->prepare("UPDATE utilisateurs SET photo_profil=:photo_profil WHERE id=:id");

                    $stmt->bindParam(":photo_profil", $file_name);
                    $stmt->bindParam(":id", $_SESSION["id"]);
                    $stmt->execute();

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

    <h1>Modifier ma photo de profil</h1>
    
    <form action="edition_photo_profil.php" method="POST" enctype="multipart/form-data">   
        <input type="file" name="photo_profil" accept=".png">
        <button type="submit" name="submit">Valider</button>
    </form>

    <?php
        if (isset($error)) {
            echo "<p>$error</p>";
        }
    ?>

</body>
</html>

