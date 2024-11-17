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
    ?>

    <h1>Accueil</h1>

    <?php 
        $stmt = $conn->prepare("SELECT * FROM messages LEFT JOIN utilisateurs ON messages.utilisateurs_id = utilisateurs.id ORDER BY date_post DESC LIMIT 10");
        $stmt->execute();

        while ($result = $stmt->fetch()) {
            $dateFormat = new IntlDateFormatter('fr_FR', IntlDateFormatter::LONG, IntlDateFormatter::SHORT);

            echo "<div>";
            echo "<p>" . $dateFormat->format(strtotime($result['date_post'])) . "</p>";
            echo "<p>" . $result['contenu_text'] . "</p>";
            echo "<p>" . $result['nom'] . "</p>";
            echo "</div>";
        }
    ?>

    <?php
        // Je vérifie si l'utilisateur est connecté
        if (isset($_SESSION['id'])) {
            echo '<form action="publication_message.php" method="POST" enctype="multipart/form-data">';
            echo '<textarea name="message" placeholder="Ecrivez votre message" required></textarea>';
            echo '<button type="submit">Publier</button>';
            echo '</form>';        
        }
    ?>

</body>
</html>
