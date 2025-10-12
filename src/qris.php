<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

include 'koneksi.php';

// Ambil data item + game
$query = $koneksi->query("SELECT * FROM emyucoin ORDER BY jumlah ASC");
$paket = $query->fetch_all(MYSQLI_ASSOC);
?>

<div class="flex text-center translate-y-[50px] gap-10">
    <img src="assets/qris.png" alt="qris" class="w-[250px] h-[350px]">
    <p class="text-3xl font-extrabold translate-y-[50px]">Scan QRIS ini untuk membayar</p>
</div>
<div>
    <form method="POST" action="proses_bukti.php" enctype="multipart/form-data" class="flex items-center translate-x-[400px]">
        <!-- Input file disembunyikan -->
        <input type="file" id="bukti_transfer" name="bukti_transfer" accept="image/*" class="hidden" onchange="previewBukti(this)">

        <!-- Tombol seperti 'Bayar' -->
        <label for="bukti_transfer"
            class="cursor-pointer bg-red-500 hover:bg-red-600 px-6 py-2 rounded-full text-white font-semibold transition">
            Bukti Transfer
        </label>

        <!-- Preview gambar (opsional) -->
        <img id="preview" src="" alt="" class="hidden w-[200px] rounded-lg shadow-md">
    </form>
</div>