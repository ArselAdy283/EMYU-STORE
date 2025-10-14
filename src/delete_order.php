<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = intval($_POST['id_order']);
  if ($id > 0) {
    $koneksi->query("DELETE FROM orders WHERE id_order = $id");
  }
}
header('Location: admin.php?page=orders&tipe=game');
exit;
?>
