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

// cari data item & harga
$stmt = $koneksi->prepare("SELECT id_game, harga_item FROM items WHERE id_item = ?");
$stmt->bind_param("i", $id_item);
$stmt->execute();
$res = $stmt->get_result();
$itemGame = $res->fetch_assoc();
$stmt->close();

if (!$itemGame) {
    die("Item tidak ditemukan.");
}

$id_game = $itemGame['id_game'];
$harga_item = (int)$itemGame['harga_item'];

// ambil akun game user
$id_akun_game  = $_POST['id_akun_game'] ?? null;
$id_zona_game  = $_POST['id_zona_game'] ?? null;
$username_akun = $_POST['username_akun'] ?? null;

// buat akun_game_info tergantung game
$akun_game_info = null;
if ($id_game == 1) { // Mobile Legends
    $akun_game_info = $id_akun_game . " (" . $id_zona_game . ")";
} elseif ($id_game == 2) { // Efootball
    $akun_game_info = $username_akun;
} elseif ($id_game == 3) { // Free Fire
    $akun_game_info = $id_akun_game;
}

// === CEK & KURANGI SALDO EMYUCOIN ===

// ambil saldo user
$cekSaldo = $koneksi->prepare("SELECT emyucoin FROM emyucoin_user WHERE id_user = ?");
$cekSaldo->bind_param("i", $id_user);
$cekSaldo->execute();
$resSaldo = $cekSaldo->get_result();
$cekSaldo->close();

if ($resSaldo->num_rows === 0) {
    echo "<script>alert('Kamu belum memiliki saldo EMYUCOIN. Silakan top up dulu.'); history.back();</script>";
    exit;
}

$saldo = (int)$resSaldo->fetch_assoc()['emyucoin'];

if ($saldo < $harga_item) {
    echo "<script>alert('Saldo EMYUCOIN kamu tidak cukup untuk membeli item ini.'); history.back();</script>";
    exit;
}

// kurangi saldo
$saldo_baru = $saldo - $harga_item;
$updateSaldo = $koneksi->prepare("UPDATE emyucoin_user SET emyucoin = ? WHERE id_user = ?");
$updateSaldo->bind_param("ii", $saldo_baru, $id_user);
$updateSaldo->execute();
$updateSaldo->close();

// === SIMPAN ORDER ===
$stmt = $koneksi->prepare("INSERT INTO orders (id_user, id_item, akun_game_info, status, tanggal) 
                           VALUES (?, ?, ?, 'pending', NOW())");
$stmt->bind_param("iis", $id_user, $id_item, $akun_game_info);

if ($stmt->execute()) {
    header('Location: orders.php');
    exit;
} else {
    echo "Gagal menyimpan order: " . $stmt->error;
}
$stmt->close();
?>
