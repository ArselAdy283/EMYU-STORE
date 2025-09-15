<?php

$koneksi = mysqli_connect("localhost","root","","emyustore");
if(!$koneksi){
    die("Koneksi gagal: " . mysqli_connect_error());
}

?>