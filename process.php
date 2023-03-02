<?php
session_start();

// Vérification que l'utilisateur est connecté
if (!isset($_SESSION['pseudo'])) {
    header('Location: login.php');
    exit();
}

// Récupération des données du formulaire
$postmessage = $_POST['postmessage'];
$userpseudo = $_SESSION['pseudo'];
$userid = $_SESSION['id'];

// Connexion à la base de données
require_once('./connect.php');

// Requête pour insérer les données dans la table "posts"
$sql = "INSERT INTO posts (postmessage, userpseudo, userid, date) VALUES (:postmessage, :userpseudo, :userid, NOW())";
$stmt = $db->prepare($sql);
$stmt->bindParam(':postmessage', $postmessage);
$stmt->bindParam(':userid', $userid);
$stmt->bindParam(':userpseudo', $userpseudo, PDO::PARAM_STR);
$stmt->execute();

// Récupération de l'ID du post que l'on vient d'insérer
$postid = $db->lastInsertId();

// Traitement des images
if (!empty($_FILES['image'])) {
    $errors = array();
    $count = 0; // compteur d'images
    // Parcours de toutes les images envoyées
    foreach ($_FILES['image']['tmp_name'] as $key => $tmp_name) {
        // Vérification des erreurs lors de l'envoi
        if ($_FILES['image']['error'][$key] != 0) {
            $errors[] = "Erreur lors de l'envoi de l'image : ".$_FILES['image']['name'][$key];
            continue;
        }

        // Vérification du type de fichier
        $file_type = strtolower(pathinfo($_FILES['image']['name'][$key], PATHINFO_EXTENSION));
        if (!in_array($file_type, array('jpg', 'jpeg', 'png', 'gif'))) {
            $errors[] = "Le format de fichier ".$_FILES['image']['name'][$key]." n'est pas autorisé.";
            continue;
        }
        // Sortie de la boucle si 4 images ont déjà été insérées dans la base de données
        if ($count >= 4) {
            break;
        }
// Déplacement de l'image dans le dossier d'upload
$upload_dir = './upload/';
$upload_file = $upload_dir.$postid.'_'.$key.'.'.$file_type;
if (!move_uploaded_file($tmp_name, $upload_file)) {
    $errors[] = "Erreur lors du déplacement de l'image : ".$_FILES['image']['name'][$key];
    continue;
}

// Récupération du nom du fichier
$filename = $postid.'_'.$key.'.'.$file_type;

// Insertion de l'image dans la base de données
$sql = "INSERT INTO post_images (postid, img_data, img_extension, filename) VALUES (:postid, :img_data, :img_extension, :filename)";
$stmt = $db->prepare($sql);
$img_data = '';
$img_extension = $file_type;
$stmt->bindParam(':postid', $postid);
$stmt->bindParam(':img_data', $img_data);
$stmt->bindParam(':img_extension', $img_extension);
$stmt->bindParam(':filename', $filename);
$stmt->execute();
    
$count++; // incrémenter le compteur d'images
}

    // Affichage des erreurs s'il y en a
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo $error.'<br>';
        }
    }
}

// Redirection vers une page de confirmation
header('Location: ./feed.php');
exit();
?>