<?php
include 'koneksi.php';
session_start();
if ($_SESSION['role'] !== 'admin') exit;

$jumlah = $_POST['jumlah'];
$harga = $_POST['harga'];

$stmt = $koneksi->prepare("INSERT INTO emyucoin (jumlah, harga) VALUES (?, ?)");
$stmt->bind_param("ii", $jumlah, $harga);
$stmt->execute();
header("Location: admin.php?page=item");
exit;
?>
