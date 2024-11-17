<!DOCTYPE html>
<html>
<head>
    <title>Mini Chat</title>
    <style>
        /* Un peu de CSS pour l'apparence */
    </style>
</head>
<body>

    <?php include 'connexion_bdd.php' ?>

    <?php 

    $error = null;
    
    // Inscription
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nom'], $_POST['email'], $_POST['mot_de_passe'])) {
        $nom = $_POST['nom'];
        $email = $_POST['email'];
        $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_BCRYPT);

        // Vérifier si l'email existe déjà dans la base de données
        $stmt = $conn->prepare("SELECT COUNT(*) FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        $emailCount = $stmt->fetchColumn();

        if ($emailCount > 0) {
            $error = "Cet email est déjà utilisé. Veuillez en choisir un autre.";
        } 
        else {
            // Insérer l'utilisateur dans la base de données
            $stmt = $conn->prepare("INSERT INTO utilisateurs (nom, email, mot_de_passe, photo_profil) VALUES (?, ?, ?, ?)");
            
            // Liaison des paramètres avec `bindParam` ou `bindValue`
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':mot_de_passe', $mot_de_passe);
            $photo = "default.png";
            $stmt->bindParam(':photo_profil', $photo);
                
            $stmt->execute([$nom, $email, $mot_de_passe, $photo]);


            // Recupérer l'id de l'utilisateur 
            $stmt = $conn->prepare("SELECT id FROM utilisateurs WHERE email = ?");
            $stmt->execute([$email]);
            $result_id = $stmt->fetchColumn();


            // Démarrer la session 
            session_start();
            $_SESSION['nom'] = $nom;
            $_SESSION['email'] = $email;
            $_SESSION['id'] = $result_id;

            header('Location: profil.php');
        }
    }

    ?>

    <h1>Inscription</h1>
    <form action="inscription.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="nom" placeholder="Nom" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="mot_de_passe" placeholder="Mot de passe" required>            
        <button type="submit">S'inscrire</button>
    </form>

    <?php
        if (isset($error)) {
            echo "<p>$error</p>";
        }
    ?>

</body>
</html>

