<?php

// Include file untuk koneksi database
include 'components/connect.php';

// Identifikasi pengguna menggunakan cookie 'user_id'
if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

// Kueri database untuk menghitung jumlah likes, comments, dan bookmarked items
$select_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ?");
$select_likes->execute([$user_id]);
$total_likes = $select_likes->rowCount();

$select_comments = $conn->prepare("SELECT * FROM `comments` WHERE user_id = ?");
$select_comments->execute([$user_id]);
$total_comments = $select_comments->rowCount();

$select_bookmark = $conn->prepare("SELECT * FROM `bookmark` WHERE user_id = ?");
$select_bookmark->execute([$user_id]);
$total_bookmarked = $select_bookmark->rowCount();

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>home</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- HERO SECTION START  -->
<main>
   <div class="container">
      <img src="images/hero.png" alt="">
      <div class="hero-text">
         <h1>The Ultimate Platform for Learning Applications and Coding.</h1>
         <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Impedit ratione mollitia saepe iure, veniam alias assumenda omnis!</p>
         <a href="courses.php" class="inline-btn">Mulai Belajar!</a>
      </div>
   </div>
</main>

<!-- HERO SECTION START  -->

<!-- quick select section starts  -->
<!-- Bagian Quick Select: Menampilkan opsi-opsi cepat berdasarkan status pengguna -->
<section class="quick-select">

   <h1 class="heading">quick options</h1>

    <!-- Kontainer untuk kotak-kotak opsi cepat -->
   <div class="box-container">

      <?php
       // Kondisi untuk mengecek apakah pengguna sudah login
         if($user_id != ''){
      ?>

      <!-- Kotak untuk menampilkan jumlah likes, comments, dan bookmarked items -->
      <div class="box">
         <h3 class="title">likes and comments</h3>
         <p>total likes : <span><?= $total_likes; ?></span></p>
         <a href="likes.php" class="inline-btn">view likes</a>
         <p>total comments : <span><?= $total_comments; ?></span></p>
         <a href="comments.php" class="inline-btn">view comments</a>
         <p>saved playlist : <span><?= $total_bookmarked; ?></span></p>
         <a href="bookmark.php" class="inline-btn">view bookmark</a>
      </div>
      <?php
         }else{ 
      ?>
      <!-- Kotak untuk pengguna yang belum login -->
      <div class="box" style="text-align: center;">
         <h3 class="title">please login or register</h3>
          <div class="flex-btn" style="padding-top: .5rem;">
            <a href="login.php" class="option-btn">login</a>
            <a href="register.php" class="option-btn">register</a>
         </div>
      </div>
      <?php
      }
      ?>
      
      <div class="box">
         <!-- Kotak-kotak untuk kategori, topik populer, dan menjadi tutor -->
         <h3 class="title">top categories</h3>
         <div class="flex">
            <a href="#"><i class="fas fa-code"></i><span>Development</span></a>
            <a href="#"><i class="fas fa-pen-nib"></i><span>Design</span></a>
            <a href="#"><i class="fas fa-camera"></i><span>Photo & Video Editing</span></a>
            <a href="#"><i class="fas fa-unity"></i><span>3D Modeling</span></a>
   
         </div>
      </div>

      <div class="box">
         <h3 class="title">popular topics</h3>
         <div class="flex">
            <a href="#"><i class="fab fa-unity"></i><span>Unity</span></a>
            <a href="#"><i class="fab fa-cubes"></i><span>Blender</span></a>
            <a href="#"><i class="fab fa-c"></i><span>Capcut</span></a>
            <a href="#"><i class="fas fa-paintbrush"></i><span>Gimp</span></a>
            <a href="#"><i class="fas fa-pen-fancy"></i><span>Photoshop</span></a>
            <a href="#"><i class="fas fa-video"></i><span>Premier Pro</span></a>
            <a href="#"><i class="fab fa-figma"></i><span>Figma</span></a>
         </div>
      </div>

      <div class="box tutor">
         <h3 class="title">become a tutor</h3>
         <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsa, laudantium.</p>
         <a href="admin/register.php" class="inline-btn">get started</a>
      </div>

   </div>

</section>

<!-- quick select section ends -->

<!-- courses section starts  -->
<!-- Bagian Courses: Menampilkan kursus-kursus terbaru -->
<section class="courses">

   <h1 class="heading">latest courses</h1>
       <!-- Kontainer untuk kotak-kotak kursus -->
   <div class="box-container">

      <?php
          // Mempersiapkan dan menjalankan kueri SQL untuk mendapatkan kursus terbaru
         $select_courses = $conn->prepare("SELECT * FROM `playlist` WHERE status = ? ORDER BY date DESC LIMIT 6");
         $select_courses->execute(['active']);
         // Memeriksa apakah terdapat kursus yang ditemukan
         if($select_courses->rowCount() > 0){
            // Loop untuk setiap kursus yang ditemukan
            while($fetch_course = $select_courses->fetch(PDO::FETCH_ASSOC)){
               // Mendapatkan ID kursus
               $course_id = $fetch_course['id'];

               // Mempersiapkan dan menjalankan kueri SQL untuk mendapatkan informasi tutor
               $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE id = ?");
               $select_tutor->execute([$fetch_course['tutor_id']]);
               $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);
      ?>
      
      <!-- Bagian HTML untuk Menampilkan Setiap Kursus dalam box -->
      <div class="box">
         <div class="tutor">
            <img src="uploaded_files/<?= $fetch_tutor['image']; ?>" alt="">
            <div>
               <h3><?= $fetch_tutor['name']; ?></h3>
               <span><?= $fetch_course['date']; ?></span>
            </div>
         </div>
         <img src="uploaded_files/<?= $fetch_course['thumb']; ?>" class="thumb" alt="">
         <h3 class="title"><?= $fetch_course['title']; ?></h3>
         <a href="playlist.php?get_id=<?= $course_id; ?>" class="inline-btn">view playlist</a>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">no courses added yet!</p>';
      }
      ?>

   </div>

   <div class="more-btn">
      <a href="courses.php" class="inline-option-btn">view more</a>
   </div>

</section>

<!-- courses section ends -->


<!-- footer section starts  -->
<?php include 'components/footer.php'; ?>
<!-- footer section ends -->

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>