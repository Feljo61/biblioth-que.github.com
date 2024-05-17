<?php

class GestionSanctions
{
    private $conn;

    public function __construct($servername, $username, $password, $dbname)
    {
        $this->conn = new mysqli($servername, $username, $password, $dbname);

        // Vérifier la connexion
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function sanitizeInput($data)
    {
        return mysqli_real_escape_string($this->conn, $data);
    }

    public function showConfirmationMessage($message)
    {
        echo '<div style="color: green;">' . $message . '</div>';
    }

    public function showErrorMessage($message)
    {
        echo '<div style="color: red;">' . $message . '</div>';
    }

    public function getAllSanctions()
    {
        $sql = "SELECT * FROM sanctions";
        $result = $this->conn->query($sql);

        $sanctions = array();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $sanctions[] = $row;
            }
        }

        return $sanctions;
    }

    public function createSanction($noml, $prenoml, $cote, $titre, $auteur, $date_retour, $sanctions, $etat_livre)
    {
        $noml = $this->sanitizeInput($noml);
        $prenoml = $this->sanitizeInput($prenoml);
        $cote = $this->sanitizeInput($cote);
        $titre = $this->sanitizeInput($titre);
        $auteur = $this->sanitizeInput($auteur);
        $date_retour = $this->sanitizeInput($date_retour);
        $sanctions = $this->sanitizeInput($sanctions);
        $etat_livre = $this->sanitizeInput($etat_livre);

        $sql = "INSERT INTO sanctions (noml, prenoml, cote, titre, auteur, date_retour, sanctions, etat_livre) VALUES ('$noml', '$prenoml', '$cote', '$titre', '$auteur', '$date_retour', '$sanctions', '$etat_livre')";

        if ($this->conn->query($sql) === TRUE) {
            $this->showConfirmationMessage("Sanction ajoutée avec succès !");
        } else {
            $this->showErrorMessage("Erreur lors de l'ajout de la sanction : " . $this->conn->error);
        }
    }

    public function updateSanction($id_sanction, $noml, $prenoml, $cote, $titre, $auteur, $date_retour, $sanctions, $etat_livre)
    {
        $id_sanction = $this->sanitizeInput($id_sanction);
        $noml = $this->sanitizeInput($noml);
        $prenoml = $this->sanitizeInput($prenoml);
        $cote = $this->sanitizeInput($cote);
        $titre = $this->sanitizeInput($titre);
        $auteur = $this->sanitizeInput($auteur);
        $date_retour = $this->sanitizeInput($date_retour);
        $sanctions = $this->sanitizeInput($sanctions);
        $etat_livre = $this->sanitizeInput($etat_livre);

        $sql = "UPDATE sanctions SET noml='$noml', prenoml='$prenoml', cote='$cote', titre='$titre', auteur='$auteur', date_retour='$date_retour', sanctions='$sanctions', etat_livre='$etat_livre' WHERE id_sanction=$id_sanction";

        if ($this->conn->query($sql) === TRUE) {
            $this->showConfirmationMessage("Sanction mise à jour avec succès !");
        } else {
            $this->showErrorMessage("Erreur lors de la mise à jour de la sanction : " . $this->conn->error);
    }

    public function deleteSanction($id_sanction)
    {
        $id_sanction = $this->sanitizeInput($id_sanction);

        $sql = "DELETE FROM sanctions WHERE id_sanction=$id_sanction";

        if ($this->conn->query($sql) === TRUE) {
            $this->showConfirmationMessage("Sanction supprimée avec succès !");
        } else {
            $this->showErrorMessage("Erreur lors de la suppression de la sanction : " . $this->conn->error);
        }
    }
}

