<?php
include 'koneksi.php';

$id = intval($_POST['id'] ?? 0);
$amount = intval($_POST['amount'] ?? 0);

if ($id > 0 && $amount > 0) {
    // Cek apakah user sudah punya data emyucoin
    $stmt = $koneksi->prepare("SELECT emyucoin FROM emyucoin_user WHERE id_user = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Jika ada, kurangi jumlah emyucoin (boleh jadi minus)
        $update = $koneksi->prepare("UPDATE emyucoin_user SET emyucoin = emyucoin - ? WHERE id_user = ?");
        $update->bind_param("ii", $amount, $id);
        if ($update->execute()) {
            echo "Saldo berhasil dikurangi!";
        } else {
            echo "Gagal mengurangi emyucoin.";
        }
    } else {
        // Jika belum ada, buat data baru langsung dengan nilai negatif
        $minusAmount = -$amount;
        $insert = $koneksi->prepare("INSERT INTO emyucoin_user (id_user, emyucoin) VALUES (?, ?)");
        $insert->bind_param("ii", $id, $minusAmount);
        if ($insert->execute()) {
            echo "Data baru dibuat dengan saldo minus!";
        } else {
            echo "Gagal membuat data baru.";
        }
    }
} else {
    echo "Data tidak valid!";
}
?>
