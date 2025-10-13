<?php
include 'koneksi.php';
session_start();
if ($_SESSION['role'] !== 'admin') exit;

$id_game = $_POST['id_game'];
$nama_item = $_POST['nama_item'];
$jumlah_item = $_POST['jumlah_item'];
$harga_item = $_POST['harga_item'];

switch ($id_game) {
  case 1: // MLBB
    $fileName = "diamondmlbb.png";
    $nama_item = "diamond";
    break;
  case 2: // eFootball
    $fileName = "coinefootball.png";
    $nama_item = "coin";
    break;
  case 3: // Free Fire
    $fileName = "diamondff.png";
    $nama_item = "diamond";
    break;
  default:
    echo "Kesalahan: ID game tidak dikenal";
    exit;
}

$stmt = $koneksi->prepare("INSERT INTO items (id_game, nama_item, jumlah_item, harga_item, icon_item) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("issis", $id_game, $nama_item, $jumlah_item, $harga_item, $fileName);
$stmt->execute();

header("Location: admin.php?page=item");
exit;
?>
