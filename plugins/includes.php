<?php

$headers = "From: <moghomra@gmail.com>\r\n";
$headers .= "X-Mailer: PHP ".phpversion()."\n";
$headers .= "X-Priority: 1 \n";
$headers .= "Mime-Version: 1.0\n";
$headers .= "Content-Transfer-Encoding: 8bit\n";
$headers .= "Content-type: text/html; charset= utf-8\n";
$headers .= "Date:" . date("D, d M Y h:s:i") . " +0200\n";

include "form.php";