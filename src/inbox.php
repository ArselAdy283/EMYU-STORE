<?php
session_start();
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
  <?php include 'navbar.php'; ?>
  <div class="max-w-2xl mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6 text-center">📢 Announcement</h1>
    <div class="bg-[#18181c] p-6 rounded-xl space-y-4">
      <?php
      include 'koneksi.php';
      $messages = $koneksi->query("
        SELECT inbox.message, inbox.created_at, users.display_name
        FROM inbox
        JOIN users ON inbox.id_user = users.id_user
        ORDER BY inbox.created_at DESC
      ");
      while ($msg = $messages->fetch_assoc()) :
      ?>
        <div class="border-b border-gray-700 pb-3">
          <div class="flex items-center gap-2 mb-1">
            <span class="font-semibold text-[#db2525]"><?= htmlspecialchars($msg['display_name']); ?></span>
            <span class="text-xs text-gray-400"><?= date('d M H:i', strtotime($msg['created_at'])); ?></span>
          </div>
          <p class="text-gray-200"><?= nl2br(htmlspecialchars($msg['message'])); ?></p>
        </div>
      <?php endwhile; ?>
    </div>
  </div>
</body>
</html>