// Vérifier si des données ont été soumises
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Contrôles côté serveur pour s'assurer que les champs obligatoires ne sont pas vides
    if (empty($_POST["noml"]) || empty($_POST["prenoml"]) || empty($_POST["cote"]) || empty($_POST["titre"]) || empty($_POST["auteur"]) || empty($_POST["date_retour"]) || empty($_POST["sanctions"]) || empty($_POST["etat_livre"])) {
        showErrorMessage("Les champs Nom, Prénom, Cote, Titre, Auteur, Date de Retour, Sanctions et État du Livre sont obligatoires.");
    } else {
        // Créer une instance de la classe GestionSanctions
        $gestionSanctions = new GestionSanctions("localhost", "root", "", "bibliotheque");

        // Traiter l'action correspondante (ajout, mise à jour, suppression)
        if (isset($_POST["ajouter"])) {
            $gestionSanctions->createSanction($_POST["noml"], $_POST["prenoml"], $_POST["cote"], $_POST["titre"], $_POST["auteur"], $_POST["date_retour"], $_POST["sanctions"], $_POST["etat_livre"]);
        } elseif (isset($_POST["modifier"])) {
            $gestionSanctions->updateSanction($_POST["id_sanction"], $_POST["noml"], $_POST["prenoml"], $_POST["cote"], $_POST["titre"], $_POST["auteur"], $_POST["date_retour"], $_POST["sanctions"], $_POST["etat_livre"]);
        } elseif (isset($_POST["supprimer"])) {
            $gestionSanctions->deleteSanction($_POST["id_sanction"]);
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
    
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">

    <link rel="icon" type="image/x-icon" href="logo/logo.png">

    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body class="poi">
     <header>
        <div class="bouton">
        <a class="btn btn-primary" href="acceuil.html">Accueil</a><button type="button"   style="color: white" class="bouton-acceuil"></button>
        </div>
    </header>

    <h1 class="fui" style="font-size:50px" >ENREGISTRER UNE SANCTION</h1><br>

    <!-- Formulaire pour ajouter, modifier et supprimer une sanction -->
    <form  class="formulaire" class="fui" align= "center" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="nom">Nom:</label>
        <input type="text" name="noml" required>
        <br><br>

        <label for="prenom">Prénom:</label>
        <input type="text" name="prenoml" required>
        <br><br>

        <label for="cote">Cote:</label>
        <input type="text" name="cote" required>
        <br><br>

        <label for="titre">Titre:</label>
        <input type="text" name="titre" required>
        <br><br>

        <label for="auteur">Auteur:</label>
        <input type="text" name="auteur" required>
        <br><br>

        <label for="date_retour">Date de Retour:</label>
        <input type="date" name="date_retour" required>
        <br><br>

        <label for="sanctions">Sanctions:</label>
        <input type="text" name="sanctions" required>
        <br><br>

        <label for="etat_livre">État du Livre:</label>
        <input type="text" name="etat_livre" required>
        <br><br>

        <!-- Champs cachés pour identifier la sanction lors de la modification ou de la suppression -->
        <input type="hidden" name="id_sanction">

        <!-- Boutons d'action -->
        <div class="bouton">
        <button type="submit"   class="bouton-ajouter" name="ajouter"><i class="fas fa-plus"></i>Ajouter</button>
        <button type="submit" class="bouton-modifier" name="modifier"><i class="fas fa-pen"></i>Modifier</button>
        <button type="submit" class="bouton-supprimer" name="supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette sanction ?')"><i class="fas fa-trash"></i>Supprimer</button>
        </div>
    </form><br><br>

    <hr><hr>

    <!-- Afficher la liste des sanctions -->
    <div class="conteneur">
    <h1 class="fui" style="font-size:50px">LISTE DES SANCTIONS</h1><br>

    <table class="lu"  class="tableau" align="center" cellspacing="5"  border ="5" cellpadding="30" border="0">
        <tr class="pui">
            <th>ID</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Cote</th>
            <th>Titre</th>
            <th>Auteur</th>
            <th>Date de Retour</th>
            <th>Sanctions</th>
            <th>État du Livre</th>
            <th>Action</th>
        </tr>

        <?php
        // Créer une nouvelle instance de la classe GestionSanctions pour récupérer les sanctions
        $gestionSanctions = new GestionSanctions("localhost", "root", "", "bibliotheque");

        // Récupérer toutes les sanctions de la base de données
        $sanctions = $gestionSanctions->getAllSanctions();

        // Afficher chaque sanction dans le tableau
        foreach ($sanctions as $sanction) {
            echo "<tr>";
            echo "<td>" . $sanction["id_sanction"] . "</td>";
            echo "<td>" . $sanction["noml"] . "</td>";
            echo "<td>" . $sanction["prenoml"] . "</td>";
            echo "<td>" . $sanction["cote"] . "</td>";
            echo "<td>" . $sanction["titre"] . "</td>";
            echo "<td>" . $sanction["auteur"] . "</td>";
            echo "<td>" . $sanction["date_retour"] . "</td>";
            echo "<td>" . $sanction["sanctions"] . "</td>";
            echo "<td>" . $sanction["etat_livre"] . "</td>";
            echo "<td><button onclick=\"editSanction(" . $sanction["id_sanction"] . ",'" . $sanction["noml"] . "','" . $sanction["prenoml"] . "','" . $sanction["cote"] . "','" . $sanction["titre"] . "','" . $sanction["auteur"] . "','" . $sanction["date_retour"] . "','" . $sanction["sanctions"] . "','" . $sanction["etat_livre"] . "')\">Modifier</button></td>";
            echo "</tr>";
        }
        ?>

    </table>

    <script>
        // Fonction pour remplir le formulaire lors de la modification
        function editSanction(id, noml, prenoml, cote, titre, auteur, date_retour, sanctions, etat_livre) {
            document.getElementsByName("id_sanction")[0].value = id;
            document.getElementsByName("noml")[0].value = noml;
            document.getElementsByName("prenoml")[0].value = prenoml;
            document.getElementsByName("cote")[0].value = cote;
            document.getElementsByName("titre")[0].value = titre;
            document.getElementsByName("auteur")[0].value = auteur;
            document.getElementsByName("date_retour")[0].value = date_retour;
            document.getElementsByName("sanctions")[0].value = sanctions;
            document.getElementsByName("etat_livre")[0].value = etat_livre;
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
