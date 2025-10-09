<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user'])) {
  http_response_code(403);
  exit('Unauthorized');
}

$id_user = $_SESSION['id_user'];
$message = trim($_POST['message']);

if (empty($message)) exit;

// Simpan pesan baru
$stmt = $koneksi->prepare("INSERT INTO inbox (id_user, message) VALUES (?, ?)");
$stmt->bind_param("is", $id_user, $message);
$stmt->execute();
$newId = $stmt->insert_id;
$stmt->close();

// Ambil hanya pesan yang baru saja disimpan
$result = $koneksi->query("
  SELECT inbox.id_inbox, inbox.message, inbox.created_at, users.display_name
  FROM inbox
  JOIN users ON inbox.id_user = users.id_user
  WHERE inbox.id_inbox = $newId
  LIMIT 1
");
$row = $result->fetch_assoc();

// Kirim HTML satu pesan
echo '
<div class="border-b border-gray-700 pb-10 flex flex-col relative chat-message" 
  data-id="' . $row['id_inbox'] . '">
  <div class="flex items-center gap-2">
    <span class="font-semibold text-[#db2525]">' . htmlspecialchars($row['display_name']) . '</span>
    <span class="text-xs text-gray-400">' . date('d M H:i', strtotime($row['created_at'])) . '</span>
  </div>
  <p class="text-gray-200">' . nl2br(htmlspecialchars($row['message'])) . '</p>
</div>';
