<?php
$pdo = new PDO("mysql:host=localhost;dbname=gestion_football", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (isset($_POST['inscr'])) {
    $nom = $_POST['name'];
    $prenom = $_POST['prenom'];
    $mail = $_POST['email'];
    $password = $_POST['password'];

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $table = [$nom, $prenom, $mail, $hashedPassword];
    $req = $pdo->prepare("INSERT INTO users (nom_user, prenom_user, email_user, mdp_user) VALUES (?, ?, ?, ?)");
    $success = $req->execute($table);

    if ($success) {
        echo "Compte créé avec succès!";
        // header("Location: login.php");
    } else {
        echo "Erreur lors de la création du compte.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Inscription</title>
</head>
<body>

<form class="row g-3 needs-validation" method="post" novalidate>
    <div class="col-12">
        <label for="yourName" class="form-label">Nom:</label>
        <input type="text" name="name" class="form-control" id="yourName" required>
    </div>

    <div class="col-12">
        <label for="yourLastName" class="form-label">Prenom:</label>
        <input type="text" name="prenom" class="form-control" id="yourLastName" required>
    </div>

    <div class="col-12">
        <label for="yourEmail" class="form-label"> Email:</label>
        <input type="email" name="email" class="form-control" id="yourEmail" required>
    </div>

    <div class="col-12">
        <label for="yourPassword" class="form-label">Password :</label>
        <input type="password" name="password" class="form-control" id="yourPassword" required>
        <div class="invalid-feedback">Please enter your password!</div>
    </div>

    <div class="col-12">
        <button class="btn btn-primary w-100" type="submit" name="inscr">Create Account</button>
    </div>

    <div class="col-12">
        <p class="small mb-0">Avez vous déja un compte ? <a href="Connection.php">Connection</a></p>
    </div>
</form>

</body>
</html>
