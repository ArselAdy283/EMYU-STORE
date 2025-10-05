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

// cari id_game dari item
$stmt = $koneksi->prepare("SELECT id_game FROM items WHERE id_item = ?");
$stmt->bind_param("i", $id_item);
$stmt->execute();
$res = $stmt->get_result();
$itemGame = $res->fetch_assoc();
$id_game = $itemGame['id_game'] ?? null;
$stmt->close();

if (!$id_game) {
    die("Game tidak ditemukan untuk item ini.");
}

// ambil akun game user
$id_item = $_POST['id_item'] ?? null;
$id_akun_game = $_POST['id_akun_game'] ?? null;
$id_zona_game = $_POST['id_zona_game'] ?? null;
$username_akun = $_POST['username_akun'] ?? null;

$akun_game_info = null;
if ($id_game == 1) { // Mobile Legends
    $akun_game_info = $id_akun_game . " (" . $id_zona_game . ")";
} elseif ($id_game == 2) { // Efootball
    $akun_game_info = $username_akun;
} elseif ($id_game == 3) { // Free Fire
    $akun_game_info = $id_akun_game;
}


// simpan order baru
$stmt = $koneksi->prepare("INSERT INTO orders (id_user, id_item, akun_game_info, status, tanggal) 
                           VALUES (?, ?, ?, 'pending', NOW())");
$stmt->bind_param("iis", $id_user, $id_item, $akun_game_info);

if ($stmt->execute()) {
    header("Location: orders.php");
    exit;
} else {
    echo "Gagal menyimpan order: " . $stmt->error;
}
$stmt->close();
