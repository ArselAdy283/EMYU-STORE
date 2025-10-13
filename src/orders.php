<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header('Location: login.php');
    exit;
}

$id_user = $_SESSION['id_user'];

// Tentukan jenis order (default: game)
$tipe = $_GET['tipe'] ?? 'game';

if ($tipe === 'emyucoin') {
    // Ambil data order EMYUCOIN
    $stmt = $koneksi->prepare("
        SELECT o.id, o.status, o.tanggal, o.bukti_transfer,
           e.jumlah as emyu_jumlah, e.harga
        FROM orders_emyucoin o
        JOIN emyucoin e ON o.id_emyucoin = e.id_emyucoin
        WHERE o.id_user = ? AND o.is_hidden = 0
        ORDER BY o.tanggal DESC
    ");
    $stmt->bind_param("i", $id_user);
} else {
    // Ambil data order ITEM GAME
    $stmt = $koneksi->prepare("
        SELECT o.id_order, o.tanggal, o.status, o.akun_game_info, o.is_hidden,
               i.nama_item, i.jumlah_item, i.harga_item, i.icon_item,
               g.nama_game, g.icon_game
        FROM orders o
        JOIN items i ON o.id_item = i.id_item
        JOIN games g ON i.id_game = g.id_game
        WHERE o.id_user = ? AND o.is_hidden = 0
        ORDER BY o.tanggal DESC
    ");
    $stmt->bind_param("i", $id_user);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EMYUSTORE</title>
    <link href="./output.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body class="overflow-x-hidden overflow-y-scroll bg-gradient-to-tr from-[color:#ff392c] via-black to-[color:#ff392c] min-h-screen text-white">
    <?php include 'navbar.php'; ?>

    <div class="max-w-5xl mx-auto px-4 pt-8 pb-12">

        <!-- Tombol Navigasi -->
        <div class="flex justify-center gap-4 mb-6">
            <a href="?tipe=game"
                class="px-6 py-2 rounded-full font-semibold transition
                       <?= $tipe === 'game' ? 'bg-red-600 text-white' : 'bg-gray-600 hover:bg-gray-700' ?>">
                Order Game
            </a>
            <a href="?tipe=emyucoin"
                class="px-6 py-2 rounded-full font-semibold transition
                       <?= $tipe === 'emyucoin' ? 'bg-red-600 text-white' : 'bg-gray-600 hover:bg-gray-700' ?>">
                Order Emyucoin
            </a>
        </div>

        <!-- Tabel -->
        <?php if ($tipe === 'emyucoin'): ?>
            <table class="w-full text-sm text-left text-white border border-yellow-500 rounded-xl overflow-hidden">
                <thead class="bg-red-700 text-white text-center uppercase text-base">
                    <tr>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Jumlah</th>
                        <th class="px-6 py-4">Harga</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr class="odd:bg-[color:#171717] even:bg-[color:#333333] hover:bg-red-600/60 transition text-center">
                            <td class="px-6 py-6"><?= htmlspecialchars($row['tanggal']); ?></td>
                            <td class="px-6 py-6 flex item-center justify-center gap-2"><span class="text-[#ffed00]">EC</span><?= htmlspecialchars($row['emyu_jumlah']); ?></td>
                            <td class="px-6 py-6">
                                <div class="flex gap-2 justify-center text-yellow-400 font-bold">
                                    <span>IDR</span>
                                    <span><?= number_format($row['harga'], 0, ',', '.'); ?></span>
                                </div></td>
                            <td class="px-6 py-6 flex justify-center">
                                <?php if ($row['status'] === 'done'): ?>
                                    <div class="flex">
                                        <span class="bg-green-600 text-white px-3 py-1 rounded-full text-xs font-bold">done</span>
                                        <form method="post" action="hide_order.php" class="inline">
                                            <input type="hidden" name="id_order_emyucoin" value="<?= $row['id']; ?>">
                                            <button type="submit" class="ml-2 bg-gray-600 text-white px-3 py-1 rounded-full text-xs font-bold hover:bg-gray-700">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                <?php else: ?>
                                    <span class="bg-red-600 text-white px-3 py-1 rounded-full text-xs font-bold">pending</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <table class="w-full text-sm text-left text-white border border-yellow-500 rounded-xl overflow-hidden">
                <thead class="bg-red-700 text-white text-center uppercase text-base">
                    <tr>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Game</th>
                        <th class="px-4 py-3">Akun Game</th>
                        <th class="px-6 py-4">Item</th>
                        <th class="px-6 py-4">Harga</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr class="odd:bg-[color:#171717] even:bg-[color:#333333] hover:bg-red-600/60 transition">
                            <td class="px-6 py-10"><?= htmlspecialchars($row['tanggal']); ?></td>
                            <td class="px-6 py-10 font-semibold">
                                <div class="flex items-center gap-4">
                                    <img src="assets/<?= htmlspecialchars($row['icon_game']); ?>" alt="<?= htmlspecialchars($row['nama_game']); ?>" class="w-[20%] h-[20%]">
                                    <?= htmlspecialchars($row['nama_game']); ?>
                                </div>
                            </td>
                            <td class="px-6 py-10"><?= htmlspecialchars($row['akun_game_info'] ?? '-'); ?></td>
                            <td class="px-6 py-10">
                                <div class="flex items-center gap-4">
                                    <img src="assets/<?= htmlspecialchars($row['icon_item']); ?>" alt="<?= htmlspecialchars($row['nama_item']); ?>" class="w-[40%] h-[40%]">
                                    <span><?= htmlspecialchars($row['jumlah_item']); ?> <?= htmlspecialchars($row['nama_item']); ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-10 text-yellow-400 font-bold">
                                <div class="flex gap-2">
                                    <span>EC</span>
                                    <span><?= number_format($row['harga_item'], 0, ',', '.'); ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-10">
                                <?php if ($row['status'] === 'done'): ?>
                                    <div class="flex">
                                        <span class="bg-green-600 text-white px-3 py-1 rounded-full text-xs font-bold">done</span>
                                        <form method="post" action="hide_order.php" class="inline">
                                            <input type="hidden" name="id_order" value="<?= $row['id_order']; ?>">
                                            <button type="submit" class="ml-2 bg-gray-600 text-white px-3 py-1 rounded-full text-xs font-bold hover:bg-gray-700">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                <?php else: ?>
                                    <span class="bg-red-600 text-white px-3 py-1 rounded-full text-xs font-bold">pending</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <script src="script.js"></script>
</body>
</html>
