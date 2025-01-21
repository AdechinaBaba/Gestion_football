<?php
$pdo = new PDO("mysql:host=localhost;dbname=gestion_football", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$query = "SELECT * FROM ligue";
$execute = $pdo->query($query);
$ligues = $execute->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['action']) && $_POST['action'] == 'Ajouter') {
    $id_ligue = $_POST['id_ligue'];
    $libelle = $_POST['libelle_club'];
    $pays = $_POST['pays'];

    $data = [ $libelle, $pays, $id_ligue];
    $req = $pdo->prepare("INSERT INTO club (libelle_club, pays, id_ligue) VALUES (?, ?, ?)");
    $success = $req->execute($data);

    if ($success) {
        echo "Club ajouté avec succès.";
    } else {
        echo "Erreur lors de l'ajout du club.";
    }
}

if(isset($_POST["modifier"]) && $_POST["modifier"] == "Modifier"){
    $club=$_POST["id_club"];
    $ligue=$_POST["id_ligue"];
    $libelle=$_POST["libelle_club"];
    $pays= $_POST["pays"];
    
    
    $d=[$libelle,$pays,$club, $ligue];
    $req= $pdo->prepare("UPDATE club SET libelle_club = ?, pays = ?, id_ligue = ? WHERE id_club = ?");
    $sucess= $req->execute($d);
    if ($sucess) {
        echo "Club modifié avec succès.";
    } else {
        echo "Erreur lors de la modification du club.";
    }
}

if(isset($_POST["suppr"]) && $_POST["suppr"] == "Supprimer"){
    $club=$_POST["id_club"];

    $req= $pdo->prepare("DELETE FROM club WHERE id_club= ?");
    $sucess= $req->execute([$club]);

    if($sucess){
        echo "Club supprimé avec succès.";
    } else {
        echo "Erreur lors de la suppression du club.";
    }

}

$query = "SELECT * FROM club";
$execute = $pdo->query($query);
$clubs = $execute->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajout de club</title>
</head>
<body>
    <div class="container text-center">
        <h2>Ajout d'un club</h2>
        <form action="" method="post">
            <div>
                <select name="id_ligue" id="ligue" required>
                <option value="" disabled >ligue</option>

                <?php
                $requete=$pdo ->query("SELECT * FROM ligue");
                while ($rep = $requete ->fetch()){
                echo"<option value= ".$rep["id_ligue"].">" .$rep["libelle_ligue"]. "</option>";
                }
                ?>
                </select>
                <br>
                <input type="text" name="pays" id="pays" placeholder="Pays" required><br>
                <input type="text" name="libelle_club" id="libelle" placeholder="Libellé du club" required><br>
                <input type="submit" name="action" value="Ajouter">
            </div>
        </form>
    </div>
    <div>
        <h2>Modification d'un club</h2>
        <form action="" method="post">
            <div>
                <select name="id_ligue" id="ligue" required>
                    <option value=""disabled>ligue</option>
                    <?php
                    $requete=$pdo ->query("SELECT * FROM ligue");
                    while ($rep = $requete ->fetch()){
                    echo"<option value= ".$rep["id_ligue"].">" .$rep["libelle_ligue"]. "</option>";
                    }
                    ?>
                </select>
                <select name="id_club" id="club" required>
                    <option value=""disabled>Selectionner le Club</option>
                    <?php
                    $request= $pdo->query(" SELECT * FROM club");
                    while($rep=$request->fetch()){
                        echo "<option value=".$rep["id_club"].">" .$rep["libelle_club"]. "</option>";
                    }
                    ?>
                </select>
                <input type="text" id="libelle" name="libelle_club" placeholder="libelle du club" required>
                <input type="text" id="pays" name="pays" placeholder="pays" required>
                <input type="submit"  name="modifier" value="Modifier" required>
            </div>
        </form>
    </div>
    <div>
        <h2>Suppression d'un club</h2>
        <form action="" method="post">
            <div>
                <select name="id_ligue" id="ligue" >
                    <option value="" disabled>Selectionner une ligue</option>
                    <?php
                    $red= $pdo->query("SELECT * FROM ligue");
                    While($rep=$red->fetch()){
                        echo"<option value=".$rep["id_ligue"].">" .$rep["libelle_ligue"].   "</option>";
                    }
                    ?>
                </select>
                <select name="id_club" id="club" required>
                    <option value=""disabled>Selectionner le Club</option>
                    <?php
                    $request= $pdo->query(" SELECT * FROM club");
                    while($rep=$request->fetch()){
                        echo "<option value=".$rep["id_club"].">" .$rep["libelle_club"]. "</option>";
                    }
                    ?>
                </select>
                <input type="submit"  name="suppr" value="Supprimer" required>
            </div>
        </form>
    </div>
    <div>
    <h2>Liste des clubs</h2>
        <table border="1">
            <thead>
                <tr>
                    <th>Libellé</th>
                    <th>Pays</th>
                    <th>Ligue</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($clubs as $club) {
                    echo "<tr>
                        <td>".$club["libelle_club"]."</td>
                        <td>".$club["pays"]."</td>
                        <td>".$club["id_ligue"]."</td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
