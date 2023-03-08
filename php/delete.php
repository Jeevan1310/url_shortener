<?php
    require "config.php";
    if(isset($_GET['id'])){
        $delete_id = mysqli_real_escape_string($conn, $_GET['id']);
        $sql = mysqli_query($conn, "DELETE FROM url WHERE shorten_url = '{$delete_id}'");
        if($sql){
            header("Location: ../");
        }else{
            header("Location: ../");
        }
    }elseif(isset($_GET['delete_user_id'])){
        $delete_user_id=$_GET['delete_user_id'];
        $sql3 = mysqli_query($conn, "DELETE FROM url where user_id=$delete_user_id");
        if($sql3){
            header("Location: ../");
        }else{
            header("Location: ../");
        }
    }else{
        header("Location: ../");
    }
?>