<?php
include("user-pdo.php");

$myUser = new UserPDO(
    "tata84",
    "tatadu84",
    "tatadu84@gmail.com",
    "Tata",
    "Dupuis"
);

$myUser->register(
    "tata84",
    "tatadu84",
    "tatatdu84@gmail.com",
    "Tata",
    "Dupuis"
);

$myUser->connect(
    "tata84",
    "tatadu84"
);

var_dump($myUser->getAllInfos());
?>