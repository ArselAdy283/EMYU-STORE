<?php
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

<nav id="navbar" class="flex justify-between items-center px-8 py-5 
            sticky top-0 z-50 transition-all duration-500 bg-transparent">
    <!-- Logo Game -->
    <div class="hidden md:flex items-center space-x-4 h-[50px] gap-2 md:gap-4">
        <div class="h-[40px] md:h-[80px] transition-all duration-300"></div>
        <div class="h-[80px] md:h-[150px] transition-all duration-300"></div>
        <div class="h-[80px] md:h-[150px] transition-all duration-300"></div>
    </div>

    <!-- Menu -->
    <ul class="flex items-center space-x-4 md:space-x-8 text-base md:text-xl text-white">
        <!-- Kotak EMYUCOIN -->
        <li>
            <div class="bg-red-800/70 px-3 py-1 rounded-[4px] flex items-center justify-between gap-2 w-[150px] md:w-[180px] text-sm md:text-base">
                <span>
                    <span class="text-yellow-300">EC</span>
                    <?= number_format($emyucoin, 0, ',', '.') ?>
                </span>
                <a href="emyucoin.php"
                    class="bg-red-700/80 px-2 rounded-[2px] text-yellow-300 hover:bg-red-700">+</a>
            </div>
        </li>

        <li class="hidden md:block"><a href="index.php" class="nav-link font-semibold hover:text-yellow-300">Home</a></li>
        <li class="hidden md:block"><a href="orders.php" class="nav-link font-semibold hover:text-yellow-300">Orders</a></li>
        <li class="hidden md:block"><a href="inbox.php" class="nav-link font-semibold hover:text-yellow-300">Inbox</a></li>
        <li class="hidden md:block"><a href="account.php" class="nav-link font-semibold hover:text-yellow-300">Account</a></li>
    </ul>
    <div class="md:hidden">
        <img src="assets/list.svg" alt="list" class="invert" />
    </div>
</nav>
<script src="script.js"></script>