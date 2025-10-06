<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user'])) {
  header('Location: login.php');
  exit;
}

if ($_SESSION['role'] !== 'admin') {
  header('Location: login.php');
  exit;
}

$filter = $_GET['filter'] ?? 'all';

$query = "
          SELECT o.id_order, o.tanggal, o.status,
          u.username, o.akun_game_info,
          i.nama_item, i.jumlah_item, i.harga_item, i.icon_item,
          g.nama_game, g.icon_game
          FROM orders o
          JOIN users u ON o.id_user = u.id_user
          JOIN items i ON o.id_item = i.id_item
          JOIN games g ON i.id_game = g.id_game
          ";

if ($filter === 'pending') {
  $query .= " WHERE o.status = 'pending'";
} elseif ($filter === 'done') {
  $query .= " WHERE o.status = 'done'";
}

$query .= " ORDER BY o.tanggal DESC";

$result = $koneksi->query($query);

$totalOrders = $koneksi->query("SELECT COUNT(*) as total FROM orders")->fetch_assoc()['total'];
$totalPending = $koneksi->query("SELECT COUNT(*) as total FROM orders WHERE status = 'pending'")->fetch_assoc()['total'];
$totalDone = $koneksi->query("SELECT COUNT(*) as total FROM orders WHERE status = 'done'")->fetch_assoc()['total'];

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
      <li><a href="inbox.php" class="text-[#db2525]">Inbox</a></li>
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

      <?php if ($page === 'orders'): ?>
        <header class="flex justify-between items-center mb-8">
          <h1 class="text-3xl font-bold">Orders</h1>
        </header>

        <section class="grid grid-cols-3 gap-6 mb-10">
          <div class="bg-[#18181c] rounded-xl shadow p-6">
            <h2 class="text-xl font-semibold mb-2">Total Orders</h2>
            <p class="text-3xl font-bold text-[#db2525]"><?= $totalOrders; ?></p>
          </div>
          <div class="bg-[#18181c] rounded-xl shadow p-6">
            <h2 class="text-xl font-semibold mb-2">Total Pending</h2>
            <p class="text-3xl font-bold text-[#db2525]"><?= $totalPending; ?></p>
          </div>
          <div class="bg-[#18181c] rounded-xl shadow p-6">
            <h2 class="text-xl font-semibold mb-2">Total Done</h2>
            <p class="text-3xl font-bold text-[#db2525]"><?= $totalDone; ?></p>
          </div>
        </section>

        <div class="mb-10 flex gap-4">
          <a href="admin.php?page=orders&filter=all" class="px-4 py-2 rounded-lg bg-gray-600 hover:bg-gray-700">Semua</a>
          <a href="admin.php?page=orders&filter=pending" class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700">Pending</a>
          <a href="admin.php?page=orders&filter=done" class="px-4 py-2 rounded-lg bg-green-600 hover:bg-green-700">Done</a>
        </div>

        <!-- Tambahkan container untuk table -->
        <div class="table-container">
          <table class="w-full text-sm text-left text-white border border-yellow-500 rounded-xl overflow-hidden">
            <thead class="bg-[color:#db2525] text-white text-center uppercase text-base">
              <tr>
                <th class="px-4 py-3">Tanggal</th>
                <th class="px-4 py-3">Game</th>
                <th class="px-4 py-3">User</th>
                <th class="px-4 py-3">Akun Game</th>
                <th class="px-4 py-3">Item</th>
                <th class="px-4 py-3">Harga</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = $result->fetch_assoc()): ?>
                <tr class="odd:bg-[color:#18181c] even:bg-[color:#212121] hover:bg-red-600/60 transition">
                  <td class="px-4 py-4"><?= $row['tanggal']; ?></td>
                  <td class="px-4 py-4 font-semibold">
                    <div class="flex items-center gap-3">
                      <img src="assets/<?= $row['icon_game']; ?>" alt="<?= $row['nama_game']; ?>" class="w-8 h-8">
                      <span><?= $row['nama_game']; ?></span>
                    </div>
                  </td>
                  <td class="px-4 py-4"><?= $row['username']; ?></td>
                  <td class="px-4 py-4">
                    <?= !empty($row['akun_game_info']) ? $row['akun_game_info'] : "-" ?>
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
                      <span><?= number_format($row['harga_item'], 0, ',', '.') ?></span>
                    </div>
                  </td>
                  <td class="px-4 py-4">
                    <?php if ($row['status'] === 'done'): ?>
                      <span class="bg-green-600 text-white px-3 py-1 rounded-full text-xs font-bold">Done</span>
                    <?php elseif ($row['status'] === 'pending'): ?>
                      <span class="bg-red-600 text-white px-3 py-1 rounded-full text-xs font-bold">Pending</span>
                    <?php else: ?>
                      <span class="bg-red-600 text-white px-3 py-1 rounded-full text-xs font-bold"><?= $row['status'] ?></span>
                    <?php endif; ?>
                  </td>
                  <td class="px-4 py-4 text-center">
                    <?php if ($row['status'] === 'pending'): ?>
                      <form method="post" action="update_order.php" class="inline">
                        <input type="hidden" name="id_order" value="<?= $row['id_order']; ?>">
                        <input type="hidden" name="filter" value="<?= htmlspecialchars($filter); ?>">
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
        <h1 class='text-3xl font-bold mb-20'>Item</h1>

        <!-- Mobile Legend -->
        <div class="flex items-center gap-4 mb-10">
          <img src="assets/mlbb-logo.jpg" alt="mlbb" class="w-16 h-16 rounded-xl" />
          <h1 class="text-2xl font-bold">Mobile Legend: Bang Bang</h1>
        </div>

        <?php
        $ml = mysqli_query($koneksi, "
    SELECT i.id_item, i.nama_item, i.jumlah_item, i.icon_item, i.harga_item 
    FROM items i 
    JOIN games g ON i.id_game = g.id_game
    WHERE g.nama_game = 'Mobile Lagends: Bang Bang'
  ");
        ?>
        <div class="grid gap-6 [grid-template-columns:repeat(auto-fit,minmax(180px,1fr))] max-w-[888px] mx-auto">
          <?php
          while ($row = mysqli_fetch_assoc($ml)) : ?>
            <button onclick="editItemPopup('<?= $row['id_item']; ?>')"  class="aspect-square bg-[#18181c] backdrop-blur-md rounded-2xl p-4 flex flex-col items-center justify-center text-center cursor-pointer">
              <img src="assets/<?= $row['icon_item']; ?>" alt="<?= $row['nama_item']; ?>" class="w-30 h-30 mb-3">
              <p class="text-white text-sm font-semibold">
                <?= $row['jumlah_item']; ?> <?= $row['nama_item']; ?>
              </p>
              <p class="text-yellow-400 text-sm font-bold">
                IDR <?= number_format($row['harga_item'], 0, ',', '.'); ?>
              </p>
            </button>
          <?php endwhile; ?>
        </div>


        <!-- eFootball -->
        <div class="flex items-center gap-4 mb-10 mt-16">
          <img src="assets/efootball-logo.jpg" alt="efootball" class="w-16 h-16 rounded-xl" />
          <h1 class="text-2xl font-bold">eFootball™</h1>
        </div>

        <?php
        $ef = mysqli_query($koneksi, "
    SELECT i.id_item, i.nama_item, i.jumlah_item, i.icon_item, i.harga_item 
    FROM items i 
    JOIN games g ON i.id_game = g.id_game
    WHERE g.nama_game = 'eFootball™'
  ");
        ?>
        <div class="grid gap-6 [grid-template-columns:repeat(auto-fit,minmax(180px,1fr))] max-w-[888px] mx-auto" data-item="<?= $row['id_item']; ?>">
          <?php
          while ($row = mysqli_fetch_assoc($ef)) : ?>
            <button onclick="editItemPopup('<?= $row['id_item']; ?>')" class="aspect-square bg-[#18181c] backdrop-blur-md rounded-2xl p-4 flex flex-col items-center justify-center text-center cursor-pointer">
              <img src="assets/<?= $row['icon_item']; ?>" alt="<?= $row['nama_item']; ?>" class="w-30 h-30 mb-3">
              <p class="text-white text-sm font-semibold">
                <?= $row['jumlah_item']; ?> <?= $row['nama_item']; ?>
              </p>
              <p class="text-yellow-400 text-sm font-bold">
                IDR <?= number_format($row['harga_item'], 0, ',', '.'); ?>
              </p>
            </button>
          <?php endwhile; ?>
        </div>


        <!-- Free Fire -->
        <div class="flex items-center gap-4 mb-10 mt-16">
          <img src="assets/ff-logo.jpg" alt="ff" class="w-16 h-16 rounded-xl" />
          <h1 class="text-2xl font-bold">Free Fire</h1>
        </div>
        <?php
        $ff = mysqli_query($koneksi, "
    SELECT i.id_item, i.nama_item, i.jumlah_item, i.icon_item, i.harga_item 
    FROM items i 
    JOIN games g ON i.id_game = g.id_game
    WHERE g.nama_game = 'Free Fire'
  ");
        ?>
        <div class="grid gap-6 [grid-template-columns:repeat(auto-fit,minmax(180px,1fr))] max-w-[888px] mx-auto" data-item="<?= $row['id_item']; ?>">
          <?php
          while ($row = mysqli_fetch_assoc($ff)) : ?>
            <button onclick="editItemPopup('<?= $row['id_item']; ?>')" class="aspect-square bg-[#18181c] backdrop-blur-md rounded-2xl p-4 flex flex-col items-center justify-center text-center cursor-pointer">
              <img src="assets/<?= $row['icon_item']; ?>" alt="<?= $row['nama_item']; ?>" class="w-30 h-30 mb-3">
              <p class="text-white text-sm font-semibold">
                <?= $row['jumlah_item']; ?> <?= $row['nama_item']; ?>
              </p>
              <p class="text-yellow-400 text-sm font-bold">
                IDR <?= number_format($row['harga_item'], 0, ',', '.'); ?>
              </p>
            </button>
          <?php endwhile; ?>
        </div>

  </div>
  <div id="editItemPopup"
    class="hidden fixed inset-0 flex items-center justify-center bg-black/80 z-50">
    <div class="bg-[color:#1f1f1f] rounded-xl shadow-lg w-[700px] h-[500px] p-6 relative">
      <button onclick="document.getElementById('editItemPopup').classList.add('hidden')"
        class="absolute top-4 right-6 text-white hover:text-gray-500 text-xl">✖</button>

      <div id="editItemPopupContent"></div>
    </div>
  </div>

<?php elseif ($page === 'inbox'): ?>
  <h1 class='text-3xl font-bold mb-4'>💻 Inbox</h1>
  <p>Kelola data user di sini...</p>

<?php elseif ($page === 'user'): ?>
  <h1 class='text-3xl font-bold mb-4'>👤 User</h1>
  <p>Kelola data user di sini...</p>

<?php else: ?>
  <?php header("Location: admin.php?page=orders")?>
<?php endif ?>

</main>

</div>
<script>
  function editItemPopup(item) {
    fetch("edit_item.php?id_item=" + item)
      .then(res => res.text())
      .then(html => {
        document.getElementById("editItemPopupContent").innerHTML = html;
        document.getElementById("editItemPopup").classList.remove("hidden");
      });
  }
</script>
</body>

</html>