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

<!-- Wrapper agar bisa responsif -->
<div class="flex flex-col md:flex-row items-center text-center mt-12 gap-4 md:gap-8">
    <!-- Gambar QRIS -->
    <img src="assets/qris.png" alt="qris" 
         class="w-[120px] h-[180px] md:w-[250px] md:h-[350px]">

    <!-- Teks -->
    <div class="md:translate-y-[-50px] px-4 md:px-0">
        <p class="text-2xl md:text-3xl font-extrabold text-yellow-300 mb-6 md:mb-10">
            Scan QRIS ini untuk membayar
        </p>
        <p class="text-sm md:text-base text-gray-200 max-w-xs md:translate-x-[40px] mx-auto md:mx-0">
            Setelah itu, upload bukti transfer untuk melanjutkan pembayaran.
        </p>
    </div>
</div>

<!-- Form -->
<div class="flex flex-col md:flex-row items-center justify-center gap-4 md:translate-x-[150px] md:translate-y-[-50px] mt-6 md:mt-0">
    <form method="POST" action="proses_bukti.php" enctype="multipart/form-data" 
          class="flex flex-col md:flex-row items-center gap-4">
        <input type="hidden" name="id_emyucoin" value="<?= htmlspecialchars($id_emyucoin) ?>">

        <!-- Input file -->
        <input type="file" id="bukti_transfer" name="bukti_transfer" accept="image/*" 
               class="hidden" onchange="previewBukti(this)">

        <label for="bukti_transfer"
            class="cursor-pointer bg-red-500 hover:bg-red-600 px-6 py-2 rounded-full text-white font-semibold transition">
            Bukti Transfer
        </label>

        <img id="preview" src="" alt="" class="hidden w-[180px] md:w-[200px] rounded-lg shadow-md mx-auto md:mx-0">
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
