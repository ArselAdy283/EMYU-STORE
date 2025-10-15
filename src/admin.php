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
$tipe = $_GET['tipe'] ?? 'game';

// Ambil data sesuai tipe order
if ($tipe === 'emyucoin') {
  $query = "
        SELECT 
            oe.id AS id_order,
            oe.tanggal,
            oe.status,
            oe.bukti_transfer,
            e.jumlah AS jumlah_emyu,
            e.harga AS harga_emyu,
            u.username
        FROM orders_emyucoin oe
        JOIN emyucoin e ON oe.id_emyucoin = e.id_emyucoin
        JOIN users u ON oe.id_user = u.id_user
    ";

  // Filter status
  if ($filter === 'pending') {
    $query .= " WHERE oe.status = 'pending'";
  } elseif ($filter === 'done') {
    $query .= " WHERE oe.status = 'done'";
  }

  $query .= " ORDER BY oe.tanggal DESC";
} else {
  $query = "
        SELECT 
            o.id_order,
            o.tanggal,
            o.status,
            o.akun_game_info,
            i.nama_item,
            i.jumlah_item,
            i.harga_item,
            i.icon_item,
            g.nama_game,
            g.icon_game,
            u.username
        FROM orders o
        JOIN items i ON o.id_item = i.id_item
        JOIN games g ON i.id_game = g.id_game
        JOIN users u ON o.id_user = u.id_user
    ";

  // Filter status
  if ($filter === 'pending') {
    $query .= " WHERE o.status = 'pending'";
  } elseif ($filter === 'done') {
    $query .= " WHERE o.status = 'done'";
  }

  $query .= " ORDER BY o.tanggal DESC";
}

$result = $koneksi->query($query);
if (!$result) {
  die("Query error: " . $koneksi->error);
}

if ($tipe === 'emyucoin') {
  $totalOrders = $koneksi->query("SELECT COUNT(*) as total FROM orders_emyucoin")->fetch_assoc()['total'];
  $totalPending = $koneksi->query("SELECT COUNT(*) as total FROM orders_emyucoin WHERE status = 'pending'")->fetch_assoc()['total'];
  $totalDone = $koneksi->query("SELECT COUNT(*) as total FROM orders_emyucoin WHERE status = 'done'")->fetch_assoc()['total'];
} else {
  $totalOrders = $koneksi->query("SELECT COUNT(*) as total FROM orders")->fetch_assoc()['total'];
  $totalPending = $koneksi->query("SELECT COUNT(*) as total FROM orders WHERE status = 'pending'")->fetch_assoc()['total'];
  $totalDone = $koneksi->query("SELECT COUNT(*) as total FROM orders WHERE status = 'done'")->fetch_assoc()['total'];
}

