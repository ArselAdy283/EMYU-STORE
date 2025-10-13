<?php
session_start();
if ($_SESSION['role'] !== 'admin') exit;

include 'koneksi.php';

$id_emyucoin = $_POST['id_emyucoin'] ?? 0;
$jumlah = $_POST['jumlah'] ?? null;
$harga = $_POST['harga'] ?? null;

if ($id_emyucoin <= 0 || !$jumlah || !$harga) {
    die("Data tidak lengkap.");
}

$stmt = $koneksi->prepare("UPDATE emyucoin SET jumlah = ?, harga = ? WHERE id_emyucoin = ?");
$stmt->bind_param("dii", $jumlah, $harga, $id_emyucoin);
$stmt->execute();

header("Location: admin.php?page=emyucoin");
exit;
?>
