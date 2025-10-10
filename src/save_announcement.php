<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user'])) {
  http_response_code(403);
  exit('Unauthorized');
}

$id_user = $_SESSION['id_user'];
$message = trim($_POST['message']);
$image_path = null;

// ==== UPLOAD GAMBAR (jika ada) ====
if (!empty($_FILES['image']['name'])) {
  $upload_dir = 'uploads/';
  if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

  $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
  $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
  if (!in_array($ext, $allowed)) exit('Invalid file type');

  $new_name = uniqid('img_', true) . '.' . $ext;
  $target = $upload_dir . $new_name;

  if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
    $image_path = $target;
  }
}

// === Simpan ke DB ===
$stmt = $koneksi->prepare("INSERT INTO inbox (id_user, message, image_path) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $id_user, $message, $image_path);
$stmt->execute();
$id_inbox = $stmt->insert_id;

// === Ambil data lengkap ===
$stmt2 = $koneksi->prepare("
  SELECT i.id_inbox, i.message, i.created_at, i.image_path, u.display_name
  FROM inbox i
  JOIN users u ON i.id_user = u.id_user
  WHERE i.id_inbox = ?
");
$stmt2->bind_param("i", $id_inbox);
$stmt2->execute();
$result = $stmt2->get_result();

if ($row = $result->fetch_assoc()) {
  echo '
  <div class="border-b border-gray-700 pb-8 flex flex-col relative chat-message" data-id="' . $row['id_inbox'] . '">
    <div class="flex items-center gap-2">
      <span class="font-semibold text-[#db2525]">' . htmlspecialchars($row['display_name']) . '</span>
      <span class="text-xs text-gray-400">' . date('d M H:i', strtotime($row['created_at'])) . '</span>
    </div>
    <p class="text-gray-200 mt-1">' . nl2br(htmlspecialchars($row['message'])) . '</p>' .
    ($row['image_path'] ? '<img src="' . htmlspecialchars($row['image_path']) . '" class="max-w-[250px] rounded-lg mt-3 border border-gray-700"/>' : '') . '
  </div>';
}
