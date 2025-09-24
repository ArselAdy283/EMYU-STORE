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

    <!-- Navbar -->
    <nav class="flex justify-between items-center px-8 py-5">
        <!-- Logo Game -->
        <div class="flex items-center space-x-8 h-[50px]">
            <img src="assets/efootball.png" alt="Efootball" class="h-[80px]">
            <img src="assets/ff.png" alt="Free Fire">
            <img src="assets/mlbb.png" alt="Mobile Legends">
        </div>

        <!-- Menu -->
        <ul class="flex space-x-8 text-xl font-semibold">
            <li><a href="index.php" class="transform transition duration-300 hover:text-[color:#ffed00]">Home</a></li>
            <li><a href="#" class="transform transition duration-500 hover:text-[color:#ffed00]">History</a></li>
            <li><a href="#" class="transform transition duration-500 text-[color:#ffed00]">Social</a></li>
            <li><a href="account.php" class="transform transition duration-500 hover:text-[color:#ffed00]">Account</a></li>
        </ul>
    </nav>

    <!-- Hero Section -->
    <section class="h-screen flex items-center justify-between px-12">
        <!-- Character Image -->
        <div class="flex-shrink-0 mt-90 translate-x-[-100px]">
            <img src="assets/hero.webp" alt="Hero" class="w-[600px] transform scale-x-[-1] brightness-140">
        </div>

        <!-- Content -->
        <div class="w-1/3 text-left translate-x-[-200px] translate-y-[-100px]">
            <h1 class="text-[70px] font-extrabold text-[color:#ffed00]">EMYUSTORE</h1>
            <p class="text-white mt-4 max-w-md">
                Penuhi kebutuhan gamingmu hanya di EMYUSTORE aja, paling lengkap dan juga paling MURAH.
            </p>
            <button class="mt-20 translate-x-[180px] px-8 py-3 bg-red-500 rounded-full font-semibold text-white text-lg shadow-lg hover:bg-red-600 transition" onclick="window.location.href='topup.php'">
                Top Up
            </button>
        </div>
    </section>

</body>

</html>