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
        include "navigation_non_logue.php";

        // Si l'utilisateur n'est pas connecté, je le rediriges vers l'accueil
        if (isset($_SESSION["id"])) {
            header("Location: index.php");
        } 
    ?>

    <?php 

    $error = null;
    
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nom"], $_POST["email"], $_POST["mot_de_passe"])) {
        $nom = $_POST["nom"];
        $email = $_POST["email"];
        $mot_de_passe = password_hash($_POST["mot_de_passe"], PASSWORD_BCRYPT);

        // Je vérifie si l'email existe déjà dans ma table
        $stmt = $conn->prepare("SELECT COUNT(*) FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        $emailCount = $stmt->fetchColumn();

        // L'utilisateur existe déjà
        if ($emailCount > 0) {
            $error = "Cet email est déjà utilisé. Veuillez en choisir un autre.";
        } 
        // L'utilisateur n'existe pas encore
        else {
            // Insérer l'utilisateur dans la base de données
            $stmt = $conn->prepare("INSERT INTO utilisateurs (nom, email, mot_de_passe, photo_profil) VALUES (?, ?, ?, ?)");
            
            // Liaison des paramètres avec `bindParam` ou `bindValue`
            $stmt->bindParam(":nom", $nom);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":mot_de_passe", $mot_de_passe);
            $photo = "default.png";
            $stmt->bindParam(":photo_profil", $photo);
                
            $stmt->execute([$nom, $email, $mot_de_passe, $photo]);


            // Recupérer l'id de l"utilisateur 
            $stmt = $conn->prepare("SELECT id FROM utilisateurs WHERE email = ?");
            $stmt->execute([$email]);
            $result_id = $stmt->fetchColumn();


            // Démarrer la session 
            session_start();
            $_SESSION["nom"] = $nom;
            $_SESSION["email"] = $email;
            $_SESSION["id"] = $result_id;

            // Redirection vers l'accueil
            header("Location: index.php");
        }
    }

    ?>

    <section>
        <div class="container container-small">
            <h1>Inscription 🖊️</h1>
            <form action="inscription.php" method="POST" enctype="multipart/form-data">
                <input type="text" name="nom" placeholder="Nom" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="mot_de_passe" placeholder="Mot de passe" required>            
                <button class="button" type="submit">S'inscrire</button>
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

