<?php
session_start();
if (!isset($_SESSION['username'])) {
  header('Location: login.php');
  exit;
}

include 'koneksi.php';
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Inbox - EMYUSTORE</title>
  <link href="./output.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>

<body class="overflow-x-hidden overflow-y-scroll min-h-screen bg-gradient-to-tr from-[#ff392c] via-black to-[#ff392c] font-sans text-white">
  <?php include 'navbar.php'; ?>

  <div class="max-w-2xl mx-auto p-6">
    <div class="flex gap-4 mb-6">
      <img src="assets/message.svg" class="invert translate-y-[-5px] w-8 h-8" />
      <h1 class="text-3xl font-bold">Inbox</h1>
    </div>

    <div id="chatContainer" class="bg-[#18181c] rounded-xl p-6 h-[500px] overflow-y-auto flex flex-col space-y-4">
      <?php
      $messages = $koneksi->query("
        SELECT inbox.id_inbox, inbox.message, inbox.created_at, inbox.image_path, users.display_name
        FROM inbox
        JOIN users ON inbox.id_user = users.id_user
        ORDER BY inbox.created_at ASC
      ");
      while ($msg = $messages->fetch_assoc()):
      ?>
        <div class="flex <?= $msg['display_name'] === $_SESSION['username'] ? 'justify-end' : 'justify-start' ?>">
          <div class="max-w-[75%] <?= $msg['display_name'] === $_SESSION['username']
              ? 'bg-[#db2525] text-white rounded-tl-2xl rounded-tr-2xl rounded-bl-2xl'
              : 'bg-[#2a2a2a] text-gray-100 rounded-tr-2xl rounded-tl-2xl rounded-br-2xl' ?> px-4 py-3 relative">

            <div class="flex items-center gap-2 mb-1">
              <span class="font-semibold text-sm text-[#ffb4b4]"><?= htmlspecialchars($msg['display_name']) ?></span>
              <span class="text-xs text-gray-400"><?= date('d M H:i', strtotime($msg['created_at'])) ?></span>
            </div>

            <p class="text-sm leading-snug"><?= nl2br(htmlspecialchars($msg['message'])) ?></p>

            <?php if (!empty($msg['image_path'])): ?>
              <img src="<?= htmlspecialchars($msg['image_path']) ?>"
                   class="mt-3 max-w-[250px] rounded-lg border border-gray-600 cursor-pointer hover:opacity-80 transition"
                   onclick="showImagePopup('<?= htmlspecialchars($msg['image_path']) ?>')">
            <?php endif; ?>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </div>

  <!-- Popup Gambar -->
  <div id="imagePopup" class="hidden fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50">
    <img id="popupImg" src="" alt="Preview"
      class="max-w-[90%] max-h-[90%] rounded-lg border border-gray-700 shadow-lg">
    <button id="closePopup" class="absolute top-6 right-6 text-white text-2xl font-bold hover:text-[#db2525] transition">✕</button>
  </div>

  <script>
    // Popup gambar
    function showImagePopup(src) {
      const popup = document.getElementById('imagePopup');
      const popupImg = document.getElementById('popupImg');
      popupImg.src = src;
      popup.classList.remove('hidden');
    }

    document.getElementById('closePopup').addEventListener('click', () => {
      document.getElementById('imagePopup').classList.add('hidden');
      document.getElementById('popupImg').src = '';
    });

    document.getElementById('imagePopup').addEventListener('click', (e) => {
      if (e.target === e.currentTarget) {
        e.currentTarget.classList.add('hidden');
        document.getElementById('popupImg').src = '';
      }
    });
  </script>
</body>
</html>
