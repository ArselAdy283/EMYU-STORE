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
    include 'koneksi.php';

    $game = isset($_GET['id']) ? intval($_GET['id']) : 0;

    // Query semua data game + item
    $query = mysqli_query(
        $koneksi,
        "SELECT g.id_game, g.nama_game, g.icon_game, 
            i.nama_item, i.jumlah_item, i.icon_item, i.harga_item
     FROM games g
     JOIN items i ON g.id_game = i.id_game
     WHERE g.id_game = $game"
    );

    // Ambil satu baris pertama untuk header game
    $dataGame = mysqli_fetch_assoc($query);
    ?>

    <!-- Header Game -->
    <div class="flex items-center gap-4 mb-10 translate-x-[125px]">
        <img src="assets/<?= $dataGame['icon_game']; ?>"
            alt="<?= $dataGame['nama_game'] ?>"
            class="w-16 h-16 rounded-xl">
        <div>
            <h1 class="text-2xl font-bold"><?= $dataGame['nama_game']; ?></h1>
        </div>
    </div>

    <!-- Grid Item -->
    <div class="grid gap-6 [grid-template-columns:repeat(auto-fit,minmax(180px,1fr))] max-w-[888px] mx-auto translate-x-[-200px]">
        <?php
        // balikin pointer ke awal biar bisa di-loop lagi
        mysqli_data_seek($query, 0);
        while ($row = mysqli_fetch_assoc($query)) : ?>
            <div class="aspect-square bg-red-800/70 backdrop-blur-md rounded-2xl
            flex flex-col items-center justify-center
            hover:scale-105 transition">
                <img src="assets/<?= $row['icon_item']; ?>"
                    alt="<?= $row['nama_item']; ?>"
                    class="w-25 h-25 mb-2">
                <p class="text-white text-sm font-semibold">
                    <?= $row['nama_item']; ?> (<?= $row['jumlah_item']; ?>)
                </p>
                <p class="text-white text-xs">Rp <?= number_format($row['harga_item']); ?></p>
            </div>
        <?php endwhile; ?>
    </div>
    <div class="translate-y-[-500px] translate-x-[1100px]">
        <img src="assets/hero.webp" alt="Hero" class="w-[600px] transform scale-x-[-1] brightness-140">
    </div>
</body>

</html>