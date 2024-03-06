<?php 
//vytvoreni pripojeni na databazi
$db = new PDO(
"mysql:host = localhostl;dbname=exchange;charset=utf8",
"root",
"",
array(
PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
),
);