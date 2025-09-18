<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>EMYUSTORE</title>
    <link href="./output.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body class="min-h-screen bg-gradient-to-tr from-[#ff392c] via-black to-[#ff392c] font-sans text-white">
    <?php

    include 'navbar.php';

    ?>

    <div class="flex flex-col items-center mt-10 translate-x-[200px] translate-y-[-50px]">
        <section class="w-2/3 pt-6 pb-20">
            <div class="mt-8 w-full max-w-2xl bg-red-800/70 backdrop-blur-md rounded-2xl p-6 shadow-2xl">
                <div class="flex items-center gap-6">
                    <div class="flex-none bg-white rounded-full w-20 h-20 flex items-center justify-center">
                        <img src="assets/user.svg" alt="avatar" class="w-12 h-12 object-contain">
                    </div>

                    <div class="flex-1">
                        <div class="bg-white text-[color:#81302b] inline-block rounded-full px-6 py-2 text-xl font-semibold translate-y-[10px]">
                            <?php echo $_SESSION['display_name']; ?>
                        </div>
                        <div class="mt-2 text-[20px] translate-x-[10px] translate-y-[7px] text-white">username: <?php echo $_SESSION['username']; ?></div>
                    </div>
                </div>
            </div>

            <div class="mt-8 w-full max-w-2xl bg-red-800/70 rounded-2xl p-8 shadow-2xl">
                <div class="grid grid-cols-3 gap-6 text-white text-center">
                    <a href="history.php" class="flex flex-col items-center gap-3 hover:opacity-80">
                        <img src="assets/clock-counter-clockwise.svg" alt="history" class="w-14 h-14 invert">
                        <div class="mt-1 text-lg">History</div>
                    </a>

                    <a href="logout.php" class="flex flex-col items-center gap-3 hover:opacity-80">
                        <img src="assets/sign-out.svg" alt="logout" class="w-14 h-14 invert">
                        <div class="mt-1 text-lg">Logout</div>
                    </a>

                    <a href="game.php" class="flex flex-col items-center gap-3 hover:opacity-80">
                        <img src="assets/game-controller.svg" alt="game" class="w-14 h-14 invert">
                        <div class="mt-1 text-lg">Akun Game</div>
                    </a>

                    <div class="col-span-3 flex justify-center mt-4">
                        <div class="flex flex-col items-center gap-3">
                            <img src="assets/gear.svg" alt="security" class="w-16 h-16 invert">
                            <div class="mt-1 text-lg">Setting</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
</body>

</html>