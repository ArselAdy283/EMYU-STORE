<?php
include "koneksi.php";
session_start();

if (isset($_GET['id'])) {
    $id_akun_game = intval($_GET['id']);

    // Hapus data akun_game berdasarkan id unik
    $stmt = $koneksi->prepare("DELETE FROM akun_game WHERE id_akun_game = ?");
    $stmt->bind_param("i", $id_akun_game);
    $stmt->execute();
    $stmt->close();

    // Redirect kembali ke account.php
    header("Location: account.php");
    exit;
} else {
    echo "ID akun tidak ditemukan.";
}
?>
