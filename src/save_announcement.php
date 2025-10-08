<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user'])) {
  http_response_code(403);
  exit('Unauthorized');
}

$id_user = $_SESSION['id_user'];
$message = trim($_POST['message']);

if (!empty($message)) {
  $stmt = $koneksi->prepare("INSERT INTO inbox (id_user, message) VALUES (?, ?)");
  $stmt->bind_param("is", $id_user, $message);
  $stmt->execute();
}

// Kirim ulang isi chat terbaru setelah disimpan
$result = $koneksi->query("
  SELECT inbox.message, inbox.created_at, users.username
  FROM inbox
  JOIN users ON inbox.id_user = users.id_user
  ORDER BY inbox.created_at ASC
");

while ($row = $result->fetch_assoc()) {
  echo '<div class="flex flex-col">
          <div class="flex items-center gap-2">
            <span class="font-semibold text-[#db2525]">' . htmlspecialchars($row['username']) . '</span>
            <span class="text-xs text-gray-400">' . date('H:i', strtotime($row['created_at'])) . '</span>
          </div>
          <p class="text-gray-200">' . nl2br(htmlspecialchars($row['message'])) . '</p>
        </div>';
}
?>
