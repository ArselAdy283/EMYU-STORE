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
    <div class="flex items-center space-x-8 h-[50px]">
        <div class="w-[80px] h-[80px]"></div>
        <div class="w-[80px] h-[80px]"></div>
        <div class="w-[80px] h-[80px]"></div>
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
        <img src="assets/list.svg" alt="list" class="invert"/>
    </div>
</nav>
<script src="script.js"></script>