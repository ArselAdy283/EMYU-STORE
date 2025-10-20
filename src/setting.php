<?php
include 'koneksi.php';
?>

<h2 class="text-5xl font-extrabold text-[#ffed00] text-center mb-8">Setting</h2>

<form method="POST" action="" enctype="multipart/form-data"
      class="flex flex-col md:flex-row items-center justify-center gap-4 md:gap-12 px-4">
    <input type="hidden" name="id" value="<?= $_SESSION['id_user']; ?>">

    <!-- Kolom Kiri: Foto Profil -->
    <div class="relative group flex-shrink-0">
        <img src="profile_pic/<?= $_SESSION['profile_pic'] ?? 'profile_pic.png'; ?>" 
             id="preview" 
             class="w-[100px] h-[100px] md:w-[180px] md:h-[180px] rounded-full object-cover cursor-pointer" />

        <!-- Overlay Pensil -->
        <label for="profile_pic" 
               class="absolute inset-0 flex items-center justify-center bg-black/70 rounded-full opacity-0 group-hover:opacity-100 transition cursor-pointer">
            <img src="assets/pencil.svg" class="w-8 h-8 md:w-16 md:h-16 invert" />
        </label>

        <!-- Input file hidden -->
        <input type="file" id="profile_pic" name="profile_pic" accept="image/*" class="hidden" 
               onchange="previewImage(event)" />
    </div>

    <!-- Kolom Kanan: Form Input -->
    <div class="flex flex-col space-y-6 w-full md:w-auto mt-2 md:mt-0">
        <!-- Display Name -->
        <div class="flex flex-col items-center md:items-start">
            <h2 class="text-lg md:text-xl font-semibold mb-2">Display Name</h2>
            <div class="flex items-center w-full max-w-xs md:w-80 bg-white rounded-2xl px-4 py-3 text-[#922]">
                <img src="assets/user.svg" class="h-5 w-5 md:h-6 md:w-6 mr-3" />
                <input type="text" name="display_name" value="<?= $_SESSION['display_name']; ?>" required
                    class="w-full outline-none bg-transparent text-[#922]" />
            </div>
        </div>

        <!-- Username -->
        <div class="flex flex-col items-center md:items-start">
            <h2 class="text-lg md:text-xl font-semibold mb-2">Username</h2>
            <div class="flex items-center w-full max-w-xs md:w-80 bg-white rounded-2xl px-4 py-3 text-[#922]">
                <img src="assets/user.svg" class="h-5 w-5 md:h-6 md:w-6 mr-3" />
                <input type="text" name="username" value="<?= $_SESSION['username']; ?>" 
                    pattern="^[a-z0-9._]+$" 
                    title="Gunakan huruf kecil, angka, underscore (_) atau titik (.) saja" 
                    required
                    class="w-full outline-none bg-transparent text-[#922]" />
            </div>
        </div>

        <!-- Tombol -->
        <button type="submit" name="update" 
            class="bg-[#ffed00] text-black font-bold px-8 py-3 rounded-2xl hover:bg-yellow-400 transition self-center md:self-start">
            EDIT
        </button>
    </div>
</form>

<script>
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function(){
        document.getElementById('preview').src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
}
</script>

<?php
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $display_name = $_POST['display_name'];
    $username = $_POST['username'];

    $profile_pic = $_SESSION['profile_pic'] ?? 'profile_pic.png';

    if (!empty($_FILES['profile_pic']['name'])) {
        $targetDir = "profile_pic/";
        $fileName = time() . "_" . basename($_FILES['profile_pic']['name']);
        $targetFilePath = $targetDir . $fileName;
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
        $allowTypes = ['jpg','jpeg','png','gif'];

        if (in_array($fileType, $allowTypes)) {
            if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $targetFilePath)) {
                $profile_pic = $fileName;
            }
        }
    }

    $update = mysqli_query($koneksi, "UPDATE users 
        SET username='$username', display_name='$display_name', profile_pic='$profile_pic'
        WHERE id_user='$id'");

    if ($update) {
        $_SESSION['username'] = $username;
        $_SESSION['display_name'] = $display_name;
        $_SESSION['profile_pic'] = $profile_pic;
        echo "<script>location.href='account.php'</script>";
    } else {
        echo "Update gagal: " . mysqli_error($koneksi);
    }
}
?>
