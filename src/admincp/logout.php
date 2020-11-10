<?php
// *** Logout the current user.
$logoutGoTo = "login.php";
if (!isset($_SESSION)) {
  @session_start();
}
$_SESSION['auser'] = NULL;
$_SESSION['apass'] = NULL;
$_SESSION['achkpass'] = NULL;
$_SESSION["securicheckadm"]=NULL;
$_SESSION["awebsite"]=NULL;
unset($_SESSION['auser']);
unset($_SESSION['apass']);
unset($_SESSION['achkpass']);
unset($_SESSION['securicheckadm']);
unset($_SESSION['awebsite']);
unset($_SESSION['adomain']);
if ($logoutGoTo != "") {header("Location: $logoutGoTo");
exit;
}
?>