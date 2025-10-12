<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

include 'koneksi.php';

// Ambil data dari POST
$id_item       = $_POST['id_item'] ?? 0;
$id_akun_game  = $_POST['id_akun_game'] ?? null;
$id_zona_game  = $_POST['id_zona_game'] ?? null;
$username_akun = $_POST['username_akun'] ?? null;

if ($id_item <= 0) {
    die("Item tidak ditemukan.");
}

// Ambil data item + game
$stmt = $koneksi->prepare(
    "SELECT i.id_item, i.nama_item, i.jumlah_item, i.icon_item, i.harga_item,
            g.nama_game, g.icon_game, g.id_game
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

$id_user      = $_SESSION['id_user'];
$id_game      = $item['id_game'];
$display_name = $_SESSION['display_name'] ?? $_SESSION['username'];
?>

<div class="text-center">
    <h1 class="text-4xl font-bold text-[color:#ffed00]">Konfirmasi</h1>
    <p class="text-2xl text-[color:#ffed00]">Pesananmu</p>
</div>

<!-- Section Konfirmasi -->
<div class="w-full max-w-3xl flex flex-col translate-y-[-20px] md:flex-row items-center p-6 md:p-10">

    <!-- Kiri: Gambar & Harga -->
    <div class="flex flex-col items-center w-full md:w-1/2 text-center md:text-left translate-y-[-50px]">
        <img src="assets/<?= htmlspecialchars($item['icon_item']); ?>"
            alt="<?= htmlspecialchars($item['nama_item']); ?>"
            class="w-60 h-60">
        <h2 class="text-xl font-semibold">
            <?= htmlspecialchars($item['jumlah_item']); ?> <?= htmlspecialchars($item['nama_item']); ?>
        </h2>
        <p class="text-[#ffed00] text-lg font-semibold">IDR <?= number_format($item['harga_item'], 0, ',', '.'); ?></p>

        <div class="mt-6 text-lg bg-red-800 p-2 rounded-[5px]">
            <?php if ($id_akun_game): ?>
                <p><strong>ID Akun:</strong>
                    <?= htmlspecialchars($id_akun_game); ?>
                    <?php if (!empty($id_zona_game)): ?>
                        (<?= htmlspecialchars($id_zona_game); ?>)
                    <?php endif; ?>
                </p>
            <?php endif; ?>
            <?php if ($username_akun): ?>
                <p><strong>Username:</strong> <?= htmlspecialchars($username_akun); ?></p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Garis pemisah -->
    <div class="hidden md:block border-l border-white mx-6 h-60"></div>

    <!-- Kanan: Informasi -->
    <div class="flex flex-col justify-center w-full md:w-1/2 text-center md:text-left translate-x-[10px]">
        <h3 class="text-4xl font-semibold mb-2">Halo <?= htmlspecialchars($display_name); ?></h3><br>
        <p class="text-xl mb-15">Pastikan data dan item yang ingin kamu bayar benar yaa..</p>
        <form method="POST" action="proses_pembayaran.php" class="flex flex-col justify-center">
            <input type="hidden" name="id_item" value="<?= $item['id_item']; ?>">
            <input type="hidden" name="id_akun_game" value="<?= htmlspecialchars($id_akun_game); ?>">
            <input type="hidden" name="id_zona_game" value="<?= htmlspecialchars($id_zona_game); ?>">
            <input type="hidden" name="username_akun" value="<?= htmlspecialchars($username_akun); ?>">
            <button type="submit"
                class="bg-red-500 hover:bg-red-600 px-6 py-2 rounded-full text-white font-semibold transition">
                Bayar
            </button>
        </form>
    </div>
</div>