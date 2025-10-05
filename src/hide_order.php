<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

$id_order = $_POST['id_order'] ?? 0;

if ($id_order > 0) {
    $stmt = $koneksi->prepare("UPDATE orders SET is_hidden = 1 WHERE id_order = ?");
    $stmt->bind_param("i", $id_order);
    $stmt->execute();
    $stmt->close();
}

header("Location: orders.php"); // ganti sesuai nama file order usermu
exit;
