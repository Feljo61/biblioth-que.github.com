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

    public function loginUser($email, $mdp)
    {
        $email = $this->sanitizeInput($email);
        $mdp = $this->sanitizeInput($mdp);

        $sql = "SELECT * FROM utilisateurs WHERE email='$email' AND mdp='$mdp'";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            // Utilisateur authentifié, rediriger vers la page d'accueil
            header("Location: acceuil.html");
            exit();
        } else {
            $this->showErrorMessage("Adresse e-mail ou mot de passe incorrect.");
        }
    }
}

// Vérifier si des données ont été soumises
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Contrôles côté serveur pour s'assurer que les champs obligatoires ne sont pas vides
    if (empty($_POST["email"]) || empty($_POST["mdp"])) {
        $gestionUtilisateurs = new GestionUtilisateurs("localhost", "root", "", "bibliotheque");
        $gestionUtilisateurs->showErrorMessage("Les champs Adresse e-mail et Mot de passe sont obligatoires.");
    } else {
        // Créer une instance de la classe GestionUtilisateurs
        $gestionUtilisateurs = new GestionUtilisateurs("localhost", "root", "", "bibliotheque");

        // Appeler la fonction de connexion de l'utilisateur
        $gestionUtilisateurs->loginUser($_POST["email"], $_POST["mdp"]);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>

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


<body style="background-color: antiquewhite;" class="hold-transition login-page">
    <div class="login-box">
        <!-- Formulaire de connexion -->
        <div style="background-color:antiquewhite;" class="card card-outline card-primary">
            <div class="card-header text-center">
                <b>Connexion</b>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Entrer vos informations</p>
                <form  style="background-color : wheat;"  method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="text-center">
                    <div class="input-group mb-3">
                        <label for="email">Adresse e-mail:</label>
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
                    <!-- Bouton de connexion -->
                    <button type="submit" class="btn btn-primary">Se connecter</button><br><br>
                    </div>

                </form>
                <!-- Lien vers la page d'inscription -->
                <p style="text-align: center;" >Vous n'avez pas de compte ? <a href="inscription.php"><button class="btn btn-primary">S'inscrire</button></a></p>
                <footer><a href="acceuil.html"></a></footer>
            </div>
        </div>
    </div>

    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
</body>

</html>
