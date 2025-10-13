<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

include 'koneksi.php';

// Ambil ID dari POST (bukan GET)
$id_emyucoin = $_POST['id_emyucoin'] ?? 0;
if ($id_emyucoin <= 0) {
    die("Produk Emyucoin tidak ditemukan.");
}

// Ambil data emyucoin
$stmt = $koneksi->prepare("SELECT * FROM emyucoin WHERE id_emyucoin = ?");
$stmt->bind_param("i", $id_emyucoin);
$stmt->execute();
$result = $stmt->get_result();
$emyucoin = $result->fetch_assoc();
$stmt->close();

if (!$emyucoin) die("Data tidak ditemukan.");
?>

<!-- Popup Edit Emyucoin -->
<div class="text-center mb-6">
    <h1 class="text-3xl font-bold text-[#db2525]">Edit Produk Emyucoin</h1>
</div>

<div class="w-full max-w-2xl mx-auto flex flex-col items-center p-6 md:flex-row gap-6 bg-[#1f1f1f] rounded-lg">
    <!-- Gambar Coin -->
    <div class="flex flex-col items-center w-full md:w-1/3 text-center">
        <img src="assets/coins.svg" alt="Emyucoin" class="w-40 h-40 object-contain invert">
        <h2 class="text-xl font-semibold mt-3 text-yellow-400">Emyucoin</h2>
    </div>

    <!-- Form Edit -->
    <div class="flex flex-col justify-center w-full md:w-2/3">
        <form method="POST" action="proses_edit_emyucoin.php" class="flex flex-col gap-4">
            <input type="hidden" name="id_emyucoin" value="<?= htmlspecialchars($emyucoin['id_emyucoin']); ?>">

            <div>
                <label class="block text-sm font-semibold mb-1 text-white">Jumlah Emyucoin</label>
                <input type="number" name="jumlah" value="<?= htmlspecialchars($emyucoin['jumlah']); ?>"
                       class="w-full px-3 py-2 rounded bg-[#2a2a2a] text-white">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1 text-white">Harga (IDR)</label>
                <input type="number" name="harga" value="<?= htmlspecialchars($emyucoin['harga']); ?>"
                       class="w-full px-3 py-2 rounded bg-[#2a2a2a] text-white">
            </div>

            <button type="submit"
                class="mt-4 bg-[#db2525] hover:bg-red-700 px-6 py-2 rounded-full text-black font-semibold transition">
                Simpan Perubahan
            </button>
        </form>

        <!-- Tombol Hapus -->
        <form method="POST" action="hapus_emyucoin.php" class="mt-4">
            <input type="hidden" name="id_emyucoin" value="<?= htmlspecialchars($emyucoin['id_emyucoin']); ?>">
            <button type="submit"
                class="mt-4 bg-[#db2525] hover:bg-red-700 px-[150px] py-2 rounded-full text-black font-semibold transition">
                Hapus Item
            </button>
        </form>
    </div>
</div>
