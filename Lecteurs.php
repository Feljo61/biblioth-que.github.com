<?php

class GestionLecteurs
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

    public function getAllLecteurs()
    {
        $sql = "SELECT * FROM lecteurs";
        $result = $this->conn->query($sql);

        $lecteurs = array();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $lecteurs[] = $row;
            }
        }

        return $lecteurs;
    }

    public function createLecteur($nom, $prenom, $email, $sexe, $date_naissance, $type_piece, $num_piece, $telephone, $adresse)
    {
        $nom = $this->sanitizeInput($nom);
        $prenom = $this->sanitizeInput($prenom);
        $email = $this->sanitizeInput($email);
        $sexe = $this->sanitizeInput($sexe);
        $date_naissance = $this->sanitizeInput($date_naissance);
        $type_piece = $this->sanitizeInput($type_piece);
        $num_piece = $this->sanitizeInput($num_piece);
        $telephone = $this->sanitizeInput($telephone);
        $adresse = $this->sanitizeInput($adresse);

        $sql = "INSERT INTO lecteurs (nom, prenom, email, sexe, date_naissance, type_piece, num_piece, telephone, adresse) VALUES ('$nom', '$prenom', '$email', '$sexe', '$date_naissance', '$type_piece', '$num_piece', '$telephone', '$adresse')";

        if ($this->conn->query($sql) === TRUE) {
            $this->showConfirmationMessage("Lecteur ajouté avec succès !");
        } else {
            $this->showErrorMessage("Erreur lors de l'ajout du lecteur : " . $this->conn->error);
        }
    }

    public function updateLecteur($id_lecteur, $nom, $prenom, $email, $sexe, $date_naissance, $type_piece, $num_piece, $telephone, $adresse)
    {
        $id_lecteur = $this->sanitizeInput($id_lecteur);
        $nom = $this->sanitizeInput($nom);
        $prenom = $this->sanitizeInput($prenom);
        $email = $this->sanitizeInput($email);
        $sexe = $this->sanitizeInput($sexe);
        $date_naissance = $this->sanitizeInput($date_naissance);
        $type_piece = $this->sanitizeInput($type_piece);
        $num_piece = $this->sanitizeInput($num_piece);
        $telephone = $this->sanitizeInput($telephone);
        $adresse = $this->sanitizeInput($adresse);

        $sql = "UPDATE lecteurs SET nom='$nom', prenom='$prenom', email='$email', sexe='$sexe', date_naissance='$date_naissance', type_piece='$type_piece', num_piece='$num_piece', telephone='$telephone', adresse='$adresse' WHERE id_lecteur=$id_lecteur";

        if ($this->conn->query($sql) === TRUE) {
            $this->showConfirmationMessage("Lecteur mis à jour avec succès !");
        } else {
            $this->showErrorMessage("Erreur lors de la mise à jour du lecteur : " . $this->conn->error);
        }
    }

    public function deleteLecteur($id_lecteur)
    {
        $id_lecteur = $this->sanitizeInput($id_lecteur);

        $sql = "DELETE FROM lecteurs WHERE id_lecteur=$id_lecteur";

        if ($this->conn->query($sql) === TRUE) {
            $this->showConfirmationMessage("Lecteur supprimé avec succès !");
        } else {
            $this->showErrorMessage("Erreur lors de la suppression du lecteur : " . $this->conn->error);
        }
    }
}

