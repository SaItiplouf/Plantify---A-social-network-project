<?php
include_once('./connect.php');
$pseudorecup= $_GET['name'];
$url = 'http://localhost/profile.php?name=' . $pseudorecup;
$json_data = file_get_contents($url);

// Décoder les données JSON en tableau PHP
$data = json_decode($json_data, true);

// Récupérer le tableau posts
$posts = $data['posts'];
$user = $data['users'];

?>

<header>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./header.css" rel="stylesheet">
    <link href="https://unpkg.com/tailwindcss@1.4.6/dist/tailwind.min.css" rel="stylesheet">
    <title>profile</title>
</head>
<body>



<?php
	echo "<header>
		<div class='container'>
			<div class='profile'>
				<div class='profile-image ml-auto'>
        <img class='rounded-full w-40 h-40' src='" . (!empty($user['profilepicture']) ? "./profilepicture/{$user['profilepicture']}" : $user['defaultprofilepicture']) . "'  alt=''>
				</div>
				<div class='profile-user-settings'>
					<h1 class='profile-user-name'>". $user['pseudo'] ."</h1>

					<a href='editprofile.php'> <button class='btn profile-edit-btn'>Edit Profile</button></a>

					<button class='btn profile-settings-btn' aria-label='profile settings'><i class='fas fa-cog' aria-hidden='true'></i></button>
				</div>

				<div class='profile-bio'>
					<p><span class='profile-real-name'>" . $user['nom'] . " " . $user['prenom'] . "</span> " . $user['bio'] . "</p>
					<p>Contact: ". $user['email'] ."</p>
				</div>
			</div>
			<!-- End of profile section -->
		</div>
		<!-- End of container -->
	</header>";
?>
<div id="posts-container">

</div>
<script>
// Définir la variable pseudo
const pseudo = "<?php echo $pseudorecup; ?>";

// Appeler la fonction displayPosts() après que la page soit chargée
window.addEventListener("load", displayPosts);

async function getPosts() {
  const response = await fetch(`profile.php?name=${pseudo}`);
  if (!response.ok) {
    throw new Error(`HTTP error! status: ${response.status}`);
  }
  const data = await response.text();
  try {
    const { posts, users } = JSON.parse(data);
    return { posts, users };
  } catch (err) {
    console.error(`Error parsing JSON data: ${err}`);
    return null;
  }
}
  
