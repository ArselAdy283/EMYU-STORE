<?php
session_start();
include 'koneksi.php';

$emyucoin = 0;
if (isset($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];

    $query = $koneksi->query("SELECT emyucoin FROM emyucoin_user WHERE id_user = $id_user");

    if ($query && $query->num_rows > 0) {
        $row = $query->fetch_assoc();
        $emyucoin = $row['emyucoin'] ?? 0;
    }
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

<body class="overflow-x-hidden overflow-y-scroll bg-gradient-to-tr from-[color:#ff392c] via-black to-[color:#ff392c] min-h-screen text-white">

    <!-- Navbar -->
    <nav id="navbar" class="flex justify-between items-center px-8 py-5 
            sticky top-0 z-50 transition-all duration-500 bg-transparent">
        <!-- Logo Game -->
        <div class="flex items-center space-x-4 h-[50px] gap-2 md:gap-4">
            <img src="assets/efootball.png" alt="Efootball" class="h-[40px] md:h-[80px] transition-all duration-300">
            <img src="assets/ff.png" alt="Free Fire" class="h-[80px] md:h-[150px] transition-all duration-300">
            <img src="assets/mlbb.png" alt="Mobile Legends" class="h-[80px] md:h-[150px] transition-all duration-300">
        </div>

        <!-- Menu -->
        <ul class="flex space-x-8 text-xl text-white items-center max-md:hidden">
            <div class="font-sm text-lg bg-red-800/70 w-[200px] pl-3 pr-3 py-1 rounded-[2px] flex justify-between items-center">
                <span>
                    <span class="text-yellow-300">EC</span> <?= number_format($emyucoin, 0, ',', '.') ?>
                </span>
                <a href="emyucoin.php" class="bg-red-700/80 px-2 rounded-[2px] translate-x-[7px] text-yellow-300 hover:bg-red-700">+</a>
            </div>

            <li><a href="index.php" class="nav-link font-semibold hover:text-yellow-300">Home</a></li>
            <li><a href="orders.php" class="nav-link font-semibold hover:text-yellow-300">Orders</a></li>
            <li><a href="inbox.php" class="nav-link font-semibold hover:text-yellow-300">Inbox</a></li>
            <li><a href="account.php" class="nav-link font-semibold hover:text-yellow-300">Account</a></li>
        </ul>
        <div class="md:hidden">
            <img src="assets/list.svg" alt="list" class="invert" />
        </div>
    </nav>

    <section class="h-screen flex items-center justify-between px-12">
        <div class="hidden md:flex flex-shrink-0 mt-90 translate-x-[-100px]">
            <img src="assets/hero.webp" alt="Hero" class="w-[600px] transform scale-x-[-1] brightness-140">
        </div>

        <div class="translate-y-[-100px] md:w-1/3 md:translate-x-[-200px] md:translate-y-[-100px]">
            <h1 class="text-[50px] text-center md:text-[70px] font-extrabold text-[color:#ffed00]">EMYUSTORE</h1>
            <p class="text-center md:text-left md:translate-x-[10px] text-white mt-4 max-w-md">
                Penuhi kebutuhan gamingmu hanya di EMYUSTORE aja, paling lengkap dan juga paling MURAH.
            </p>
            <button class="flex translate-x-[130px] mt-20 md:translate-x-[180px] px-8 py-3 bg-red-500 rounded-full font-semibold text-white text-lg shadow-lg hover:bg-red-600 transition" onclick="window.location.href='topup.php'">
                Top Up
            </button>
        </div>
    </section>
    <section class="md:mt-[500px]">
        <h1 class="text-center text-[30px] md:text-[50px] font-extrabold text-[color:#ffed00] mb-9">GAME POPULER</h1>
        <div class="flex items-center justify-center gap-2 md:gap-9">
            <!-- Card MLBB -->
            <div class="relative group md:w-[250px] md:h-[500px] rounded-[10px] overflow-hidden shadow-lg">
                <img src="assets/mlbb-banner.webp" alt="mlbb" class="w-full h-full object-cover transition duration-500 group-hover:brightness-50">
                <div class="absolute inset-0 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition duration-500">
                    <img src="assets/mlbb.png" alt="mlbb logo" class="h-[100px] md:h-[200px] mb-4">
                </div>
            </div>

            <!-- Card eFootball -->
            <div class="relative group md:w-[250px] md:h-[500px] rounded-[10px] overflow-hidden shadow-lg">
                <img src="assets/efootball-banner.jpg" alt="ef" class="w-full h-full object-cover transition duration-500 group-hover:brightness-50">
                <div class="absolute inset-0 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition duration-500">
                    <img src="assets/efootball.png" alt="ef logo" class="h-[100px] md:h-[200px] mb-4">
                </div>
            </div>

            <!-- Card Free Fire -->
            <div class="relative group md:w-[250px] md:h-[500px] rounded-[10px] overflow-hidden shadow-lg">
                <img src="assets/ff-banner.webp" alt="ff" class="w-full h-full object-cover transition duration-500 group-hover:brightness-50">
                <div class="absolute inset-0 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition duration-500">
                    <img src="assets/ff.png" alt="ff logo" class="h-[100px] md:h-[200px] mb-4">
                </div>
            </div>
        </div>
    </section>

    <section class="mt-[100px] md:mt-[200px]"> <!-- kasih margin top biar turun -->
        <div class="bg-red-800/70 flex items-center justify-center gap-10 md:gap-[150px] py-4">
            <button onclick="githubPopup()" class="cursor-pointer">
                <img src="assets/github.png" alt="github" class="w-[60px]">
            </button>
            <button onclick="instagramPopup()" class="cursor-pointer">
                <img src="assets/instagram-white-icon.webp" alt="instagram" class="w-[50px]">
            </button>
            <a href="https://discord.com/invite/xMGjMPS3cf" target="_blank">
                <img src="assets/discord-white-icon-logo-app-transparent-background-premium-social-media-design-for-digital-download-free-png.webp" alt="discord" class="w-[60px]">
            </a>
        </div>
        <img src="assets/banner_topup_now.jpg" alt="banner" class="w-full object-cover" />

        <div id="githubPopup"
            class="hidden fixed inset-0 flex items-center justify-center bg-black/60 backdrop-blur-sm z-50">
            <div class="bg-red-800 rounded-xl shadow-lg w-[700px] h-[350px] p-6 relative">
                <button onclick="document.getElementById('githubPopup').classList.add('hidden')"
                    class="absolute top-4 right-6 text-white hover:text-[#ffed00] transition0 text-xl">✖</button>

                <div id="githubPopupContent"></div>
            </div>
        </div>
        <div id="instagramPopup"
            class="hidden fixed inset-0 flex items-center justify-center bg-black/60 backdrop-blur-sm z-50">
            <div class="bg-red-800 rounded-xl shadow-lg w-[700px] h-[350px] p-6 relative">
                <button onclick="document.getElementById('instagramPopup').classList.add('hidden')"
                    class="absolute top-4 right-6 text-white hover:text-[#ffed00] transition text-xl">✖</button>

                <div id="instagramPopupContent"></div>
            </div>
        </div>
    </section>
    <script src="script.js"></script>
    <script src="app.js"></script>
</body>

</html>