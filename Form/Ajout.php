<?php
$pdo = new PDO("mysql:host=localhost;dbname=gestion_football", "root", "");

// Gestion de l'ajout d'un joueur
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $uploadDir = 'uploads/';
    $response = ["status" => false, "message" => ""];

    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $file = $_FILES['file'];
    $photo = $uploadDir . basename($file['name']);
    if (move_uploaded_file($file['tmp_name'], $photo)) {
        $stmt = $pdo->prepare("INSERT INTO joueur(nom_joueur, prenom_joueur, photo_joueur, id_club) VALUES(?, ?, ?, ?)");
        $data = [$_POST['name'], $_POST['prenom'], $photo, $_POST['id_club']];
        $response["status"] = $stmt->execute($data);
        $response["message"] = $response["status"] ? "Joueur ajouté avec succès." : "Erreur d'insertion.";
    } else {
        $response["message"] = "Téléversement échoué.";
    }
    echo json_encode($response);
    exit;
}

// Récupération des joueurs
$joueurs = $pdo->query("
    SELECT joueur.id_joueur, joueur.photo_joueur, joueur.nom_joueur, joueur.prenom_joueur, club.libelle_club
    FROM joueur
    JOIN club ON joueur.id_club = club.id_club
")->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ajouter et lister les joueurs</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
  <h1>Ajouter un joueur</h1>
  <form id="playerForm" enctype="multipart/form-data" method="post">
    <input type="text" name="name" placeholder="Nom" required>
    <input type="text" name="prenom" placeholder="Prénom" required>
    <input type="file" name="file" required>
    <select name="id_club" required>
      <option value="">Choisir un club</option>
      <?php
      $clubs = $pdo->query("SELECT * FROM club");
      while ($club = $clubs->fetch()) {
          echo "<option value='{$club['id_club']}'>{$club['libelle_club']}</option>";
      }
      ?>
    </select>
    <button type="submit">Ajouter</button>
  </form>

  <p id="responseMessage"></p>

  <h1>Liste des joueurs</h1>
  <table border="1">
    <thead>
      <tr>
        <th>ID</th>
        <th>Photo</th>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Club</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($joueurs as $joueur): ?>
        <tr>
          <td><?= $joueur['id_joueur'] ?></td>
          <td><img src="<?= $joueur['photo_joueur'] ?>" alt="Photo" width="60"></td>
          <td><?= $joueur['nom_joueur'] ?></td>
          <td><?= $joueur['prenom_joueur'] ?></td>
          <td><?= $joueur['libelle_club'] ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <script>
    $("#playerForm").on("submit", function (e) {
      e.preventDefault();
      let formData = new FormData(this);

      $.ajax({
        url: '', // Le fichier PHP actuel
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
          let res = JSON.parse(response);
          $("#responseMessage").text(res.message);
          if (res.status) {
            $("#playerForm")[0].reset();
            location.reload(); // Recharge la page pour afficher la mise à jour
          }
        },
        error: function () {
          $("#responseMessage").text("Une erreur est survenue.");
        }
      });
    });
  </script>
</body>