// Vérifier si des données ont été soumises
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Contrôles côté serveur pour s'assurer que les champs obligatoires ne sont pas vides
    if (empty($_POST["nom"]) || empty($_POST["prenom"]) || empty($_POST["email"]) || empty($_POST["type_piece"]) || empty($_POST["num_piece"]) || empty($_POST["telephone"]) || empty($_POST["adresse"])) {
        showErrorMessage("Les champs Nom, Prénom, Email, Type de Pièce, Numéro de Pièce, Téléphone et Adresse sont obligatoires.");
    } else {
        // Créer une instance de la classe GestionLecteurs
        $gestionLecteurs = new GestionLecteurs("localhost", "root", "", "bibliotheque");

        // Traiter l'action correspondante (ajout, mise à jour, suppression)
        if (isset($_POST["ajouter"])) {
            $gestionLecteurs->createLecteur($_POST["nom"], $_POST["prenom"], $_POST["email"], $_POST["sexe"], $_POST["date_naissance"], $_POST["type_piece"], $_POST["num_piece"], $_POST["telephone"], $_POST["adresse"]);
        } elseif (isset($_POST["modifier"])) {
            $gestionLecteurs->updateLecteur($_POST["id_lecteur"], $_POST["nom"], $_POST["prenom"], $_POST["email"], $_POST["sexe"], $_POST["date_naissance"], $_POST["type_piece"], $_POST["num_piece"], $_POST["telephone"], $_POST["adresse"]);
        } elseif (isset($_POST["supprimer"])) {
            $gestionLecteurs->deleteLecteur($_POST["id_lecteur"]);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LA PLUME ECRIT</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    

    <link rel="icon" type="image/x-icon" href="logo/logo.png">

    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body  class="poi" >
     <header>
        <div class="bouton">
        <a class="btn btn-primary" href="acceuil.html">Accueil</a><button type="button" style="color:white;" ></button>
        </div>
      </header>

        

    <h1 class="fui" style="font-size:50px" >AJOUTER UN LECTEUR</h1><br>

    <!-- Formulaire pour ajouter, modifier et supprimer un lecteur -->
    <form class="formulaire" class="fui" align= "center" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="nom">Nom:</label>
        <input type="text" name="nom" required>
        <br><br>

        <label for="prenom">Prénom:</label>
        <input type="text" name="prenom" required>
        <br><br>

        <label for="email">Email:</label>
        <input type="email" name="email" required>
        <br><br>

        <label for="sexe">Sexe:</label>
        <select name="sexe">
            <option value="Homme">Homme</option>
            <option value="Femme">Femme</option>
        </select>
        <br><br>

        <label for="date_naissance">Date de Naissance:</label>
        <input type="date" name="date_naissance">
        <br><br>

        <label for="type_piece">Type de Pièce:</label>
        <select name="type_piece">
            <option value="Passeport">Passeport</option>
            <option value="CNI">Carte Nationale d'Identité (CNI)</option>
            <option value="Permis">Permis de Conduire</option>
            <option value="Carte_scolaire">Carte Scolaire</option>
            <option value="Carte_etudiant">Carte Étudiant</option>
        </select>
        <br><br>

        <label for="num_piece">Numéro de Pièce:</label>
        <input type="text" name="num_piece" required>
        <br><br>

        <label for="telephone">Téléphone:</label>
        <input type="text" name="telephone" required>
        <br><br>

        <label for="adresse">Adresse:</label>
        <input type="text" name="adresse" required>
        <br><br>

        <!-- Champs cachés pour identifier le lecteur lors de la modification ou de la suppression -->
        <input type="hidden" name="id_lecteur">

        <!-- Boutons d'action -->
        <div class="bouton">
        <button type="submit" class="bouton-ajouter"  name="ajouter"><i class="fas fa-plus"></i>Ajouter</button>
        <button type="submit" class="bouton-modifier" name="modifier"><i class="fas fa-pen"></i>Modifier</button>
        <button type="submit"  class="bouton-supprimer" name="supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce lecteur ?')"><i class="fas fa-trash"></i>Supprimer</button>
    </div>
    </form><br><br>

    <hr><hr>

    <!-- Afficher la liste des lecteurs -->
    <div class="conteneur">
    <h1 class="fui" style="font-size:50px">LISTE DES LECTEURS</h1><br>

    <table class="lu"  class="tableau" align="center" cellspacing="5"  border ="5" cellpadding="30" border="0">
        <tr class="pui">
            <th>ID</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Email</th>
            <th>Sexe</th>
            <th>Date de Naissance</th>
            <th>Type de Pièce</th>
            <th>Numéro de Pièce</th>
            <th>Téléphone</th>
            <th>Adresse</th>
            <th>Action</th>
        </tr>

        <?php
        // Créer une nouvelle instance de la classe GestionLecteurs pour récupérer les lecteurs
        $gestionLecteurs = new GestionLecteurs("localhost", "root", "", "bibliotheque");

        // Récupérer tous les lecteurs de la base de données
        $lecteurs = $gestionLecteurs->getAllLecteurs();

        // Afficher chaque lecteur dans le tableau
        foreach ($lecteurs as $lecteur) {
            echo "<tr>";
            echo "<td>" . $lecteur["id_lecteur"] . "</td>";
            echo "<td>" . $lecteur["nom"] . "</td>";
            echo "<td>" . $lecteur["prenom"] . "</td>";
            echo "<td>" . $lecteur["email"] . "</td>";
            echo "<td>" . $lecteur["sexe"] . "</td>";
            echo "<td>" . $lecteur["date_naissance"] . "</td>";
            echo "<td>" . $lecteur["type_piece"] . "</td>";
            echo "<td>" . $lecteur["num_piece"] . "</td>";
            echo "<td>" . $lecteur["telephone"] . "</td>";
            echo "<td>" . $lecteur["adresse"] . "</td>";
            echo "<td><button onclick=\"editLecteur(" . $lecteur["id_lecteur"] . ",'" . $lecteur["nom"] . "','" . $lecteur["prenom"] . "','" . $lecteur["email"] . "','" . $lecteur["sexe"] . "','" . $lecteur["date_naissance"] . "','" . $lecteur["type_piece"] . "','" . $lecteur["num_piece"] . "','" . $lecteur["telephone"] . "','" . $lecteur["adresse"] . "')\">Modifier</button></td>";
            echo "</tr>";
        }
        ?>

    </table>

    <script>
        // Fonction pour remplir le formulaire lors de la modification
        function editLecteur(id, nom, prenom, email, sexe, date_naissance, type_piece, num_piece, telephone, adresse) {
            document.getElementsByName("id_lecteur")[0].value = id;
            document.getElementsByName("nom")[0].value = nom;
            document.getElementsByName("prenom")[0].value = prenom;
            document.getElementsByName("email")[0].value = email;
            document.getElementsByName("sexe")[0].value = sexe;
            document.getElementsByName("date_naissance")[0].value = date_naissance;
            document.getElementsByName("type_piece")[0].value = type_piece;
            document.getElementsByName("num_piece")[0].value = num_piece;
            document.getElementsByName("telephone")[0].value = telephone;
            document.getElementsByName("adresse")[0].value = adresse;
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
