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

<body class="overflow-x-hidden overflow-y-scroll bg-gradient-to-tr from-[#ff392c] via-black to-[#ff392c] min-h-screen text-white">
  <?php include 'navbar.php'; ?>

  <!-- Judul -->
  <h1 class="text-5xl font-extrabold text-[#ffed00] text-center mt-12">Account</h1>
  <?php

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $koneksi->prepare("SELECT id_user, password, display_name, profile_pic, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if ($user && password_verify($password, $user['password'])) {
      $_SESSION['id_user'] = $user['id_user'];
      $_SESSION['username'] = $username;
      $_SESSION['display_name'] = $user['display_name'];
      $_SESSION['profile_pic'] = $user['profile_pic'];
      $_SESSION['role'] = $user['role'];
      header('Location: account.php');
      exit;
    } else {
      echo '<h1 class="bg-red-500 text-white text-center translate-x-[580px] translate-y-[20px] w-[382px]">Ada yang salah dengan username atau passwordnya</h1>';
    }
  }

  ?>

  <!-- Form -->
  <form method="POST" action="" class="flex flex-col items-center mt-10 space-y-6">
    <div class="flex items-center w-80 bg-white rounded-2xl px-4 py-3 text-[#922]">
      <img src="assets/user.svg" class="h-6 w-6 mr-3" />
      <input type="text" name="username" placeholder="Username" required
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
      class="bg-[#ffed00] text-black font-bold px-8 py-3 rounded-2xl hover:bg-yellow-400 transition">LOGIN</button>

    <p class="text-sm mt-6">
      Belum punya akun?
      <a href="register.php" class="text-blue-400 hover:underline">registrasi sekarang</a>
    </p>
  </form>
  <!-- Link daftar -->
</body>

</html>