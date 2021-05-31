<?php
session_start();
 
// Fjern alle variabler i sessionen
$_SESSION = array();
session_destroy();
header("location: /skp_intra/");
exit;
?>