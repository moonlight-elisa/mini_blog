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
        if (isset($_SESSION["id"])) {
            include "navigation_logue.php";
        } 
        else {
            include "navigation_non_logue.php";
        } 
    ?>

    <section class="hero">
        <div class="container">
            <h1>Bonjour et bienvenue sur le mini blog 👋</h1>
            <div class="switch__container">
                <p class="p-bold">Afficher/masquer les messages</p>
                <form action="index.php" method="GET">
                    <label class="switch">
                        <input type="checkbox" name="messages" <?php if (isset($_GET["messages"]) && $_GET["messages"] == "on") { echo "checked"; } ?> onChange="this.form.submit()">
                        <span class="slider"></span>
                    </label>
                </form>
            </div>
        </div>
    </section>

    <?php
        // On vérifie si l'utilisateur souhaite afficher les messages ou non
        if (isset($_GET["messages"]) && $_GET["messages"] == "on") {
            $stmt = $conn->prepare("SELECT * FROM messages LEFT JOIN utilisateurs ON messages.utilisateurs_id = utilisateurs.id ORDER BY date_post DESC LIMIT 10");
            $stmt->execute();

            echo "<section>";
            echo "<div class='container container-small'>";

            while ($result = $stmt->fetch()) {
                $dateFormat = new IntlDateFormatter('fr_FR', IntlDateFormatter::LONG, IntlDateFormatter::SHORT);
                echo "<div class='message'>";
                echo "<p class='message__nom'>" . $result['nom'] . "</p>";
                echo "<p class='message__date'>" . $dateFormat->format(strtotime($result['date_post'])) . "</p>";
                echo "<p>" . $result['contenu_text'] . "</p>";
                echo "</div>";
            }

            echo "</div>";
            echo "</section>";
        }
    ?>

    
    <?php
        // Je vérifie si l'utilisateur est connecté
        if (isset($_SESSION['id'])) {

            echo "<section class='section-color'>";
            echo "<div class='container'>";

            echo "<h2>Publier un message 🖊️</h2>";
            echo '<form action="publication_message.php" method="POST" enctype="multipart/form-data">';
            echo '<textarea name="message" placeholder="Ecrivez votre message" required></textarea>';
            echo '<button class="button" type="submit">Publier</button>';
            echo '</form>';    
            
            echo "</div>";
            echo "</section>";
        }
    ?>

</body>
</html>
