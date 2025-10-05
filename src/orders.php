<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header('Location: login.php');
    exit;
}

$id_user = $_SESSION['id_user'];

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

<body class="bg-gradient-to-tr from-[color:#ff392c] via-black to-[color:#ff392c] min-h-screen text-white">
    <?php
    include 'navbar.php';
    ?>

    <div class="overflow-x-auto max-w-5xl mx-auto px-4 pb-12">
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
                        <td class="px-6 py-10"><?= $row['tanggal']; ?></td>
                        <td class="px-6 py-10 font-semibold">
                            <div class="flex items-center gap-4">
                                <img src="assets/<?= $row['icon_game']; ?> " alt="<?= $row['nama_game']; ?>" class="w-[20%] h-[20%]">
                                <?= $row['nama_game']; ?>
                            </div>
                        </td>
                        <td class="px-6 py-10">
                            <?= !empty($row['akun_game_info']) ? $row['akun_game_info'] : "-" ?>
                        </td>

                        <td class="px-6 py-10">
                            <div class="flex items-center gap-4">
                                <img src="assets/<?= $row['icon_item']; ?>" alt="<?= $row['nama_game']; ?>" class="w-[40%] h-[40%]">
                                <span><?= $row['jumlah_item']; ?> <?= $row['nama_item']; ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-10 text-yellow-400 font-bold">
                            <div class="flex gap-2">
                                <span>IDR</span>
                                <span><?= number_format($row['harga_item'], 0, ',', '.') ?></span>
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
                            <?php elseif ($row['status'] === 'pending'): ?>
                                <span class="bg-red-600 text-white px-3 py-1 rounded-full text-xs font-bold">pending</span>
                            <?php else: ?>
                                <span class="bg-red-600 text-white px-3 py-1 rounded-full text-xs font-bold"><?= $row['status'] ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="script.js"></script>
</body>

</html>