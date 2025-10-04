<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user'])) {
  header('Location: login.php');
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - Dashboard</title>
  <link href="./output.css" rel="stylesheet">
  <style>
    @font-face {
      font-family: 'Montserrat';
      src: url('/assets/fonts/Montserrat-Regular.ttf') format('truetype');
      font-weight: 400;
      font-style: normal;
    }

    @font-face {
      font-family: 'Montserrat';
      src: url('/assets/fonts/Montserrat-SemiBold.ttf') format('truetype');
      font-weight: 600;
      font-style: normal;
    }

    @font-face {
      font-family: 'Montserrat';
      src: url('/assets/fonts/Montserrat-Bold.ttf') format('truetype');
      font-weight: 700;
      font-style: normal;
    }

    body {
      font-family: 'Montserrat', sans-serif;
      background-attachment: fixed;
      min-height: 100vh;
      color: white;
    }
    
    /* Tambahkan style untuk table container */
    .table-container {
      overflow-x: auto;
      margin-top: 30px;
    }
    
    table {
      min-width: 800px;
    }
  </style>
</head>

<body class="bg-[color:#212121] overflow-x-hidden overflow-y-scroll">

  <!-- NAVBAR -->
  <nav id="navbar" class="flex justify-between items-center px-8 py-5 
            sticky top-0 z-50 transition-all duration-500 bg-[color:#18181c]">
    <div class="flex items-center space-x-8 h-[50px]">
      <div class="w-[80px] h-[80px]"></div>
      <div class="w-[80px] h-[80px]"></div>
      <div class="w-[80px] h-[80px]"></div>
    </div>
    <ul class="flex space-x-8 text-xl font-semibold text-white">
      <li><a href="index.php" class="hover:text-[#db2525] transition">Home</a></li>
      <li><a href="orders.php" class="hover:text-[#db2525] transition">Orders</a></li>
      <li><a href="#" class="text-[#db2525]">Inbox</a></li>
      <li><a href="account.php" class="hover:text-[#db2525] transition">Account</a></li>
    </ul>
  </nav>

  <!-- LAYOUT: SIDEBAR + MAIN -->
  <div class="flex">

    <!-- SIDEBAR -->
    <aside class="fixed left-0 top-[80px] w-64 bg-[color:#18181c] h-[calc(100vh-80px)] flex flex-col">
      <div class="p-6 text-2xl font-bold text-[#db2525] border-b border-gray-700">
        <a href="admin.php">Dashboard</a>
      </div>
      <nav class="flex-1 p-4 space-y-4">
        <a href="admin.php?page=order" class="block py-2 px-4 rounded-lg hover:bg-[#db2525] hover:text-white transition">Order</a>
        <a href="admin.php?page=item" class="block py-2 px-4 rounded-lg hover:bg-[#db2525] hover:text-white transition">Item</a>
        <a href="admin.php?page=inbox" class="block py-2 px-4 rounded-lg hover:bg-[#db2525] hover:text-white transition">Inbox</a>
        <a href="admin.php?page=user" class="block py-2 px-4 rounded-lg hover:bg-[#db2525] hover:text-white transition">User</a>
      </nav>
      <div class="p-4 border-t border-gray-700">
        <button class="w-full py-2 bg-[#db2525] rounded-lg hover:bg-red-700 transition" onclick="window.location.href='logout.php'">
          Logout
        </button>
      </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 p-8 ml-64">
      <?php
      $page = $_GET['page'] ?? 'dashboard'; ?>

      <?php if ($page === 'order'): ?>
        <header class="flex justify-between items-center mb-8">
          <h1 class="text-3xl font-bold">Orders</h1>
        </header>

        <section class="grid grid-cols-3 gap-6">
          <div class="bg-[#18181c] rounded-xl shadow p-6">
            <h2 class="text-xl font-semibold mb-2">Total Orders</h2>
            <p class="text-3xl font-bold text-[#db2525]">120</p>
          </div>
          <div class="bg-[#18181c] rounded-xl shadow p-6">
            <h2 class="text-xl font-semibold mb-2">Total Items</h2>
            <p class="text-3xl font-bold text-[#db2525]">58</p>
          </div>
          <div class="bg-[#18181c] rounded-xl shadow p-6">
            <h2 class="text-xl font-semibold mb-2">Total Users</h2>
            <p class="text-3xl font-bold text-[#db2525]">340</p>
          </div>
        </section>

        <?php
        $filter = $_GET['filter'] ?? 'all';

        $query = "
          SELECT o.id_order, o.tanggal, o.status,
                u.username,
                i.nama_item, i.jumlah_item, i.harga_item, i.icon_item,
                g.nama_game, g.icon_game
          FROM orders o
          JOIN users u ON o.id_user = u.id_user
          JOIN items i ON o.id_item = i.id_item
          JOIN games g ON i.id_game = g.id_game";

        if ($filter === 'pending') {
          $query .= " WHERE o.status = 'pending'";
        } elseif ($filter === 'done') {
          $query .= " WHERE o.status = 'done'";
        }

        $query .= " ORDER BY o.tanggal DESC";

        $result = $koneksi->query($query);

        ?>

        <div class="mb-6 flex gap-4">
          <a href="admin.php?page=order&filter=all" class="px-4 py-2 rounded-lg bg-gray-700 hover:bg-gray-600">Semua</a>
          <a href="admin.php?page=order&filter=pending" class="px-4 py-2 rounded-lg bg-yellow-600 hover:bg-yellow-500">Pending</a>
          <a href="admin.php?page=order&filter=done" class="px-4 py-2 rounded-lg bg-green-600 hover:bg-green-500">Done</a>
        </div>

        <!-- Tambahkan container untuk table -->
        <div class="table-container">
          <table class="w-full text-sm text-left text-white border border-yellow-500 rounded-xl overflow-hidden">
            <thead class="bg-red-700 text-white text-center uppercase text-base">
              <tr>
                <th class="px-4 py-3">Tanggal</th>
                <th class="px-4 py-3">Game</th>
                <th class="px-4 py-3">Item</th>
                <th class="px-4 py-3">Harga</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = $result->fetch_assoc()): ?>
                <tr class="odd:bg-[color:#171717] even:bg-[color:#333333] hover:bg-red-600/60 transition">
                  <td class="px-4 py-4"><?= $row['tanggal']; ?></td>
                  <td class="px-4 py-4 font-semibold">
                    <div class="flex items-center gap-3">
                      <img src="assets/<?= $row['icon_game']; ?>" alt="<?= $row['nama_game']; ?>" class="w-8 h-8">
                      <span><?= $row['nama_game']; ?></span>
                    </div>
                  </td>
                  <td class="px-4 py-4">
                    <div class="flex items-center gap-3">
                      <img src="assets/<?= $row['icon_item']; ?>" alt="<?= $row['nama_item']; ?>" class="w-8 h-8">
                      <span><?= $row['jumlah_item']; ?> <?= $row['nama_item']; ?></span>
                    </div>
                  </td>
                  <td class="px-4 py-4 text-yellow-400 font-bold">
                    <div class="flex gap-2">
                      <span>IDR</span>
                      <span><?= number_format($row['harga_item']) ?></span>
                    </div>
                  </td>
                  <td class="px-4 py-4">
                    <?php if ($row['status'] === 'done'): ?>
                      <span class="bg-green-600 text-white px-3 py-1 rounded-full text-xs font-bold">Done</span>
                    <?php elseif ($row['status'] === 'pending'): ?>
                      <span class="bg-red-600 text-black px-3 py-1 rounded-full text-xs font-bold">Pending</span>
                    <?php else: ?>
                      <span class="bg-red-600 text-white px-3 py-1 rounded-full text-xs font-bold"><?= $row['status'] ?></span>
                    <?php endif; ?>
                  </td>
                  <td class="px-4 py-4 text-center">
                    <?php if ($row['status'] === 'pending'): ?>
                      <form method="post" action="update_order.php" class="inline">
                        <input type="hidden" name="id_order" value="<?= $row['id_order']; ?>">
                        <button type="submit" class="bg-green-600 text-white px-3 py-2 rounded-lg text-sm font-bold hover:bg-green-700 transition">
                          Selesai
                        </button>
                      </form>
                    <?php else: ?>
                      <span class="text-gray-400 text-sm">Tidak ada aksi</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>

      <?php elseif ($page === 'item'): ?>
        <h1 class='text-3xl font-bold mb-4'>📦 Item</h1>
        <p>Manajemen item ada di sini...</p>

      <?php elseif ($page === 'inbox'): ?>
        <h1 class='text-3xl font-bold mb-4'>💻 Inbox</h1>
        <p>Kelola data user di sini...</p>

      <?php elseif ($page === 'user'): ?>
        <h1 class='text-3xl font-bold mb-4'>👤 User</h1>
        <p>Kelola data user di sini...</p>

      <?php else: ?>
        <h1 class='text-3xl font-bold mb-4'>📊 Dashboard</h1>
        <p>Selamat datang di Admin Dashboard.</p>
      <?php endif ?>

    </main>

  </div>

</body>

</html>