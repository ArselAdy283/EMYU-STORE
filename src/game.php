<?php
session_start();
include 'navbar.php';
include 'koneksi.php';

$id_user = $_SESSION['id_user'] ?? null;

$game = isset($_GET['id']) ? intval($_GET['id']) : 0;

$query = mysqli_query(
    $koneksi,
    "SELECT g.id_game, g.nama_game, g.icon_game, i.id_item, 
            i.nama_item, i.jumlah_item, i.icon_item, i.harga_item
     FROM games g
     JOIN items i ON g.id_game = i.id_game
     WHERE g.id_game = $game
     ORDER BY CAST(i.jumlah_item AS UNSIGNED) ASC"
);
$dataGame = mysqli_fetch_assoc($query);

$akun = null;
if ($id_user) {
    $stmt = $koneksi->prepare("SELECT * FROM akun_game WHERE id_user = ? AND id_game = ?");
    $stmt->bind_param("ii", $id_user, $game);
    $stmt->execute();
    $result = $stmt->get_result();
    $akun = $result->fetch_assoc();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EMYUSTORE</title>
    <link href="./output.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body class="overflow-x-hidden overflow-y-scroll min-h-screen bg-gradient-to-tr from-[#ff392c] via-black to-[#ff392c] font-sans text-white">

    <div class="translate-x-[200px]">
        <div class="flex items-center gap-4 mb-10 translate-x-[125px]">
            <img src="assets/<?= $dataGame['icon_game']; ?>"
                alt="<?= $dataGame['nama_game'] ?>"
                class="w-16 h-16 rounded-xl">
            <div>
                <h1 class="text-2xl font-bold"><?= $dataGame['nama_game']; ?></h1>
            </div>
        </div>

        <!-- FORM INPUT -->
        <form method="POST" action="edit_akun_game.php?game=<?= strtolower($dataGame['nama_game']); ?>"
            class="flex flex-col gap-4 w-[500px] translate-x-[103px] mb-[25px]">

            <?php if ($game == 1): // MLBB 
            ?>
                <div class="flex px-[20px] w-[500px] h-[50px] gap-6">
                    <input type="tel" name="id_akun_game"
                        placeholder="ID AKUN"
                        value="<?= htmlspecialchars($akun['id_akun'] ?? '') ?>"
                        class="w-80 bg-red-800/70 rounded-2xl px-4 py-2 text-white" required>

                    <input type="tel" name="id_zona_game"
                        placeholder="ID ZONA"
                        value="<?= htmlspecialchars($akun['id_zona_game'] ?? '') ?>"
                        class="w-80 bg-red-800/70 rounded-2xl px-4 py-2 text-white">
                </div>

            <?php elseif ($game == 2): // Efootball 
            ?>
                <div class="flex px-[20px] w-[500px] h-[50px] gap-6">
                    <input type="text" name="username_efootball"
                        placeholder="USERNAME"
                        value="<?= htmlspecialchars($akun['username_akun'] ?? '') ?>"
                        class="w-80 bg-red-800/70 rounded-2xl px-4 py-2 text-white" required>
                </div>

            <?php elseif ($game == 3): // Free Fire 
            ?>
                <div class="flex px-[20px] w-[500px] h-[50px] gap-6">
                    <input type="tel" name="id_akun_game"
                        placeholder="ID AKUN"
                        value="<?= htmlspecialchars($akun['id_akun'] ?? '') ?>"
                        class="w-80 bg-red-800/70 rounded-2xl px-4 py-2 text-white" required>
                </div>

            <?php endif; ?>
        </form>

        <!-- LIST ITEM -->
        <div class="grid gap-6 [grid-template-columns:repeat(auto-fit,minmax(180px,1fr))] max-w-[888px] mx-auto translate-x-[-200px]">
            <?php
            mysqli_data_seek($query, 0);
            while ($row = mysqli_fetch_assoc($query)) : ?>
                <div class="aspect-square bg-red-800/70 backdrop-blur-md rounded-2xl transform transition duration-300 hover:scale-110">
                    <?php if ($id_user): ?>
                        <!-- Sudah login -->
                        <button onclick="pembayaranPopup('<?= $row['id_item']; ?>')"
                            class="flex flex-col items-center justify-center translate-x-[32px]"
                            data-item="<?= $row['id_item']; ?>">
                            <img src="assets/<?= $row['icon_item']; ?>" alt="<?= $row['nama_item']; ?>" class="w-35 h-35 mb-2">
                            <p class="text-white text-sm font-semibold">
                                <?= $row['jumlah_item']; ?> <?= $row['nama_item']; ?>
                            </p>
                            <p class="text-[#ffed00] text-xs font-semibold">EC <?= number_format($row['harga_item'], 0, ',', '.'); ?></p>
                        </button>
                    <?php else: ?>
                        <!-- Belum login -->
                        <a href="login.php"
                            class="flex flex-col items-center justify-center item-link">
                            <img src="assets/<?= $row['icon_item']; ?>" alt="<?= $row['nama_item']; ?>" class="w-35 h-35 mb-2">
                            <p class="text-white text-sm font-semibold">
                                <?= $row['jumlah_item']; ?> <?= $row['nama_item']; ?>
                            </p>
                            <p class="text-[#ffed00] text-xs font-semibold">IDR <?= number_format($row['harga_item'], 0, ',', '.'); ?></p>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- MODAL PEMABAYARAN  -->
    </div>
    <div id="pembayaranPopup"
        class="hidden fixed inset-0 flex items-center justify-center bg-black/60 backdrop-blur-sm z-50">
        <div class="bg-[color:#6f050c] rounded-xl shadow-lg w-[700px] h-[500px] p-6 relative">
            <button onclick="document.getElementById('pembayaranPopup').classList.add('hidden')"
                class="absolute top-4 right-6 text-white hover:text-[#ffed00] transition text-xl">✖</button>

            <div id="pembayaranPopupContent"></div>
        </div>
    </div>
    
    <script>
        function pembayaranPopup(item) {
            let idAkun = document.querySelector("input[name='id_akun_game']");
            let idZona = document.querySelector("input[name='id_zona_game']");
            let username = document.querySelector("input[name='username_efootball']");

            if ((idAkun && idAkun.value.trim() === "") ||
                (idZona && idZona.required && idZona.value.trim() === "") ||
                (username && username.value.trim() === "")) {
                alert("Harap isi data akun terlebih dahulu!");
                return;
            }

            let data = new FormData();
            data.append("id_item", item);
            if (idAkun) data.append("id_akun_game", idAkun.value);
            if (idZona) data.append("id_zona_game", idZona.value);
            if (username) data.append("username_akun", username.value);

            fetch("pembayaran.php", {
                    method: "POST",
                    body: data
                })
                .then(res => res.text())
                .then(html => {
                    document.getElementById("pembayaranPopupContent").innerHTML = html;
                    document.getElementById("pembayaranPopup").classList.remove("hidden");
                });
        }
    </script>

</body>

</html>