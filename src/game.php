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
    <!-- Content -->
    <div class="px-6 md:px-16 lg:px-24 py-10">
        <!-- Header Game -->
        <div class="flex items-center gap-4 mb-10 translate-x-[25px]">
            <img src="assets/mlbb-logo.jpg" alt="Mobile Legends" class="w-16 h-16 rounded-xl">
            <div>
                <h1 class="text-2xl font-bold">Mobile Legends:</h1>
                <p class="text-lg font-medium -mt-1">bang bang</p>
            </div>
        </div>

        <!-- Grid Diamond -->
        <div class="grid gap-6 [grid-template-columns:repeat(auto-fit,minmax(180px,1fr))] max-w-[888px] mx-auto translate-x-[-200px]">
            <?php for ($i = 0; $i < 6; $i++): ?>
                <div class="aspect-square bg-red-800/70 backdrop-blur-md rounded-2xl
                flex flex-col items-center justify-center
                hover:scale-105 transition">
                    <div class="text-4xl mb-2">💎</div>
                    <p class="text-white text-sm font-semibold">5 Diamond</p>
                </div>
            <?php endfor; ?>
        </div>
        <div class="translate-y-[-500px] translate-x-[1000px]">
            <img src="assets/hero.webp" alt="Hero" class="w-[600px] transform scale-x-[-1] brightness-140">
        </div>
    </div>
</body>

</html>