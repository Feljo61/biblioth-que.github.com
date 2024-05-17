<?php

class GestionEmprunts
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

    public function getAllEmprunts()
    {
        $sql = "SELECT * FROM emprunts";
        $result = $this->conn->query($sql);

        $emprunts = array();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $emprunts[] = $row;
            }
        }

        return $emprunts;
    }

    public function createEmprunt($nom, $prenom, $titre, $cote, $auteur, $id_lecteur, $id_ouvrage, $date_emprunt)
    {
        $nom = $this->sanitizeInput($nom);
        $prenom = $this->sanitizeInput($prenom);
        $titre = $this->sanitizeInput($titre);
        $cote = $this->sanitizeInput($cote);
        $auteur = $this->sanitizeInput($auteur);
        $id_lecteur = $this->sanitizeInput($id_lecteur);
        $id_ouvrage = $this->sanitizeInput($id_ouvrage);
        $date_emprunt = $this->sanitizeInput($date_emprunt);

        $sql = "INSERT INTO emprunts (nom, prenom, titre, cote, auteur, id_lecteur, id_ouvrage, date_emprunt) VALUES ('$nom', '$prenom', '$titre', '$cote', '$auteur', '$id_lecteur', '$id_ouvrage', '$date_emprunt')";

        if ($this->conn->query($sql) === TRUE) {
            $this->showConfirmationMessage("Emprunt ajouté avec succès !");
        } else {
            $this->showErrorMessage("Erreur lors de l'ajout de l'emprunt : " . $this->conn->error);
        }
    }

    public function updateEmprunt($id_emprunt, $nom, $prenom, $titre, $cote, $auteur, $id_lecteur, $id_ouvrage, $date_emprunt)
    {
        $id_emprunt = $this->sanitizeInput($id_emprunt);
        $nom = $this->sanitizeInput($nom);
        $prenom = $this->sanitizeInput($prenom);
        $titre = $this->sanitizeInput($titre);
        $cote = $this->sanitizeInput($cote);
        $auteur = $this->sanitizeInput($auteur);
        $id_lecteur = $this->sanitizeInput($id_lecteur);
        $id_ouvrage = $this->sanitizeInput($id_ouvrage);
        $date_emprunt = $this->sanitizeInput($date_emprunt);

        $sql = "UPDATE emprunts SET nom='$nom', prenom='$prenom', titre='$titre', cote='$cote', auteur='$auteur', id_lecteur='$id_lecteur', id_ouvrage='$id_ouvrage', date_emprunt='$date_emprunt' WHERE id_emprunt=$id_emprunt";

        if ($this->conn->query($sql) === TRUE) {
            $this->showConfirmationMessage("Emprunt mis à jour avec succès !");
        } else {
            $this->showErrorMessage("Erreur lors de la mise à jour de l'emprunt : " . $this->conn->error);
        }
    }

    public function deleteEmprunt($id_emprunt)
    {
        $id_emprunt = $this->sanitizeInput($id_emprunt);

        $sql = "DELETE FROM emprunts WHERE id_emprunt=$id_emprunt";

        if ($this->conn->query($sql) === TRUE) {
            $this->showConfirmationMessage("Emprunt supprimé avec succès !");
        } else {
            $this->showErrorMessage("Erreur lors de la suppression de l'emprunt : " . $this->conn->error);
        }
    }
}

