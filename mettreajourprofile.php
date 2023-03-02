<?php
include_once('./connect.php');
session_start();
$userid = $_SESSION['id'];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pseudo = $_POST['pseudo'];
    $email = $_POST['email'];
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $password = $_POST["password"];
    $pass_crypt = md5($password);
   

  
    if (isset($_FILES['profilepicture']) && $_FILES['profilepicture']['error'] == UPLOAD_ERR_OK) {
        $profilepicture_tmp_name = $_FILES['profilepicture']['tmp_name'];
        $profilepicture_name = $_FILES['profilepicture']['name'];
        $profilepicture_destination = './profilepicture/' . $profilepicture_name;
      

        if (move_uploaded_file($profilepicture_tmp_name, $profilepicture_destination)) {
          // Le fichier a été enregistré avec succès
          $profilepicture = $profilepicture_name;
          $stmt = $db->prepare("UPDATE users SET pseudo=:pseudo, email=:email, nom=:nom, prenom=:prenom,password=:password,profilepicture=:profilepicture WHERE id=:id");
          $stmt->bindParam(':profilepicture', $profilepicture);    
            $stmt->bindParam(':pseudo', $pseudo);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':prenom', $prenom);
            $stmt->bindParam(':password', $pass_crypt );
            $stmt->bindParam(':id', $userid);
            $stmt->execute();
        } else {
          // Une erreur s'est produite lors de l'enregistrement du fichier
          echo "Une erreur s'est produite lors de l'enregistrement de l'image.";
        }
      } else {
        $stmt = $db->prepare("UPDATE users SET pseudo=:pseudo, email=:email, nom=:nom, prenom=:prenom,password=:password,  WHERE id=:id");
      }
  

   
    if ($stmt->rowCount() > 0) {
        // Mettre à jour le pseudo dans la table posts pour l'utilisateur en cours
        $stmt = $db->prepare("UPDATE posts SET userpseudo=:pseudo WHERE userid=:id");
        $stmt->bindParam(':pseudo', $pseudo);
        $stmt->bindParam(':id', $userid);
        $stmt->execute();
      
        echo "Les informations de profil ont été mises à jour avec succès.";
      } else {
        echo "Une erreur s'est produite lors de la mise à jour des informations de profil.";
      }
  }

header('location: feed.php');
  ?>