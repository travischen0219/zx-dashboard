<?php
header("Cache-control: private");

if ($_SERVER['SERVER_NAME'] == '127.0.0.1') {
    $con = @mysqli_connect('localhost', 'root', '1234', 'zx');
} elseif($_SERVER['SERVER_NAME'] == 'zx.4family.co') {
    $con = @mysqli_connect('localhost', 'zx', 'o2RcDEOd34p7J4Eg', 'zx');
}

if (!$con) {
    echo "Error: " . mysqli_connect_error();
	exit();
}
