<?php

class GestionReservations
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

    public function getAllReservations()
    {
        $sql = "SELECT * FROM reservations";
        $result = $this->conn->query($sql);

        $reservations = array();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $reservations[] = $row;
            }
        }

        return $reservations;
    }

    public function createReservation($nom, $prenom, $cote, $titre, $auteur, $date_reserv, $date_retour)
    {
        $nom = $this->sanitizeInput($nom);
        $prenom = $this->sanitizeInput($prenom);
        $cote = $this->sanitizeInput($cote);
        $titre = $this->sanitizeInput($titre);
        $auteur = $this->sanitizeInput($auteur);
        $date_reserv = $this->sanitizeInput($date_reserv);
        $date_retour = $this->sanitizeInput($date_retour);

        $sql = "INSERT INTO reservations (nom, prenom, cote, titre, auteur, date_reserv, date_retour) VALUES ('$nom', '$prenom', '$cote', '$titre', '$auteur', '$date_reserv', '$date_retour')";

        if ($this->conn->query($sql) === TRUE) {
            $this->showConfirmationMessage("Réservation ajoutée avec succès !");
        } else {
            $this->showErrorMessage("Erreur lors de l'ajout de la réservation : " . $this->conn->error);
        }
    }

    public function updateReservation($id_reservation, $nom, $prenom, $cote, $titre, $auteur, $date_reserv, $date_retour)
    {
        $id_reservation = $this->sanitizeInput($id_reservation);
        $nom = $this->sanitizeInput($nom);
        $prenom = $this->sanitizeInput($prenom);
        $cote = $this->sanitizeInput($cote);
        $titre = $this->sanitizeInput($titre);
        $auteur = $this->sanitizeInput($auteur);
        $date_reserv = $this->sanitizeInput($date_reserv);
        $date_retour = $this->sanitizeInput($date_retour);

        $sql = "UPDATE reservations SET nom='$nom', prenom='$prenom', cote='$cote', titre='$titre', auteur='$auteur', date_reserv='$date_reserv', date_retour='$date_retour' WHERE id_reservation=$id_reservation";

        if ($this->conn->query($sql) === TRUE) {
            $this->showConfirmationMessage("Réservation mise à jour avec succès !");
        } else {
            $this->showErrorMessage("Erreur lors de la mise à jour de la réservation : " . $this->conn->error);
        }
    }

    public function deleteReservation($id_reservation)
    {
        $id_reservation = $this->sanitizeInput($id_reservation);

        $sql = "DELETE FROM reservations WHERE id_reservation=$id_reservation";

        if ($this->conn->query($sql) === TRUE) {
            $this->showConfirmationMessage("Réservation supprimée avec succès !");
        } else {
            $this->showErrorMessage("Erreur lors de la suppression de la réservation : " . $this->conn->error);
        }
    }
}

