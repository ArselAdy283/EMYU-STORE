<?php
session_start();
include 'koneksi.php';

$id_item = $_POST['id_item'] ?? 0;
$jumlah_item = $_POST['jumlah_item'] ?? 0;
$harga_item = $_POST['harga_item'] ?? 0;

if ($id_item > 0) {
    $stmt = $koneksi->prepare("UPDATE items SET jumlah_item = ?, harga_item = ? WHERE id_item = ?");
    $stmt->bind_param("iii", $jumlah_item, $harga_item, $id_item);
    $stmt->execute();
    $stmt->close();
    header("Location: admin.php?page=item&success=1");
    exit;
} else {
    die("Data tidak valid.");
}
