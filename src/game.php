<?php
session_start();
include 'navbar.php';
include 'koneksi.php';

$id_user = $_SESSION['id_user'] ?? null;

$game = isset($_GET['id']) ? intval($_GET['id']) : 0;

$query = mysqli_query(
    $koneksi,
    "SELECT g.id_game, g.nama_game, g.icon_game, i.id_item, 
            i.nama_item, i.jumlah_item, i.icon_item, i.harga_item
     FROM games g
     JOIN items i ON g.id_game = i.id_game
     WHERE g.id_game = $game"
);
$dataGame = mysqli_fetch_assoc($query);

$akun = null;
if ($id_user) {
    $stmt = $koneksi->prepare("SELECT * FROM akun_game WHERE id_user = ? AND id_game = ?");
    $stmt->bind_param("ii", $id_user, $game);
    $stmt->execute();
    $result = $stmt->get_result();
    $akun = $result->fetch_assoc();
    $stmt->close();
}
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

    <div class="flex items-center gap-4 mb-10 translate-x-[125px]">
        <img src="assets/<?= $dataGame['icon_game']; ?>"
            alt="<?= $dataGame['nama_game'] ?>"
            class="w-16 h-16 rounded-xl">
        <div>
            <h1 class="text-2xl font-bold"><?= $dataGame['nama_game']; ?></h1>
        </div>
    </div>

    <!-- FORM INPUT -->
    <form method="POST" action="edit_akun_game.php?game=<?= strtolower($dataGame['nama_game']); ?>"
        class="flex flex-col gap-4 w-[500px] translate-x-[103px] mb-[25px]">

        <?php if ($game == 1): // MLBB 
        ?>
            <div class="flex px-[20px] w-[500px] h-[50px] gap-6">
                <input type="tel" name="id_akun_game"
                    placeholder="ID AKUN"
                    value="<?= htmlspecialchars($akun['id_akun'] ?? '') ?>"
                    class="w-80 bg-red-800/70 rounded-2xl px-4 py-2 text-white" required>

                <input type="tel" name="id_zona_game"
                    placeholder="ID ZONA"
                    value="<?= htmlspecialchars($akun['id_zona_game'] ?? '') ?>"
                    class="w-80 bg-red-800/70 rounded-2xl px-4 py-2 text-white">
            </div>

        <?php elseif ($game == 2): // Efootball 
        ?>
            <div class="flex px-[20px] w-[500px] h-[50px] gap-6">
                <input type="text" name="username_efootball"
                    placeholder="USERNAME"
                    value="<?= htmlspecialchars($akun['username_akun'] ?? '') ?>"
                    class="w-80 bg-red-800/70 rounded-2xl px-4 py-2 text-white" required>
            </div>

        <?php elseif ($game == 3): // Free Fire 
        ?>
            <div class="flex px-[20px] w-[500px] h-[50px] gap-6">
                <input type="tel" name="id_akun_game"
                    placeholder="ID AKUN"
                    value="<?= htmlspecialchars($akun['id_akun'] ?? '') ?>"
                    class="w-80 bg-red-800/70 rounded-2xl px-4 py-2 text-white" required>
            </div>

        <?php endif; ?>
    </form>

    <!-- LIST ITEM -->
    <div class="grid gap-6 [grid-template-columns:repeat(auto-fit,minmax(180px,1fr))] max-w-[888px] mx-auto translate-x-[-200px]">
        <?php
        mysqli_data_seek($query, 0);
        while ($row = mysqli_fetch_assoc($query)) : ?>
            <div class="aspect-square bg-red-800/70 backdrop-blur-md rounded-2xl transform transition duration-300 hover:scale-110">
                <?php if ($id_user): ?>
                    <!-- Sudah login -->
                    <a href="pembayaran.php?id_item=<?= $row['id_item']; ?>"
                        class="flex flex-col items-center justify-center item-link"
                        data-item="<?= $row['id_item']; ?>">
                        <img src="assets/<?= $row['icon_item']; ?>" alt="<?= $row['nama_item']; ?>" class="w-35 h-35 mb-2">
                        <p class="text-white text-sm font-semibold">
                            <?= $row['jumlah_item']; ?> <?= $row['nama_item']; ?>
                        </p>
                        <p class="text-white text-xs">IDR <?= number_format($row['harga_item']); ?></p>
                    </a>
                <?php else: ?>
                    <!-- Belum login -->
                    <a href="login.php"
                        class="flex flex-col items-center justify-center item-link">
                        <img src="assets/<?= $row['icon_item']; ?>" alt="<?= $row['nama_item']; ?>" class="w-35 h-35 mb-2">
                        <p class="text-white text-sm font-semibold">
                            <?= $row['jumlah_item']; ?> <?= $row['nama_item']; ?>
                        </p>
                        <p class="text-white text-xs">IDR <?= number_format($row['harga_item']); ?></p>
                    </a>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>

    <div class="translate-y-[-500px] translate-x-[1100px]">
        <img src="assets/hero.webp" alt="Hero" class="w-[600px] transform scale-x-[-1] brightness-140">
    </div>
    <?php if ($id_user): ?>
        <script src="script.js"></script>
    <?php endif; ?>
</body>

</html>