async function displayPosts() {
  const postsContainer = document.getElementById("posts-container");
  const { posts, users } = await getPosts();
  for (let i = 0; i < posts.length; i++) {
    const post = posts[i];
    const postElement = document.createElement("div");
    postElement.innerHTML = `
  

      <div class="bg-white w-full rounded-md shadow-md h-auto py-3 px-3 my-5">
          <div class="w-full h-16 flex items-center flex justify-between ">
              <div class="flex">
              <a href="./showprofile.php?name=${post.userpseudo}"><img class="rounded-full w-10 h-10 mr-3" src="<?php echo (!empty($user['profilepicture']) ? "./profilepicture/{$user['profilepicture']}" : $user['defaultprofilepicture']) ?>" alt="Profile Picture"></a>
                  <div>
                      <h3 class="text-md font-semibold "><span class="author">${post.userpseudo}</span></h3>
                      <p class="text-xs text-gray-500">${post.date}</p>
                  </div>
              </div>
              <svg class="w-16" xmlns="http://www.w3.org/2000/svg" width="27" height="27" viewBox="0 0 24 24" fill="none" stroke="#b0b0b0" stroke-width="2" stroke-linecap="square" stroke-linejoin="round">
                  <circle cx="12" cy="12" r="1"></circle>
                  <circle cx="19" cy="12" r="1"></circle>
                  <circle cx="5" cy="12" r="1"></circle>
              </svg>
            

          </div>
          
          <hr>
          <!-- System Like and tools Feed -->
          <div class="flex items-center justify-between mx-4 mt-3 mb-2">
              <div class="flex gap-5">
              <div class="ceciestunbouttonlike" data-postid="${post.postid}"data-userid="${post.userid}">
              <button class="bouttonlikejs" onclick="likePost(event)">
              <svg fill="#262626" height="24" viewBox="0 0 48 48" width="24">
                        <path d="M34.6 6.1c5.7 0 10.4 5.2 10.4 11.5 0 6.8-5.9 11-11.5 16S25 41.3 24 41.9c-1.1-.7-4.7-4-9.5-8.3-5.7-5-11.5-9.2-11.5-16C3 11.3 7.7 6.1 13.4 6.1c4.2 0 6.5 2 8.1 4.3 1.9 2.6 2.2 3.9 2.5 3.9.3 0 .6-1.3 2.5-3.9 1.6-2.3 3.9-4.3 8.1-4.3m0-3c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5.6 0 1.1-.2 1.6-.5 1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z">
                      </path>
                     </svg>
                    </button> 
                    </div>           
                  <svg fill="#262626" height="24" viewBox="0 0 48 48" width="24">
                      <path clip-rule="evenodd" d="M47.5 46.1l-2.8-11c1.8-3.3 2.8-7.1 2.8-11.1C47.5 11 37 .5 24 .5S.5 11 .5 24 11 47.5 24 47.5c4 0 7.8-1 11.1-2.8l11 2.8c.8.2 1.6-.6 1.4-1.4zm-3-22.1c0 4-1 7-2.6 10-.2.4-.3.9-.2 1.4l2.1 8.4-8.3-2.1c-.5-.1-1-.1-1.4.2-1.8 1-5.2 2.6-10 2.6-11.4 0-20.6-9.2-20.6-20.5S12.7 3.5 24 3.5 44.5 12.7 44.5 24z" fill-rule="evenodd"></path>
                  </svg>
                  <svg fill="#262626" height="24" viewBox="0 0 48 48" width="24">
                      <path d="M47.8 3.8c-.3-.5-.8-.8-1.3-.8h-45C.9 3.1.3 3.5.1 4S0 5.2.4 5.7l15.9 15.6 5.5 22.6c.1.6.6 1 1.2 1.1h.2c.5 0 1-.3 1.3-.7l23.2-39c.4-.4.4-1 .1-1.5zM5.2 6.1h35.5L18 18.7 5.2 6.1zm18.7 33.6l-4.4-18.4L42.4 8.6 23.9 39.7z">
                      </path>
                  </svg>
              </div>
              <div class="flex">
                  <svg fill="#262626" height="24" viewBox="0 0 48 48" width="24">
                      <path d="M43.5 48c-.4 0-.8-.2-1.1-.4L24 29 5.6 47.6c-.4.4-1.1.6-1.6.3-.6-.2-1-.8-1-1.4v-45C3 .7 3.7 0 4.5 0h39c.8 0 1.5.7 1.5 1.5v45c0 .6-.4 1.2-.9 1.4-.2.1-.4.1-.6.1zM24 26c.8 0 1.6.3 2.2.9l15.8 16V3H6v39.9l15.8-16c.6-.6 1.4-.9 2.2-.9z">
                      </path>
                  </svg>
              </div>
          </div>
          <div class="p-2 flex flex-col space-y-3">
          <p class="break-words"> ${post.postmessage}</p>
          <br>
            </div>
          <div class="p-2 flex flex-col space-y-3">
              <div class="w-full">
              <div class=javascriptlike>
                <p class="font-bold text-sm text-gray-700">${post.likecounter} likes</p>
              </div>
              </div>
              <div class="w-full">
                  <p class="font-normal text-xs text-gray-500">10 hours ago</p>
              </div>
          </div>
          <!-- End System Like and tools Feed -->
          <div class="z-50">
              <form>
                  <div class="flex justify-between border-t items-center w-full" :class="comment ? 'absolute bottom-0' : '' ">
                      <div class="w-full ">
                          <input type="text" name="comment" id="comment" placeholder="Add A Comment..." class="w-full text-sm py-4 px-3 rounded-none focus:outline-none">
                      </div>
                      <div class="w-20">
                          <button class="border-none text-sm px-4 bg-white py-4 text-indigo-600 focus:outline-none">Post</button>
                      </div>
                  </div>
              </form>
          </div>
          <hr>
      </div>
  </div>
</div>
      `;
      postsContainer.appendChild(postElement);
    }
  }
</script>
<script src="like2.js"></script>
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
</body>
</html>
