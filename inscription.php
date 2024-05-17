<?php

class GestionUtilisateurs
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

    public function showErrorMessage($message)
    {
        echo '<div style="color: red;">' . $message . '</div>';
    }

    public function showSuccessMessage($message)
    {
        echo '<div style="color: green;">' . $message . '</div>';
    }

    public function checkUserExistence($email)
    {
        $email = $this->sanitizeInput($email);

        $sql = "SELECT * FROM utilisateurs WHERE email='$email'";
        $result = $this->conn->query($sql);

        return $result->num_rows > 0;
    }

    public function loginUser($email, $mdp)
    {
        $email = $this->sanitizeInput($email);
        $mdp = $this->sanitizeInput($mdp);

        $sql = "SELECT * FROM utilisateurs WHERE email='$email' AND mdp='$mdp'";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            $this->showSuccessMessage("Connexion réussie !");
        } else {
            $this->showErrorMessage("Identifiants incorrects. Veuillez réessayer.");
        }
    }

    public function registerUser($nom, $prenom, $email, $mdp)
    {
        $nom = $this->sanitizeInput($nom);
        $prenom = $this->sanitizeInput($prenom);
        $email = $this->sanitizeInput($email);
        $mdp = $this->sanitizeInput($mdp);

        // Vérifier que les champs obligatoires ne sont pas vides
        if (empty($nom) || empty($prenom) || empty($email) || empty($mdp)) {
            $this->showErrorMessage("Tous les champs sont obligatoires.");
            return;
        }

        // Vérifier si l'utilisateur existe déjà
        if ($this->checkUserExistence($email)) {
            $this->showErrorMessage("Un compte avec cet email existe déjà. Veuillez vous connecter.");
            return;
        }

        // Insérer le nouvel utilisateur dans la base de données
        $mdp = password($mdp,);
        $sql = "INSERT INTO utilisateurs (nom, prenom, email, mdp) VALUES ('$nom', '$prenom', '$email', '$mdp')";

        if ($this->conn->query($sql) === TRUE) {
            $this->showSuccessMessage("Compte créé avec succès.");
        } else {
            $this->showErrorMessage("Erreur lors de la création du compte : " . $this->conn->error);
        }
    }
}

// Vérifier si des données ont été soumises pour la connexion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["connexion"])) {
    // Contrôles côté serveur pour s'assurer que les champs obligatoires ne sont pas vides
    if (empty($_POST["email"]) || empty($_POST["mdp"])) {
        $gestionUtilisateurs = new GestionUtilisateurs("localhost", "root", "", "bibliotheque");
        $gestionUtilisateurs->showErrorMessage("Les champs Email et Mot de passe sont obligatoires.");
    } else {
        $gestionUtilisateurs = new GestionUtilisateurs("localhost", "root", "", "bibliotheque");
        $gestionUtilisateurs->loginUser($_POST["email"], $_POST["mdp"]);
    }
}

// Vérifier si des données ont été soumises pour l'inscription
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["inscription"])) {
    // Créer une instance de la classe GestionUtilisateurs
    $gestionUtilisateurs = new GestionUtilisateurs("localhost", "root", "", "bibliotheque");

    // Traiter l'inscription
    $gestionUtilisateurs->registerUser($_POST["nom"], $_POST["prenom"], $_POST["email"], $_POST["mdp"]);
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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>

 <table align="center">
        <tr>
            <td>
            <img src="logo/poi.png" width="60"   height="60" >
            </td><br>
            <td><h1   class="fui">LA PLUME QUI ECRIT</h1></td>
        </tr>
    </table><br><br>


<body style="background-color: antiquewhite;" class="hold-transition register-page">
    <div style="background-color:antiquewhite;" class="card card-outline card-primary">
        <div class="card-header text-center">
            <b>INSCRIPTION</b>
        </div>
        <div style="background-color:antiquewhite; color: black;" class="card">


            <div style="background-color:antiquewhite; color: black;" class="card-body register-card-body">
                <p class="login-box-msg" style="color:black;">Enregistrer un nouveau membre</p>

                <!-- Formulaire d'inscription -->
                <form style="background-color: wheat;" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="input-group mb-3">
                        <label for="nom">Nom:</label>
                        <input type="text" class="form-control" name="nom" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <label for="prenom">Prénom:</label>
                        <input type="text" class="form-control" name="prenom" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" name="email" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <label for="mdp">Mot de passe:</label>
                        <input type="password" class="form-control" name="mdp" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" name="inscription">Enregistrer</button>
                </form><br>

                <p style="text-align: center;">Vous avez un compte ? <a href="connexion.php"><button type="button" class="btn btn-primary">Connectez-vous</button></a></p><br>

                <footer align="center" ><a href="acceuil.html"><button type="submit" class="btn btn-primary" >Accueil</button></a></footer>

                
            </div>
        </div>
        <script src="plugins/jquery/jquery.min.js"></script>
        <!-- Bootstrap 4 -->
        <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- AdminLTE App -->
        <script src="dist/js/adminlte.min.js"></script>
</body>

</html>
