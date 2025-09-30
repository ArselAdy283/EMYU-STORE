<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header('Location: login.php');
    exit;
}

$id_user = $_SESSION['id_user'];

// ambil data order user
$stmt = $koneksi->prepare("
    SELECT o.id_order, o.tanggal, o.status,
           i.nama_item, i.jumlah_item, i.harga_item, g.nama_game
    FROM orders o
    JOIN items i ON o.id_item = i.id_item
    JOIN games g ON i.id_game = g.id_game
    WHERE o.id_user = ?
    ORDER BY o.tanggal DESC
");
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();
?>

<h1>Daftar Order Saya</h1>
<table border="1" cellpadding="8">
    <tr>
        <th>Tanggal</th>
        <th>Game</th>
        <th>Item</th>
        <th>Harga</th>
        <th>Status</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['tanggal'] ?></td>
            <td><?= $row['nama_game'] ?></td>
            <td><?= $row['jumlah_item'] ?> <?= $row['nama_item'] ?></td>
            <td><?= number_format($row['harga_item']) ?></td>
            <td><?= $row['status'] ?></td>
        </tr>
    <?php endwhile; ?>
</table>
