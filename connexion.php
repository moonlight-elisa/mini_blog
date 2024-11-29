<!DOCTYPE html>
<html>
<head>
    <?php include "head.php"; ?>
</head>
<body>


    <?php 
        // Connexion BDD
        include "connexion_bdd.php";

        // Affichage du menu
        include "navigation_non_logue.php";

        // Si l'utilisateur n'est pas connecté, je le rediriges vers l'accueil
        if (isset($_SESSION["id"])) {
            header("Location: index.php");
        }
    ?>
    <?php 

    $error = null;
    
    // Je vérifie que l'email et le mot de passe existent dans ma table
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["email"], $_POST["mot_de_passe"])) {
        $email = $_POST["email"];
        $mot_de_passe = $_POST["mot_de_passe"]; 

        $stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE email=:email");
        $stmt->bindParam(":email", $email);

        $stmt->execute();

        // Je vérifie que l'email existe dans ma table
        if ($result = $stmt->fetch()) { 
            // Je vérifie que le mot de passe correspond
            if (password_verify($mot_de_passe, $result["mot_de_passe"])) {
                // Je démarre la session
                session_start();
                $_SESSION["nom"] = $result["nom"];
                $_SESSION["email"] = $result["email"];
                $_SESSION["id"] = $result["id"]; 
                header("Location: index.php");
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

    <section>
        <div class="container container-small">
            <h1>Connexion 👋</h1>
            <form action="connexion.php" method="POST" enctype="multipart/form-data">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="mot_de_passe" placeholder="Mot de passe" required>            
                <button class="button" type="submit">Se connecter</button>
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

