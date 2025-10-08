<?php
include 'koneksi.php';
session_start();

if ($_SESSION['role'] !== 'admin') {
  http_response_code(403);
  exit;
}

$id = intval($_POST['id'] ?? 0);
if ($id > 0) {
  $stmt = $koneksi->prepare("DELETE FROM inbox WHERE id_inbox = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $stmt->close();
}
?>
