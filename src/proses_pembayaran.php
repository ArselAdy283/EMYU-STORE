<?php
session_start();
include 'koneksi.php';

// cek login
if (!isset($_SESSION['id_user'])) {
    header('Location: login.php');
    exit;
}

$id_user = $_SESSION['id_user'];
$id_item = $_POST['id_item'] ?? null;

if (!$id_item) {
    die("Item tidak valid.");
}

// simpan order baru
$stmt = $koneksi->prepare("INSERT INTO orders (id_user, id_item, status) VALUES (?, ?, 'pending')");
$stmt->bind_param("ii", $id_user, $id_item);

if ($stmt->execute()) {
    header("Location: orders.php");
    exit;
} else {
    echo "Gagal menyimpan order: " . $stmt->error;
}
$stmt->close();
