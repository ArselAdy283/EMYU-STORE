<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header('Location: login.php');
    exit;
}

$id_user = $_SESSION['id_user'];
$id_emyucoin = $_POST['id_emyucoin'] ?? null;

if (!$id_emyucoin) {
    die("Paket tidak valid.");
}

if (!empty($_FILES['bukti_transfer']['tmp_name'])) {
    $namaFile = time() . "_" . basename($_FILES['bukti_transfer']['name']);
    $tujuan = "bukti_transfer/" . $namaFile;
    move_uploaded_file($_FILES['bukti_transfer']['tmp_name'], $tujuan);

    // Simpan ke tabel orders_emyucoin
    $stmt = $koneksi->prepare("
        INSERT INTO orders_emyucoin (id_user, id_emyucoin, tanggal, status, bukti_transfer)
        VALUES (?, ?, NOW(), 'pending', ?)
    ");
    $stmt->bind_param("iis", $id_user, $id_emyucoin, $namaFile);
    $stmt->execute();
}

header("Location: orders.php?tipe=emyucoin");
exit;
