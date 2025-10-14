<?php
include 'koneksi.php';
$id = $_POST['id'] ?? 0;

if ($id) {
  $koneksi->query("DELETE FROM users WHERE id_user = $id");
  $koneksi->query("DELETE FROM emyucoin_user WHERE id_user = $id");
  header("location: admin.php?page=user");
} else {
  echo "ID tidak valid!";
}
?>
