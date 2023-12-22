<?php
// Menghubungkan ke file 'connect.php' yang berisi informasi koneksi ke database
include '../components/connect.php';

// Memeriksa apakah cookie 'tutor_id' telah diatur
if(isset($_COOKIE['tutor_id'])){
   // Jika diatur, menyimpan nilai 'tutor_id' ke dalam variabel $tutor_id
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   // Jika tidak diatur, mengatur $tutor_id sebagai string kosong
   $tutor_id = '';
   // Mengarahkan pengguna ke halaman login.php jika cookie tidak diatur
   header('location:login.php');
}
// Memeriksa apakah formulir telah disubmit
if(isset($_POST['submit'])){
   // Menghasilkan ID unik menggunakan fungsi unique_id()
   $id = unique_id();

   // Mengambil dan membersihkan nilai 'status' dari formulir
   $status = $_POST['status'];
   $status = filter_var($status, 513); // 513 adalah filter untuk FILTER_SANITIZE_STRING
   
   // Mengambil dan membersihkan nilai 'title' dari formulir
   $title = $_POST['title'];
   $title = filter_var($title, 513);

   // Mengambil dan membersihkan nilai 'description' dari formulir
   $description = $_POST['description'];
   $description = filter_var($description, 513);

   // Mengambil dan membersihkan nilai 'playlist' dari formulir
   $playlist = $_POST['playlist'];
   $playlist = filter_var($playlist, 513);

   // Mengambil nama file thumbnail dari formulir
   $thumb = $_FILES['thumb']['name'];
   $thumb = filter_var($thumb, 513);
    // Mendapatkan ekstensi file thumbnail
   $thumb_ext = pathinfo($thumb, PATHINFO_EXTENSION);

   // Menamai ulang file thumbnail menggunakan ID unik dan ekstensi asli
   $rename_thumb = unique_id().'.'.$thumb_ext;

   // Mendapatkan ukuran file thumbnail
   $thumb_size = $_FILES['thumb']['size'];

   // Mendapatkan nama sementara file thumbnail
   $thumb_tmp_name = $_FILES['thumb']['tmp_name'];

   // Menentukan lokasi folder penyimpanan untuk thumbnail
   $thumb_folder = '../uploaded_files/'.$rename_thumb;

   // Mengambil nama file video dari formulir
   $video = $_FILES['video']['name'];
   $video = filter_var($video, 513);

    // Mendapatkan ekstensi file video
   $video_ext = pathinfo($video, PATHINFO_EXTENSION);

   // Menamai ulang file video menggunakan ID unik dan ekstensi asli
   $rename_video = unique_id().'.'.$video_ext;

   // Mendapatkan nama sementara file video
   $video_tmp_name = $_FILES['video']['tmp_name'];
   // Menentukan lokasi folder penyimpanan untuk video
   $video_folder = '../uploaded_files/'.$rename_video;

   // Memeriksa apakah ukuran file thumbnail melebihi batas (2MB)
   if($thumb_size > 2000000){
      // Jika melebihi, menambahkan pesan kesalahan ke dalam array
      $message[] = 'image size is too large!';
   }else{
      // Jika tidak melebihi, menyiapkan dan menjalankan kueri SQL untuk menyimpan informasi konten baru
      $add_playlist = $conn->prepare("INSERT INTO `content`(id, tutor_id, playlist_id, title, description, video, thumb, status) VALUES(?,?,?,?,?,?,?,?)");
      $add_playlist->execute([$id, $tutor_id, $playlist, $title, $description, $rename_video, $rename_thumb, $status]);
      // Memindahkan file thumbnail ke lokasi yang ditentukan
      move_uploaded_file($thumb_tmp_name, $thumb_folder);
      // Memindahkan file video ke lokasi yang ditentukan
      move_uploaded_file($video_tmp_name, $video_folder);
      // Menambahkan pesan keberhasilan ke dalam array
      $message[] = 'new course uploaded!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>
   
<section class="video-form">

   <h1 class="heading">upload content</h1>

   <!-- Formulir untuk mengunggah konten video -->
   <form action="" method="post" enctype="multipart/form-data">
      <p>video status <span>*</span></p>
      <!-- Pilihan status video (aktif atau nonaktif) -->
      <select name="status" class="box" required>
         <option value="" selected disabled>-- select status</option>
         <option value="active">active</option>
         <option value="deactive">deactive</option>
      </select>
      <p>video title <span>*</span></p>
      <!-- Kotak input untuk judul video -->
      <input type="text" name="title" maxlength="100" required placeholder="enter video title" class="box">
      <p>video description <span>*</span></p>
      <!-- Kotak input untuk deskripsi video -->
      <textarea name="description" class="box" required placeholder="write description" maxlength="1000" cols="30" rows="10"></textarea>
      <p>video playlist <span>*</span></p>
      <select name="playlist" class="box" required>
         <option value="" disabled selected>--select playlist</option>
         <?php
         // Menyiapkan dan menjalankan kueri SQL untuk mengambil daftar playlist milik tutor
         $select_playlists = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ?");
         $select_playlists->execute([$tutor_id]);
         if($select_playlists->rowCount() > 0){
            while($fetch_playlist = $select_playlists->fetch(PDO::FETCH_ASSOC)){
         ?>
         <option value="<?= $fetch_playlist['id']; ?>"><?= $fetch_playlist['title']; ?></option>
         <?php
            }
         ?>
         <?php
         }else{
             // Pesan jika tidak ada playlist yang dibuat oleh tutor
            echo '<option value="" disabled>no playlist created yet!</option>';
         }
         ?>
      </select>
      <p>select thumbnail <span>*</span></p>
      <!-- Input untuk memilih thumbnail (file gambar) -->
      <input type="file" name="thumb" accept="image/*" required class="box">
      <p>select video <span>*</span></p>
      <!-- Input untuk memilih video (file video) -->
      <input type="file" name="video" accept="video/*" required class="box">
      <!-- Tombol submit untuk mengunggah video -->
      <input type="submit" value="upload video" name="submit" class="btn">
   </form>

</section>
















<script src="../js/admin_script.js"></script>

</body>
</html>