// Vérifier si des données ont été soumises
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Contrôles côté serveur pour s'assurer que les champs obligatoires ne sont pas vides
    if (empty($_POST["nom"]) || empty($_POST["prenom"]) || empty($_POST["titre"]) || empty($_POST["cote"]) || empty($_POST["auteur"]) || empty($_POST["id_lecteur"]) || empty($_POST["id_ouvrage"]) || empty($_POST["date_emprunt"])) {
        showErrorMessage("Les champs Nom, Prénom, Titre, Cote, Auteur, ID Lecteur, ID Ouvrage et Date d'Emprunt sont obligatoires.");
    } else {
        // Créer une instance de la classe GestionEmprunts
        $gestionEmprunts = new GestionEmprunts("localhost", "root", "", "bibliotheque");

        // Traiter l'action correspondante (ajout, mise à jour, suppression)
        if (isset($_POST["ajouter"])) {
            $gestionEmprunts->createEmprunt($_POST["nom"], $_POST["prenom"], $_POST["titre"], $_POST["cote"], $_POST["auteur"], $_POST["id_lecteur"], $_POST["id_ouvrage"], $_POST["date_emprunt"]);
        } elseif (isset($_POST["modifier"])) {
            $gestionEmprunts->updateEmprunt($_POST["id_emprunt"], $_POST["nom"], $_POST["prenom"], $_POST["titre"], $_POST["cote"], $_POST["auteur"], $_POST["id_lecteur"], $_POST["id_ouvrage"], $_POST["date_emprunt"]);
        } elseif (isset($_POST["supprimer"])) {
            $gestionEmprunts->deleteEmprunt($_POST["id_emprunt"]);
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
        <a class="btn btn-primary" href="acceuil.html">Accueil</a><button type="button" style="color:white;" class="bouton-acceuil"></button>
        </div>
    </header>

    <h1 class="fui"  style="font-size:50px" >AJOUTER UN EMPRUNT</h1><br>

    <!-- Formulaire pour ajouter, modifier et supprimer un emprunt -->
    <form  class="formulaire" class="fui" align= "center" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="nom">Nom:</label>
        <input type="text" name="nom" required>
        <br><br>

        <label for="prenom">Prénom:</label>
        <input type="text" name="prenom" required>
        <br><br>

        <label for="titre">Titre:</label>
        <input type="text" name="titre" required>
        <br><br>

        <label for="cote">Cote:</label>
        <input type="text" name="cote" required>
        <br><br>

        <label for="auteur">Auteur:</label>
        <input type="text" name="auteur" required>
        <br><br>

        <label for="id_lecteur">ID Lecteur:</label>
        <input type="text" name="id_lecteur" required>
        <br><br>

        <label for="id_ouvrage">ID Ouvrage:</label>
        <input type="text" name="id_ouvrage" required>
        <br><br>

        <label for="date_emprunt">Date d'Emprunt:</label>
        <input type="text" name="date_emprunt" required>
        <br><br>

        <!-- Champs cachés pour identifier l'emprunt lors de la modification ou de la suppression -->
        <input type="hidden" name="id_emprunt">

        <!-- Boutons d'action -->
        <div>
        <button type="submit"  class="bouton-ajouter" name="ajouter"><i class="fas fa-plus"></i>Ajouter</button>
        <button type="submit"  class="bouton-modifier" name="modifier"><i class="fas fa-pen"></i>Modifier</button>
        <button type="submit"   class="bouton-supprimer" name="supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet emprunt ?')"><i class="fas fa-trash"></i>Supprimer</button>
        </div>
    </form><br><br>

    <hr><hr>

    <!-- Afficher la liste des emprunts -->
    <div class="conteneur">
    <h1  class="fui" style="font-size:50px" >LISTE DES EMPRUNTS</h1><br>

    <table class="lu"  class="tableau" align="center" cellspacing="5"  border ="5" cellpadding="30" border="0">
        <tr class="pui">
            <th>ID</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Titre</th>
            <th>Cote</th>
            <th>Auteur</th>
            <th>ID Lecteur</th>
            <th>ID Ouvrage</th>
            <th>Date d'Emprunt</th>
            <th>Action</th>
        </tr>

        <?php
        // Créer une nouvelle instance de la classe GestionEmprunts pour récupérer les emprunts
        $gestionEmprunts = new GestionEmprunts("localhost", "root", "", "bibliotheque");

        // Récupérer tous les emprunts de la base de données
        $emprunts = $gestionEmprunts->getAllEmprunts();

        // Afficher chaque emprunt dans le tableau
        foreach ($emprunts as $emprunt) {
            echo "<tr>";
            echo "<td>" . $emprunt["id_emprunt"] . "</td>";
            echo "<td>" . $emprunt["nom"] . "</td>";
            echo "<td>" . $emprunt["prenom"] . "</td>";
            echo "<td>" . $emprunt["titre"] . "</td>";
            echo "<td>" . $emprunt["cote"] . "</td>";
            echo "<td>" . $emprunt["auteur"] . "</td>";
            echo "<td>" . $emprunt["id_lecteur"] . "</td>";
            echo "<td>" . $emprunt["id_ouvrage"] . "</td>";
            echo "<td>" . $emprunt["date_emprunt"] . "</td>";
            echo "<td><button onclick=\"editEmprunt(" . $emprunt["id_emprunt"] . ",'" . $emprunt["nom"] . "','" . $emprunt["prenom"] . "','" . $emprunt["titre"] . "','" . $emprunt["cote"] . "','" . $emprunt["auteur"] . "','" . $emprunt["id_lecteur"] . "','" . $emprunt["id_ouvrage"] . "','" . $emprunt["date_emprunt"] . "')\">Modifier</button></td>";
            echo "</tr>";
        }
        ?>

    </table>

    <script>
        // Fonction pour remplir le formulaire lors de la modification
        function editEmprunt(id, nom, prenom, titre, cote, auteur, id_lecteur, id_ouvrage, date_emprunt) {
            document.getElementsByName("id_emprunt")[0].value = id;
            document.getElementsByName("nom")[0].value = nom;
            document.getElementsByName("prenom")[0].value = prenom;
            document.getElementsByName("titre")[0].value = titre;
            document.getElementsByName("cote")[0].value = cote;
            document.getElementsByName("auteur")[0].value = auteur;
            document.getElementsByName("id_lecteur")[0].value = id_lecteur;
            document.getElementsByName("id_ouvrage")[0].value = id_ouvrage;
            document.getElementsByName("date_emprunt")[0].value = date_emprunt;
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
