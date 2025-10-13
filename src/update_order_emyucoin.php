<?php
session_start();
include 'koneksi.php';

// Pastikan admin login
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
  header('Location: login.php');
  exit;
}

// Pastikan ada id_order
if (empty($_POST['id_order'])) {
  die("Data tidak lengkap.");
}

$id_order = intval($_POST['id_order']);
$filter = $_POST['filter'] ?? 'all'; // 🟡 Ambil filter dari form, default ke 'all'

// Ambil data order
$query = $koneksi->prepare("
  SELECT oe.id_user, e.jumlah AS jumlah_emyu
  FROM orders_emyucoin oe
  JOIN emyucoin e ON oe.id_emyucoin = e.id_emyucoin
  WHERE oe.id = ?
");
$query->bind_param("i", $id_order);
$query->execute();
$result = $query->get_result();

if ($result->num_rows === 0) {
  die("Order tidak ditemukan.");
}

$order = $result->fetch_assoc();
$id_user = $order['id_user'];
$jumlah_emyu = $order['jumlah_emyu'];

// Update status order menjadi done
$update = $koneksi->prepare("UPDATE orders_emyucoin SET status = 'done' WHERE id = ?");
$update->bind_param("i", $id_order);
$update->execute();

// Tambahkan emyucoin ke user
$cek = $koneksi->prepare("SELECT emyucoin FROM emyucoin_user WHERE id_user = ?");
$cek->bind_param("i", $id_user);
$cek->execute();
$hasil = $cek->get_result();

if ($hasil->num_rows > 0) {
  $row = $hasil->fetch_assoc();
  $total_emyu = $row['emyucoin'] + $jumlah_emyu;
  $updateSaldo = $koneksi->prepare("UPDATE emyucoin_user SET emyucoin = ? WHERE id_user = ?");
  $updateSaldo->bind_param("ii", $total_emyu, $id_user);
  $updateSaldo->execute();
} else {
  $insertSaldo = $koneksi->prepare("INSERT INTO emyucoin_user (id_user, emyucoin) VALUES (?, ?)");
  $insertSaldo->bind_param("ii", $id_user, $jumlah_emyu);
  $insertSaldo->execute();
}

// 🟢 Redirect kembali ke halaman sesuai filter
header("Location: admin.php?page=orders&tipe=emyucoin&filter=" . urlencode($filter));
exit;
?>
