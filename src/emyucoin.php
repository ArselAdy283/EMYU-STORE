<?php
include 'koneksi.php';
session_start();

if (!isset($_SESSION['id_user'])) {
  header('Location: login.php');
  exit;
}

$query = $koneksi->query("SELECT * FROM emyucoin ORDER BY jumlah ASC");
$paket = $query->fetch_all(MYSQLI_ASSOC);
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

<body class="overflow-x-hidden overflow-y-scroll min-h-screen bg-gradient-to-tr from-[#ff392c] via-black to-[#ff392c] font-sans text-white">

    <?php
    include 'navbar.php';
    ?>

    <div class="container mx-auto py-10 px-6">
        <h1 class="text-center text-3xl font-bold mb-6 text-yellow-300">EMYUCOIN</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($paket as $row): ?>
                <div class="bg-red-800/70 p-6 rounded-2xl shadow-lg transform transition duration-300 hover:scale-110">
                    <h2 class="text-2xl font-bold text-yellow-300 mb-3">
                        EC <?= number_format($row['jumlah'], 0, ',', '.') ?>
                    </h2>
                    <p class="text-lg mb-4">IDR <?= number_format($row['harga'], 0, ',', '.') ?></p>
                    <button onclick="qrisPopup('<?= $row['id_emyucoin']; ?>')" class="w-full bg-yellow-400 text-gray-900 font-bold py-2 rounded-xl hover:bg-yellow-300 transition">
                        Beli Sekarang
                    </button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div id="qrisPopup"
        class="hidden fixed inset-0 flex items-center justify-center bg-black/60 backdrop-blur-sm z-50">
        <div class="bg-[color:#6f050c] rounded-xl shadow-lg w-[700px] h-[500px] p-6 relative">
            <button onclick="document.getElementById('qrisPopup').classList.add('hidden')"
                class="absolute top-4 right-6 text-white hover:text-[#ffed00] transition text-xl">✖</button>

            <div id="qrisPopupContent"></div>
        </div>
    </div>

    <script src="app.js"></script>

</body>

</html>