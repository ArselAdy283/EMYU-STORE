<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

include 'koneksi.php';

// Ambil ID item hanya dari POST
$id_item = $_POST['id_item'] ?? 0;
if ($id_item <= 0) {
    die("Item tidak ditemukan.");
}

// $id_emyucoin = $_POST['id_emyucoin'] ?? null;
// if (!$id_emyucoin) {
//     echo "Emyucoin tidak ditemukan";
//     exit;
// }

// Ambil data item + game
$stmt = $koneksi->prepare("
    SELECT i.id_item, i.nama_item, i.jumlah_item, i.icon_item, i.harga_item,
           g.nama_game, g.icon_game, g.id_game
    FROM items i
    JOIN games g ON i.id_game = g.id_game
    WHERE i.id_item = ?
");
$stmt->bind_param("i", $id_item);
$stmt->execute();
$result = $stmt->get_result();
$item = $result->fetch_assoc();
$stmt->close();

if (!$item) die("Item tidak ditemukan.");
?>

<div class="text-center mb-6">
    <h1 class="text-3xl font-bold text-[#db2525]">Edit Item</h1>
    <p class="text-lg text-white"><?= htmlspecialchars($item['nama_game']); ?></p>
</div>

<div class="w-full max-w-2xl mx-auto flex flex-col items-center p-6 md:flex-row gap-6 bg-[#1f1f1f] rounded-lg">
    <!-- Gambar Item -->
    <div class="flex flex-col items-center w-full md:w-1/3 text-center">
        <img src="assets/<?= htmlspecialchars($item['icon_item']); ?>"
             alt="<?= htmlspecialchars($item['nama_item']); ?>"
             class="w-40 h-40 object-contain">
        <h2 class="text-xl font-semibold mt-3"><?= htmlspecialchars($item['nama_item']); ?></h2>
    </div>

    <!-- Form Edit -->
    <div class="flex flex-col justify-center w-full md:w-2/3">
        <form method="POST" action="proses_edit_item.php" class="flex flex-col gap-4">
            <input type="hidden" name="id_item" value="<?= $item['id_item']; ?>">

            <div>
                <label class="block text-sm font-semibold mb-1">Jumlah Item</label>
                <input type="number" name="jumlah_item" value="<?= htmlspecialchars($item['jumlah_item']); ?>"
                       class="w-full px-3 py-2 rounded bg-[#2a2a2a] text-white">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1">Harga</label>
                <input type="tel" name="harga_item" value="<?= htmlspecialchars($item['harga_item']); ?>"
                       class="w-full px-3 py-2 rounded bg-[#2a2a2a] text-white">
            </div>

            <button type="submit"
                class="mt-4 bg-[#db2525] hover:bg-red-700 px-6 py-2 rounded-full text-black font-semibold transition">
                Simpan Perubahan
            </button>
        </form>

        <!-- Tombol Hapus -->
        <form method="POST" action="hapus_item.php" class="mt-4">
            <input type="hidden" name="id_item" value="<?= $item['id_item']; ?>">
            <button type="submit"
                class="mt-4 bg-[#db2525] hover:bg-red-700 px-[150px] py-2 rounded-full text-black font-semibold transition">
                Hapus Item
            </button>
        </form>
    </div>
</div>
