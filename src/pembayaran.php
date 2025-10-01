<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

include 'koneksi.php';

// cek id_item
$id_item = isset($_GET['id_item']) ? intval($_GET['id_item']) : 0;
if ($id_item <= 0) {
    die("Item tidak ditemukan.");
}

// ambil data item + game
$stmt = $koneksi->prepare(
    "SELECT i.id_item, i.nama_item, i.jumlah_item, i.icon_item, i.harga_item,
            g.nama_game, g.icon_game
     FROM items i
     JOIN games g ON i.id_game = g.id_game
     WHERE i.id_item = ?"
);
$stmt->bind_param("i", $id_item);
$stmt->execute();
$result = $stmt->get_result();
$item = $result->fetch_assoc();
$stmt->close();

if (!$item) {
    die("Item tidak ditemukan.");
}
?>

<div class="text-center">
    <h1 class="text-4xl font-bold text-[color:#ffed00]">Konfirmasi</h1>
    <p class="text-2xl text-[color:#ffed00]">Pesananmu</p>
</div>

<!-- Section Konfirmasi -->
<div class="w-full max-w-3xl flex flex-col md:flex-row items-center p-6 md:p-10">

    <!-- Kiri: Gambar & Harga -->
    <div class="flex flex-col items-center w-full md:w-1/2 text-center md:text-left translate-y-[-50px]">
        <img src="assets/<?= $item['icon_item']; ?>" alt="<?= $item['nama_item']; ?>" class="w-60 h-60">
        <h2 class="text-xl font-semibold"><?= $item['jumlah_item']; ?> <?= $item['nama_item']; ?></h2>
        <p class="text-lg">IDR <?= number_format($item['harga_item']); ?></p>
    </div>

    <!-- Garis pemisah -->
    <div class="hidden md:block border-l border-white mx-6 h-60"></div>

    <!-- Kanan: Informasi -->
    <div class="flex flex-col justify-center w-full md:w-1/2 text-center md:text-left translate-x-[10px]">
        <h3 class="text-4xl font-semibold mb-2">Halo <?= $_SESSION['display_name']; ?></h3><br>
        <p class="text-xl mb-24">Pastikan data dan item yang ingin kamu bayar benar yaa..</p>
        <form method="POST" action="proses_pembayaran.php" class="flex flex-col justify-center">
            <input type="hidden" name="id_item" value="<?= $item['id_item']; ?>">
            <button type="submit" class="bg-red-500 hover:bg-red-600 px-6 py-2 rounded-full text-white font-semibold transition">
                Bayar
            </button>
        </form>
    </div>
</div>