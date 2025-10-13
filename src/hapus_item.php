<?php
include "koneksi.php";

$id_item = $_POST['id_item'] ?? null;
$id_emyucoin = $_POST['id_emyucoin'] ?? null;

if (!$id_item && !$id_emyucoin) {
    die("Item tidak valid.");
}

if ($id_item) {
    $id = intval($id_item);
    $cek = $koneksi->query("SELECT * FROM items WHERE id_item = $id");
    if ($cek->num_rows === 0) {
        die("Item tidak ditemukan.");
    }

    $koneksi->query("DELETE FROM items WHERE id_item = $id");
    header("Location: admin.php?page=item");
}