// Vérifier si des données ont été soumises
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Contrôles côté serveur pour s'assurer que les champs obligatoires ne sont pas vides
    if (empty($_POST["nom"]) || empty($_POST["prenom"]) || empty($_POST["cote"]) || empty($_POST["titre"]) || empty($_POST["auteur"]) || empty($_POST["date_reserv"]) || empty($_POST["date_retour"])) {
        showErrorMessage("Les champs Nom, Prénom, Cote, Titre, Auteur, Date de Réservation et Date de Retour sont obligatoires.");
    } else {
        // Créer une instance de la classe GestionReservations
        $gestionReservations = new GestionReservations("localhost", "root", "", "bibliotheque");

        // Traiter l'action correspondante (ajout, mise à jour, suppression)
        if (isset($_POST["ajouter"])) {
            $gestionReservations->createReservation($_POST["nom"], $_POST["prenom"], $_POST["cote"], $_POST["titre"], $_POST["auteur"], $_POST["date_reserv"], $_POST["date_retour"]);
        } elseif (isset($_POST["modifier"])) {
            $gestionReservations->updateReservation($_POST["id_reservation"], $_POST["nom"], $_POST["prenom"], $_POST["cote"], $_POST["titre"], $_POST["auteur"], $_POST["date_reserv"], $_POST["date_retour"]);
        } elseif (isset($_POST["supprimer"])) {
            $gestionReservations->deleteReservation($_POST["id_reservation"]);
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

<body  class="poi">
     <header>
        <div class="bouton">
        <a class="btn btn-primary" href="acceuil.html">Accueil</a><button type="button" style="color:white;" class="bouton-acceuil"></button>
        </div>
    </header>
    <h1 class="fui" style="font-size:50px" >ENREGISTRER UNE RESERVATION</h1><br>

    <!-- Formulaire pour ajouter, modifier et supprimer une réservation -->
    <form  class="formulaire" class="fui" align= "center"   method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="nom">Nom:</label>
        <input type="text" name="nom" required>
        <br><br>

        <label for="prenom">Prénom:</label>
        <input type="text" name="prenom" required>
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

        <label for="date_reserv">Date de Réservation:</label>
        <input type="date" name="date_reserv" required>
        <br><br>

        <label for="date_retour">Date de Retour:</label>
        <input type="date" name="date_retour" required>
        <br><br>

        <!-- Champs cachés pour identifier la réservation lors de la modification ou de la suppression -->
        <input type="hidden" name="id_reservation">

        <!-- Boutons d'action -->
        <div>
        <button type="submit" class="bouton-ajouter" name="ajouter"><i class="fas fa-plus"></i>Ajouter</button>
        <button type="submit" class="bouton-modifier" name="modifier"><i class="fas fa-pen"></i>Modifier</button>
        <button type="submit"  class="bouton-supprimer" name="supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réservation ?')"><i class="fas fa-trash"></i>Supprimer</button>
        </div>
    </form><br><br>

    <hr><hr>

    <!-- Afficher la liste des réservations -->
    <div class="conteneur" >
    <h1 class="fui" style="font-size:50px" >LISTE DES RESERVATIONS </h1><br>

    <table  class="lu"  class="tableau" align="center" cellspacing="5"  border ="5" cellpadding="30" border="0" >
        <tr class="pui">
            <th>ID</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Cote</th>
            <th>Titre</th>
            <th>Auteur</th>
            <th>Date de Réservation</th>
            <th>Date de Retour</th>
            <th>Action</th>
        </tr>

        <?php
        // Créer une nouvelle instance de la classe GestionReservations pour récupérer les réservations
        $gestionReservations = new GestionReservations("localhost", "root", "", "bibliotheque");

        // Récupérer toutes les réservations de la base de données
        $reservations = $gestionReservations->getAllReservations();

        // Afficher chaque réservation dans le tableau
        foreach ($reservations as $reservation) {
            echo "<tr>";
            echo "<td>" . $reservation["id_reservation"] . "</td>";
            echo "<td>" . $reservation["nom"] . "</td>";
            echo "<td>" . $reservation["prenom"] . "</td>";
            echo "<td>" . $reservation["cote"] . "</td>";
            echo "<td>" . $reservation["titre"] . "</td>";
            echo "<td>" . $reservation["auteur"] . "</td>";
            echo "<td>" . $reservation["date_reserv"] . "</td>";
            echo "<td>" . $reservation["date_retour"] . "</td>";
            echo "<td><button onclick=\"editReservation(" . $reservation["id_reservation"] . ",'" . $reservation["nom"] . "','" . $reservation["prenom"] . "','" . $reservation["cote"] . "','" . $reservation["titre"] . "','" . $reservation["auteur"] . "','" . $reservation["date_reserv"] . "','" . $reservation["date_retour"] . "')\">Modifier</button></td>";
            echo "</tr>";
        }
        ?>

    </table>

    <script>
        // Fonction pour remplir le formulaire lors de la modification
        function editReservation(id, nom, prenom, cote, titre, auteur, date_reserv, date_retour) {
            document.getElementsByName("id_reservation")[0].value = id;
            document.getElementsByName("nom")[0].value = nom;
            document.getElementsByName("prenom")[0].value = prenom;
            document.getElementsByName("cote")[0].value = cote;
            document.getElementsByName("titre")[0].value = titre;
            document.getElementsByName("auteur")[0].value = auteur;
            document.getElementsByName("date_reserv")[0].value = date_reserv;
            document.getElementsByName("date_retour")[0].value = date_retour;
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
