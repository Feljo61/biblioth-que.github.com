<?php

// Connexion à la base de données (remplacez les valeurs par les vôtres)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bibliotheque";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fonction pour échapper les entrées
function sanitize_input($data) {
    global $conn;
    return mysqli_real_escape_string($conn, $data);
}

// Fonction pour afficher un message de confirmation
function showConfirmationMessage($message) {
    echo '<div style="color: green;">' . $message . '</div>';
}

// Fonction pour afficher un message d'erreur
function showErrorMessage($message) {
    echo '<div style="color: red;">' . $message . '</div>';
}

// CRUD

// Lire tous les ouvrages
function getAllOuvrages() {
    global $conn;
    $sql = "SELECT * FROM ouvrages";
    $result = $conn->query($sql);

    $ouvrages = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ouvrages[] = $row;
        }
    }

    return $ouvrages;
}

// Créer un nouvel ouvrage
function createOuvrage($titre, $cote, $auteur, $edition, $date_publication, $genre) {
    global $conn;

    $titre = sanitize_input($titre);
    $cote = sanitize_input($cote);
    $auteur = sanitize_input($auteur);
    $edition = sanitize_input($edition);
    $date_publication = sanitize_input($date_publication);
    $genre = sanitize_input($genre);

    $sql = "INSERT INTO ouvrages (titre, cote, auteur, edition, date_publication, genre) VALUES ('$titre', '$cote', '$auteur', '$edition', '$date_publication', '$genre')";

    if ($conn->query($sql) === TRUE) {
        showConfirmationMessage("Ouvrage ajouté avec succès !");
    } else {
        showErrorMessage("Erreur lors de l'ajout de l'ouvrage : " . $conn->error);
    }
}
// Mettre à jour un ouvrage
function updateOuvrage($id_ouvrage, $titre, $cote, $auteur, $edition, $date_publication, $genre) {
    global $conn;

    $id_ouvrage = sanitize_input($id_ouvrage);
    $titre = sanitize_input($titre);
    $cote = sanitize_input($cote);
    $auteur = sanitize_input($auteur);
    $edition = sanitize_input($edition);
    $date_publication = sanitize_input($date_publication);
    $genre = sanitize_input($genre);

    $sql = "UPDATE ouvrages SET titre='$titre', cote='$cote', auteur='$auteur', edition='$edition', date_publication='$date_publication', genre='$genre' WHERE id_ouvrage=$id_ouvrage";

    if ($conn->query($sql) === TRUE) {
        showConfirmationMessage("Ouvrage mis à jour avec succès !");
    } else {
        showErrorMessage("Erreur lors de la mise à jour de l'ouvrage : " . $conn->error);
    }



}   




// Supprimer un ouvrage
function deleteOuvrage($id_ouvrage) {
    global $conn;

    $id_ouvrage = sanitize_input($id_ouvrage);

    $sql = "DELETE FROM ouvrages WHERE id_ouvrage=$id_ouvrage";

    if ($conn->query($sql) === TRUE) {
        showConfirmationMessage("Ouvrage supprimé avec succès !");
    } else {
        showErrorMessage("Erreur lors de la suppression de l'ouvrage : " . $conn->error);
    }
}

