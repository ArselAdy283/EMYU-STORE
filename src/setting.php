<?php
include 'koneksi.php';
?>

<h2 class="text-5xl font-extrabold text-[#ffed00] text-center mb-4">Setting</h2>
<form method="POST" action="" class="flex flex-col items-center mt-10 space-y-6">
    <h2 class="text-xl font-semibold mb-2">Display Name</h2>
    <div class="flex items-center w-80 bg-white rounded-2xl px-4 py-3 text-[#922]">
        <img src="assets/user.svg" class="h-6 w-6 mr-3" />
        <input type="text" name="display_name" value="<?= $_SESSION['display_name']; ?>" required
            class="w-full outline-none bg-transparent text-[#922]" />
    </div>

    <h2 class="text-xl font-semibold mb-2">Username</h2>
    <div class="flex items-center w-80 bg-white rounded-2xl px-4 py-3 text-[#922]">
        <img src="assets/user.svg" class="h-6 w-6 mr-3" />
        <input type="text" name="username" value="<?= $_SESSION['username']; ?>" pattern="^[a-z0-9._]+$" title="Gunakan huruf kecil, angka, underscore (_) atau titik (.) saja" required
            class="w-full outline-none bg-transparent text-[#922]" />
    </div>

    <!-- Tombol -->
    <button type="submit" name="update" class="bg-[#ffed00] text-black font-bold px-8 py-3 rounded-2xl hover:bg-yellow-400 transition" name="submit">EDIT</button>
</form>

<?php
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $display_name = $_POST['display_name'];
    $username = $_POST['username'];

    $update = mysqli_query($koneksi, "UPDATE users SET username='$username', display_name='$display_name' WHERE id_user='$id'");

    if ($update) {
        $_SESSION['username'] = $username;
        $_SESSION['display_name'] = $display_name;
        echo "<script>location.href='account.php'</script>";
    } else {
        echo "Update gagal: " . mysqli_error($koneksi);
    }
}
?>
