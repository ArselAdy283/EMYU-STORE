<h2 class="text-2xl font-extrabold text-[#ffed00] text-center mb-4">Setting</h2>
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