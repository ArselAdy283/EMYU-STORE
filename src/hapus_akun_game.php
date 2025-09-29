<?php
include "koneksi.php";
session_start();

if (isset($_GET['id'])) {
    $id_akun_game = intval($_GET['id']);

    $stmt = $koneksi->prepare("DELETE FROM akun_game WHERE id_akun_game = ?");
    $stmt->bind_param("i", $id_akun_game);
    $stmt->execute();
    $stmt->close();

    header("Location: account.php");
    exit;
} else {
    echo "ID akun tidak ditemukan.";
}
?>