$id_user = $_SESSION['id_user'];
$query = $koneksi->query("SELECT emyucoin FROM emyucoin_user WHERE id_user = $id_user");
$row = $query->fetch_assoc();
$emyucoin = $row['emyucoin'] ?? 0;

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
    <ul class="flex space-x-8 text-xl text-white items-center">
      <div class="font-sm text-lg bg-[color:#212121]/70 w-[200px] pl-3 pr-3 py-1 rounded-[2px] flex justify-between items-center">
        <span>
          <span class="text-[#db2525]">EC</span> <?= number_format($emyucoin, 0, ',', '.') ?>
        </span>
        <a href="emyucoin.php" class="bg-[color:#18181c] px-2 rounded-[2px] translate-x-[7px] text-[#db2525] hover:bg-[color:#18181c]/70">+</a>
      </div>
      <li><a href="index.php" class="nav-link font-semibold transform transition duration-300 hover:text-[#db2525]">Home</a></li>
      <li><a href="orders.php" class="nav-link font-semibold ransform transition duration-300 hover:text-[#db2525]">Orders</a></li>
      <li><a href="inbox.php" class="nav-link font-semibold transform transition duration-300 hover:text-[#db2525]">Inbox</a></li>
      <li><a href="account.php" class="nav-link font-semibold text-[#db2525]">Account</a></li>
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
        <a href="admin.php?page=orders" class="block py-2 px-4 rounded-lg hover:bg-[#db2525] hover:text-white transition">Orders</a>
        <a href="admin.php?page=item" class="block py-2 px-4 rounded-lg hover:bg-[#db2525] hover:text-white transition">Item</a>
        <a href="admin.php?page=announcement" class="block py-2 px-4 rounded-lg hover:bg-[#db2525] hover:text-white transition">Announcement</a>
        <a href="admin.php?page=user" class="block py-2 px-4 rounded-lg hover:bg-[#db2525] hover:text-white transition">User</a>
      </nav>
      <div class="p-4 border-t border-gray-700">
        <button class="w-full py-2 bg-[#db2525] rounded-lg hover:bg-red-700 transition" onclick="window.location.href='logout.php'">
          Logout
        </button>
      </div>
    </aside>


    <main class="flex-1 p-8 ml-64">
      <?php
      $page = $_GET['page'] ?? 'dashboard'; ?>

      <!-- ==================================================/ORDER/========================================================== -->

      <?php if ($page === 'orders'): ?>

        <div class="flex gap-4 mb-10">
          <img src="assets/tray.svg" class="invert" />
          <h1 class="text-3xl font-bold">Orders</h1>
        </div>

        <div class="flex justify-center gap-4 mb-6">
          <a href="admin.php?page=orders&tipe=game&filter=<?= htmlspecialchars($filter) ?>"
            class="px-6 py-2 rounded-full font-semibold transition
            <?= $tipe === 'game' ? 'bg-red-600 text-white' : 'bg-gray-600 hover:bg-gray-700' ?>">
            Order Game
          </a>
          <a href="admin.php?page=orders&tipe=emyucoin&filter=<?= htmlspecialchars($filter) ?>"
            class="px-6 py-2 rounded-full font-semibold transition
            <?= $tipe === 'emyucoin' ? 'bg-red-600 text-white' : 'bg-gray-600 hover:bg-gray-700' ?>">
            Order Emyucoin
          </a>
        </div>

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
          <a href="admin.php?page=orders&tipe=<?= htmlspecialchars($tipe) ?>&filter=all"
            class="px-4 py-2 rounded-lg <?= $filter === 'all' ? 'bg-gray-700' : 'bg-gray-600 hover:bg-gray-700' ?>">Semua</a>
          <a href="admin.php?page=orders&tipe=<?= htmlspecialchars($tipe) ?>&filter=pending"
            class="px-4 py-2 rounded-lg <?= $filter === 'pending' ? 'bg-red-700' : 'bg-red-600 hover:bg-red-700' ?>">Pending</a>
          <a href="admin.php?page=orders&tipe=<?= htmlspecialchars($tipe) ?>&filter=done"
            class="px-4 py-2 rounded-lg <?= $filter === 'done' ? 'bg-green-700' : 'bg-green-600 hover:bg-green-700' ?>">Done</a>
        </div>


        <div class="table-container">
          <?php if ($tipe === 'emyucoin'): ?>
            <table class="w-full text-sm text-left text-white border border-yellow-500 rounded-xl overflow-hidden">
              <thead class="bg-[color:#db2525] text-white text-center uppercase text-base">
                <tr>
                  <th class="px-4 py-3">Tanggal</th>
                  <th class="px-4 py-3">User</th>
                  <th class="px-4 py-3">Jumlah Emyucoin</th>
                  <th class="px-4 py-3">Harga</th>
                  <th class="px-4 py-3">Bukti Transfer</th>
                  <th class="px-4 py-3">Status</th>
                  <th class="px-4 py-3">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                  <tr class="odd:bg-[color:#18181c] even:bg-[color:#212121] hover:bg-red-600/60 transition">
                    <td class="px-4 py-4"><?= $row['tanggal']; ?></td>
                    <td class="px-4 py-4"><?= htmlspecialchars($row['username']); ?></td>
                    <td class="px-4 py-4 font-semibold text-white">
                      <span class="text-yellow-400">EC</span> <?= number_format($row['jumlah_emyu'], 0, ',', '.'); ?>
                    </td>
                    <td class="px-4 py-4 text-yellow-400 font-bold">
                      IDR <?= number_format($row['harga_emyu'], 0, ',', '.'); ?>
                    </td>
                    <td class="px-4 py-4">
                      <?php if (!empty($row['bukti_transfer'])): ?>
                        <a href="#" data-bukti="bukti_transfer/<?= htmlspecialchars($row['bukti_transfer']); ?>"
                          class="underline text-blue-400 hover:text-blue-300">Lihat</a>
                      <?php else: ?>
                        <span class="text-gray-400">Belum ada</span>
                      <?php endif; ?>
                    </td>
                    <td class="px-4 py-4">
                      <?php if ($row['status'] === 'done'): ?>
                        <span class="bg-green-600 text-white px-3 py-1 rounded-full text-xs font-bold">Done</span>
                      <?php elseif ($row['status'] === 'pending'): ?>
                        <span class="bg-red-600 text-white px-3 py-1 rounded-full text-xs font-bold">Pending</span>
                      <?php else: ?>
                        <span class="bg-gray-600 text-white px-3 py-1 rounded-full text-xs font-bold"><?= htmlspecialchars($row['status']); ?></span>
                      <?php endif; ?>
                    </td>
                    <td class="px-4 py-4 text-center">
                      <?php if ($row['status'] === 'pending'): ?>
                        <form method="post" action="update_order_emyucoin.php" class="inline">
                          <input type="hidden" name="id_order" value="<?= $row['id_order']; ?>">
                          <input type="hidden" name="filter" value="<?= htmlspecialchars($filter); ?>">
                          <button type="submit"
                            class="bg-green-600 text-white px-3 py-2 rounded-lg text-sm font-bold hover:bg-green-700 transition">
                            Selesai
                          </button>
                        </form>
                        <form method="post" action="delete_order_emyucoin.php" class="inline" onsubmit="return confirm('Yakin ingin menghapus pesanan ini?');">
                          <input type="hidden" name="id_order" value="<?= $row['id_order']; ?>">
                          <button type="submit"
                            class="bg-red-600 text-white px-3 py-2 rounded-lg text-sm font-bold hover:bg-red-700 transition ml-2">
                            Hapus
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

            <div id="buktiTransferPopup"
              class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
              <img id="popupBukti" src="" alt="Preview" class="max-w-[90%] max-h-[90%] rounded-lg border border-gray-700 shadow-lg">
              <button id="closeBuktiTransferPopup"
                class="absolute top-6 right-6 text-white text-2xl font-bold hover:text-[#db2525] transition">✕</button>
            </div>

          <?php else: ?>
            <!-- ============== TABEL UNTUK ORDER GAME ============== -->
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
                        <img src="assets/<?= htmlspecialchars($row['icon_game']); ?>" alt="<?= htmlspecialchars($row['nama_game']); ?>" class="w-8 h-8">
                        <span><?= htmlspecialchars($row['nama_game']); ?></span>
                      </div>
                    </td>
                    <td class="px-4 py-4"><?= htmlspecialchars($row['username']); ?></td>
                    <td class="px-4 py-4"><?= !empty($row['akun_game_info']) ? htmlspecialchars($row['akun_game_info']) : '-' ?></td>
                    <td class="px-4 py-4">
                      <div class="flex items-center gap-3">
                        <img src="assets/<?= htmlspecialchars($row['icon_item']); ?>" alt="<?= htmlspecialchars($row['nama_item']); ?>" class="w-8 h-8">
                        <span><?= $row['jumlah_item']; ?> <?= htmlspecialchars($row['nama_item']); ?></span>
                      </div>
                    </td>
                    <td class="px-4 py-4 text-yellow-400 font-bold">
                      EC <?= number_format($row['harga_item'], 0, ',', '.'); ?>
                    </td>
                    <td class="px-4 py-4">
                      <?php if ($row['status'] === 'done'): ?>
                        <span class="bg-green-600 text-white px-3 py-1 rounded-full text-xs font-bold">Done</span>
                      <?php elseif ($row['status'] === 'pending'): ?>
                        <span class="bg-red-600 text-white px-3 py-1 rounded-full text-xs font-bold">Pending</span>
                      <?php else: ?>
                        <span class="bg-gray-600 text-white px-3 py-1 rounded-full text-xs font-bold"><?= htmlspecialchars($row['status']); ?></span>
                      <?php endif; ?>
                    </td>
                    <td class="px-4 py-4 flex text-center">
                      <?php if ($row['status'] === 'pending'): ?>
                        <form method="post" action="update_order.php" class="inline">
                          <input type="hidden" name="id_order" value="<?= $row['id_order']; ?>">
                          <input type="hidden" name="filter" value="<?= htmlspecialchars($filter); ?>">
                          <button type="submit"
                            class="bg-green-600 text-white px-3 py-2 rounded-lg text-sm font-bold hover:bg-green-700 transition">
                            Selesai
                          </button>
                        </form>
                        <form method="post" action="delete_order.php" class="inline" onsubmit="return confirm('Yakin ingin menghapus pesanan ini?');">
                          <input type="hidden" name="id_order" value="<?= $row['id_order']; ?>">
                          <button type="submit"
                            class="bg-red-600 text-white px-3 py-2 rounded-lg text-sm font-bold hover:bg-red-700 transition ml-2">
                            Hapus
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
          <?php endif; ?>
        </div>

        <!-- ===================================================/ITEM/=============================================================== -->

      <?php elseif ($page === 'item'): ?>
        <div class="flex gap-4 mb-10">
          <img src="assets/cube.svg" class="invert" />
          <h1 class='text-3xl font-bold'>Item</h1>
        </div>

        <!-- ========== DAFTAR GAME ITEM ========== -->
        <?php
        $games = mysqli_query($koneksi, "SELECT * FROM games");
        while ($game = mysqli_fetch_assoc($games)):
        ?>
          <div class="flex justify-between items-center mb-20 mt-10">
            <div class="flex items-center gap-4 translate-x-[160px]">
              <img src="assets/<?= $game['icon_game']; ?>" alt="<?= htmlspecialchars($game['nama_game']); ?>" class="w-16 h-16 rounded-xl" />
              <h1 class="text-2xl font-bold"><?= htmlspecialchars($game['nama_game']); ?></h1>
            </div>
            <button onclick="openAddPopup('<?= $game['id_game']; ?>')"
              class="bg-[#db2525] hover:bg-red-700 px-4 py-2 rounded-lg font-semibold transition translate-x-[-160px]">
              + Tambah Item
            </button>
          </div>

          <?php
          $items = mysqli_query($koneksi, "
      SELECT i.id_item, i.nama_item, i.jumlah_item, i.icon_item, i.harga_item
      FROM items i
      WHERE i.id_game = {$game['id_game']}
      ORDER BY CAST(i.jumlah_item AS UNSIGNED) ASC
    ");
          ?>
          <div class="grid gap-6 [grid-template-columns:repeat(auto-fit,minmax(180px,1fr))] max-w-[888px] mx-auto">
            <?php while ($row = mysqli_fetch_assoc($items)) : ?>
              <button onclick="editItemPopup('item', '<?= $row['id_item']; ?>')"
                class="aspect-square bg-[#18181c] backdrop-blur-md rounded-2xl p-4 flex flex-col items-center justify-center text-center cursor-pointer">
                <img src="assets/<?= $row['icon_item']; ?>" alt="<?= $row['nama_item']; ?>" class="w-30 h-30 mb-3">
                <p class="text-white text-sm font-semibold">
                  <?= $row['jumlah_item']; ?> <?= $row['nama_item']; ?>
                </p>
                <p class="text-yellow-400 text-sm font-bold">
                  EC <?= number_format($row['harga_item'], 0, ',', '.'); ?>
                </p>
              </button>
            <?php endwhile; ?>
          </div>
        <?php endwhile; ?>

        <!-- Tombol Tambah Produk Emyucoin -->
        <div class="flex justify-between items-center mb-20 mt-10">
          <div class="flex items-center gap-4 translate-x-[160px]">
            <img src="assets/coins.svg" alt="emyucoin" class="w-20 h-20 invert" />
            <h1 class="text-2xl font-bold">Emyucoin</h1>
          </div>
          <button onclick="openAddPopup('emyucoin')"
            class="bg-[#db2525] hover:bg-red-700 px-4 py-2 rounded-lg font-semibold transition translate-x-[-160px]">
            + Tambah Emyucoin
          </button>
        </div>

        <?php
        $emyucoin = mysqli_query($koneksi, "SELECT * FROM emyucoin ORDER BY harga ASC");
        ?>
        <div class="grid gap-6 [grid-template-columns:repeat(auto-fit,minmax(180px,1fr))] max-w-[888px] mx-auto mb-16">
          <?php while ($row = mysqli_fetch_assoc($emyucoin)) : ?>
            <button onclick="editItemPopup('emyucoin', '<?= $row['id_emyucoin']; ?>')"
              class="aspect-square bg-[#18181c] backdrop-blur-md rounded-2xl p-4 flex flex-col items-center justify-center text-center cursor-pointer">
              <img src="assets/coins.svg" alt="emyucoin" class="w-20 h-20 mb-3 invert">
              <p class="text-yellow-400 text-sm font-semibold">
                EC <?= number_format($row['jumlah'], 0, ',', '.'); ?>
              </p>
              <p class="text-white text-sm font-bold">
                IDR <?= number_format($row['harga'], 0, ',', '.'); ?>
              </p>
            </button>
          <?php endwhile; ?>
        </div>

        <!-- POPUP FORM TAMBAH DAN EDIT -->
        <div id="editItemPopup"
          class="hidden fixed inset-0 flex items-center justify-center bg-black/60 backdrop-blur-sm z-50">
          <div class="bg-[color:#1f1f1f] rounded-xl shadow-lg w-[700px] h-auto p-6 relative">
            <button onclick="document.getElementById('editItemPopup').classList.add('hidden')"
              class="absolute top-4 right-6 text-white hover:text-[#db2525] transition text-xl">✖</button>

            <div id="editItemPopupContent"></div>
          </div>
        </div>

        <!-- =============================================/ANNOUNCEMENT/============================================================= -->

      <?php elseif ($page === 'announcement'): ?>
        <div class="flex gap-4">
          <img src="assets/megaphone.svg" class="invert translate-y-[-15px]" />
          <h1 class="text-3xl font-bold mb-6">Announcement</h1>
        </div>

        <div id="chatContainer" class="bg-[#18181c] rounded-xl p-6 h-[450px] overflow-y-auto mb-4 flex flex-col space-y-4">
          <?php
          // Ambil pesan dari database
          $messages = $koneksi->query("
              SELECT inbox.id_inbox, inbox.message, inbox.created_at, inbox.image_path, users.display_name
              FROM inbox
              JOIN users ON inbox.id_user = users.id_user
              ORDER BY inbox.created_at ASC
          ");
          while ($msg = $messages->fetch_assoc()):
          ?>
            <div class="border-b border-gray-700 pb-8 flex flex-col relative chat-message" data-id="<?= $msg['id_inbox'] ?>">
              <div class="flex items-center gap-2">
                <span class="font-semibold text-[#db2525]"><?= htmlspecialchars($msg['display_name']) ?></span>
                <span class="text-xs text-gray-400"><?= date('d M H:i', strtotime($msg['created_at'])) ?></span>
              </div>
              <p class="text-gray-200 mt-1"><?= nl2br(htmlspecialchars($msg['message'])) ?></p>
              <?php if (!empty($msg['image_path'])): ?>
                <img src="<?= htmlspecialchars($msg['image_path']) ?>" class="max-w-[250px] rounded-lg mt-3 border border-gray-700" />
              <?php endif; ?>
            </div>
          <?php endwhile; ?>
        </div>

        <!-- Menu klik kanan -->
        <div id="contextMenu"
          class="hidden fixed bg-[#18181c] border border-gray-600 rounded-lg shadow-lg text-white text-sm z-50">
          <button id="editMessageBtn" class="block w-full text-left px-4 py-2 hover:bg-[#db2525]/80">Edit</button>
          <button id="deleteMessageBtn" class="block w-full text-left px-4 py-2 hover:bg-red-700">Delete</button>
        </div>

        <form id="chatForm" class="flex gap-2 items-end relative">
          <div class="flex-1 relative h-auto">

            <!-- Wrapper input -->
            <div id="chatInputWrapper" class="absolute bottom-0 left-0 right-0 flex flex-col-reverse">

              <!-- Tombol upload -->
              <div class="absolute left-3 bottom-3 cursor-pointer">
                <label for="chatImage">
                  <img src="assets/image.svg" class="h-6 w-6 invert hover:opacity-80">
                </label>
                <input type="file" id="chatImage" name="image" accept="image/*" class="hidden" />
              </div>

              <!-- Textarea -->
              <textarea id="chatInput" name="message" rows="1"
                placeholder="Ketik pesan"
                class="pl-12 pr-4 py-3 rounded-lg bg-[#212121] text-white outline-none resize-none focus:ring-2 focus:ring-[#db2525]
        overflow-y-auto max-h-[225px] transition-all duration-150"
                autocomplete="off"></textarea>

              <!-- Preview gambar (di dalam area input) -->
              <div id="imagePreviewInside"
                class="hidden absolute bottom-full left-0 mb-2 bg-[#2a2a2a] p-2 rounded-lg flex items-center gap-2 max-w-[250px]">
                <img id="previewImgInside" src="" alt="Preview"
                  class="h-40 w-70 object-cover rounded-md border border-gray-700">
                <button type="button" id="removePreviewInside"
                  class="text-xs text-white bg-black/50 hover:bg-black rounded px-2 py-1">✕</button>
              </div>
            </div>
          </div>

          <button type="submit"
            class="bg-[#db2525] px-5 py-3 rounded-lg font-bold hover:bg-red-700 transition">
            Kirim
          </button>
        </form>
        <div id="imagePopup"
          class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
          <img id="popupImg" src="" alt="Preview" class="max-w-[90%] max-h-[90%] rounded-lg border border-gray-700 shadow-lg">
          <button id="closePopup"
            class="absolute top-6 right-6 text-white text-2xl font-bold hover:text-[#db2525] transition">✕</button>
        </div>

        <!-- ===============================================/USER/==================================================================== -->

      <?php elseif ($page === 'user'): ?>
        <div class="flex gap-4 mb-10">
          <img src="assets/user.svg" class="invert w-10 h-10" />
          <h1 class="text-3xl font-bold">Kelola User</h1>
        </div>

        <?php
        // Ambil semua user dan saldo emyucoin-nya
        $users = $koneksi->query("
      SELECT u.id_user, u.username, u.profile_pic, u.display_name, u.role, u.timestamp, 
             COALESCE(eu.emyucoin, 0) AS saldo
      FROM users u
      LEFT JOIN emyucoin_user eu ON u.id_user = eu.id_user
      ORDER BY u.timestamp DESC
  ");
        ?>

        <table class="w-full text-sm text-left text-white border border-yellow-500 rounded-xl overflow-hidden">
          <thead class="bg-[color:#db2525] text-white text-center uppercase text-base">
            <tr>
              <th class="px-4 py-3">No</th>
              <th class="px-4 py-3">Username</th>
              <th class="px-4 py-3">Display Name</th>
              <th class="px-4 py-3">Role</th>
              <th class="px-4 py-3">Tanggal Daftar</th>
              <th class="px-4 py-3">Saldo Emyucoin</th>
              <th class="px-4 py-3">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $no = 1;
            while ($user = $users->fetch_assoc()):
            ?>
              <tr class="odd:bg-[color:#18181c] even:bg-[color:#212121] hover:bg-red-600/60 transition">
                <td class="px-4 py-3 text-center"><?= $no++; ?></td>
                <td class="px-4 py-3 font-semibold">
                  <div class="flex gap-2 items-center">
                    <img src="profile_pic/<?= htmlspecialchars($user['profile_pic']); ?>" alt="<?= htmlspecialchars($user['profile_pic']); ?>" class="w-8 h-8 rounded-full object-cover">
                    <?= htmlspecialchars($user['username']); ?>
                  </div>
                </td>
                <td class="px-4 py-3"><?= htmlspecialchars($user['display_name']); ?></td>
                <td class="px-4 py-3 text-center">
                  <?php if ($user['role'] === 'admin'): ?>
                    <span class="bg-yellow-600 text-white px-3 py-1 rounded-full text-xs font-bold">Admin</span>
                  <?php else: ?>
                    <span class="bg-gray-600 text-white px-3 py-1 rounded-full text-xs font-bold">User</span>
                  <?php endif; ?>
                </td>
                <td class="px-4 py-3 text-center"><?= date('d M Y', strtotime($user['timestamp'])); ?></td>
                <td class="px-4 py-3 text-yellow-400 font-bold text-center">
                  EC <?= number_format($user['saldo'], 0, ',', '.'); ?>
                </td>
                <td class="px-4 py-3 flex justify-center gap-3">
                  <button onclick="openSaldoPopup(<?= $user['id_user'] ?>, '<?= htmlspecialchars($user['display_name']) ?>')" class="bg-[#ffb4b4]/30 hover:bg-[#ff3939] text-white px-3 py-1 rounded-lg text-xs transition">Tambah Saldo</button>
                  <button onclick="openSaldoKurangPopup(<?= $user['id_user'] ?>, '<?= htmlspecialchars($user['display_name']) ?>')" class="bg-[#ffb4b4]/30 hover:bg-[#ff3939] text-white px-3 py-1 rounded-lg text-xs transition">Kurangi Saldo</button>
                  <?php if ($user['role'] !== 'admin'): ?>
                    <button onclick="openDeletePopup(<?= $user['id_user'] ?>, '<?= htmlspecialchars($user['username']) ?>')" class="bg-[#2a2a2a] hover:bg-red-700 text-white px-3 py-1 rounded-lg text-xs transition">Hapus</button>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>

        <!-- Popup Tambah Saldo -->
        <div id="saldoPopup" class="hidden fixed inset-0 flex items-center justify-center bg-black/60 backdrop-blur-sm z-50">
          <div class="bg-[color:#1f1f1f] rounded-xl shadow-lg w-[700px] h-auto p-6 relative">
            <button onclick="closeSaldoPopup()" class="absolute top-3 right-4 text-xl text-white hover:text-[#db2525]">✕</button>
            <h3 class="text-xl font-bold text-white mb-4">Tambah Saldo</h3>
            <p class="text-sm text-gray-300 mb-2">User: <span id="saldoUser" class="font-semibold text-gray-300"></span></p>
            <input type="number" id="saldoInput" placeholder="Masukkan jumlah Emyucoin" class="w-full px-3 py-2 rounded bg-[#2a2a2a] text-white outline-none mb-4" />
            <button onclick="tambahSaldo()" class="w-full bg-[#db2525] hover:bg-[#ff3939] text-white py-2 rounded-lg transition">Simpan</button>
            <input type="hidden" id="saldoUserId">
          </div>
        </div>

        <!-- Popup Kurangi Saldo -->
        <div id="saldoKurangPopup" class="hidden fixed inset-0 flex items-center justify-center bg-black/60 backdrop-blur-sm z-50">
          <div class="bg-[color:#1f1f1f] rounded-xl shadow-lg w-[700px] h-auto p-6 relative">
            <button onclick="closeSaldoKurangPopup()" class="absolute top-3 right-4 text-xl text-white hover:text-[#db2525]">✕</button>
            <h3 class="text-xl font-bold text-white mb-4">Kurangi Saldo</h3>
            <p class="text-sm text-gray-300 mb-2">User: <span id="saldoKurangUser" class="font-semibold text-gray-300"></span></p>
            <input type="number" id="saldoKurangInput" placeholder="Masukkan jumlah Emyucoin yang dikurangi" class="w-full px-3 py-2 rounded bg-[#2a2a2a] text-white outline-none mb-4" />
            <button onclick="kurangiSaldo()" class="w-full bg-[#db2525] hover:bg-[#ff3939] text-white py-2 rounded-lg transition">Simpan</button>
            <input type="hidden" id="saldoKurangUserId">
          </div>
        </div>

        <!-- Popup Hapus User -->
        <div id="deletePopup" class="hidden fixed inset-0 flex items-center justify-center bg-black/60 backdrop-blur-sm z-50">
          <div class="bg-[color:#1f1f1f] rounded-xl shadow-lg w-[700px] h-auto p-6 relative">
            <button onclick="closeDeletePopup()" class="absolute top-3 right-4 text-xl text-white hover:text-[#db2525]">✕</button>
            <h3 class="text-xl font-bold text-white mb-4">Hapus User</h3>
            <p class="text-gray-300 mb-5">Apakah kamu yakin ingin menghapus user <b id="deleteUser"></b>?</p>
            <div class="flex justify-end gap-3">
              <button onclick="closeDeletePopup()" class="px-4 py-2 bg-gray-600 rounded-lg hover:bg-gray-700">Batal</button>
              <button onclick="hapusUser()" class="px-4 py-2 bg-red-600 rounded-lg hover:bg-red-700 text-white">Hapus</button>
            </div>
            <input type="hidden" id="deleteUserId">
          </div>
        </div>

        <script>
          // Popup Tambah Saldo
          function openSaldoPopup(id, name) {
            document.getElementById('saldoPopup').classList.remove('hidden');
            document.getElementById('saldoUser').textContent = name;
            document.getElementById('saldoUserId').value = id;
          }

          function closeSaldoPopup() {
            document.getElementById('saldoPopup').classList.add('hidden');
            document.getElementById('saldoInput').value = '';
          }

          function tambahSaldo() {
            const id = document.getElementById('saldoUserId').value;
            const amount = document.getElementById('saldoInput').value;
            if (amount <= 0) return alert('Masukkan jumlah saldo yang valid!');
            fetch('tambah_saldo.php', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
              },
              body: `id=${id}&amount=${amount}`
            }).then(r => r.text()).then(res => {
              location.reload();
            });
          }

          // Popup Kurangi Saldo
          function openSaldoKurangPopup(id, name) {
            document.getElementById('saldoKurangPopup').classList.remove('hidden');
            document.getElementById('saldoKurangUser').textContent = name;
            document.getElementById('saldoKurangUserId').value = id;
          }

          function closeSaldoKurangPopup() {
            document.getElementById('saldoKurangPopup').classList.add('hidden');
            document.getElementById('saldoKurangInput').value = '';
          }

          function kurangiSaldo() {
            const id = document.getElementById('saldoKurangUserId').value;
            const amount = document.getElementById('saldoKurangInput').value;
            if (amount <= 0) return alert('Masukkan jumlah saldo yang valid!');
            fetch('kurangi_saldo.php', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
              },
              body: `id=${id}&amount=${amount}`
            }).then(r => r.text()).then(res => {
              location.reload();
            });
          }

          // Popup Hapus User
          function openDeletePopup(id, username) {
            document.getElementById('deletePopup').classList.remove('hidden');
            document.getElementById('deleteUser').textContent = username;
            document.getElementById('deleteUserId').value = id;
          }

          function closeDeletePopup() {
            document.getElementById('deletePopup').classList.add('hidden');
          }

          function hapusUser() {
            const id = document.getElementById('deleteUserId').value;
            fetch('hapus_user.php', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
              },
              body: `id=${id}`
            }).then(r => r.text()).then(res => {
              location.reload();
            });
          }
        </script>
      <?php else: ?>
        <?php header("Location: admin.php?page=orders") ?>
      <?php endif ?>

    </main>

  </div>

  <script src="app.js"></script>
</body>

</html>