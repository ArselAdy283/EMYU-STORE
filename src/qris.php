<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header('Location: login.php');
    exit;
}

$id_emyucoin = $_GET['id_emyucoin'] ?? null;

if (!$id_emyucoin) {
    echo "<p class='text-red-500'>Terjadi kesalahan: paket tidak ditemukan.</p>";
    exit;
}
?>

<div class="flex items-center text-center mt-12 gap-4">
    <img src="assets/qris.png" alt="qris" class="w-[250px] h-[350px]">
    <div class="translate-y-[-50px]">
        <p class="text-3xl font-extrabold text-yellow-300 mb-10">Scan QRIS ini untuk membayar</p>
        <p class="text-base text-gray-200 max-w-xs translate-x-[40px]">
            Setelah itu, upload bukti transfer untuk melanjutkan pembayaran.
        </p>
    </div>
</div>

<div>
    <form method="POST" action="proses_bukti.php" enctype="multipart/form-data" 
          class="flex items-center translate-x-[400px] translate-y-[-50px]">
        <input type="hidden" name="id_emyucoin" value="<?= htmlspecialchars($id_emyucoin) ?>">

        <!-- Input file -->
        <input type="file" id="bukti_transfer" name="bukti_transfer" accept="image/*" 
               class="hidden" onchange="previewBukti(this)">

        <label for="bukti_transfer"
            class="cursor-pointer bg-red-500 hover:bg-red-600 px-6 py-2 rounded-full text-white font-semibold transition">
            Bukti Transfer
        </label>

        <img id="preview" src="" alt="" class="hidden w-[200px] rounded-lg shadow-md">
    </form>
</div>

<script>
function previewBukti(input) {
    const file = input.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.getElementById('preview');
            img.src = e.target.result;
            img.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
}
</script>