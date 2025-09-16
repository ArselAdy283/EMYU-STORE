<?php

session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link  href="./output.css" rel="stylesheet">
    <title>Document</title>
</head>
<body class="bg-gradient-to-tr from-[color:#ff392c] via-black to-[color:#ff392c] min-h-screen text-white">
    <?php
    include 'navbar.php';
    ?>
</body>
</html>