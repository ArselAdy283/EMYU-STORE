<?php
session_start();
include 'koneksi.php';
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>EMYUSTORE</title>
  <link href="./output.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <style>
    .line-clamp-3 {
      display: -webkit-box;
      -webkit-line-clamp: 3;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }
  </style>
</head>

<body class="overflow-x-hidden overflow-y-scroll min-h-screen bg-gradient-to-tr from-[#ff392c] via-black to-[#ff392c] text-white">
  <?php include 'navbar.php'; ?>

  <div class="max-w-6xl mx-auto px-4 py-10">
    <?php
    $messages = $koneksi->query("
      SELECT inbox.id_inbox, inbox.message, inbox.created_at, inbox.image_path, users.display_name
      FROM inbox
      JOIN users ON inbox.id_user = users.id_user
      ORDER BY inbox.created_at DESC
    ");

    if ($messages->num_rows === 0): ?>
      <!-- Jika inbox kosong -->
      <div class="text-center text-gray-300 py-20 animate-fadeIn">
        <h2 class="text-2xl font-semibold text-[#3b3b3b]">Inbox Kosong</h2>
        <p class="text-sm text-[#3b3b3b] mt-2">Belum ada pengumuman yang dikirimkan.</p>
      </div>
    <?php else: ?>
      <!-- Jika ada data -->
      <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php while ($msg = $messages->fetch_assoc()): ?>
          <div class="bg-red-800/70 backdrop-blur-md rounded-xl overflow-hidden shadow-lg transform transition duration-300 hover:scale-105 flex flex-col h-full min-h-[420px]">
            <?php if (!empty($msg['image_path'])): ?>
              <div class="bg-black/20 flex items-center justify-center p-2">
                <img src="<?= htmlspecialchars($msg['image_path']) ?>"
                  class="w-full h-56 object-cover rounded-md cursor-pointer transition hover:scale-[1.02]"
                  onclick="showImagePopup('<?= htmlspecialchars($msg['image_path']) ?>')">
              </div>
            <?php else: ?>
              <div class="h-48 bg-gradient-to-br from-[#ff3c2f]/40 to-[#1a1a1e] flex items-center justify-center text-gray-400 italic">
                Tidak ada gambar
              </div>
            <?php endif; ?>

            <div class="p-5 flex-grow flex flex-col justify-between">
              <div>
                <div class="text-xs text-[#ffb4b4] font-semibold mb-1">
                  <?= strtoupper(date('d M Y', strtotime($msg['created_at']))) ?> · PENGUMUMAN
                </div>

                <h2 class="font-bold text-lg text-white mb-2 line-clamp-3">
                  <?= nl2br(htmlspecialchars($msg['message'])) ?>
                </h2>
              </div>

              <?php if (mb_strlen($msg['message']) > 50): ?>
                <button onclick="showTextPopup(`<?= htmlspecialchars(addslashes($msg['message'])) ?>`)"
                  class="mt-2 text-[#ffb4b4] hover:text-[#ff3939] text-sm font-semibold transition self-start">
                  Baca selengkapnya
                </button>
              <?php endif; ?>
            </div>

            <div class="border-t border-[#2a2a2f] px-5 py-3 text-xs text-[#ffb4b4] flex justify-between items-center">
              <span>Diumumkan oleh <b><?= htmlspecialchars($msg['display_name']) ?></b></span>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    <?php endif; ?>
  </div>

  <!-- Popup Gambar -->
  <div id="imagePopup" class="hidden fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50">
    <img id="popupImg" src="" alt="Preview"
      class="max-w-[90%] max-h-[90%] rounded-lg border border-gray-700 shadow-lg">
    <button id="closePopup" class="absolute top-6 right-6 text-white text-2xl font-bold hover:text-[#ffed00] transition">✖</button>
  </div>

  <!-- Popup Teks -->
  <div id="textPopup" class="hidden fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50 p-4">
    <div class="bg-red-800 max-w-2xl w-full max-h-[80vh] rounded-xl p-6 overflow-y-auto relative text-white shadow-xl">
      <button id="closeTextPopup" class="absolute top-3 right-3 text-xl text-[#ffb4b4] hover:text-[#ffed00]">✖</button>
      <h3 class="text-[#ffb4b4] font-semibold mb-3">Detail Pengumuman</h3>
      <p id="popupText" class="whitespace-pre-line leading-relaxed"></p>
    </div>
  </div>

  <script>
    // === Popup Gambar ===
    function showImagePopup(src) {
      if (!src) return;
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

    // === Popup Teks ===
    function showTextPopup(text) {
      const popup = document.getElementById('textPopup');
      const popupText = document.getElementById('popupText');
      popupText.textContent = text;
      popup.classList.remove('hidden');
    }

    document.getElementById('closeTextPopup').addEventListener('click', () => {
      document.getElementById('textPopup').classList.add('hidden');
      document.getElementById('popupText').textContent = '';
    });

    document.getElementById('textPopup').addEventListener('click', (e) => {
      if (e.target === e.currentTarget) {
        e.currentTarget.classList.add('hidden');
        document.getElementById('popupText').textContent = '';
      }
    });
  </script>
</body>

</html>
