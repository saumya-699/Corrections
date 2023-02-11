<?php

session_start();

unset($_SESSION["uid"]);

unset($_SESSION["name"]);

$BackToMyPage = $_SERVER['HTTP_REFERER'];
if(isset($BackToMyPage)) {
    //header('Location: '.$BackToMyPage);
    //self::$_headers->header('Location: '.$BackToMyPage);
    header('Location: '.filter_var($BackToMyPage, FILTER_SANITIZE_URL));
} else {
    header('Location: index.php'); // default page
}

?>
