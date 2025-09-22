<?php

session_start();
include 'koneksi.php';

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

<body class="bg-gradient-to-tr from-[#ff392c] via-black to-[#ff392c] min-h-screen text-white">
    <?php include 'navbar.php'; ?>

    <!-- Judul -->
    <h1 class="text-5xl font-extrabold text-[#ffed00] text-center mt-12">Account</h1>

    <!-- Form -->

    <form method="POST" action="" class="flex flex-col items-center mt-10 space-y-6">
        <div class="flex items-center w-80 bg-white rounded-2xl px-4 py-3 text-[#922]">
            <img src="assets/user.svg" class="h-6 w-6 mr-3" />
            <input type="text" name="display_name" placeholder="Display Name" required
                class="w-full outline-none bg-transparent text-[#922]" />
        </div>

        <div class="flex items-center w-80 bg-white rounded-2xl px-4 py-3 text-[#922]">
            <img src="assets/user.svg" class="h-6 w-6 mr-3" />
            <input type="text" name="username" placeholder="Username" pattern="^[a-z0-9._]+$" title="Gunakan huruf kecil, angka, underscore (_) atau titik (.) saja" required
                class="w-full outline-none bg-transparent text-[#922]" />
        </div>

        <!-- Password -->
        <div class="flex items-center w-80 bg-white rounded-2xl px-4 py-3 text-[#922]">
            <img src="assets/lock-key.svg" class="h-6 w-6 mr-3" />
            <input type="password" name="password" placeholder="Password" required
                class="w-full outline-none bg-transparent text-[#922]" />
        </div>

        <!-- Tombol -->
        <button type="submit"
            class="bg-[#ffed00] text-black font-bold px-8 py-3 rounded-2xl hover:bg-yellow-400 transition" name="submit">REGISTER</button>

        <p class="text-sm mt-6">
            Sudah punya akun?
            <a href="login.php" class="text-blue-400 hover:underline">login sekarang</a>
        </p>

    </form>

    <?php

    if (isset($_POST['submit'])) {
        $display_name = $_POST['display_name'];
        $username = $_POST['username'];
        $password = $_POST['password'];

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $koneksi->prepare("INSERT INTO users (username, password, display_name) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $hash, $display_name);
        $stmt->execute();
        header('Location: login.php');
    }

    ?>
    <!-- Link daftar -->
</body>

</html>