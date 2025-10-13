<?php
include "koneksi.php";
session_start();

if ($_SESSION['role'] !== 'admin') {
    die("Akses ditolak.");
}

$id_emyucoin = $_POST['id_emyucoin'] ?? null;

if (!$id_emyucoin) {
    die("Produk Emyucoin tidak valid.");
}

$id = intval($id_emyucoin);

// Pastikan data ada
$cek = $koneksi->prepare("SELECT id_emyucoin FROM emyucoin WHERE id_emyucoin = ?");
$cek->bind_param("i", $id);
$cek->execute();
$result = $cek->get_result();

if ($result->num_rows === 0) {
    die("Produk Emyucoin tidak ditemukan.");
}
$cek->close();

// Hapus data
$hapus = $koneksi->prepare("DELETE FROM emyucoin WHERE id_emyucoin = ?");
$hapus->bind_param("i", $id);
$hapus->execute();
$hapus->close();

header("Location: admin.php?page=item");
?>
