<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

$id_order = $_POST['id_order'] ?? 0;
$id_order_emyucoin = $_POST['id_order_emyucoin'] ?? 0;

if ($id_order > 0) {
    // sembunyikan order game
    $stmt = $koneksi->prepare("UPDATE orders SET is_hidden = 1 WHERE id_order = ?");
    $stmt->bind_param("i", $id_order);
    $stmt->execute();
    $stmt->close();
} elseif ($id_order_emyucoin > 0) {
    // sembunyikan order emyucoin
    $stmt = $koneksi->prepare("UPDATE orders_emyucoin SET is_hidden = 1 WHERE id = ?");
    $stmt->bind_param("i", $id_order_emyucoin);
    $stmt->execute();
    $stmt->close();
}

header("Location: orders.php?tipe=" . ($_POST['id_order_emyucoin'] ? 'emyucoin' : 'game'));
exit;
