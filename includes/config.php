<?php
$con = mysqli_connect("mysql-service", "root", "root", "onlinecourse");

if (mysqli_connect_errno()) {
    echo "Database connection failed: " . mysqli_connect_error();
}
?>