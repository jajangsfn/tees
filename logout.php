<?php
session_start();

include "koneksi.php";

if (isset($_SESSION['is_login'])) {
    session_destroy();
}

?>