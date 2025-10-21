<?php
session_start();
include 'koneksi.php';

$id_user = $_SESSION['id_user'] ?? null;
if (!$id_user) {
    exit("<p class='text-red-400 text-center'>Sesi tidak valid. Silakan login ulang.</p>");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';

    $stmt = $koneksi->prepare("SELECT password FROM users WHERE id_user = ?");
    $stmt->bind_param("i", $id_user);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($current_password, $user['password'])) {
        $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
        $update = $koneksi->prepare("UPDATE users SET password = ? WHERE id_user = ?");
        $update->bind_param("si", $new_hash, $id_user);
        $update->execute();

        header("Location: account.php");
    } else {
        header("Location: account.php");
    }
}
?>

<form method="post" action="ganti_password.php">
    <div class="flex flex-col items-center md:items-start">
        <h2 class="text-lg md:text-xl font-semibold mb-2">Password Lama</h2>
        <div class="flex items-center w-full max-w-xs md:w-80 bg-white rounded-2xl px-4 py-3 text-[#922]">
            <input type="password" name="current_password" required class="w-full outline-none bg-transparent text-[#922]" />
        </div>
    </div>
    <div class="flex flex-col items-center md:items-start mt-4">
        <h2 class="text-lg md:text-xl font-semibold mb-2">Password Baru</h2>
        <div class="flex items-center w-full max-w-xs md:w-80 bg-white rounded-2xl px-4 py-3 text-[#922]">
            <input type="password" name="new_password" required class="w-full outline-none bg-transparent text-[#922]" />
        </div>
    </div>
    <button type="submit" class="mt-4 bg-[#ffed00] text-black font-bold px-8 py-3 rounded-2xl hover:bg-yellow-400 transition">
        SIMPAN
    </button>
</form>
