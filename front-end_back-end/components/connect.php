<?php

// Informasi Koneksi Database
$db_name = 'mysql:host=localhost;dbname=course_db';
$user_name = 'root';
$user_password = '';

// Membuat Objek Koneksi Database menggunakan PDO
$conn = new PDO($db_name, $user_name, $user_password);

// Fungsi Pembuatan ID Unik
function unique_id() {
   // Karakter yang mungkin digunakan untuk ID unik
   $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
   $rand = array();
   $length = strlen($str) - 1;

   // Membangun ID unik sepanjang 20 karakter
   for ($i = 0; $i < 20; $i++) {
       $n = mt_rand(0, $length);
       $rand[] = $str[$n];
   }

   // Menggabungkan karakter-karakter acak menjadi sebuah string
   return implode($rand);
}

?>
