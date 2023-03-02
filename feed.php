<?php
session_start();
if ($_SESSION["connecter"] != "yes") {
    header("location:login.php");
    exit();
}
?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- COMPONENT -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/tailwindcss@1.2.0/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://unpkg.com/tailwindcss@1.4.6/dist/tailwind.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="./Images/favicon_canabis.png" />
    <script>
        tailwind.config = {
            darkMode: "class",
        };
    </script>
    <title>Plantify</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-white dark:bg-gray-900 h-screen overflow-x-hidden flex items-center justify-center">
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>

    <div class="bg-white dark:bg-gray-900 h-screen w-full flex flex-col px-3 lg:px-10">

        <div class="w-4/6 mx-auto">
            <form action="./process.php" method="post" enctype="multipart/form-data">
                <!-- DRAG AND DROP -->
                <div class="degageretcentrercettemerdededraganddrop">
                    <div class="bg-white w-full h-32 rounded-md shadow-md">
                        <div class="w-full h-16 flex items-center flex justify-between px-5">
<?php
  include_once("connect.php");
  $stmt = $db->prepare("SELECT profilepicture FROM users WHERE id = :userid");
  $stmt->execute(['userid' => $_SESSION["id"]]);
  $profilepicture = $stmt->fetchColumn();
  $imageSrc = ($profilepicture !== null) ? "./profilepicture/$profilepicture" : "./Images/LogoPlantify.png";
  echo '<img class="rounded-full w-10 h-10 mr-3" src="' . $imageSrc . '" alt="">';
?>

                            <input type="text" class="w-full rounded-full h-10 bg-gray-200 px-5" id="postmessage"
                                name="postmessage" placeholder="Write here (minimum 16 characters)" required
                                minlength="16">
                        </div>
                        <div class="w-full h-16 flex justify-between items-center px-3 md:px-10 lg:px-24 xl:px-5">
                            <input type="file" id="image" name="image[]" accept="image/*" multiple class="hidden"
                                onchange="previewImage(event)">
                            <label for="image"
                                class="hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded cursor-pointer">
                                <div class="flex h-full items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor"
                                        class="bi bi-link" viewBox="0 0 16 16">
                                        <path
                                            d="M6.354 5.5H4a3 3 0 0 0 0 6h3a3 3 0 0 0 2.83-4H9c-.086 0-.17.01-.25.031A2 2 0 0 1 7 10.5H4a2 2 0 1 1 0-4h1.535c.218-.376.495-.714.82-1z" />
                                        <path
                                            d="M9 5.5a3 3 0 0 0-2.83 4h1.098A2 2 0 0 1 9 6.5h3a2 2 0 1 1 0 4h-1.535a4.02 4.02 0 0 1-.82 1H12a3 3 0 1 0 0-6H9z" />
                                    </svg>
                                </div>
                            </label>
                            <button type="submit" class="hover:bg-dark-100 text-white font-bold py-2 px-4 rounded">
                                <div class="flex h-full items-center">
                                    <svg fill="#262626" height="24" viewBox="0 0 48 48" width="24">
                                        <path
                                            d="M47.8 3.8c-.3-.5-.8-.8-1.3-.8h-45C.9 3.1.3 3.5.1 4S0 5.2.4 5.7l15.9 15.6 5.5 22.6c.1.6.6 1 1.2 1.1h.2c.5 0 1-.3 1.3-.7l23.2-39c.4-.4.4-1 .1-1.5zM5.2 6.1h35.5L18 18.7 5.2 6.1zm18.7 33.6l-4.4-18.4L42.4 8.6 23.9 39.7z">
                                        </path>
                                    </svg>
                                </div>
                            </button>

                            <!-- Affichage de l'image miniature -->
                            <img id="preview" src="" alt="" style="max-width: 100px; max-height: 100px;">
                        </div>
                    </div>
                    <div id="messages"></div>
                </div>
            </form>

            <script>
            function previewImage(event) {
                // Sélectionnez l'élément de l'input file et l'image miniature
                let input = event.target;
                let preview = document.getElementById('preview');

                // Vérifiez si un fichier a été sélectionné
                if (input.files && input.files[0]) {
                    // Instancier un nouvel objet FileReader
                    let reader = new FileReader();

                    // Définir la fonction callback lorsque le fichier est lu
                    reader.onload = function(e) {
                        // Mettre à jour la source de l'image miniature avec l'URL du fichier
                        preview.src = e.target.result;
                    }

                    // Lire le fichier en tant que data URI
                    reader.readAsDataURL(input.files[0]);
                }
            }
            </script>
        </div>





        <?php
require_once('./connect.php');
global $db;
$query = $db->prepare("SELECT * FROM likes WHERE postid = :postid AND userid = :userid");
$query->bindParam(':postid', $postid);
$query->bindParam(':userid', $userid);
$query->execute();
$result = $query->fetch(PDO::FETCH_ASSOC);

if ($result) {
  // L'utilisateur a déjà aimé ce post
  $liked = true;
} else {
  // L'utilisateur n'a pas encore aimé ce post
  $liked = false;
}
?>

        <!-- Navbar -->
        <?php
    include_once('./navbar.php');
    ?>
        <script src="darkmode.js"></script>
        <script src="./main.js"></script>
        <script src="./like2.js"></script>
</body>

</html>