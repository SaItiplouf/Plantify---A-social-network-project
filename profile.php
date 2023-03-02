<?php
$pseudo = $_GET['name'];
require('connect.php');

// Requête SQL pour récupérer les informations de l'utilisateur
$sql = "SELECT nom, prenom, email, pseudo, id, profilepicture, defaultprofilepicture, bio FROM users WHERE pseudo = :pseudo";
$stmt = $db->prepare($sql);
$stmt->bindParam(':pseudo', $pseudo);
$stmt->execute();
$user = $stmt->fetch();

// Requête SQL pour récupérer les posts de l'utilisateur avec leurs images
$sql = "SELECT posts.*, post_images.filename, post_images.img_extension FROM posts LEFT JOIN post_images ON posts.postid = post_images.postid WHERE userpseudo = :pseudo ORDER BY date DESC";
$stmt = $db->prepare($sql);
$stmt->bindParam(':pseudo', $pseudo);
$stmt->execute();
$posts = $stmt->fetchAll();

// Stocker les informations de l'utilisateur et les posts dans un tableau
$data = [
  'posts' => $posts,
  'users' => $user
];

$json = json_encode($data);

header('Content-Type: application/json');
echo $json;
?>
