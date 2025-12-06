<?php
session_start();
$conn = mysqli_connect('localhost', 'root', '', 'rs_sederhana');

function cekLogin() {
    if (!isset($_SESSION['login'])) {
        header('Location: login.php');
        exit;
    }
}
?>