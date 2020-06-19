<?php
    date_default_timezone_set( "Asia/Hong_Kong" );
    $hostname = "localhost";
    $username = "root";
    $pwd = "pi";
    $db = "iot";
    $conn = mysqli_connect($hostname, $username, $pwd, $db) or die(mysqli_connect_error());
    mysqli_query($conn,"SET NAMES utf8");
?>