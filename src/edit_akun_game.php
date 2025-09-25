<?php
session_start();
include 'koneksi.php';

$game = isset($_GET['game']) ? $_GET['game'] : '';
$id_user = $_SESSION['id_user'];

$id_game = null;
if ($game == 'mlbb') $id_game = 1;
elseif ($game == 'efootball') $id_game = 2;
elseif ($game == 'ff') $id_game = 3;

$old = null;
if ($id_game) {
    $stmt = $koneksi->prepare("SELECT * FROM akun_game WHERE id_user = ? AND id_game = ?");
    $stmt->bind_param("ii", $id_user, $id_game);
    $stmt->execute();
    $result = $stmt->get_result();
    $old = $result->fetch_assoc();
    $stmt->close();
}

if (isset($_POST['edit_akun_game'])) {
    $id_akun = $_POST['id_akun_game'] ?? null;
    $id_zona_game = $_POST['id_zona_game'] ?? null;
    $username_akun = $_POST['username_efootball'] ?? null;

    $sql = "INSERT INTO akun_game (id_game, id_user, id_akun, id_zona_game, username_akun) 
            VALUES (?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE 
                id_akun = VALUES(id_akun),
                id_zona_game = VALUES(id_zona_game),
                username_akun = VALUES(username_akun)";

    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("iisss", $id_game, $id_user, $id_akun, $id_zona_game, $username_akun);

    if ($stmt->execute()) {
        header("Location: account.php");
        exit;
    } else {
        echo "<p class='text-red-400 text-center'>Gagal simpan: " . $koneksi->error . "</p>";
    }
}
?>

<form method="POST" action="edit_akun_game.php?game=<?= $game ?>" class="flex flex-col items-center mt-10 space-y-6">
    <?php if ($game == 'mlbb') : ?>
        <h2 class="text-xl font-semibold mb-2">ID AKUN</h2>
        <div class="flex items-center w-80 bg-white rounded-2xl px-4 py-3 text-[#922]">
            <input type="tel" name="id_akun_game" value="<?= htmlspecialchars($old['id_akun'] ?? '') ?>" required class="w-full outline-none bg-transparent text-[#922]" />
        </div>

        <h2 class="text-xl font-semibold mb-2">ID ZONA</h2>
        <div class="flex items-center w-80 bg-white rounded-2xl px-4 py-3 text-[#922]">
            <input type="tel" name="id_zona_game" value="<?= htmlspecialchars($old['id_zona_game'] ?? '') ?>" class="w-full outline-none bg-transparent text-[#922]" />
        </div>
        <button type="submit" name="edit_akun_game" class="bg-[#ffed00] text-black font-bold px-8 py-3 rounded-2xl hover:bg-yellow-400 transition">SIMPAN</button>

        <button type="submit" name="hapus_akun_game" class="bg-red-500 text-black font-bold px-8 py-3 rounded-2xl hover:bg-red-700 transition">HAPUS</button>

    <?php elseif ($game == 'ff') : ?>
        <h2 class="text-xl font-semibold mb-2">ID AKUN</h2>
        <div class="flex items-center w-80 bg-white rounded-2xl px-4 py-3 text-[#922]">
            <input type="tel" name="id_akun_game" value="<?= htmlspecialchars($old['id_akun'] ?? '') ?>" required class="w-full outline-none bg-transparent text-[#922]" />
        </div>

        <button type="submit" name="edit_akun_game" class="bg-[#ffed00] text-black font-bold px-8 py-3 rounded-2xl hover:bg-yellow-400 transition">SIMPAN</button>

        <button type="submit" name="hapus_akun_game" class="bg-red-500 text-black font-bold px-8 py-3 rounded-2xl hover:bg-red-700 transition">HAPUS</button>

    <?php elseif ($game == 'efootball') : ?>
        <h2 class="text-xl font-semibold mb-2">USERNAME</h2>
        <div class="flex items-center w-80 bg-white rounded-2xl px-4 py-3 text-[#922]">
            <input type="text" name="username_efootball" value="<?= htmlspecialchars($old['username_akun'] ?? '') ?>" required class="w-full outline-none bg-transparent text-[#922]" />
        </div>

        <button type="submit" name="edit_akun_game" class="bg-[#ffed00] text-black font-bold px-8 py-3 rounded-2xl hover:bg-yellow-400 transition">SIMPAN</button>

        <button type="submit" name="hapus_akun_game" class="bg-red-500 text-black font-bold px-8 py-3 rounded-2xl hover:bg-red-700 transition">HAPUS</button>
    <?php else : ?>
        <p class="text-red-400">Pilih game dari daftar terlebih dahulu.</p>
    <?php endif; ?>
</form>
