<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = intval($_POST['id_order']);
  if ($id > 0) {
    $koneksi->query("DELETE FROM orders_emyucoin WHERE id = $id");
  }
}
header('Location: admin.php?page=orders&tipe=emyucoin');
exit;
?>