// Vérifier si des données ont été soumises
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Contrôles côté serveur pour s'assurer que les champs obligatoires ne sont pas vides
    if (empty($_POST["titre"]) || empty($_POST["auteur"])) {
        showErrorMessage("Les champs Titre et Auteur sont obligatoires.");
    } else {
        // Traiter l'action correspondante (ajout, mise à jour, suppression)
        if (isset($_POST["ajouter"])) {
            createOuvrage($_POST["titre"], $_POST["cote"], $_POST["auteur"], $_POST["edition"], $_POST["date_publication"], $_POST["genre"]);
        } elseif (isset($_POST["modifier"])) {
            updateOuvrage($_POST["id_ouvrage"], $_POST["titre"], $_POST["cote"], $_POST["auteur"], $_POST["edition"], $_POST["date_publication"], $_POST["genre"]);
        } elseif (isset($_POST["supprimer"])) {
            deleteOuvrage($_POST["id_ouvrage"]);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LA PLUME QUI ECRIT</title>

 
    <link rel="icon" type="image/x-icon" href="logo/logo.png">

    <link rel="stylesheet" type="text/css" href="style.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    

</head>

<body   class="poi">

    <header>
        <div class="bouton">
        <a class="btn btn-primary" href="acceuil.html">Accueil</a><button type="button" style="color:white;" class="bouton-acceuil"></button>
        </div>
    </header>

    <h1 class="fui" style="font-size:50px" >AJOUTER UN OUVRAGE </h1><br>

    <!-- Formulaire pour ajouter, modifier et supprimer un ouvrage -->
    <form  class="formulaire" class="fui" align= "center" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">


        <label for="titre">Titre:</label>
        <input type="text" name="titre" required>
        <br><br>

        <label for="cote">Cote:</label>
        <input type="text" name="cote">
        <br><br>

        <label for="auteur">Auteur:</label>
        <input type="text" name="auteur" required>
        <br><br>

        <label for="edition">Edition:</label>
        <input type="text" name="edition">
        <br><br>

        <label for="date_publication">Date de Publication:</label>
        <input type="date" name="date_publication">
        <br><br>

        <label for="genre">Genre:</label>
        <input type="text" name="genre">
        <br><br>

        <!-- Champs cachés pour identifier l'ouvrage lors de la modification ou de la suppression -->
        <input type="hidden" name="id_ouvrage">

        <!-- Boutons d'action -->
        <div class="bouton">
        <button type="submit"  class="bouton-ajouter" name="ajouter"><i class="fas fa-plus"></i>Ajouter</button>
        <button type="submit"  class="bouton-modifier" name="modifier"><i class="fas fa-pen"></i>Modifier</button>
        <button type="submit"  class="bouton-supprimer" name="supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet ouvrage ?')"><i class="fas fa-trash"></i>Supprimer</button>
        </div>
    </div>
    </form><br><br>

    <hr><hr>

    <!-- Afficher la liste des ouvrages -->
    <div class="conteneur">
    <h1  class="fui" style="font-size:50px">LISTE DES OUVRAGES</h1><br>

    <table class="lu"  class="tableau" align="center" cellspacing="5"  border ="5" cellpadding="30" border="0">
        <tr class="pui">
            <th>ID</th>
            <th>Titre</th>
            <th>Cote</th>
            <th>Auteur</th>
            <th>Edition</th>
            <th>Date de Publication</th>
            <th>Genre</th>
            <th>Action</th>
        </tr>

        <?php
        // Récupérer tous les ouvrages de la base de données
        $ouvrages = getAllOuvrages();

        // Afficher chaque ouvrage dans le tableau
        foreach ($ouvrages as $ouvrage) {
            echo "<tr>";
            echo "<td>" . $ouvrage["id_ouvrage"] . "</td>";
            echo "<td>" . $ouvrage["titre"] . "</td>";
            echo "<td>" . $ouvrage["cote"] . "</td>";
            echo "<td>" . $ouvrage["auteur"] . "</td>";
            echo "<td>" . $ouvrage["edition"] . "</td>";
            echo "<td>" . $ouvrage["date_publication"] . "</td>";
            echo "<td>" . $ouvrage["genre"] . "</td>";
            echo "<td><button   onclick=\"editOuvrage(" . $ouvrage["id_ouvrage"] . ",'" . $ouvrage["titre"] . "','" . $ouvrage["cote"] . "','" . $ouvrage["auteur"] . "','" . $ouvrage["edition"] . "','" . $ouvrage["date_publication"] . "','" . $ouvrage["genre"] . "')\">Modifier</button></td>";
            echo "</tr>";
           
        }
        ?>

    </table>

    <script>
        // Fonction pour remplir le formulaire lors de la modification
        function editOuvrage(id, titre, cote, auteur, edition, date_publication, genre) {
            document.getElementsByName("id_ouvrage")[0].value = id;
            document.getElementsByName("titre")[0].value = titre;
            document.getElementsByName("cote")[0].value = cote;
            document.getElementsByName("auteur")[0].value = auteur;
            document.getElementsByName("edition")[0].value = edition;
            document.getElementsByName("date_publication")[0].value = date_publication;
            document.getElementsByName("genre")[0].value = genre;
        }
    </script>

   
        
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>    
</body>
</body>

</html>
