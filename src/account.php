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

<body class="overflow-x-hidden overflow-y-scroll min-h-screen bg-gradient-to-tr from-[#ff392c] via-black to-[#ff392c] font-sans text-white">
    <?php

    include 'navbar.php';

    ?>

    <div class="flex flex-col items-center mt-10 md:translate-x-[180px] translate-y-[-50px]">
        <section class="md:w-2/3 pt-6 pb-20">
            <div class="mt-8 w-full md:max-w-2xl bg-red-800/70 backdrop-blur-md rounded-2xl p-6 shadow-2xl">
                <div class="flex items-center gap-6">
                    <div>
                        <img src="profile_pic/<?= $_SESSION['profile_pic'] ?? 'profile_pic.png'; ?>" class="w-20 h-20 rounded-full object-cover">
                    </div>

                    <div class="flex-1">
                        <div class="bg-white text-[color:#81302b] inline-block rounded-full px-6 py-2 text-xl font-semibold translate-y-[10px]">
                            <?= $_SESSION['display_name']; ?>
                        </div>
                        <div class="mt-2 text-[20px] translate-x-[10px] translate-y-[7px] text-white">username: <?= $_SESSION['username']; ?></div>
                    </div>
                </div>
            </div>

            <div class="mt-8 w-full max-w-2xl bg-red-800/70 rounded-2xl p-8 shadow-2xl">
                <div class="grid grid-cols-3 gap-6 text-white text-center">
                    <a href="emyucoin.php" class="flex flex-col items-center gap-3 transform transition duration-300 hover:scale-110">
                        <img src="assets/coins.svg" alt="history" class="w-14 h-14 invert">
                        <div class="mt-1 text-lg">Emyucoin</div>
                    </a>

                    <button onclick="document.getElementById('settingPopup').classList.remove('hidden')"
                        class="flex flex-col items-center gap-3 cursor-pointer transform transition duration-300 hover:scale-110">
                        <img src="assets/gear.svg" alt="setting" class="w-14 h-14 invert">
                        <div class="mt-1 text-lg">Setting</div>
                    </button>

                    <button onclick="document.getElementById('akunGamePopup').classList.remove('hidden')" class="flex flex-col items-center gap-3 transform cursor-pointer transition duration-300 hover:scale-110">
                        <img src="assets/game-controller.svg" alt="game" class="w-14 h-14 invert">
                        <div class="mt-1 text-lg">Akun Game</div>
                    </button>

                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <div class="flex justify-start mt-2 md:translate-x-[68px]">
                            <a href="admin.php" class="flex flex-col items-center transform transition duration-300 hover:scale-110">
                                <img src="assets/code.svg" alt="admin" class="w-14 h-14 invert">
                                <div class="mt-1 text-lg">Admin</div>
                            </a>
                        </div>
                    <?php endif; ?>
                    <?php if ($_SESSION['role'] === 'user'): ?>
                        <div class="flex justify-start mt-2 translate-x-[68px]">
                            <div class="flex flex-col items-center">
                                <div class="w-14 h-14 invert"></div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="flex justify-center mt-2 col-span-2 translate-x-[-45px] md:translate-x-[-103px]">
                        <a href="logout.php" class="flex flex-col items-center transform transition duration-300 hover:scale-110">
                            <img src="assets/sign-out.svg" alt="logout" class="w-14 h-14 invert">
                            <div class="mt-1 text-lg">Logout</div>
                        </a>
                    </div>
                </div>
            </div>
        </section>
        <!-- SETTING POPUP -->
        <div id="settingPopup" class="hidden fixed bg-opacity-50 md:translate-x-[-170px] translate-y-[56px]">
            <div class="bg-[color:#70060d] rounded-xl shadow-lg md:w-[680px] w-[400px] h-[500px] md:h-[445px] p-6 relative">
                <button onclick="document.getElementById('settingPopup').classList.add('hidden')"
                    class="absolute top-2 right-2 translate-x-[-20px] translate-y-[10px] text-white hover:text-[#ffed00] transition text-xl">✖</button>

                <?php include 'setting.php'; ?>
            </div>
        </div>

        <!-- AKUN GAME POPUP -->
        <div id="akunGamePopup" class="hidden fixed bg-opacity-50 md:translate-x-[-170px] translate-y-[56px]">
            <div class="bg-[color:#70060d] rounded-xl shadow-lg md:w-[680px] w-[400px] h-[500px] md:h-[445px] p-6 relative">
                <button onclick="document.getElementById('akunGamePopup').classList.add('hidden')"
                    class="absolute top-2 right-2 translate-x-[-20px] translate-y-[10px] text-white hover:text-[#ffed00] transition text-xl">✖</button>

                <?php include 'account_game.php'; ?>
            </div>
        </div>

        <!-- INPUT ID GAME POPUP -->
        <div id="idGamePopup" class="hidden fixed bg-opacity-50 md:translate-x-[-170px] translate-y-[56px]">
            <div class="bg-[color:#70060d] rounded-xl shadow-lg md:w-[680px] h-[500px] md:h-[445px] p-6 relative">
                <button onclick="document.getElementById('idGamePopup').classList.add('hidden')"
                    class="absolute top-2 right-2 translate-x-[-20px] translate-y-[10px] text-white hover:text-[#ffed00] transition text-xl">✖</button>

                <div id="idGamePopupContent">
                    <!-- Konten edit_akun_game.php bakal dimuat di sini lewat fetch -->
                </div>
            </div>
        </div>

</body>

</html>