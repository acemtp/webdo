<?

session_cache_expire(60*60*24*30);
session_start();
session_unset();

header("Location: login.php");

?>