<?php
include 'koneksi.php';
$id = $_POST['id'];
$message = trim($_POST['message']);

$koneksi->query("UPDATE inbox SET message = '$message' WHERE id_inbox = '$id'");

$result = $koneksi->query("
  SELECT inbox.id_inbox, inbox.message, inbox.created_at, inbox.image_path, users.display_name
  FROM inbox
  JOIN users ON inbox.id_user = users.id_user
  WHERE inbox.id_inbox = '$id'
  LIMIT 1
");
$msg = $result->fetch_assoc();
?>
<div class="border-b border-gray-700 pb-8 flex flex-col relative chat-message"
  data-id="<?= $msg['id_inbox'] ?>">
  <div class="flex items-center gap-2">
    <span class="font-semibold text-[#db2525]"><?= htmlspecialchars($msg['display_name']); ?></span>
    <span class="text-xs text-gray-400"><?= date('d M H:i', strtotime($msg['created_at'])); ?></span>
  </div>
  <p class="text-gray-200 mt-1"><?= nl2br(htmlspecialchars($msg['message'])); ?></p>
  <img src=" <?= htmlspecialchars($msg['image_path']) ?>" class="max-w-[250px] rounded-lg mt-3 border border-gray-700"/>
</div>