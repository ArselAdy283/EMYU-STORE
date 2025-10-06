<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_order'])) {
    $id_order = intval($_POST['id_order']);
    
    $filter = $_POST['filter'] ?? $_GET['filter'] ?? 'all';
    
    $stmt = $koneksi->prepare("UPDATE orders SET status = 'done' WHERE id_order = ?");
    $stmt->bind_param("i", $id_order);

    if ($stmt->execute()) {
        switch($filter) {
            case 'pending':
                header("Location: admin.php?page=orders&filter=pending");
                break;
            case 'done':
                header("Location: admin.php?page=orders&filter=done");
                break;
            case 'all':
            default:
                header("Location: admin.php?page=orders&filter=all");
                break;
        }
        exit;
    } else {
        echo "Gagal update status: " . $stmt->error;
    }
} else {
    header("Location: admin.php?page=order");
    exit;
}
?>