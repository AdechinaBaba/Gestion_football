<?php
session_start();  // Commencer la session dès le début

$db = new PDO("mysql:host=localhost;dbname=gestion_football", "root", "");
$error = '';

if (isset($_POST['Connect'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Vérifier si l'email existe dans la base de données
    $query = $db->prepare("SELECT * FROM users WHERE email_user = ?");
    $query->execute([$email]);
    $user = $query->fetch(PDO::FETCH_ASSOC);

    // Si l'utilisateur existe
    if ($user) {
        // Vérifier si le mot de passe est correct
        if (password_verify($password, $user['mdp_user'])) {
            // Le mot de passe est correct, démarrer la session
            $_SESSION['user_id'] = $user['id_user'];
            $_SESSION['user_name'] = $user['nom_user'];
            $_SESSION['user_email'] = $user['email_user'];

            // Rediriger l'utilisateur vers une page protégée (exemple: dashboard)
            header("Location: Ajout_club.php");
            exit();
        } else {
            // Mot de passe incorrect
            $error = "Mot de passe incorrect.";
        }
    } else {
        // L'email n'existe pas dans la base de données
        $error = "Email non trouvé.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connection</title>
</head>
<body class="text-center">
    <!-- Afficher l'erreur si présente -->
    <?php if ($error): ?>
        <div style="color: red;"><?php echo $error; ?></div>
    <?php endif; ?>

    <form class="row g-3 needs-validation" method="post" novalidate>
        <div class="input-group has-validation">
            <label for="yourMail" class="form-label">Email :</label>
            <input type="email" name="email" class="form-control" id="yourMail" required>
        </div>

        <div class="col-12">
            <label for="yourPassword" class="form-label">Password :</label>
            <input type="password" name="password" class="form-control" id="yourPassword" required>
            <div class="invalid-feedback">Please enter your password!</div>
        </div>

        <div class="col-12">
            <button class="btn btn-primary w-100" type="submit" name="Connect">Login</button>
        </div>

        <div class="col-12">
            <p class="small mb-0">Don't have an account? <a href="inscription.php">Create an account</a></p>
        </div>
    </form>
</body>
</html>
