<?php
session_start(); // start session
session_destroy(); // hapus SESSION
header("location:login.php"); // lempar ke login.php