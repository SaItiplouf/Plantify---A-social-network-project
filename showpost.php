<?php
require_once('./connect.php');

function getMessages() {
    global $db;
    $resultats = $db->query("SELECT p.postmessage, u.pseudo, p.date, p.postid, p.userid, p.likecounter, u.profilepicture, u.defaultprofilepicture, pi.filename
                             FROM posts p
                             INNER JOIN users u ON p.userid = u.id
                             LEFT JOIN post_images pi ON p.postid = pi.postid
                             ORDER BY p.date DESC");
    $messages = $resultats->fetchAll(PDO::FETCH_ASSOC);
    if (!empty($messages)) {
      foreach ($messages as &$message) {
        if (isset($message['profilepicture']) && is_string($message['profilepicture'])) {
          $profilePicturePath = './profilepicture/' . $message['profilepicture'];
          if (file_exists($profilePicturePath)) {
            $profilePictureBase64 = base64_encode(file_get_contents($profilePicturePath));
            $message['profilepicture'] = "data:image/jpeg;base64,$profilePictureBase64";
          } else {
            $message['profilepicture'] = $message['defaultprofilepicture'];
          }
        } else {
          $message['profilepicture'] = $message['defaultprofilepicture'];
        }
        // Récupération de tous les noms de fichiers associés au post et stockage dans un tableau
        $imageResultats = $db->query("SELECT filename FROM post_images WHERE postid = {$message['postid']}");
        $imageFilenames = $imageResultats->fetchAll(PDO::FETCH_COLUMN);
        $message['images'] = $imageFilenames;
      }
      header('Content-Type: application/json');
      echo json_encode(['messages' => $messages]);
    }
  }
  
  getMessages();
?>
