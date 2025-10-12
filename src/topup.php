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
    session_start();
    include 'navbar.php';
    ?>

    <div class="flex flex-col items-center text-center space-y-12 translate-y-[50px]">
        <!-- Judul -->
        <h1 class="text-[70px] font-extrabold text-[color:#ffed00]">GAMES</h1>

        <!-- Card Games -->
        <div class="flex space-x-8">
            <!-- Card 1 -->
            <div class="bg-red-800/70 backdrop-blur-md rounded-2xl p-4 w-[160px] shadow-xl transform transition duration-300 hover:scale-110">
                <a href="game.php?id=1">
                    <img src="assets/mlbb-logo.jpg" alt="Mobile Legends" class="rounded-lg">
                    <p class="mt-2">Mobile Legends: bang bang</p>
                    <a>
            </div>
            <!-- Card 2 -->
            <div class="bg-red-800/70 backdrop-blur-md rounded-2xl p-4 w-[160px] shadow-xl transform transition duration-300 hover:scale-110">
                <a href="game.php?id=2">
                    <img src="assets/efootball-logo.jpg" alt="Efootball" class="rounded-lg">
                    <p class="mt-2">eFootball™</p>
                    <a>
            </div>
            <!-- Card 3 -->
            <div class="bg-red-800/70 backdrop-blur-md rounded-2xl p-4 w-[160px] shadow-xl transform transition duration-300 hover:scale-110">
                <a href="game.php?id=3">
                    <img src="assets/ff-logo.jpg" alt="Free Fire" class="rounded-lg">
                    <p class="mt-2">Free Fire: Rampage</p>
                    <a>
            </div>
        </div>

        <!-- Deskripsi -->
        <p class="max-w-lg text-white">
            MAU TOP UP YANG MANA?
        </p>
    </div>
</body>

</html>