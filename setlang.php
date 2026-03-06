<?php
session_start();
$lang = $_GET['lang'] ?? 'en';
$allowed = ['en','te','hi','ta','kn','ml','mr','gu','bn','pa','or','as','ur','kok','mai'];
if(in_array($lang, $allowed)){
  $_SESSION['lang'] = $lang;
}
// Redirect back to previous page
$back = $_SERVER['HTTP_REFERER'] ?? '../index.php';
header("Location: $back");
exit;
?>
