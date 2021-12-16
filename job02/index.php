<?php
include("user-pdo.php");

$myUser = new UserPDO(
    "tata84",
    "tatadu84",
    "tatadu84@gmail.com",
    "Tata",
    "Dupuis"
);

$myUser -> registerAuto();

$myUser -> connect(
    "tata84",
    "tatadu84"
);

var_dump($myUser->getAllInfos());
?>